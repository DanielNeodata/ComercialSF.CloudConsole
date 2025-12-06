<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Ftp_download extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function executeProc($client){
        try {
            $id_client=$client["id"];
            $PRE_PROC=$this->createModel(MOD_STATICS,"Pre_proc","Pre_proc");

            $subDirFtp="/tmp/";
            $items=array();
            $path=(FILE_FTP."/".$id_client);
            if (!file_exists($path)) {mkdir($path, 0777, true);}

            $ftp_server=$client["pre_ftp_server"];
            $ftp_user_name=$client["pre_ftp_username"];
            $ftp_user_pass=$client["pre_ftp_password"];

            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            ftp_pasv($conn_id, true);

            $list = ftp_mlsd($conn_id, $subDirFtp);
            /*Copy files to local server dir*/
            foreach ($list as $f) { 
                if ($f["type"]=="file") {
                    $filename=($path."/".$f["name"]);
                    ftp_get($conn_id, $filename, ($subDirFtp.$f["name"])); 
                    array_push($items, $filename);
                }
            }
            /*Delete files in remote ftp server*/
            foreach ($list as $f) { 
                if ($f["type"]=="file") {
                    $filename=($path."/".$f["name"]);
                    $PRE_PROC->save(array("id"=>0,"id_client"=>$id_client,"filename"=>$f["name"],"fullpath"=>$filename));
                    ftp_delete($conn_id, ($subDirFtp.$f["name"])); 
                }
            }
            ftp_close($conn_id);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "items"=>$items,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
