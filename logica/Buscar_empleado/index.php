<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class empleado extends pagina
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/Buscar_empleado/'>Buscar de Empleados</a></span><br/>";
		echo"<h2>Buscar empleado</h2><hr/><br/>";
		
		echo "<form action='index.php' method='get' id='formulario2'>";
		
		echo"
		<input type='search' name='valor' size='55' class='textbox' style='height:35px'/>";
		echo " <input type='submit' value='Buscar' class='btn'/>";
		echo"</form>";
		
		if(isset($_GET['valor'])) 
			$this->ResultadosBusqueda($conexion);
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
 function nregistros($con)
 {
 	$sql="Select * from Userinfo Where Name LIKE '%$_GET[valor]%' OR Userid LIKE '%$_GET[valor]%' order by Name";
		$result=$con->consultar($sql);
		$n=0;
		while($fila=$con->MostrarRegistrosAssoc($result))
		{
			$n++;
		}
		return $n;
 }
 function ResultadosBusqueda($con)
 {
 	$num=$this->nregistros($con);
		echo "<span style='color:#444; padding: 0 0 10px 10px'>$num registros encontrados <a href=''>Nueva busqueda</a></span><br/><br/>";
 	echo"<div id='result'>";
       
	
		$sql="Select * from Userinfo Where Name LIKE '%$_GET[valor]%' OR Userid LIKE '%$_GET[valor]%' OR UserCode LIKE '%$_GET[valor]%' order by Name";
		$result=$con->consultar($sql);
		echo"<table>";
		
		while($fila=$con->MostrarRegistrosAssoc($result))
		{
		    if($fila->Birthday=="") $resultado="";
			else {
				$hoy=date("Y-m-d");
			$hoy=strtotime($hoy);
			$fechaEmp=strtotime($fila->Birthday);
			$resultado=($hoy-$fechaEmp);
			$resultado=$resultado/31536000;
			
			$resultado=floor($resultado)." años";
			}
			//var_dump($fila);
			echo"<tr><td>"; $this->fotografia($fila->Foto); echo"</td><td style='padding:5px;  color:'><a href='../ver_empleado/index.php?idu=$fila->Userid'>$fila->Name</a>"; $this->retirado($fila->Retirado); echo"<hr/>
			Documento: $fila->IDCard<br/>
			Fecha Nac:"; $this->fecha($fila->Birthday); echo"<br/>
			Edad: $resultado 
			</td></tr>";
		}
		$this->nregistros($con);
		echo "</table>";
	
 	
 	echo "</div>";
 }
 
 function fotografia($id)
 {  
 	if($id=='') echo"<img src='../../presentacion/img/no_foto.png' style='max-width:100px; max-height:100px'/>";
	else echo"<img src='$id' style='max-width:100px; max-height:100px'/>";
 }
 function retirado($id)
 {
 	if($id==TRUE) echo " <span style='color:red'>(Retirado)</span>";
	else echo " <span style='color:#006680'>(Activo)</span>";
 }
  function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	$fecha = str_replace(".000", "", $fecha);
	echo $fecha;
	
}
}
$pagina = new empleado();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>