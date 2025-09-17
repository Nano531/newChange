<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class cumpleaños extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/cumpleanos/'>Cumpleaños</a>";
		echo" </span><br/><br/>";	
		$this->menu_cumple();
		switch ($_GET[cum]) {
			case '0': $this->CumpleHoy($conexion); break; 
			// case '2': $this->CumpleMes($conexion); break;
			default: $this->CumpleMes($conexion,$_GET[cum]);	break;
		}
	}
	else 
	{
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
   function menu_cumple()
 {
 	$mes = date("m");
 	echo "<hr/>
 	<a href='index.php?cum=0' class='linkmenu'>Hoy</a> | <a href='index.php?cum=$mes' class='linkmenu'>Del Mes</a>  
 	<hr/>";
 }
 
 function CumpleHoy($con)
 {
 	$hoy=date("Y-m-d");
	$año=substr($hoy, 0,4);
	$mesActual=substr($hoy, 5,2);
	$diaActual=substr($hoy, 8,2);
	
	$sql="Select * From Userinfo Where((Month([Userinfo].[Birthday]))=$mesActual) AND ((Day([Userinfo].[Birthday]))=$diaActual) AND Retirado=False";
	$result=$con->consultar($sql);
	
	echo "<h2>Empleados que cumplen años hoy $hoy - Felicitaciones</h2>";
	echo "<table>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		$fecha=$this->fecha($reg->Birthday);
		$añosCumplidos=$this->añosCumplidos($reg->Birthday);
		 echo "<tr><td>"; $this->fotografia($reg->Foto); echo"</td><td><a style='font-size:16px' href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a><hr/>($fecha)</td><tr/>";
	 }
	 echo "</table>";
 }
 
  function fotografia($id)
 {  
 	if($id=='') echo"<img src='../../presentacion/img/no_foto.png' style='max-width:100px; max-height:100px'/>";
	else echo"<img src='$id' style='max-width:100px; max-height:100px'/>";
 }
 function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	$fecha = str_replace(" .000", "", $fecha);
	return $fecha;
	
}
 function CumpleMes($con,$mes)
 {
 	$hoy=date("Y-m-d");
	$año=substr($hoy, 0,4);
	$mesActual=substr($hoy, 5,2);
	$diaActual=substr($hoy, 8,2);
	
	$meses = array("Enero", "Febrero", "Marzo", "Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	$sql="Select * From Userinfo Where((Month([Userinfo].[Birthday]))=$mes) AND Retirado=False";
	$result=$con->consultar($sql);
	echo "<script>";
		echo "function cambioMes(val) 
		{
			val++;
    		document.location.href='index.php?cum='+val;
		}";
	echo "</script>";
	echo "<b>Mes: </b>
	<select onchange='cambioMes(this.value)' >";
	for($i = 0; $i < sizeof($meses); $i++)
	{
		if($i == ( $mes - 1) )
  			echo "<option value='$i' selected>$meses[$i]</option>";
  		else
  			echo "<option value='$i'>$meses[$i]</option>";
	}
  	echo "</Select>

  	<br><br><form action='../reportes/cumpleanosExcel.php' method='post'>
  		<input type='hidden' value='$mes' id='mes' name='mes'/>
  		<input type='submit' value='Descargar Excel'/>
  	</form>";

 	echo "<h2>Empleados que cumplen años en el Mes</h2>";
	echo "<table>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		$fecha=$this->fecha($reg->Birthday);
		$añosCumplidos=$this->añosCumplidos($reg->Birthday);
		$añosCumplidos=$añosCumplidos+1;
		 echo"<tr><td>"; $this->fotografia($reg->Foto); echo"</td><td><a style='font-size:16px' href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a><hr/>($fecha)</td><tr/>";
	 }
	 echo "</table>";
 }

}
$pagina = new cumpleaños();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>