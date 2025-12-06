<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Transvina_rendiciones extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    function autoPostProc($id_client){
        try {
            $interval=3;
            //$interval=0.1;
            $id_client=8;
            $POST_PROC=$this->createModel(MOD_STATICS,"Post_proc","Post_proc");
            $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
            $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$id_client));
            $path=(FILE_TEMP."/".$id_client."/");
            $allfiles=glob($path."*.csv");
            foreach ($allfiles as $file) {
                $this->executeProc($file,$clients["data"][0]);
                $params=array("id_client"=>$id_client,"filename"=>basename($file),"fullpath"=>($path.$file));
                $POST_PROC->save($params,null);
                unlink($file);
            }
            return array(
               "code"=>"2000",
               "status"=>"OK",
               "message"=>"",
               "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
               "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function executeProc($file,$client){
        try {
           /* Cada línea debe insertarse en cada viaje 
           cada CSV es un viaje
           al final del CSV, consultar por el personBalance
           si es CERO se cierra el viaje
           */
           sleep(3);
           $POST_PROC_ITEMS = $this->createModel(MOD_STATICS, "post_proc_items", "post_proc_items");
           $server = "https://fleet.cloudfleet.com/api/v1/";
           $apiKey="-Q5-I55.C47XdP2UM-I-17IEyGT8aFKSuvsRUmfYl";
           $headers = array('Authorization: Bearer ' . $apiKey, 'Content-Type:application/json; charset=utf-8');
           $id_client = $client["id"];
           $fSegments=explode("_",basename($file));
           $id_travel=(int)$fSegments[1];
           try {
               if ($f = fopen($file, "r")) {
                    $i=0;
                    while(!feof($f)) {
                       $line=fgets($f);
                       if ($i>0){
                           $line=utf8_encode($line);
                           $idx=str_getcsv($line,",");
                           $conceptCode=$idx[0];
                           $quantity=$idx[1];
                           $unitCost=$idx[2];
                           $discountPercentage=$idx[3];  // ver si debe venir en el csv
                           $taxPercentage=$idx[4];       // ver si debe venir en el csv
                           $affectsAdvance=$idx[5];      // ver si debe venir en el csv
                           $invoice=$idx[6];
                           $invoiceDate=$idx[7];
                           $data=array(
                               "conceptCode"=>$conceptCode,
                               "quantity"=>$quantity,
                               "unitCost"=>$unitCost,
                               "discountPercentage"=>null,        // ver si debe venir en el csv
                               "taxPercentage"=>null,             // ver si debe venir en el csv
                               "affectsAdvance"=>$affectsAdvance, // ver si debe venir en el csv
                               "vendorIdentification"=>null,      // ver si debe venir en el csv
                               "invoice"=>$invoice,
                               "invoiceDate"=>$invoiceDate,
                               "comments"=>"API"  
                           );
                           /*se fuerza el tre cuando es peaje*/
                           //if ($conceptCode=="peaje"){$data["affectsAdvance"]=true;}

                            $data_post = json_encode($data);
                            $url=($server."travels/".$id_travel."/expenses");
                            
                            $data_response=cUrlRestfulPost($url,$headers, $data_post);
                            $params = array("id_client" => $id_client, "filename" => $file, "line" => $i, "data_post" => $data_post, "data_response" => $data_response);
                            $POST_PROC_ITEMS->save($params, null);                
                       }
                       $i+=1;
                    }
                    fclose($f);
               }
           } catch(Excepton $err){}

           $url=($server."travels/".$id_travel);
           
            $data_response=cUrlRestfulGet($url,$headers);
           $ret=json_decode($data_response,true);
           $raw_response=json_encode($ret,JSON_PRETTY_PRINT);
           $status="";
            $params = array("id_client" => $id_client, "filename" => $file, "line" => $i, "data_post" => "GET", "data_response" => $data_response);
            $POST_PROC_ITEMS->save($params, null);

            if(isset($ret["personBalance"])){
                $bGo = ((float) $ret["personBalance"] == 0);
                //if (!$bGo) {
                //    if (isset($ret["advanceValue"])) {
                //        $bGo = (((float) $ret["personBalance"] - (float) $ret["advanceValue"]) == 0);
                //    }
                //}
               if ($bGo) {
                   $ret=null;
                   $url.="/close";
                   $fields=array("closeDate"=>$this->now);
     
			       $ret=cUrlRestfulPost($url,$headers,json_encode($fields));
                   if ($ret==null or $ret=="null") {
                       $status="CERRADO";
                   } else {
                       $ret=json_decode($ret,true);
                       $status=json_encode($ret);
                   }
               } else {
                   /*Registrar que no se efectúa el cierre*/
                   $status="NO CERRADO";
               }
           } else {
               $status=("ERROR ID VIAJE: ".$id_travel);
               if((int)$client["mod_ad_hoc"]!=0){
                   $EMAILING=$this->createModel(MOD_EMAIL,"Emailing","Emailing");
                   $body=$client["ad_hoc_notification"];
                   $body=str_replace("[ID_VIAJE]",(string)$id_travel,$body);
                   $body=str_replace("[RESPONSE]",$raw_response,$body);
                   $mail=array(
                       "to"=>$client["ad_hoc_email"],
                       "cc"=>"",
                       "bcc"=>"",
                       "reply_to"=>$client["ad_hoc_email"],
                       "subject"=>"Cloud-Capture - Alerta de viaje inexistente - ".$client["description"],
                       "body"=>$body
                   );
                   //$ret=$EMAILING->send($mail);
                   //log_message("error", "RELATED EMAIL ".json_encode($ret,JSON_PRETTY_PRINT));
               }
           }

           /*Log the personaBalance request!*/
           $POST_RESPONSES=$this->createModel(MOD_STATICS,"Post_responses","Post_responses");
           $params=array("id_client"=>$client["id"],"filename"=>basename($file),"raw_data"=>$raw_response,"exception"=>$status);
           $POST_RESPONSES->save($params,null);
           sleep(3);

           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
           );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
