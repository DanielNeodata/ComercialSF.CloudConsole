<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(APPPATH.'third_party/phpseclib');
include_once APPPATH.'third_party/phpseclib/Net/SSH2.php';

class V_ssh2 {
    public $vSSH2;
    public function __construct($server)
    {
        $this->vSSH2 = new Net_SSH2($server[0]);
    }
    
    public function Execute($username,$password,$command){
        try {
            if (!$this->vSSH2->login($username, $password)) {
               throw new Exception("No se ha podido conectar al servidor remoto via SSH2");
            }    
            
            $message=$this->vSSH2->exec($command);
            return array("status"=>"OK","message"=>$message);
        } catch(Exception $e) {
            return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }
}
