<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "DATOS email ".json_encode($patient,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div style='font-family:arial;'>
<table>
   <tr>
	  <td valign='middle'>
		<span style='font-weight:bold;'>Notificación de actividad</span> - <b><?php echo $details["data"][0]["description"];?></b>
	  </td>
   </tr>
</table>

<h5>Desde el día <?php echo $details["date_from"];?> hasta el día <?php echo $details["date_to"];?></h5>

<?php
$html="";
foreach ($details['totals'] as $total) {
    $html="<table>";
    $html.="<tr style='background-color:grey;font-weight:bold;'>";
    $html.="   <td>Fecha</td>";
    $html.="   <td align='right'>Archivos</td>";
    $html.="   <td align='right'>Páginas</td>";
    $html.="</tr>";
    $i=0;
	foreach ($details['details'] as $item) {
	   if ($total["extension"]==$item["extension"]) {
          $style="";
          if ($i==0){$style="font-color:navy;background-color:silver;";}
          $html.="<tr style='".$style."'>";
          $html.="   <td>".$item["day"]."/".$item["month"]."/".$item["year"]."</td>";
          $html.="   <td align='right'>".$item["files"]."</td>";
          $html.="   <td align='right'>".$item["pages"]."</td>";
          $html.="</tr>";
          $i+=1;
	   }
	}
    $html.="<tr style='background-color:ivory;font-weight:bold;'>";
    $html.="   <td>Totales ".$total["extension"]."</td>";
    $html.="   <td align='right'>".$total["files"]."</td>";
    $html.="   <td align='right'>".$total["pages"]."</td>";
    $html.="</tr>";
    $html.="</table>";
    $html.="<br/>";
}
echo $html;
?>

<p>
Estos datos son precisos hasta el <?php echo date(FORMAT_DATE_DMYHMS);?>.<br/>
Pasada esa fecha y hora los mismos pueden haber variado por procesamientos posteriores.
</p>

<p>
Gracias por confiar en nosotros!
</p>
<br/>
</div>