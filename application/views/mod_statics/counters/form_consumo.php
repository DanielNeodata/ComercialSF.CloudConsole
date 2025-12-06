<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
?>
<?php
    $valor=0.000233;
    $html="<h4>Consumo valorizado últimos 12 meses</h4><hr/>";
    $html.="<div class='row mr-1'>";
    $html.="   <div class='col-12'>";
    $html.="      <table class='table'>";
    $html.="         <tr style='background-color:silver;'>";
    $html.="            <td align='left'></td>";
    $html.="            <td align='center'><b>Año</b></td>";
    $html.="            <td align='center'><b>Mes</b></td>";
    $html.="            <td align='right'><b>Páginas</b></td>";
    $html.="            <td align='right'><b>UF</b></td>";
    $html.="         </tr>";
    foreach ((array)$consumo_mensual as $record){
        $status="Cerrado";
        $month = (int)date('m');
        $year = (int)date('Y');
        $uf = ((int)$record["pages"]*$valor);
        $color="#F0F0F0";
        if ($month==(int)$record["month"] && $year==(int)$record["year"]){
           $color="lightgreen";
           $status="<i>En curso</i>";
        }
        $html.="<tr style='background-color:".$color."'>";
        $html.="   <td align='left'>".$status."</td>";
        $html.="   <td align='center'>".$record["year"]."</td>";
        $html.="   <td align='center'>".$record["month"]."</td>";
        $html.="   <td align='right'>".$record["pages"]."</td>";
        $html.="   <td align='right'>".number_format($uf,6,",",".")."</td>";
        $html.="</tr>";
    }

    $html.="      </table>";
    $html.="   </div>";
    $html.="</div>";

    $html.="<h4>Consumo valorizado últimos 5 años</h4><hr/>";
    $html.="<div class='row mr-1'>";
    $html.="   <div class='col-12'>";
    $html.="      <table class='table'>";
    $html.="         <tr style='background-color:silver;'>";
    $html.="            <td align='left'></td>";
    $html.="            <td align='center'><b>Año</b></td>";
    $html.="            <td align='right'><b>Páginas</b></td>";
    $html.="            <td align='right'><b>UF</b></td>";
    $html.="         </tr>";
    foreach ((array)$consumo_anual as $record){
        $status="Cerrado";
        $year=(int)date('Y');
        $uf=((int)$record["pages"]*$valor);
        $color="#F0F0F0";
        $html.="<tr style='background-color:".$color."'>";
        $html.="   <td align='left'>".$status."</td>";
        $html.=   "<td align='center'>".$record["year"]."</td>";
        $html.=   "<td align='right'>".$record["pages"]."</td>";
        $html.=   "<td align='right'>".number_format($uf,6,",",".")."</td>";
        $html.="</tr>";
    }

    $html.="      </table>";
    $html.="   </div>";
    $html.="</div>";


    echo $html;
?>

<div class="divTransacciones container-full mt-1 p-0">

</div>

<script>
    var _auto = "<?php echo $auto;?>";
    if(_auto=="S") {    $(".cbo_id_client").change();}
</script>


