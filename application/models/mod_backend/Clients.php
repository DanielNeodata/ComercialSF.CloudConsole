<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Clients extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"id","format"=>"text"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"date_from_contract","format"=>"date"),
                array("field"=>"date_to_contract","format"=>"date"),
                array("field"=>"pages_contract","format"=>"code"),
                array("field"=>"","format"=>""),
                array("field"=>"","format"=>""),
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
            $values["interface"]=(MOD_BACKEND."/clients/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_application=array(
                "model"=>(MOD_BACKEND."/Applications"),
                "table"=>"Applications",
                "name"=>"id_application",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_application"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_procesamiento=array(
                "model"=>(MOD_STATICS."/Type_procesamientos"),
                "table"=>"Type_procesamientos",
                "name"=>"id_type_procesamiento",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_procesamiento"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_application"=>getCombo($parameters_id_application,$this),
                "id_type_procesamiento"=>getCombo($parameters_id_type_procesamiento,$this),
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
                    'id_application' => secureEmptyNull($values,"id_application"),
                    'date_from_contract' => $values["date_from_contract"],
                    'date_to_contract' => $values["date_to_contract"],
                    'pages_contract' => $values["pages_contract"],
                    'post_model' => $values["post_model"],
                    'mod_alert' => $values["mod_alert"],
                    'alert_fixed_time' => $values["alert_fixed_time"],
                    'alert_from_date' => $values["alert_from_date"],
                    'alert_email' => $values["alert_email"],
                    'alert_email_cc' => $values["alert_email_cc"],
                    'alert_email_bcc' => $values["alert_email_bcc"],
                    'alert_email_reply_to' => $values["alert_email_reply_to"],
                    'post_ftp_server' => $values["post_ftp_server"],
                    'post_ftp_port' => $values["post_ftp_port"],
                    'post_ftp_username' => $values["post_ftp_username"],
                    'post_ftp_password' => $values["post_ftp_password"],
                    'pre_model' => $values["pre_model"],
                    'pre_ftp_server' => $values["pre_ftp_server"],
                    'pre_ftp_username' => $values["pre_ftp_username"],
                    'pre_ftp_password' => $values["pre_ftp_password"],
                    'pre_ftp_port' => $values["pre_ftp_port"],
                    'ad_hoc_email' => $values["ad_hoc_email"],
                    'ad_hoc_notification' => $values["ad_hoc_notification"],
                    'mod_ad_hoc' => $values["mod_ad_hoc"],
                    'id_type_procesamiento' => secureEmptyNull($values,"id_type_procesamiento"),
                    'mod_new_files' => $values["mod_new_files"],
                    'new_files_email' => $values["new_files_email"],
                    'new_files_alert' => $values["new_files_alert"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'id_application' => secureEmptyNull($values,"id_application"),
                    'date_from_contract' => $values["date_from_contract"],
                    'date_to_contract' => $values["date_to_contract"],
                    'pages_contract' => $values["pages_contract"],
                    'post_model' => $values["post_model"],
                    'mod_alert' => $values["mod_alert"],
                    'alert_fixed_time' => $values["alert_fixed_time"],
                    'alert_from_date' => $values["alert_from_date"],
                    'alert_email' => $values["alert_email"],
                    'alert_email_cc' => $values["alert_email_cc"],
                    'alert_email_bcc' => $values["alert_email_bcc"],
                    'alert_email_reply_to' => $values["alert_email_reply_to"],
                    'post_ftp_server' => $values["post_ftp_server"],
                    'post_ftp_port' => $values["post_ftp_port"],
                    'post_ftp_username' => $values["post_ftp_username"],
                    'post_ftp_password' => $values["post_ftp_password"],
                    'pre_model' => $values["pre_model"],
                    'pre_ftp_server' => $values["pre_ftp_server"],
                    'pre_ftp_username' => $values["pre_ftp_username"],
                    'pre_ftp_password' => $values["pre_ftp_password"],
                    'pre_ftp_port' => $values["pre_ftp_port"],
                    'ad_hoc_email' => $values["ad_hoc_email"],
                    'ad_hoc_notification' => $values["ad_hoc_notification"],
                    'mod_ad_hoc' => $values["mod_ad_hoc"],
                    'id_type_procesamiento' => secureEmptyNull($values,"id_type_procesamiento"),
                    'mod_new_files' => $values["mod_new_files"],
                    'new_files_email' => $values["new_files_email"],
                    'new_files_alert' => $values["new_files_alert"],
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
