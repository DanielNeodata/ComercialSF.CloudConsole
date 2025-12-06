<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
?>
<?php
    $html="<h5>Resumen de transacciones</h5>";
    $html.="<table style='width:100%;'>";
    $html.="   <tr>";
    $html.="      <td><label>¿Entre fechas?</label> <input id='chkDates' name='chkDates' type='checkbox' class='form-control chkDates text dbase' style='width:15px;height:15px;display:inline;' value='1'/></td>";
    $html.="   </tr>";
    $html.="</table>";
    $html.="<div class='row mr-1'>";
    foreach($controls as $control) {
        $html.="<div class='col-3'>".$control."</div>";
    }
    $html.="</div>";
    echo $html;
?>

<div class="divTransacciones container-full mt-1 p-0">

</div>

<script>
    var _auto = "<?php echo $auto;?>";
    if(_auto=="S") {    $(".cbo_id_client").change();}
    $(".comment").shorten();
</script>


