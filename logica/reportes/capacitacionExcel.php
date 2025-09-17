<?php
require('../../datos/gestor.php'); 
require_once '../../presentacion/Classes/PHPExcel.php';

$objPHPExcel = PHPExcel_IOFactory::load("FormatoAsistenciaCapacitacion.xls");

$conexion=new gestorDB();
$conexion->conectar();

$result=$conexion->consultar("SELECT * FROM TbCapacitaciones Where IdCapacitacion = $_GET[idcap]");
$result2=$conexion->consultar("SELECT * FROM ((((Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid)
													  INNER JOIN Procesos ON Userinfo.Proceso = Procesos.IdProceso)
													  INNER JOIN CentroCosto ON Userinfo.CentroCosto = CentroCosto.IdCentroCosto)
													  INNER JOIN Escalafon ON Userinfo.Escalafon = Escalafon.Idescalafon)
													  INNER JOIN TbCapacitaciones ON UserCapacitacion.IdCapacitacion = TbCapacitaciones.IdCapacitacion
										Where UserCapacitacion.IdCapacitacion = $_GET[idcap] Order by Name");
$nomCapacitacion = '';
$expCapacitacion = '';
$fechaCapacitacion = '';
while ($fila=$conexion->MostrarRegistrosAssoc($result)) 
{
	$nomCapacitacion = $fila->NomCapacitacion;
	$expCapacitacion = $fila->Expositor;
	$fechaCapacitacion = $fila->FechaCapacitacion;
}
$date = date_create($fechaCapacitacion);

$cont = 37;

$objPHPExcel->setActiveSheetIndex(0)
    	        ->setCellValue('J7','X')
    	        ->setCellValue('J13',$nomCapacitacion)
    	        ->setCellValue('J14',$expCapacitacion)
    	        ->setCellValue('F15',date_format($date, 'd/m/Y'));
while ($fila=$conexion->MostrarRegistrosAssoc($result2)) 
{
	// echo "$fila->Name";

	if($cont > 63)
	{
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($cont,1);
   		$objPHPExcel->setActiveSheetIndex(0)
    		        ->setCellValue('B'.$cont,$cont-33);
    	// $styleA = $objPHPExcel->getActiveSheet()->g‌etStyle('C37');
    	// $objPHPExcel->getActiveSheet()->duplicateStyle($styleA,'C37:C100');     	
	}

	$objPHPExcel->setActiveSheetIndex(0)
    	        ->setCellValue('C'.$cont,$fila->Name)
    	        ->setCellValue('P'.$cont,$fila->NomEscalafon)
    	        ->setCellValue('U'.$cont,$fila->NomCentro);
   	$cont++;
}

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="asistenciaCap.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


exit;