<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Emailing extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function send($values){
        try {
			$config = Array(
				"protocol" => "SMTP",
				"smtp_host" => "172.16.56.4",
				"smtp_timeout" => 25,
				"smtp_port" => 25,
				"smtp_user" => "",
				"smtp_pass" => "",
				"mailtype"  => "plain",
				"wordwrap"  => false,
				"charset"   => "utf-8",
				"crlf"   => "\r\n"
			);			

/* DIRECTO contra VPS2*/
/*	$config = Array(
		"protocol" => "SMTP",
		"smtp_host" => "154.56.51.151",
		"smtp_timeout" => 25,
		"smtp_port" => 465,
		"smtp_user" => "no-reply@neodata.ar",
		"smtp_pass" => "NPI1g*36sI",
		"mailtype"  => "html",
		"wordwrap"  => false,
		"charset"   => "utf-8",
		"crlf"   => "\r\n",
		"smtp_crypto"=>"tls",
	);
*/
            $this->load->library('email');
	        $this->email->initialize($config);
            $this->email->send_multipart=FALSE;
            $this->email->from("no-reply@neodata.ar","No-Reply");
            $this->email->to($values["to"]);
            $this->email->cc($values["cc"]);
            $this->email->bcc($values["bcc"]);
            $this->email->reply_to($values["reply_to"]);
            $this->email->subject($values["subject"]);
            $this->email->message($values["body"]);
            if(!isset($values["directAttach"])){$values["directAttach"]="";}
            if(!isset($values["names"])){$values["names"]="";}
            if(!isset($values["attachs"])){$values["attachs"]="";}
            $names=$values["names"];
            $attachs=$values["attachs"];
            $vattachs=array();
            if ($attachs!=""){
                $filenames=explode("[NAME]",$names);
                $files=explode("[FILE]",$attachs);
                $i=0;
                foreach($files as $file) {
                   if($file!=""){
				      if (substr($file, 0, 4)!="http") {
                        $fullPath=(FILES_ATTACHED."/".uniqid("",true)."-".$filenames[$i]);
	                    array_push($vattachs,$fullPath);
	                    saveBase64ToFile(array("data"=>$file,"path"=>FILE_ATTACHED,"fullPath"=>$fullPath));
					  } else {
						$fullPath=$file;
					  }
					  $this->email->attach($fullPath);
                      $i+=1;
                   }
                }
            }
			if ($values["directAttach"]!=""){$this->email->attach($values["directAttach"]);}
            $sended=$this->email->send(true);
            foreach($vattachs as $file) {unlink($file);}
			if ($values["directAttach"]!=""){unlink($values["directAttach"]);}
            if (!$sended) {
				$errors = $this->email->print_debugger();
log_message("error", "ERROR EMAIL PRE THROW ".json_encode($errors,JSON_PRETTY_PRINT));			
				throw new Exception($errors);
			}
            return array("status"=>"OK","message"=>lang('error_5601'));
        } catch(Exception $e) {
log_message("error", "ERROR EMAIL  ".json_encode($e,JSON_PRETTY_PRINT));			
            return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }
}
