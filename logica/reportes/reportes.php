<?php
header('Pragma: public'); 
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12 Xlsm'); // This should work for IE & Opera 
header('Content-Type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="reporte.xls"');
session_start();
require('../../datos/gestor.php');


  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
		$sql= $_POST[sql];
		$i = 1;
		$result=$conexion->consultar($sql);
		$print = "<table>";
		$print.="<tr><th>Empleado</th><th>Ausencia</th><th>Diagnostico</th><th>horas</th><th>Desde</th><th>Hasta</th><th>Mes compensado</th><th>observaciones</th></tr>";
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$print.="<tr><td>$reg->Name</td><td>$reg->NomAusencia</td><td>$reg->Diagnostico</td><td>$reg->Horas</td><td>$reg->Fecha_ini</td><td>$reg->Fecha_fin</td><td>$reg->MesCompensatorio</td><td width='150'>$reg->Observaciones</td></tr>";
		}
		$print.="</table>";
		
		echo $print;
	  echo"</div>";
?>