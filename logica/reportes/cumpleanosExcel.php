<?php
require('../../datos/gestor.php'); 
require_once '../../presentacion/Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();


$objPHPExcel->
	getProperties()
		->setCreator("Mercico Ltda.")
		->setLastModifiedBy("Mercico Ltda")
		->setTitle("Listado de Cumpleaños")
		->setSubject("Listado de Cumpleaños Excel")
		->setDescription("Documento generado con PHPExcel")
		->setKeywords("Mercico reportes")
		->setCategory("reportes");

		$conexion=new gestorDB();
		$conexion->conectar();

		$mes= $_POST[mes];
		$meses = array("Enero", "Febrero", "Marzo", "Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$sql= "Select * From Userinfo Where((Month([Userinfo].[Birthday]))=$mes) AND Retirado=False ORDER BY DAY([Userinfo].[Birthday])";
		$i = 4;
		$result=$conexion->consultar($sql);
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C1', $meses[($mes -1)])
            ->setCellValue('B1', "Mes")
            ->setCellValue('B3', "Empleado")
			->setCellValue('C3', "Dia");
			// ->setCellValue('C1', "Dia")
			// ->setCellValue('D1', "Hora Entrada")
			// ->setCellValue('E1', "Retardo")
			// ->setCellValue('F1', "Tipo Ausencia")
			
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$i, $reg->Name)
			->setCellValue('C'.$i, substr(str_replace("00:00:00", "", $reg->Birthday), 8,2));
			// ->setCellValue('C'.$i, date("H:i a",strtotime(substr($reg->Entra,11))))
			// ->setCellValue('D'.$i, $reg->date("H:i a",strtotime($reg->llegada)))
			// ->setCellValue('E'.$i, date("H:i",strtotime(substr($reg->Retardo,11,-3))))
			
			$i++;
		}
	
$objPHPExcel->getActiveSheet()->setTitle('Cumpleaños');
$objPHPExcel->setActiveSheetIndex(0);


header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


exit;