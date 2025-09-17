<?php
	require('../../datos/gestor.php');
	
	$conexion=new gestorDB();
	$conexion->conectar();
		
	if(!empty($_GET['emp'])) {
		$emp = $_GET["emp"];           
		$sql ="SELECT * FROM TbEmpresas WHERE NomEmpresa = '$emp' ORDER BY Contacto";
		$result = $conexion->consultar($sql);
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
			//var_dump($reg);
			echo "<option value='$reg->IdEmpresa'>$reg->Contacto</option>";
		}
	}
?>