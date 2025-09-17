<?php
require('../../datos/gestor.php'); 
require_once '../../presentacion/Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();


$objPHPExcel->
	getProperties()
		->setCreator("Mercico Ltda.")
		->setLastModifiedBy("Mercico Ltda")
		->setTitle("Reportes")
		->setSubject("Reportes Excel")
		->setDescription("Documento generado con PHPExcel")
		->setKeywords("Mercico reportes")
		->setCategory("reportes");

		$conexion=new gestorDB();
		$conexion->conectar();

		$result= $_POST[sql];
		$i = 2;
		$result=$conexion->consultar($sql);
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Empleado")
			->setCellValue('B1', "Fecha")
			->setCellValue('C1', "Dia")
			->setCellValue('D1', "Hora Entrada")
			->setCellValue('E1', "Retardo")
			->setCellValue('F1', "Tipo Ausencia")
			
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->Name)
			->setCellValue('B'.$i, $reg->NomDia)
			->setCellValue('C'.$i, date("H:i a",strtotime(substr($reg->Entra,11))))
			->setCellValue('D'.$i, $reg->date("H:i a",strtotime($reg->llegada)))
			->setCellValue('E'.$i, date("H:i",strtotime(substr($reg->Retardo,11,-3))))
			
			$i++;
		}
	
$objPHPExcel->getActiveSheet()->setTitle('Ausencias');
$objPHPExcel->setActiveSheetIndex(0);


header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


exit;