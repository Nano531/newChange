<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');

class convocados extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		if($_GET[opc]==1)
		{
			echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/capacitaciones/'>Capacitaciones</a> | <a href='#'>Asistentes</a><br/><br/></span>";
			$this->listaAsistentes($_GET[id],$conexion);
		} 
		else 
		{
			echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/capacitaciones/'>Capacitaciones</a> | <a href='#'>Convocados</a><br/><br/></span> ";
			$this->listaConvocados($_GET[id],$conexion);
		}
		
		
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
function listaConvocados($id, $con)
{
	echo "<h3>Lista de Convocados $_GET[name]</h3><hr/><br/>";
	echo "<a href='convocatoria.php?idcap=$id'>[ Convocar ]</a><br/><br/>";
	$sql="SELECT * FROM Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid Where UserCapacitacion.IdCapacitacion=$id ";
	
	$result=$con->consultar($sql);
	echo "<ul>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<li><a href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a></li>";
	}
	echo "</ul>";
}
function listaAsistentes($id, $con)
{
	
	$asistentes=$this->indicador1($id, $con);
	$NoAsistentes=$this->indicador2($id, $con);
	$promCalificacion=0;
	$i=0;
	echo "<div style='float:left; width:400px; padding:0px 20px 20px 0'><h3>Lista de Asistentes a $_GET[name]</h3><hr/><br/>";
	echo "<h2>Porcentaje $asistentes %</h2>";
	$sql="SELECT * FROM Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid Where UserCapacitacion.IdCapacitacion=$id AND UserCapacitacion.Asistio=TRUE;";
	$result=$con->consultar($sql);
	echo "<ul>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<li><a style='color:#000' href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a>"; 
		if(isset($reg->Calificacion)){
			echo" - Calificación: $reg->Calificacion/5</li>"; 
			$promCalificacion=$promCalificacion+$reg->Calificacion;
			$i++;
			} 
		else echo"</li>"; 
	}
	
	echo "</ul>";
	if($i!=0)
	{
		$promCal=$promCalificacion/$i;
		$promCal=round($promCal,1);
	echo"Promedio de calificacion: $promCal sobre 5.";
	} 
	echo"</div>";
	echo "<div style='float:left; width:420px; padding:0px 0px 20px 0'><h3>Lista de No Asistentes a $_GET[name]</h3><hr/><br/>";
	echo "<h2>Porcentaje $NoAsistentes %</h2>";
	$sql="SELECT * FROM Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid Where UserCapacitacion.IdCapacitacion=$id AND UserCapacitacion.Asistio=FALSE;";
	$result=$con->consultar($sql);
	echo "<ul>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<li><a style='color:#000' href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a></li>";
	}
	echo "</ul>
	</div>
	<div style='clear:both'></div>";
}
function indicador1($id, $con)
{
	$result=$con->consultar("Select * from UserCapacitacion Where IdCapacitacion=$id");
	$totalreg=0;
	$promAsistencia=0;
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$totalreg++;
		if($reg->Asistio==True) $Asistio[]=$reg->Userid;
		
	}
	if($totalreg==0){
		$porcAsistio=0;
	
	}
	else {
	$porcAsistio=(100*count($Asistio))/$totalreg;
	$porcAsistio=round($porcAsistio);
	
	
	}

	return $porcAsistio;
}
function indicador2($id, $con)
{
	$result=$con->consultar("Select * from UserCapacitacion Where IdCapacitacion=$id");
	$totalreg=0;
	$promNoAsistencia=0;
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$totalreg++;
		if($reg->Asistio==FALSE) $NoAsistio[]=$reg->Userid;
		
	}
	if($totalreg==0){
		$porcNoAsistio=0;
	
	}
	else {
	$porcNoAsistio=(100*count($NoAsistio))/$totalreg;
	$porcNoAsistio=round($porcNoAsistio);
	
	
	}

	return $porcNoAsistio;
}
}
$pagina = new convocados();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>