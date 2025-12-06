<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row shadow p-1 pb-2 mt-1'>";
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"code","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-8","name"=>"description","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"sla","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getHtmlResolved($parameters,"controls","id_user",array("col"=>"col-md-4"));
$html.=getHtmlResolved($parameters,"controls","id_client",array("col"=>"col-md-4"));
$html.="</div>";

$html.="<div class='form-row shadow p-1 pb-2 mt-2'>";
$html.="<div class='col-12'><h4>Activación</h4></div>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"mm_alive","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"time_from","type"=>"time","class"=>"form-control time dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"time_to","type"=>"time","class"=>"form-control time dbase validate"));
$html.="</div>";

$html.="<div class='form-row shadow p-1 pb-2 mt-2'>";
$html.="<div class='col-12'><h4>Módulos</h4></div>";
$html.=getInput($parameters,array("col"=>"col-md-1 text-center","custom"=>"disabled checked","name"=>"mod_files","type"=>"checkbox","class"=>"form-control text dbase text-center"));
$html.=getInput($parameters,array("col"=>"col-md-1 text-center","custom"=>"disabled checked","name"=>"mod_pages","type"=>"checkbox","class"=>"form-control text dbase text-center"));
$html.=getInput($parameters,array("col"=>"col-md-1 text-center","name"=>"pdf","type"=>"checkbox","class"=>"form-control text dbase text-center"));
$html.=getInput($parameters,array("col"=>"col-md-1 text-center","name"=>"tiff","type"=>"checkbox","class"=>"form-control text dbase text-center"));
$html.="</div>";

$html.="<div class='form-row shadow p-1 pb-2 mt-2'>";
$html.="<h4>Preprocesamiento</h4>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"unc_source","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-10","name"=>"unc_target","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getHtmlResolved($parameters,"controls","sufix_subdirs",array("col"=>"col-md-2"));

$html.=getInput($parameters,array("col"=>"col-md-10","name"=>"unc_storage","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"ttl_storage","type"=>"number","class"=>"form-control number dbase validate"));
$html.="</div>";

$html.="<div class='row'>";
$html.="   <div class='col-1'>";
$html.="	  <div class='form-row p-0 pb-2 mt-2'>";
$html.=getInput($parameters,array("col"=>"col-12 text-center","name"=>"mod_post","type"=>"checkbox","class"=>"form-control text dbase text-center mod_post"));
$html.="	  </div>";
$html.="   </div>";
$html.="   <div class='col-11'>";
$html.="	  <div class='form-row shadow p-1 pb-2 mt-2 divPost d-none'>";
$html.="	     <h4>Postprocesamiento</h4>";
$html.=getInput($parameters,array("col"=>"col-12","name"=>"post_unc_source","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-8","name"=>"post_unc_target","type"=>"text","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","id_type_end",array("col"=>"col-4"));
$html.=getInput($parameters,array("col"=>"col-12","name"=>"post_unc_bad","type"=>"text","class"=>"form-control text dbase"));
$html.="	  </div>";
$html.="   </div>";
$html.="</div>";

$html.="<div class='row'>";
$html.="   <div class='col-1'>";
$html.="      <div class='form-row p-0 pb-2 mt-2'>";
$html.=getInput($parameters,array("col"=>"col-12 text-center","name"=>"mod_status","type"=>"checkbox","class"=>"form-control text dbase text-center mod_status"));
$html.="      </div>";
$html.="   </div>";
$html.="   <div class='col-11'>";
$html.="	  <div class='form-row shadow p-1 pb-2 mt-2 divStatus d-none'>";
$html.="	     <h4>Control de estado</h4>";
$html.=getInput($parameters,array("col"=>"col-12","name"=>"db_source","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-12 d-none","name"=>"db_target","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-12","name"=>"sql_status","type"=>"text","class"=>"form-control text dbase"));
$html.="      </div>";
$html.="   </div>";
$html.="</div>";

$html.="<div class='form-row d-none'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"single_page","type"=>"checkbox","class"=>"form-control text dbase"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
   $("body").off("change", ".mod_status").on("change", ".mod_status", function () {
   	   if ($(this).prop("checked")) {
	      $(".divStatus").removeClass("d-none")
	   } else {
	      $(".divStatus").addClass("d-none")
	   }
   });
   $("body").off("change", ".mod_post").on("change", ".mod_post", function () {
   	   if ($(this).prop("checked")) {
	      $(".divPost").removeClass("d-none")
	   } else {
	      $(".divPost").addClass("d-none")
	   }
   });
   $(".mod_status").change();
   $(".mod_post").change();
</script>

