<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Backend extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
	{
        $this->status=$this->init();
        $data["title"] = TITLE_GENERAL;
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header',$data, true);
        $data["footer"] = $this->load->view('common/_footer',$data, true);
        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $this->load->view('login',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function acceptNote() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'save';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'notes';
            $_POST['table'] = 'notes';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function listNotes() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null) {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'listNotes';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'notes';
            $_POST['table'] = 'notes';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function autoPostProc_8() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null) {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'autoPostProc';
            $_POST['module'] = MOD_POST;
            $_POST['model'] = 'Transvina_rendiciones';
            $_POST['table'] = 'Transvina_rendiciones';
            $_POST['id_client'] = 8;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function deleteNote() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'delete';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'notes';
            $_POST['table'] = 'notes';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function clientDetails() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'clientDetails';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'counters';
            $_POST['table'] = 'counters';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function generateNewFiles()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'generateNewFiles';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'emails';
            $_POST['table'] = 'emails';
            $_POST['check_token'] = false;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function generarArchivosManuales(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'generarCSVManual';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'counters';
            $_POST['table'] = 'counters';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function transfersDetails() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'transfersDetails';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'counters';
            $_POST['table'] = 'counters';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function transfersDetailsExcel() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'transfersDetailsExcel';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'counters';
            $_POST['table'] = 'counters';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function transfersPostDetails() {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'transfersPostDetails';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'post_proc';
            $_POST['table'] = 'post_proc';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getProfiles(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getProfiles';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'profiles';
            $_POST['table'] = 'profiles';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function setCounter(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'setCounter';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'counters';
            $_POST['table'] = 'counters';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function setStatus(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'save';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'File_status';
            $_POST['table'] = 'File_status';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function setPost(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'setPost';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'Post_proc';
            $_POST['table'] = 'Post_proc';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function setPre(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'setPre';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'Pre_proc';
            $_POST['table'] = 'Pre_proc';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function manualUpload(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'manualUpload';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'Pre_proc';
            $_POST['table'] = 'Pre_proc';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getFilePre(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getFilePre';
            $_POST['module'] = MOD_STATICS;
            $_POST['model'] = 'Pre_proc';
            $_POST['table'] = 'Pre_proc';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function generateAlerts(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'generateAlerts';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'Emails';
            $_POST['table'] = 'Emails';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UicreateToken(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'createToken';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UireadToken(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); 
            $_POST['function'] = 'readToken';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function logGeneral(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'forcedLogGeneral';
            $_POST['table'] = 'external';
           // log_message("error", "RELATED ".json_encode($_POST,JSON_PRETTY_PRINT));
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function authenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            if (!isset($_POST["username"])) {throw new Exception(lang("error_5104"),5104);}
            if (!isset($_POST["password"])) {throw new Exception(lang("error_5105"),5105);}
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'authenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function reAuthenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            if (!isset($_POST["id_user_active"]) or $_POST["id_user_active"]=="") {throw new Exception(lang("error_5107"),5107);}
            if (!isset($_POST["token_authentication"]) or $_POST["token_authentication"]=="") {throw new Exception(lang("error_5109"),5109);}
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'reAuthenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function linkDirect($page)
	{
        try {
            $data["callback"]="";
            $data["referente"]="";
            $data["track"]="";
            if(isset($_GET["return"])){$data["callback"]=$_GET["return"];}
            if(isset($_GET["referente"])){$data["referente"]=$_GET["referente"];}
            if(isset($_GET["track"])){$data["track"]=$_GET["track"];}
            $data["UsuarioAlta"]="WEB";
            $data["prefijo"]=".";
            switch ($page) {
               default:
                   $data["heading"]=lang('error_404');
                   $data["message"]=$page;
                   $this->load->view("errors/html/error_404",$data);
                   break;
            }
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function logged(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
            $menu=$FUNCTIONS->menuTree($_POST);
            $data["title"] = TITLE_GENERAL_TINY;
            $data["language"] = $this->language;
            $data["menu"] = $menu["data"];
            $html=$this->load->view("logged",$data,true);
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
            $this->output($return);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function logout(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'logout';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}


