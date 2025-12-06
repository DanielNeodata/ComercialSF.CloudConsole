<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Post_responses extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            $id_client=secureEmptyNull($values,"id_client");
            $rec=$this->get(array("pagesize"=>-1,"where"=>"id=".$id_client." AND filename='".$values["filename"]."'"));
            if ((int)$rec["totalrecords"]!=0){
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>$message,
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>array("id"=>0)
                );                
            }

            $fields = array(
                'code' => opensslRandom(16),
                'description' => "Interacción externa",
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
                'id_client' => $id_client,
                'filename' => $values["filename"],
                'raw_data' => $values["raw_data"],
                'exception' => $values["exception"],
            );
            return parent::save(array("id"=>0),$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
