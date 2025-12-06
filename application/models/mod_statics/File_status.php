<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class File_status extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
            if(!isset($values["fast"])){$values["fast"]=false;}
            $id=0;
            $id_file=$values["id_file"];
            $id_client=secureEmptyNull($values,"id_client");
            $filename=$values["filename"];
            $status=$values["status"];
            if ($fields==null){
                $where=(" id_client=".$id_client." AND filename='".$filename."'");
                $record=$this->get(array("where"=>$where));
                if ((int)$record["totalrecords"]!=0){$id=$record["data"][0]["id"];}
                if ($id==0) {
                    $fields = array(
                        'code' => opensslRandom(16),
                        'description' => "APILinkerStatus()",
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_file' => $id_file,
                        'id_client' => $id_client,
                        'filename' => $filename,
                        'status' => $status,
                    );
                } else {
                    $fields = array(
                        'fum' => $this->now,
                        'status' => $status,
                    );
                }
            }
            if ($filename!="") {$saved=parent::save(array("id"=>$id),$fields);}
            
            if (!$values["fast"]) {
                $sql=("UPDATE ".MOD_STATICS."_counters SET processed=pages WHERE ".$where);
                //log_message("error", "RELATED " . json_encode($sql, JSON_PRETTY_PRINT));
                $this->execAdHoc($sql);

                $sql="UPDATE MOD_STATICS_counters SET processed=pages ";
                $sql.=" WHERE processed=0 AND replace([filename],'.csv','') IN ";
                $sql.=" (SELECT replace([filename],'.csv','') FROM MOD_STATICS_post_proc WHERE replace([filename],'.csv','') LIKE '%'+MOD_STATICS_counters.[filename]+'%')";
                //log_message("error", "RELATED " . json_encode($sql, JSON_PRETTY_PRINT));
                $this->execAdHoc($sql);
            }

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
