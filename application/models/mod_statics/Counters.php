<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Counters extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $userdata=getUserProfile($this,$values["id_user_active"]);
            $where="";
            $empty=true;
            $id_actual=-1;
            $data["auto"]="N";
            switch((int)$values["id_type_user_active"]) {
                case 77: //Administrator
                   break;
                default:
                   $data["auto"]="S";
                   $empty=false;
                   //$id_actual=$userdata[0]["id_client"];
                   //if($userdata["data"][0]["id_client"]==0){$userdata["data"][0]["id_client"]="";}
                   //if($userdata["data"][0]["id_client"]!=""){$where=("id=".$userdata["data"][0]["id_client"]);}
                   $where.=" id IN (SELECT id_client FROM ".MOD_BACKEND."_rel_users_clients WHERE id_user=".$values["id_user_active"].")";
                   break;
            }

            $parameters_id_client=array(
                "model"=>(MOD_BACKEND."/Clients"),
                "table"=>"Clients",
                "name"=>"id_client",
                "class"=>"form-control dbase cbo_id_client",
                "empty"=>$empty,
                "id_actual"=>$id_actual,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1,"where"=>$where),
            );

            $data["controls"]=array(
                "id_client"=>"<span class='badge badge-primary'>Cliente a analizar</span>".getCombo($parameters_id_client,$this),
                "date_from"=>"<span class='badge badge-primary dDates d-none'>".lang('p_date_from')."</span> <input id='browser_date_from' name='browser_date_from' type='date' class='browser_date_from form-control dDates d-none'/>",
                "date_to"=>"<span class='badge badge-primary dDates d-none'>".lang('p_date_to')."</span> <input id='browser_date_to' name='browser_date_to' type='date' class='browser_date_to form-control dDates d-none'/>",
                "btnAction"=>"<a href='#' class='btn btn-success btn-raised btn-counters btn-sm'>Consultar</a>",
            );

            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_STATICS."/counters/form",$data,true);
            logGeneral($this,$values,__METHOD__);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form_consumo($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_consumo"));

            $sql="SELECT TOP 12 sum(pages) as pages, month(created) as month, year(created) as year FROM mod_statics_counters GROUP BY month(created), year(created) ORDER BY 3 DESC, 2 DESC";
            $data["consumo_mensual"]=$this->getRecordsAdHoc($sql);

            $sql="SELECT TOP 5 sum(pages) as pages, year(created) as year FROM mod_statics_counters GROUP BY year(created) ORDER BY 2 DESC";
            $data["consumo_anual"]=$this->getRecordsAdHoc($sql);

            $html=$this->load->view(MOD_STATICS."/counters/form_consumo",$data,true);
            logGeneral($this,$values,__METHOD__);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data["consumo"],
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $id=0;
            if ($fields==null){
                $fields = array(
                    'code' => opensslRandom(16),
                    'description' => "APILinkerCounter()",
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_user' => secureEmptyNull($values,"id_user"),
                    'id_client' => secureEmptyNull($values,"id_client"),
                    'filename' => $values["filename"],
                    'filepath' => $values["filepath"],
                    'pages' => $values["pages"],
                    'extension' => $values["extension"],
                    'mime_type' => $values["mime_type"],
                    'json' => $values["json"],
                    'processed' => 0,
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
    public function setCounter($values){
        return $this->save($values,null);
    }
    public function transfersDetailsExcel($values){
       try {
          ini_set('memory_limit', '-1');
          $data = array ();
          $dir = './storage/';
          foreach (glob($dir."*.csv") as $file) {
             //if(time() - filectime($file) > 86400){unlink($file);}
			 unlink($file);
          }
		  $current_timestamp = time();
          $filename=($dir.$current_timestamp.'.csv');
          $values["verifylink"]=false;
          switch($values["tab"]) {
             case "detalles":
                $ret=$this->transfersDetails($values);
                //array_push($data, '"ID","FECHA","ARCHIVO","SLA","PAGINAS","DIRECTORIO","ESTADO"');
                array_push($data, '"ID","FECHA","ARCHIVO","SLA","PAGINAS"');
                foreach ($ret['data'] as $record) {
                   array_push($data, '"'.$record["id"].'","'.$record["created"].'","'.$record["filename"].'","'.$record["sla"].'","'.$record["pages"].'"');
                }
                break;
             case "postprocesados":
                $POST_PROC=$this->createModel(MOD_STATICS,"post_proc","post_proc");
                $ret=$POST_PROC->transfersPostDetails($values);

                //array_push($data, '"FECHA","ARCHIVO","ESTADO","RESPUESTA"');
                array_push($data, '"FECHA","ARCHIVO","ESTADO"');
                foreach ($ret['data'] as $record) {
                   $raw=str_replace('"',"",$record["raw_data"]);
                   $raw=str_replace(',',"",$raw);
                   $raw=str_replace('\t',"",$raw);
                   $raw=str_replace('\n',"",$raw);
                   $raw=str_replace('\r',"",$raw);

                   $excep=str_replace('"',"",$record["exception"]);
                   $excep=str_replace(',',"",$excep);
                   $excep=str_replace('\t',"",$excep);
                   $excep=str_replace('\n',"",$excep);
                   $excep=str_replace('\r',"",$excep);

                   //array_push($data, '"'.$record["created"].'","'.$record["filename"].'","'.$excep.'","'.$raw.'"');
                   array_push($data, '"'.$record["created"].'","'.$record["filename"].'","'.$excep.'"');
                }
                break;
          }
          $fp = fopen($filename, 'w');
          foreach($data as $line){
             $val = explode(",",$line);
             fputcsv($fp, $val);
          }
          fclose($fp);

          return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$filename,
                "compressed"=>false
          );
       }
       catch (Exception $e){
				log_message("error", "RELATED ERROR ".json_encode($e,JSON_PRETTY_PRINT));
          return logError($e,__METHOD__ );
       }
    }
    public function transfersDetails($values){
       try {
            if (!isset($values["verifylink"])){$values["verifylink"]=true;}
          $userdata=getUserProfile($this,$values["id_user_active"]);
          $sufixFilter=$userdata["data"][0]["sufixFilter"];

          $customDates=true;
          if(!isset($values["id_client"])){throw new Exception(lang("error_6001"),6001);}
          if(!isset($values["page"])){$values["page"]=1;}
          if(!isset($values["pagesize"])){$values["pagesize"]=25;}
          if(!isset($values["where"])){$values["where"]="";}

          $FILE_STATUS=$this->createModel(MOD_STATICS,"File_status","File_status");
          $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
          $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$values["id_client"]));
          if ((int)$clients["totalrecords"]==0){throw new Exception(lang("error_6001"),6001);}

          if(!isset($values["date_from"])){$customDates=false;}
          if(!isset($values["date_to"])){$customDates=false;}

          $ret=buildWhere($values,$clients);
          $where=$ret["where"];
          if ($values["where"]!=""){$where.=" AND ".$values["where"];}
          if ($values["extension"]!="") {$where.=(" AND extension='".$values["extension"]."'");}
          $addWhere="";
          if($sufixFilter!=""){$addWhere=" AND filepath LIKE '%".$sufixFilter."%'";}
          $where.=$addWhere;
          $order=" extension ASC,filename ASC, created DESC";


          $this->view="vw_counters";
          if ($values["verifylink"]) {
                if (strpos(strtoupper($values["where"]), "NA")) {$where = explode("filename", $where)[0]." status=0";}
                $all=$this->get(array("page"=>$values["page"],"pagesize"=>$values["pagesize"],"where"=>$where,"order"=>$order));
		  } else {
                log_message("error", "RELATED 2222" . json_encode($where, JSON_PRETTY_PRINT));
                $all=$this->get(array("page"=>1,"pagesize"=>-1,"where"=>$where,"order"=>$order));
		  }
          $i=0;

          if ($values["verifylink"]) {
              $FILE_STATUS->view="vw_file_status";
              foreach ($all["data"] as $record) {
                 $innerWhere="id_client=".$record["id_client"]." AND filename='".$record["filename"]."'";
                 $addWhere="";
                 if($sufixFilter!=""){$addWhere=" AND id_file LIKE '%".$sufixFilter."%'";}
                 $innerWhere.=$addWhere;
                 $file_status=$FILE_STATUS->get(array("pagesize"=>-1,"order"=>"created DESC","where"=>$innerWhere));
                 $all["data"][$i]["status"]=$file_status["data"];

                 $path_images=$record["unc_storage"];
                 $dir=basename($path_images);
                 $url_images=("http://162.241.157.179:8325/".$dir."/");
                 $evalFile=($url_images.$all["data"][$i]["filename"]);
                 $headers = @get_headers($evalFile);
                 if($headers && strpos( $headers[0], '200')) {$all['data'][$i]["filename"]=$evalFile;}
                 $i+=1;
              }
          }
          $all["sufixFilter"]=$sufixFilter;
		  $all["customDates"]=$customDates;
          return $all;
       }
       catch (Exception $e){
          return logError($e,__METHOD__ );
       }
    }
    public function clientDetails($values){
       try {
          $userdata=getUserProfile($this,$values["id_user_active"]);
          $sufixFilter=$userdata["data"][0]["sufixFilter"];

          $customDates=true;
          if(!isset($values["id_client"])){throw new Exception(lang("error_6001"),6001);}

          $CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
          $clients=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$values["id_client"]));
          if ((int)$clients["totalrecords"]==0){throw new Exception(lang("error_6001"),6001);}
          
          if(!isset($values["date_from"])){$customDates=false;}
          if(!isset($values["date_to"])){$customDates=false;}

          $ret=buildWhere($values,$clients);
          $where=$ret["where"];
          $group=" convert(varchar,created,102),extension,day(created),month(created),year(created)"; 
          $order=" convert(varchar,created,102) DESC, extension ASC, day(created) DESC,month(created) DESC,year(created) DESC";

          $addWhere="";
          if($sufixFilter!=""){$addWhere=" AND 1=2";}
          $sql="SELECT count(id) as files,sum(pages) as pages, convert(varchar,created,102), extension,day(created) as [day],month(created) as [month], year(created) as [year] ";
          $sql.=" FROM ".MOD_STATICS."_counters ";
          $sql.=" WHERE ".$where; 
          $sql.=" GROUP BY ".$group;
          $sql.=" ORDER BY ".$order;
          $details=$this->getRecordsAdHoc($sql);

          $addWhere="";
          if($sufixFilter!=""){$addWhere=" AND filepath LIKE '%".$sufixFilter."%'";}

          $sql="SELECT sum(isnull(processed,0)) as procesadas, sum(iif(isnull(processed,0)!=0,1,0)) as files_proc, count(id) as files,sum(pages) as pages,filepath  ";
          //$sql="SELECT count(id) as files,sum(pages) as pages,filepath  ";
          $sql.=" FROM ".MOD_STATICS."_counters ";
          $sql.=" WHERE ".$where.$addWhere; 
          $sql.=" GROUP BY filepath";
          $sql.=" ORDER BY filepath ASC";

            log_message("error", "RELATED " . json_encode($sql, JSON_PRETTY_PRINT));

		  $byFolder=$this->getRecordsAdHoc($sql);
          $i=0;
          foreach ($byFolder as $record) {
             if ($customDates) {
                 $sqlNotes="SELECT count(id) as total FROM ".MOD_STATICS."_notes ";
                 $sqlNotes.=" WHERE created>='".$ret["date_from"]."' AND created <='".$ret["date_to"]."' AND id_client=".$ret["id_client"]." AND '".$record["filepath"]."' LIKE '%'+code";
		         $hasNotes=$this->getRecordsAdHoc($sqlNotes);
                 $byFolder[$i]["hasNotes"]=$hasNotes[0]["total"];
             } else {
                 $byFolder[$i]["hasNotes"]=0;
             }
             $i+=1;
          }
          $Notes=null;
          if ($customDates) {
              $sqlNotes="SELECT * FROM ".MOD_STATICS."_notes ";
              $sqlNotes.=" WHERE created>='".$ret["date_from"]."' AND created <='".$ret["date_to"]."' AND id_client=".$ret["id_client"]." ORDER BY created DESC";
		      $Notes=$this->getRecordsAdHoc($sqlNotes);
          }

          $addWhere="";
          if($sufixFilter!=""){$addWhere=" AND 1=2";}
          $sql="SELECT count(id) as files,sum(pages) as pages, extension";
          $sql.=" FROM ".MOD_STATICS."_counters ";
          $sql.=" WHERE ".$where; 
          $sql.=" GROUP BY extension";
          $sql.=" ORDER BY extension ASC";
		  $totals=$this->getRecordsAdHoc($sql);

          $addWhere="";
          if($sufixFilter!=""){$addWhere=" AND 1=2";}
          $sql="SELECT sum(pages) as pages FROM ".MOD_STATICS."_counters ";
          $sql.=" WHERE ".$where; 
          $used=$this->getRecordsAdHoc($sql);
          
          $clients["sufixFilter"]=$sufixFilter;
          $clients["totals"] = $totals;
          $clients["details"] = $details;
          $clients["byFolder"] = $byFolder;
          $clients["notes"]=$Notes;
          $clients["used"] = $used;
          $clients["customDates"] = $customDates;
          $clients["date_from"] = date(FORMAT_DATE_DMY, strtotime($ret["date_from"]));
          $clients["date_to"] = date(FORMAT_DATE_DMY, strtotime($ret["date_to"]));
          return $clients;
       }
       catch (Exception $e){
          return logError($e,__METHOD__ );
       }
    }
    public function generarCSVManual($values){
        set_time_limit(0);
        $id_client=(int)$values["id_client"];
        $generated=(int)$values["generated"];
        //$CLIENTS=$this->createModel(MOD_BACKEND,"Clients","Clients");
        //$client=$CLIENTS->get(array("pagesize"=>-1,"where"=>"id=".$id_client));
        $t1=0;
        $t2=0;
        $t3=0;
        switch($id_client) {
           case 8: //Transviña rendiciones
                $TRANSVINA_RENDICIONES=$this->createModel(MOD_POST,"Transvina_rendiciones","Transvina_rendiciones");

                $FILE_STATUS=$this->createModel(MOD_STATICS,"file_status","file_status");
                $where=(" id_client=".$id_client." AND created>='".$values["date_from"]."' AND created<='".$values["date_to"]."'");
                $sql=("SELECT * FROM ".MOD_STATICS."_counters WHERE ".$where);
                $records=$this->getRecordsAdHoc($sql);

                if ($generated==0) {
                    $path=(FILE_TEMP."/".$id_client);
                    if (!file_exists($path)) {mkdir($path, 0777, true);}
                    deleteOldFiles($path,1,'minute');
                    foreach ($records as $item) {
                        $file=($path."/".$item["filename"].".csv");
                        file_put_contents($file, " ");
                        $FILE_STATUS->save(array("fast"=>true,"created"=>$this->now,"id_file"=>null,"id_client"=>$id_client,"filename"=>$item["filename"],"status"=>"Procesado"),null);
                    }
                    $sql=("UPDATE ".MOD_STATICS."_counters SET processed=pages WHERE ".$where);
                    $this->execAdHoc($sql);
                }

                $TRANSVINA_RENDICIONES->autoPostProc($id_client);

                $sql=("SELECT count(id) as total FROM mod_statics_post_proc WHERE ".$where);
                $r1=$this->getRecordsAdHoc($sql);
                $sql=("SELECT count(id) as total FROM mod_statics_post_responses WHERE ".$where);
                $r2=$this->getRecordsAdHoc($sql);
                $sql=("SELECT count(id) as total FROM mod_statics_file_status WHERE ".$where);
                $r3=$this->getRecordsAdHoc($sql);

                $t1=$r1[0]["total"];
                $t2=$r2[0]["total"];
                $t3=$r3[0]["total"];

                $CONTROL=$this->createModel(MOD_STATICS,"Control","Control");
                $params=array(
                    "id_client"=>$id_client,
                    "date_from"=>$values["date_from"],
                    "date_to"=>$values["date_to"],
                    "post_proc"=>$t1,
                    "post_resp"=>$t2,
                    "post_stat"=>$t3
                );
                $CONTROL->save($params,null);
                break;
        }
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>null,
            "compressed"=>false,
            "post_proc"=>$t1,
            "post_resp"=>$t2,
            "post_stat"=>$t3,
        );
    }
}
