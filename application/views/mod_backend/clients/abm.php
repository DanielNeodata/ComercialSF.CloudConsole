<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row shadow p-1 pb-2 mt-1'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"code","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"description","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getHtmlResolved($parameters,"controls","id_application",array("col"=>"col-md-3",));
$html.=getHtmlResolved($parameters,"controls","id_type_procesamiento",array("col"=>"col-md-2",));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"pages_contract","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"date_from_contract","type"=>"date","class"=>"form-control date dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"date_to_contract","type"=>"date","class"=>"form-control date dbase validate"));
$html.="</div>";

$html.="<div class='form-row shadow p-1 pb-2 mt-3'>";
$html.="<div class='col-12'><h4>Definiciones de PRE procesamiento general</h4></div>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"pre_model","type"=>"text","class"=>"form-control text dbase"));
$html.="<div class='col-12'><span class='badge badge-info'>Los datos de FTP pueden o no ser usados, dependiendo de la lógica aplicada al cliente</span></div>";
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"pre_ftp_server","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"pre_ftp_port","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"pre_ftp_username","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"pre_ftp_password","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row shadow p-1 pb-2 mt-3'>";
$html.="<div class='col-12'><h4>Definiciones de POST procesamiento general</h4></div>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"post_model","type"=>"text","class"=>"form-control text dbase"));
$html.="<div class='col-12'><span class='badge badge-info'>Los datos de FTP pueden o no ser usados, dependiendo de la lógica aplicada al cliente</span></div>";
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"post_ftp_server","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"post_ftp_port","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"post_ftp_username","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"post_ftp_password","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html .= "<div class='row'>";
$html .= "   <div class='col-1'>";
$html .= "	  <div class='form-row p-0 pb-2 mt-2'>";
$html .= getInput($parameters, array("col" => "col-12 text-center", "name" => "mod_new_files", "type" => "checkbox", "class" => "form-control text dbase text-center mod_new_files"));
$html .= "	  </div>";
$html .= "   </div>";
$html .= "   <div class='col-11'>";
$html .= "	  <div class='form-row shadow p-1 pb-2 mt-2 divAdNew d-none'>";
$html .= "	     <h4>Email para alerta de nuevos archivos</h4>";
$html .= getInput($parameters, array("col" => "col-md-12", "name" => "new_files_email", "type" => "text", "class" => "form-control text dbase"));
$html .= getTextArea($parameters, array("col" => "col-md-12", "name" => "new_files_alert", "class" => "form-control text dbase"));
$html .= "	  </div>";
$html .= "   </div>";
$html .= "</div>";

$html.="<div class='row'>";
$html.="   <div class='col-1'>";
$html.="	  <div class='form-row p-0 pb-2 mt-2'>";
$html.=getInput($parameters,array("col"=>"col-12 text-center","name"=>"mod_ad_hoc","type"=>"checkbox","class"=>"form-control text dbase text-center mod_ad_hoc"));
$html.="	  </div>";
$html.="   </div>";
$html.="   <div class='col-11'>";
$html.="	  <div class='form-row shadow p-1 pb-2 mt-2 divAdHoc d-none'>";
$html.="	     <h4>Email para alerta interna</h4>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"ad_hoc_email","type"=>"text","class"=>"form-control text dbase"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"ad_hoc_notification","class"=>"form-control text dbase"));
$html.="	  </div>";
$html.="   </div>";
$html.="</div>";

$html.="<div class='row'>";
$html.="   <div class='col-1'>";
$html.="	  <div class='form-row p-0 pb-2 mt-2'>";
$html.=getInput($parameters,array("col"=>"col-12 text-center","name"=>"mod_alert","type"=>"checkbox","class"=>"form-control text dbase text-center mod_alert"));
$html.="	  </div>";
$html.="   </div>";
$html.="   <div class='col-11'>";
$html.="	  <div class='form-row shadow p-1 pb-2 mt-2 divAlert d-none'>";
$html.="	     <h4>Alertas por email</h4>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"alert_email","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"alert_email_cc","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"alert_email_bcc","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"alert_email_reply_to","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"alert_from_date","type"=>"date","class"=>"form-control date dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"alert_fixed_time","type"=>"time","class"=>"form-control time dbase"));
$html.="	  </div>";
$html.="   </div>";
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
   $("body").off("change", ".mod_new_files").on("change", ".mod_new_files", function () {
   	   if ($(this).prop("checked")) {
	      $(".divAdNew").removeClass("d-none")
	   } else {
	      $(".divAdNew").addClass("d-none")
	   }
   });
   $("body").off("change", ".mod_alert").on("change", ".mod_alert", function () {
   	   if ($(this).prop("checked")) {
	      $(".divAlert").removeClass("d-none")
	   } else {
	      $(".divAlert").addClass("d-none")
	   }
   });
   $("body").off("change", ".mod_ad_hoc").on("change", ".mod_ad_hoc", function () {
   	   if ($(this).prop("checked")) {
	      $(".divAdHoc").removeClass("d-none")
	   } else {
	      $(".divAdHoc").addClass("d-none")
	   }
   });
   $(".mod_new_files").change();
   $(".mod_alert").change();
   $(".mod_ad_hoc").change();
</script>

