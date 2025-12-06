<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Profiles extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function getProfiles($values){
        try {
            if (!isset($values["id_user"])) {throw new Exception(lang("error_5118"),5118);}
            $values["where"]=("offline is null AND id_user=".$values["id_user"]);
            $values["order"]="description ASC";
            $ret=$this->get($values);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function brow($values){
        try {
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_STATICS."/profiles/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_user=array(
                "model"=>(MOD_BACKEND."/Users"),
                "table"=>"Users",
                "name"=>"id_user",
                "class"=>"form-control dbase validate id_user",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_user"),
                "id_field"=>"id",
                "description_field"=>"code",
                "get"=>array("order"=>"code ASC","pagesize"=>-1,"where"=>"id_type_user IN (79)"),
            );
            $parameters_id_client=array(
                "model"=>(MOD_BACKEND."/Clients"),
                "table"=>"Clients",
                "name"=>"id_client",
                "class"=>"form-control dbase validate id_client",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_client"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_sufix_subdirs=array(
                "model"=>(MOD_STATICS."/Type_sufixes"),
                "table"=>"Type_sufixes",
                "name"=>"sufix_subdirs",
                "class"=>"form-control dbase sufix_subdirs",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"sufix_subdirs"),
                "id_field"=>"code",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_end=array(
                "model"=>(MOD_STATICS."/Type_ends"),
                "table"=>"Type_ends",
                "name"=>"id_type_end",
                "class"=>"form-control dbase id_type_end",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_end"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_user"=>getCombo($parameters_id_user,$this),
                "id_client"=>getCombo($parameters_id_client,$this),
                "sufix_subdirs"=>getCombo($parameters_sufix_subdirs,$this),
                "id_type_end"=>getCombo($parameters_id_type_end,$this),
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            $fields=null;
            if($id==0){
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_user' => secureEmptyNull($values,"id_user"),
                    'id_client' => secureEmptyNull($values,"id_client"),
                    'unc_source' => $values["unc_source"],
                    'unc_target' => $values["unc_target"],
                    'mm_alive' => $values["mm_alive"],
                    'pdf' => $values["pdf"],
                    'tiff' => $values["tiff"],
                    'single_page' => $values["single_page"],
                    'time_from' => $values["time_from"],
                    'time_to' => $values["time_to"],
                    'sufix_subdirs' => $values["sufix_subdirs"],
                    'db_source' => $values["db_source"],
                    'db_target' => $values["db_target"],
                    'mod_files' => 1,
                    'mod_pages' => 1,
                    'mod_status' => $values["mod_status"],
                    'sql_status' => $values["sql_status"],
                    'post_unc_source' => $values["post_unc_source"],
                    'post_unc_target' => $values["post_unc_target"],
                    'id_type_end' => secureEmptyNull($values,"id_type_end"),
                    'mod_post' => $values["mod_post"],
                    'unc_storage' => $values["unc_storage"],
                    'ttl_storage' => $values["ttl_storage"],
                    'sla' => $values["sla"],
                    'post_unc_bad' => $values["post_unc_bad"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'id_user' => secureEmptyNull($values,"id_user"),
                    'id_client' => secureEmptyNull($values,"id_client"),
                    'unc_source' => $values["unc_source"],
                    'unc_target' => $values["unc_target"],
                    'mm_alive' => $values["mm_alive"],
                    'pdf' => $values["pdf"],
                    'tiff' => $values["tiff"],
                    'single_page' => $values["single_page"],
                    'time_from' => $values["time_from"],
                    'time_to' => $values["time_to"],
                    'sufix_subdirs' => $values["sufix_subdirs"],
                    'db_source' => $values["db_source"],
                    'db_target' => $values["db_target"],
                    'mod_files' => 1,
                    'mod_pages' => 1,
                    'mod_status' => $values["mod_status"],
                    'sql_status' => $values["sql_status"],
                    'post_unc_source' => $values["post_unc_source"],
                    'post_unc_target' => $values["post_unc_target"],
                    'id_type_end' => secureEmptyNull($values,"id_type_end"),
                    'mod_post' => $values["mod_post"],
                    'unc_storage' => $values["unc_storage"],
                    'ttl_storage' => $values["ttl_storage"],
                    'sla' => $values["sla"],
                    'post_unc_bad' => $values["post_unc_bad"],
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
