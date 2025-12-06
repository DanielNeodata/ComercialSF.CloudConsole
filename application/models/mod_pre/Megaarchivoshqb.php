<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Megaarchivoshqb extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function executeProc($client){
        try {
            $items=array();
            $subDirs=array();

            $id_client=$client["id"];
            /*ONLINE*/
            $subDirFtp="/home/andina/ABBYY/";
            
            /*Test desarrollo*/
            //$subDirFtp="/datos/andina/ABBY/";

            $path=(FILE_FTP."/".$id_client);
            if (!file_exists($path)) {mkdir($path, 0777, true);}

            $ftp_server=$client["pre_ftp_server"];
            $ftp_port=$client["pre_ftp_port"];
            $ftp_user_name=$client["pre_ftp_username"];
            $ftp_user_pass=$client["pre_ftp_password"];

            $conn_id=ssh2_connect($ftp_server, intval($ftp_port));
			$methods = ssh2_methods_negotiated($conn_id);

            $ret=ssh2_auth_password($conn_id, $ftp_user_name, $ftp_user_pass);
            $sftp=ssh2_sftp($conn_id);

            $list=array();
            $handle = opendir("ssh2.sftp://".intval($sftp).$subDirFtp);

			while (false !== ($fileName = readdir($handle))){
                $part=explode('.', $fileName);
                $ext=strtoupper(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($fileName!="." and $fileName!=".." and $ext!="THUMBS") {
                    if ($ext=="") {
						$path=(FILE_FTP."/".$id_client."/".$fileName);
						if (!file_exists($path)) {mkdir($path, 0777, true);}
						array_push($subDirs, $fileName);
					}
                }
            }
            closedir($handle);

            $iLimit=10;
			foreach ($subDirs as $sDir) { 
			    $i=0;
                $handle = opendir("ssh2.sftp://".intval($sftp).$subDirFtp.$sDir."/");
                while (false !== ($fileName = readdir($handle)) and $i<$iLimit){
                    if ($fileName!="." and $fileName!=".." and $ext!="THUMBS") {
						$path=(FILE_FTP."/".$id_client."/".$sDir);
                        $file=($path."/".$fileName);
                        $remoteFile=("ssh2.sftp://".intval($sftp).$subDirFtp.$sDir."/".$fileName);
                        $data=file_get_contents($remoteFile);
                        file_put_contents($file,$data,FILE_USE_INCLUDE_PATH);
                        array_push($items, $file);
                        unlink($remoteFile);
                   }
				   $i+=1;
                }
                closedir($handle);
            }
            $out=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "items"=>$items,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "compressed"=>false
            );
			//ssh2_disconnect($conn_id);
			return $out;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
