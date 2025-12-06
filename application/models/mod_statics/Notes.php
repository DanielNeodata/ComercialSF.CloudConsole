<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Notes extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            $id=0;
            if ($fields==null){
                $fields = array(
                    'code' => $values["folder"],
                    'description' => $values["description"],
                    'created' => $values["created"],
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_client' => secureEmptyNull($values,"id_client"),
                );
            }
            $saved=parent::save(array("id"=>$id),$fields);
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
    public function listNotes($values){
        try {
            $sql="SELECT * FROM ".MOD_STATICS."_notes ";
            $sql.=" WHERE created>='".$values["date_from"]."' AND created <='".$values["date_to"]."' AND id_client=".$values["id_client"]." AND code='".$values["folder"]."'";
            $sql.=" ORDER BY created DESC";
		    $notes=$this->getRecordsAdHoc($sql);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$notes,
                "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
