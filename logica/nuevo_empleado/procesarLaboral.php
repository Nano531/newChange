<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
require('../clasesSQL/updateLaboral.php');
class procesarLaboral extends pagina
{
	
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{
		
		//echo "$_POST[fechaIng]<br/>$_POST[contrato]<br/>$_POST[cargo]<br/>$_POST[eps]<br/>$_POST[afp]<br/>$_POST[arp]<br/>$_POST[mercico]";
		$editar=new updateLaboral();
		$editar->SetCondicion("Where Userid='$_POST[userid]'");
		$editar->validarNulos($_POST[fechaIng], "EmployDate");
		$editar->validarNulos($_POST[contrato], "IdTipoContrato"); 
		$editar->validarNulos($_POST[cargo], "IdCargo"); 
		$editar->validarNulos($_POST[eps], "IdEps"); 
		$editar->validarNulos($_POST[afp], "IdAfp");
		$editar->validarNulos($_POST[arp], "IdArp"); 
		$editar->validarNulos($_POST[dotasino], "Dotacion"); 
		$editar->validarNulos($_POST[ces], "IdCesantias"); 
		$editar->validarNulos($_POST[tipodota], "TipoDotacion"); 
		$editar->validarNulos($_POST[centro], "CentroCosto"); 
		$editar->validarNulos($_POST[proceso], "Proceso"); 
		$editar->validarNulos($_POST[escalafon], "Escalafon"); 
		$editar->validarNulos($_POST[tallacam], "TallaCamisa"); 
		$editar->validarNulos($_POST[tallapan], "TallaPantalon"); 
		$editar->validarNulos($_POST[tallacal], "TallaCalzado"); 
		$editar->validarNulos($_POST[fondo], "FondoEmpleado");
		$editar->validarNulos($_POST[mercico], "Mercico");
		$editar->validarNulos($_POST[correoCorp], "EmailCorp");
		$editar->validarNulos($_POST[ruta], "Sede");
		
		$sql=$editar->verUpdate();
		//echo "(($sql))";
		$result=$conexion->consultar($sql);
		
		if($_POST[cargo] != $_POST[antcargoid]){
			
			/*$result2=$conexion->consultar("Select IdEmpresa,Contacto From TbEmpresas Where NomEmpresa = 'Mercico Ltda' AND Contacto = '".$_GET[jefe]."'");
			while ($regEmp=$conexion->MostrarRegistrosAssoc($result2)) 
			{
				$idEmp = $regEmp->IdEmpresa;
			}*/
			
			$result=$conexion->consultar("Select IdCargo, NomCargo from TbCargos where IdCargo = $_POST[cargo] OR IdCargo = $_POST[antcargoid]");
			$ant_cargo;
			$nue_cargo;
			while($fila=$conexion->MostrarRegistrosAssoc($result))
			{
				//echo var_dump($fila);
				if($_POST[antcargoid]==$fila->IdCargo)$ant_cargo = $fila->NomCargo;
				else $nue_cargo = $fila->NomCargo;
			}
			//echo "$ant_cargo - $nue_cargo";
			
			$sql_histTable = "SELECT * FROM HistLaboral WHERE Userid='$_POST[userid]' AND FechaFin != null";
			$result_histTable = $conexion->consultar($sql_histTable);
			$hoy = date('Y-m-d');
			//echo "$hoy";
			if($conexion->NumRegistros($result_histTable)>0){
				while ($ed=$conexion->MostrarRegistrosAssoc($result_histTable)) {
					$sql1="UPDATE HistLaboral SET FechaFin = #$_POST[fec_ing]# Where idHistLab = '$ed->idHistLab'";
					$result2=$conexion->consultar($sql1);
				}
			}else{
				$sql1="INSERT INTO HistLaboral (Userid, FechaIni, FechaFin, Comentario, IdEmpresa) VALUES('$_POST[userid]', #$_POST[fechaIng]#, #$hoy#, '$ant_cargo',511)";
				$result2=$conexion->consultar($sql1);
			}
			
			$sql2="INSERT INTO HistLaboral (Userid, FechaIni, Comentario, IdEmpresa) VALUES('$_POST[userid]', #$hoy#, '$nue_cargo',511)";
			$result3=$conexion->consultar($sql2);
		}
		
		if(!$result) {
			echo "<center><h2> E R R O R - No se pudo Ejecutar la acción</h2>
			<a href='".$this->nivel."logica/ver_empleado/index.php?idu=$_POST[userid]'>Ver empleado</a></center>";
		}
		else 
		{
			echo"<center><h2>La información del Empleado se Actualizó exitosamente</h2>
		<a href='".$this->nivel."logica/ver_empleado/index.php?idu=$_POST[userid]'>Ver empleado</a></center>"; 
		}
				
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
  function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	return $fecha;
	
}
function convertirFecha($dia, $mes, $year)
{
	$tmDia=strlen($dia);
	$tmMes=strlen($mes);
		if($tmDia==1) $dia="0".$dia;
		if($tmMes==1) $mes="0".$mes;
	$fecha=$year."/".$mes."/".$dia;
	
	return $fecha;
}
}
$pagina = new procesarLaboral();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>