<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class upload extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{ 
		echo"<form action='procesarfoto.php' method='post' enctype='multipart/form-data' style='padding:40px; border:2px solid red'>";
		echo"<input type='file' name='picture' class='textbox'/> <input type='submit' value='Cargar' class='btn'/> 
		<input type='hidden' name='idu' value='$_GET[idu]'/>";
		echo"</form>";
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 
 
}
$pagina = new upload();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>