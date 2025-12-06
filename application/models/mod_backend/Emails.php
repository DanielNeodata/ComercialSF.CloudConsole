<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Emails extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function generateNewFiles($values)
    {
        try {
            $EMAILING = $this->createModel(MOD_EMAIL, "Emailing", "Emailing");
            $COUNTERS = $this->createModel(MOD_STATICS, "Counters", "Counters");
            $CLIENTS = $this->createModel(MOD_BACKEND, "Clients", "Clients");
            $clients = $CLIENTS->get(array("pagesize" => -1, "where" => "mod_new_files=1"));
            if ((int) $clients["totalrecords"] == 0) {throw new Exception(lang("error_6001"), 6001);}
            foreach ($clients['data'] as $record) {
                $sql = "SELECT count(*) as total FROM mod_statics_counters WHERE id_client=" . $record["id"] . " AND CAST(created AS DATE) = CAST(GETDATE() AS DATE)";
                $ret = $this->getRecordsAdHoc($sql);
                if ((int) $ret[0]["total"] != 0) {
                    $data["details"] = $record;
                    $data["details"]["nuevos"] = (int)$ret[0]["total"];
                    $body = $this->load->view(MOD_EMAIL . '/templates/newFiles', $data, true);
                    $params = array(
                        "id" => 0,
                        "id_client" => $record["id"],
                        "to" => $record["new_files_email"],
                        "reply_to" => "contacto@cloudcapture.cl",
                        "subject" => $record["new_files_alert"],
                        "body" => $body
                    );
                    $this->save($params, null);
                }
            }
            foreach ($clients['data'] as $record) {
                $emails = $this->get(array("where" => "id_client=". $record["id"]." AND verified IS null"));
                foreach ($emails['data'] as $mail) {
                    $ret = $EMAILING->send($mail);
                    if ($ret["status"] == "OK") {$this->save(array("id" => $mail["id"]), null);}
                }
            }

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function generateAlerts($values){
        try {
            $EMAILING=$this->createModel(MOD_EMAIL,"Emailing","Emailing");
            $EMAILS=$this->createModel(MOD_BACKEND,"Emails","Emails");
            $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
            $COUNTERS=$this->createModel(MOD_STATICS,"Counters","Counters");
            $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"mod_alert=1 AND alert_from_date<=getdate() AND cast(getdate() as time)>=cast(alert_fixed_time as time) AND dateadd(minute,30,cast(alert_fixed_time as time))>=cast(getdate() as time)"));
            if ((int)$clients["totalrecords"]==0){throw new Exception(lang("error_6001"),6001);}
            foreach ($clients['data'] as $record) {
               $emails=$EMAILS->get(array("where"=>"created>='".date(FORMAT_DATE_DB)." 00:00:00' AND created<='".date(FORMAT_DATE_DB)." 23:59:59' AND id_client=".$record["id"]));
               if ((int)$emails["totalrecords"]==0){
                  $filters=array(
                    "id_client"=>$record["id"],
                    "date_from"=>(date(FORMAT_DATE_DB,strtotime('-7 days',strtotime(date(FORMAT_DATE_DB))))." 00:00:00"),
                    "date_to"=>(date(FORMAT_DATE_DB)." 23:59:59")
                  );
                  $details=$COUNTERS->clientDetails($filters);
                  $data["details"]=$details;
                  $body=$this->load->view(MOD_EMAIL.'/templates/activity',$data, true);
                  $params=array(
                     "id"=>0,
                     "id_client"=>$record["id"],
                     "from" => "contacto@cloudcapture.cl",
                     "to"=>$record["alert_email"],
                     "cc"=>$record["alert_email_cc"],
                     "bcc"=>$record["alert_email_bcc"],
                     "reply_to"=>$record["alert_email_reply_to"],
                     "subject"=>"Resumen de actividad: Cloud-Capture",
                     "body"=>$body
                  );
                  $this->save($params,null);
               }
            }
            $emails=$EMAILS->get(array("where"=>"verified IS null"));
            foreach ($emails['data'] as $mail) {
               $ret=$EMAILING->send($mail);
               if($ret["status"]=="OK") {$this->save(array("id"=>$mail["id"]),null);}
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
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if ($fields==null) {
                    $fields = array(
                        'code' => opensslRandom(16),
                        'description' => "Email de alerta a clientes",
                        'created' => $this->now,
                        'verified' => null,
                        'offline' => null,
                        'fum' => $this->now,
                        'to' => $values["to"],
                        'cc' => $values["cc"],
                        'bcc' => $values["bcc"],
                        'reply_to' => $values["reply_to"],
                        'subject' => $values["subject"],
                        'body' => $values["body"],
                        'id_client' => secureEmptyNull($values,"id_client"),
                    );
                }
            } else {
                if ($fields==null) {
                    $fields = array(
                       'verified' => $this->now,
                       'fum' => $this->now,
                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
