<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Transvina extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function executeProc($file,$client){
        try {
            $server = "https://fleet.cloudfleet.com/api/v1/";
            $apiKey="-Q5-I55.C47XdP2UM-I-17IEyGT8aFKSuvsRUmfYl";
            $headers = array('Content-Type:application/json','Authorization: Bearer '.$apiKey);

            if ($f = fopen($file, "r")) {
                $i=0;
                while(!feof($f)) {
                   $line=fgets($f);
                   if ($i>0){
                       $line=utf8_encode($line);
                       $idx=str_getcsv($line,",");
                       $idTransfer=$idx[2];
                       $weight=;

                       $data=array(
                            "customerIdentification": "800014214",
                            "customerBranchOfficeCode": "MDE",
                            "productCode": "24",
                            "chargeBy": "weight",
                            "weight": $idx[5],
                            "weightUnitCode": $idx[6],
                            "quantity": $idx[7],
                            "quantityUnitCode": $idx[8],
                            "unitCost": 1000,
                            "manifestNumber": $idx[9],
                            "containerNumber": $idx[10],
                            "internalCode": $idx[11],
                            "comments": $idx[12]
                       );
                       $url=$server."travels/".$idTransfer."/freight";
			           $ret=cUrlRestfulGet($url,$headers);
                       $ret=json_decode($ret,true);
                       $raw_response=json_encode($ret,JSON_PRETTY_PRINT);
                       $status="ENVIADO";

                       /*Log the FREIGHT request!*/
                       $POST_RESPONSES=$this->createModel(MOD_STATICS,"Post_responses","Post_responses");
                       $params=array("id_client"=>$client["id"],"filename"=>basename($file),"raw_data"=>$raw_response,"exception"=>$status);
                       $POST_RESPONSES->save($params,null);
                   }
                   $i+=1;
                }
                fclose($f);
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
}
