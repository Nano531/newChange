<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class ausentismo extends PopUp
{
	function mostrarEstilo()
  {
  	echo"<link rel='stylesheet' name='estilos' type='text/css' href='".$this->nivel."presentacion/css/popUp2.css'/>";
	
  }
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	echo"<span class='ruta'><a href='../ver_empleado/ausentismo.php?idu=$_GET[idu]'>Ausentismo</a> | <a href='index.php?idu=$_GET[idu]'>Asignar ausentismo</a> | Nuevo Item</span><br/><br/>";
	
		
	if($_SESSION['tipo']=='Root')
	{
	 if(isset($_GET[nom])) $this->resultados($conexion);
		echo "<h3>Nuevo item - Ausencia</h3>";
		echo "<form action='' method='get'>";
		echo "Nombre de la ausencia: <input type='text' name='nom' size='35' style='height:30px'/> <input type='submit' value='Crear' class='btn' />";
		echo "<input type='hidden' name='idu' value='$_GET[idu]'/></form><br/>";
		
		 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
  
 function resultados($con)
 {
 	if(!$_GET[nom])
 	{
 		echo"<h2 style='border: 2px solid red; padding:10px'>El campo nombre no puede estar vacio</h2>";
 	}
	else {
		$id=$this->ultimoId($con);
 	$id=$id+1;
 	$result=$con->consultar("Insert into Ausencias (IdAusencias, NomAusencia) Values($id, '$_GET[nom]')");
	if($result)
	{
		echo"<h2 style='border: 2px solid red; padding:10px'>El item se ingreso correctamente</h2>"; 
	}
	}
 	
 }
 function ultimoId($con)
 {
 	$result=$con->consultar("select * from Ausencias");
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		$id=$reg->IdAusencias;
		 
	 }
	return $id;
 }
 
}
$pagina = new ausentismo();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>