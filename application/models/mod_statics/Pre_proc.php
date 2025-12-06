<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Pre_proc extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function manualUpload($values) {
        try {
            $id_user=6;// como si se enviara desde el server .179
            $id_client=secureEmptyNull($values,"id_client");

            $filename=$values["filename"];
            $filepath=$values["filepath"];
            $b64=$values["base64"];
            $extension=(".".end(explode(".",$filename)));
            $mime=getMimeType($filename);

            /*Bajar archivo transferido a disco*/
            $path=(FILE_TEMP."/".$id_client);
            if (!file_exists($path)) {mkdir($path, 0777, true);}
            $path.=("/".$filename);
            $data=base64_decode($b64);
            $params=array("id_client"=>$id_client,"filename"=>$filename,"fullpath"=>$path);
            $ret=file_put_contents($path,$data,FILE_USE_INCLUDE_PATH);

            $PROFILES=$this->createModel(MOD_STATICS,"Profiles","Profiles");
            $profile=$PROFILES->getProfiles(array("id_user"=>$id_user));
            $mod_status=(int)$profile["data"][0]["mod_status"];
            $sqlStatus=$profile["data"][0]["sql_status"];
            $sqlStatus=str_replace("@ID",$id_client,$sqlStatus);

            $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
            $client=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$id_client));
            
            /*Calcular paginas*/
            /*Usar servicio API neodata!*/
            $NEOTOOLS=$this->createModel(MOD_BACKEND,"Neotools","Neotools");
            $count=$NEOTOOLS->countPages(array("base64"=>$b64,"filename"=>$filename));
            $pages=(int)$count["data"];

            $params=array(
                    'id_user' => $id_user,
                    'id_client' => $id_client,
                    'filename' => $filename,
                    'filepath' => $filepath,
                    'pages' => $pages,
                    'extension' => $extension,
                    'base64String' => $b64,
                    'mime_type' => $mime,
                    'json' => "",
                    'processed' => 0
            );
            $COUNTERS=$this->createModel(MOD_STATICS,"Counters","Counters");
            $clients=$COUNTERS->setCounter($params);

            //$this->processSetPre($values);

            //$POST_PROC=$this->createModel(MOD_STATICS,"Post_proc","Post_proc");
            //$POST_PROC->processSetPost($params);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "uuid"=>$values["uuid"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function save($values,$fields=null){
        try {
            $fields = array(
                'code' => opensslRandom(16),
                'description' => "Registro de preprocesamiento",
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
                'id_client' => secureEmptyNull($values,"id_client"),
                'filename' => $values["filename"],
                'fullpath' => $values["fullpath"],
            );
            return parent::save(array("id"=>0),$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function setPre($values){
        try {
            if(!isset($values["id_client"])) {
				$str=trim(file_get_contents("php://input"));
				$str=str_replace('"',"'",$str);
				$str=htmlspecialchars_decode($str,ENT_NOQUOTES);
				$str=str_replace("\u0022",'"',$str);
				$str=str_replace("\\\\","\\",$str);
				$str=str_replace("'","",$str);
				$values=json_decode($str,true);
			}
            return $this->processSetPre($values);
        }
        catch (Exception $e){
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$e->getMessage(),
                "items"=>array(),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
        }
    }
    public function processSetPre($values){
        try {
            $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
            $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$values["id_client"]));

            if ((int)$clients["totalrecords"]==0){throw new Exception(lang("error_6001"),6001);}
            $id_client=$clients["data"][0]["id"];
            $model=$clients["data"][0]["pre_model"];
            if($model!=""){
                $ACTIVE=$this->createModel(MOD_PRE,$model,$model);
                $ret=$ACTIVE->executeProc($clients["data"][0]);
    			return $ret;
            } else {
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "compressed"=>false
                );
            }
        }
        catch (Exception $e){
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$e->getMessage(),
                "items"=>array(),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
        }
    }

    public function getFilePre($values){
        try {
            $str=trim(file_get_contents("php://input"));
            $str=str_replace('"',"'",$str);
            $str=htmlspecialchars_decode($str,ENT_NOQUOTES);
            $str=str_replace("\u0022",'"',$str);
            $str=str_replace("\\\\","\\",$str);
            $str=str_replace("'","",$str);
            $values=json_decode($str,true);
        
            $data=file_get_contents($values["fullpath"]);
            $base64=base64_encode($data);
            
            unlink($values["fullpath"]);
            RemoveEmptySubFolders(FILE_FTP."\\".$values["id_client"]);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$base64,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
