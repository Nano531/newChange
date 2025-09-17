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

		$sql= $_POST[sql];
		$i = 2;
		$result=$conexion->consultar($sql);
		
		
	if($_POST[opc]==1)
	{
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Empleado")
			->setCellValue('B1', "Ausencia")
			->setCellValue('C1', "Diagnostico")
			->setCellValue('D1', "Horas")
			->setCellValue('E1', "Desde")
			->setCellValue('F1', "Hasta")
			->setCellValue('G1', "Mes compensado")
			->setCellValue('H1', "Observaciones");
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->Name)
			->setCellValue('B'.$i, $reg->NomAusencia)
			->setCellValue('C'.$i, $reg->Diagnostico)
			->setCellValue('D'.$i, $reg->Horas)
			->setCellValue('E'.$i, $reg->Fecha_ini)
			->setCellValue('f'.$i, $reg->Fecha_fin)
			->setCellValue('G'.$i, $reg->MesCompensatorio)
			->setCellValue('H'.$i, $reg->Observaciones);
			
			$i++;
		}
	}
	else if($_POST[opc]==2){
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Fecha entrega")
			->setCellValue('B1', "Empleado")
			->setCellValue('C1', "Tipo dotación")
			->setCellValue('D1', "Elemento")
			->setCellValue('E1', "Talla")
			->setCellValue('F1', "Cantidad")
			->setCellValue('G1', "Observaciones");
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->FechaEntrega)
			->setCellValue('B'.$i, $reg->Name)
			->setCellValue('C'.$i, $reg->NomDotacion)
			->setCellValue('D'.$i, $reg->NomElemento)
			->setCellValue('E'.$i, $reg->NomTalla)
			->setCellValue('f'.$i, $reg->Cantidad)
			->setCellValue('G'.$i, $reg->Comentario);
			
			
			$i++;
		}
	}
	else if($_POST[opc]==4){
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Nombres")
			->setCellValue('B1', "Tipo de dotación")
			->setCellValue('C1', "Talla camisa")
			->setCellValue('D1', "Talla Pantalón")
			->setCellValue('E1', "Talla calzado");
			
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{

			//$Tdotacion= $this->tipodota($reg->TipoDotacion);
			$sql1="Select * from TipoDotacion Where IdTipoDotacion=$reg->TipoDotacion";
			$result1=$conexion->consultar($sql1);
			while ($fila1=$conexion->MostrarRegistrosAssoc($result1)) {
				$Tdotacion= $fila1->NomDotacion;
			}

			$sql2="Select * from TbTalla Where IdTalla=$reg->TallaCamisa";
			$result2=$conexion->consultar($sql2);
			while ($fila2=$conexion->MostrarRegistrosAssoc($result2)) {
				$camisa= $fila2->NomTalla;
			}

			$sql3="Select * from TbTalla Where IdTalla=$reg->TallaPantalon";
			$result3=$conexion->consultar($sql3);
			while ($fila3=$conexion->MostrarRegistrosAssoc($result3)) {
				$pantalon= $fila3->NomTalla;
			}

			$sql4="Select * from TbTalla Where IdTalla=$reg->TallaCalzado";
			$result4=$conexion->consultar($sql4);
			while ($fila4=$conexion->MostrarRegistrosAssoc($result4)) {
				$calzado= $fila4->NomTalla;
			}

			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->Name)
			->setCellValue('B'.$i, $Tdotacion)
			->setCellValue('C'.$i, $camisa)
			->setCellValue('D'.$i, $pantalon)
			->setCellValue('E'.$i, $calzado)
			;
			
			
			$i++;
		}
	}

else if($_POST[opc]==6){

		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Empleado")
			->setCellValue('B1', "Hijo")
			->setCellValue('C1', "Edad del Hijo");

		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$fecha = str_replace("00:00:00", "", $reg->FechaNacimiento);
			$hoy=date("Y-m-d");
			$hoy=strtotime($hoy);
			$fechaEmp=strtotime($fecha);
			$resultado=($hoy-$fechaEmp);
			$resultado=$resultado/31536000;
			
			$resultado=floor($resultado)." años";


			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->Name)
			->setCellValue('B'.$i, $reg->Nombres.$reg->Apellidos)
			->setCellValue('C'.$i, $resultado);
			
			$i++;
		}
			

	}
	else if($_POST[opc]==8){
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Empleado")
			->setCellValue('B1', "Fecha")
			->setCellValue('C1', "Dia")
			->setCellValue('D1', "Hora Entrada")
			->setCellValue('E1', "Hora Llegada")
			->setCellValue('F1', "Retardo")
			->setCellValue('G1', "Tipo Ausencia");
		
		$min=0;
		$hora=0;
		$total = 0;
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
				$sql1="SELECT Userinfo.Userid, Userinfo.Name, Ausencias.NomAusencia, Int(HistAusentismo.Fecha_ini) as Fecha_ini, HistAusentismo.Fecha_fin, HistAusentismo.Horas, HistAusentismo.Diagnostico, HistAusentismo.Observaciones FROM Ausencias INNER JOIN (Userinfo INNER JOIN HistAusentismo ON Userinfo.Userid = HistAusentismo.Userid) ON Ausencias.IdAusencias = HistAusentismo.IdAusencia WHERE ((Userinfo.Consecutivo)=$reg->Consecutivo)";
				$result1=$conexion->consultar($sql1);	
				$aux=0;
				while ($reg1=$conexion->MostrarRegistrosAssoc($result1)) 
				{
					if($reg->Fecha==$reg1->Fecha_ini)
					{
						$aus=$reg1->NomAusencia;
						$aux++;
					}
				}
				if ($aux==0)
				{
					$aus="Retardo";
					$total = $total + $reg->Retardo;
					$min=$min+date("i",strtotime(substr($reg->Retardo,11,-3)));
					$hora=$hora+date("H",strtotime(substr($reg->Retardo,11,-3)));					
				}
			
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->Name)
			->setCellValue('B'.$i, date("d-M-Y", strtotime($reg->Fecha)))
			->setCellValue('C'.$i, $reg->NomDia)
			->setCellValue('D'.$i, date("H:i a",strtotime($reg->Entra)))
			->setCellValue('E'.$i, date("H:i a",strtotime($reg->llegada)))
			->setCellValue('F'.$i, int_to_date($reg->Retardo))
			->setCellValue('G'.$i, $aus);
			
			$i++;
		}
		
		$nh=floor($min/60);
		$min=$min%60;
		$hora=$hora+$nh;
		$tammin=strlen($min);
		if($tammin==1)
			$min="0".$min;
			
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, "TOTAL")
			->setCellValue('B'.$i, "")
			->setCellValue('C'.$i, "")
			->setCellValue('D'.$i, "")
			->setCellValue('E'.$i, int_to_date($total))
			->setCellValue('F'.$i, "");
	}		
else {
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Empleado")
			->setCellValue('B1', "Fecha")
			->setCellValue('C1', "Novedad")
			->setCellValue('D1', "Motivos")
			->setCellValue('E1', "Observaciones");
			
		while ($reg=$conexion->MostrarRegistrosAssoc($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $reg->Name)
			->setCellValue('B'.$i, $reg->FechaEvento)
			->setCellValue('C'.$i, $reg->NomDisc)
			->setCellValue('D'.$i, $reg->Motivos)
			->setCellValue('E'.$i, $reg->Observaciones);
			$i++;
		}
}
		
function int_to_date($cant)
 {
	$cant = $cant * -1;
 	if($cant < 60)
	{
		if($cant < 10)
		{
			$salida = "00:0$cant";
		}
		else{
			$salida = "00:$cant";
		}
	}else
	{
		//$horas = intdiv($cant, 60);
		
		$horas = floor($cant/60);
		$minutos = $cant - ($horas * 60);
		
		if($horas < 10)
		{
			$horas = "0$horas";
		}
		if($minutos < 10)
		{
			$minutos = "0$minutos";
		}
		$salida = "$horas:$minutos";
	}
	return $salida;
 }

$objPHPExcel->getActiveSheet()->setTitle('Ausencias');
$objPHPExcel->setActiveSheetIndex(0);


header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


exit;