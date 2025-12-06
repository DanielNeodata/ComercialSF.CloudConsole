<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Post_proc extends MY_Model {
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
                'description' => "Registro de postprocesamiento",
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
                'id_client' => $id_client,
                'filename' => $values["filename"],
                'fullpath' => $values["fullpath"],
            );
            return parent::save(array("id"=>0),$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function setPost($values){
        try {
            $str=trim(file_get_contents("php://input"));
            $str=str_replace('"',"'",$str);
            $str=htmlspecialchars_decode($str,ENT_NOQUOTES);
            $str=str_replace("\u0022",'"',$str);
            $str=str_replace("\\\\","\\",$str);
            $str=str_replace("'","",$str);
            $values=json_decode($str,true);
                       
            return $this->processSetPost($values);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function processSetPost($values){
         try {
            $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
            $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$values["id_client"]));
            if ((int)$clients["totalrecords"]==0){throw new Exception(lang("error_6001"),6001);}
            $model=$clients["data"][0]["post_model"];
            $id_client=(int)$clients["data"][0]["id"];
            $filename=$values["filename"];
            $record=$this->get(array("where"=>"id_client=".$id_client." AND filename='".$filename."'"));
            if ((int)$record["totalrecords"]!=0){throw new Exception(lang("error_6003")." ID CLIENTE: ".$id_client,6003);}

            $path=(FILE_TEMP."/".$id_client);
            if (!file_exists($path)) {mkdir($path, 0777, true);}
            $path.=("/".$filename);
            $data=base64_decode($values["base64String"]);
            $params=array("id_client"=>$id_client,"filename"=>$filename,"fullpath"=>$path);
            $this->save($params,null);

            $ret=file_put_contents($path,$data,FILE_USE_INCLUDE_PATH);

            if($model!=""){
               $ACTIVE=$this->createModel(MOD_POST,$model,$model);
               return $ACTIVE->executeProc($path,$clients["data"][0]);
            }  else {
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
            return logError($e,__METHOD__ );
        }           
    }
    public function transfersPostDetails($values){
       try {
          $userdata=getUserProfile($this,$values["id_user_active"]);
          $sufixFilter=$userdata["data"][0]["sufixFilter"];

          $customDates=true;
          if(!isset($values["id_client"])){throw new Exception(lang("error_6001"),6001);}
          if(!isset($values["page"])){$values["page"]=1;}
          if(!isset($values["pagesize"])){$values["pagesize"]=25;}

          $id_client=$values["id_client"];
          $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
          $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$id_client));
          if ((int)$clients["totalrecords"]==0){throw new Exception(lang("error_6001"),6001);}

          if(!isset($values["date_from"])){$customDates=false;}
          if(!isset($values["date_to"])){$customDates=false;}

          $ret=buildWhere($values,$clients);
          if($values["search"]!="") {
             if ($ret["where"]!=""){$ret["where"].=" AND ";}
             $ret["where"].=(" filename LIKE '%".$values["search"]."%'");
          }

          $where=$ret["where"];
          $addWhere="";
          if($sufixFilter!=""){$addWhere=" AND filename LIKE '%".$sufixFilter."%'";}
          $where.=$addWhere;

          $order=" created DESC";
          $this->view="vw_post_proc";

          $all=$this->get(array("page"=>$values["page"],"pagesize"=>$values["pagesize"],"where"=>$where,"order"=>$order));
          $i=0;

          $all["post_proc"]=0;
          $all["post_resp"]=0;
          $all["post_stat"]=0;
          if ($customDates) {
              $where=(" id_client=".$id_client." AND created>='".$values["date_from"]."' AND created<='".$values["date_to"]."'");

              $sql=("SELECT count(id) as total FROM mod_statics_post_proc WHERE ".$where);
              $r1=$this->getRecordsAdHoc($sql);
              $sql=("SELECT count(id) as total FROM mod_statics_post_responses WHERE ".$where);
              $r2=$this->getRecordsAdHoc($sql);
              $sql=("SELECT count(id) as total FROM mod_statics_file_status WHERE ".$where);
              $r3=$this->getRecordsAdHoc($sql);
              $all["post_proc"]=$r1[0]["total"];
              $all["post_resp"]=$r2[0]["total"];
              $all["post_stat"]=$r3[0]["total"];
          }

          $all["sufixFilter"]=$sufixFilter;
		  $all["customDates"]=$customDates;
          $all["csv_manual"]=($customDates and (int)$clients["data"][0]["id_type_procesamiento"]==2);
          return $all;
       }
       catch (Exception $e){
          return logError($e,__METHOD__ );
       }
    }
}
