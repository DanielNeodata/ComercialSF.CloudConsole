<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Oxxo extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function executeProc($file,$client){
        try {
            $raw_response="";
            $POST_RESPONSES=$this->createModel(MOD_STATICS,"Post_responses","Post_responses");
            $FILE_STATUS=$this->createModel(MOD_STATICS,"file_status","file_status");
            $POST_PROC_ITEMS=$this->createModel(MOD_STATICS,"post_proc_items","post_proc_items");
            $id_client=$client["id"];
            $server = "http://api.cpech.cl/";
            $url=$server."Token/getToken";
		    $headers = array('Content-Type:application/json');
			$credentials=array("usuario"=>"XeroxUser","password"=>"q00n33vu38p0ku8y");
			$ret=cUrlRestfulPost($url,$headers,json_encode($credentials));
			$ret=json_decode($ret,true);
            if ((int)$ret["estado"]==0 or $ret["mensaje"]!="OK") {throw new Exception(lang("error_6002"),6002);}
            $token=$ret["token"];
            if ($f = fopen($file, "r")) {
                $i=0;
                $data_response="";
                while(!feof($f)) {
                   $line=fgets($f);
                   if ($i>0){
                       $line=utf8_encode($line);
                       $idx=str_getcsv($line,";");
                       if (isset($idx[9])){$estado=$idx[9];}else{$estado="N/A";}
                       if($estado==null or $estado==""){$estado="N/A";}
                       $data=array(
                            "sede"=>$idx[0],
                            "area"=>$idx[1],
                            "numero_prueba"=>$idx[2],
                            "rut"=>$idx[3],
                            "dv"=>$idx[4],
                            "resultado_general"=>$idx[5],
                            "lote"=>$idx[6],
                            "nombre_imagen"=>$idx[7],
                            "nombre_hoja"=>$idx[8],
                            "estado"=>$idx[9],
                       );
                       $data["fecha_escaneo"]=date("d-m-Y H:i:s");
                       if(isset($idx[10])){$data["fecha_escaneo"]=$idx[10];}
                       $data["curso"]="X";
                       if(isset($idx[11])){$data["curso"]=$idx[11];}
                       $data["sexo"]="X";
                       if(isset($idx[12])){$data["sexo"]=$idx[12];}
                       $data["edad"]="X";
                       if(isset($idx[13])){$data["edad"]=$idx[13];}

                       $url=$server."xerox/savePrueba";
                       $headers=array('Content-Type:application/json','Authorization: bearer '.$token);
                       $data_post=json_encode($data);
			           $data_response=cUrlRestfulPost($url,$headers,$data_post);
			           $ret=json_decode($data_response,true);
                       $params=array("id_client"=>$id_client,"filename"=>$file,"line"=>$i,"data_post"=>$data_post,"data_response"=>$data_response);
                       $POST_PROC_ITEMS->save($params,null);

                       $FILE_STATUS->save(array("created"=>$this->now,"id_file"=>$data["lote"],"id_client"=>$id_client,"filename"=>$data["nombre_imagen"],"status"=>"Exportado"),null);
                       //sleep(1);
                   }
                   $status="ERROR";
                   if($ret["status"]=="OK"){$status="DATOS ENVIADOS";}
                   $i+=1;
                }
                fclose($f);
                /*Log the full request!*/
                if($data_response==""){$data_response="ERROR El servicio contesta con una cadena de texto vacía";}
                $params=array("id_client"=>$id_client,"filename"=>basename($file),"raw_data"=>$data_response,"exception"=>$status);
                $POST_RESPONSES->save($params,null);

               if (strpos($data_response, "ERROR")!==false) {throw new Exception(lang("error_9999"),9999);}
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
