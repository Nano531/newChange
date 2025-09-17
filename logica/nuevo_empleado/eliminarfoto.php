<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class procesarfoto extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']) && isset($_GET[idu]))
	{ 
		// $id=$_POST[idu];
		$this->delete($_GET[idu], $conexion);		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
   function delete($id, $conexion)
  {
  		var_dump($id);
		$result=$conexion->consultar("UPDATE Userinfo SET Foto = null where Userid='$id'");
		echo ("<script type='text/javascript'>window.location='../ver_empleado/index.php?idu=".$id."';</script>");
		// header("../ver_empleado/index.php?idu=$id");
  }
  
 
}
$pagina = new procesarfoto();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>