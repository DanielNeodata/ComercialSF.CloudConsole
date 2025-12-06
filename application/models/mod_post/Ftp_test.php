<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Ftp_test extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function executeProc($file,$client){
        try {
            $ftp_server=$client["post_ftp_server"];//"xk000489.ferozo.com";
            $ftp_user_name=$client["post_ftp_username"];//"vadeftp@xk000489.ferozo.com";
            $ftp_user_pass=$client["post_ftp_password"];//"5504FT442wllfg";
            $remote_file = basename($file);

            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            ftp_pasv($conn_id, true);
            if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
                $status="OK";
                $code=2000;
                $msg="";
            } else {
                $status="ERROR";
                $code=6004;
                $msg=lang("error_6004");
            }
            ftp_close($conn_id);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$msg,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
