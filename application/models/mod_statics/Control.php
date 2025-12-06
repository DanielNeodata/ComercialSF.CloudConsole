<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Control extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            $fields = array(
                'code' => opensslRandom(8),
                'description' => "Registro de generación automática",
                'created' => $values["date_from"],
                'verified' => $values["date_to"],
                'offline' => null,
                'fum' => $this->now,
                'id_client' => secureEmptyNull($values,"id_client"),
                'post_proc'=>$values["proc"],
                'post_resp'=>$values["resp"],
                'post_stat'=>$values["stat"]
            );
            $saved=parent::save(array("id"=>0),$fields);
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
