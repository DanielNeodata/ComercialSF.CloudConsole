<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "DATOS email ".json_encode($patient,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div style='font-family:arial;'>
<table>
   <tr>
	  <td valign='middle'>
		<span style='font-weight:bold;'>Notificación de nuevos archivos</span> - <b><?php echo $details["description"];?></b>
	  </td>
   </tr>
</table>

<h5>En el día de hoy y hasta este momento: <?php echo date(FORMAT_DATE_DMYHMS);?> han ingresado: <?php echo $details["nuevos"]; ?></h5>

<p>
Estos datos son precisos hasta el <?php echo date(FORMAT_DATE_DMYHMS);?>.<br/>
</p>

<p>
Gracias por confiar en nosotros!
</p>
<br/>
</div>