<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Megaarchivoshqb extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function executeProc($file,$client){
        try {
            $subDirFtp="/home/andina/EntradaXML/";
            
            /*Test desarrollo*/
            //$subDirFtp="/datos/andina/EntradaXML/";

            $ftp_server=$client["post_ftp_server"];
            $ftp_port=$client["pre_ftp_port"];
            $ftp_user_name=$client["post_ftp_username"];
            $ftp_user_pass=$client["post_ftp_password"];

            $remote_file = ($subDirFtp.basename($file));

            $conn_id=ssh2_connect($ftp_server, intval($ftp_port));
			$methods = ssh2_methods_negotiated($conn_id);

            $ret=ssh2_auth_password($conn_id, $ftp_user_name, $ftp_user_pass);
            $sftp=ssh2_sftp($conn_id);
          
            $stream = fopen("ssh2.sftp://".intval($sftp).$remote_file, 'w');
            $data = file_get_contents($file);
            fwrite($stream, $data);
            fclose($stream);

			ssh2_disconnect($conn_id);
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
}
