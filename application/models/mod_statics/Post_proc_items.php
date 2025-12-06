<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Post_proc_items extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            $fields = array(
                'code' => opensslRandom(16),
                'description' => "Registro a servicio externo",
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
                'id_client' => secureEmptyNull($values,"id_client"),
                'filename' => $values["filename"],
                'line' => secureEmptyNull($values,"line"),
                'data_post' => $values["data_post"],
                'data_response' => $values["data_response"],
            );
            return parent::save(array("id"=>0),$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
