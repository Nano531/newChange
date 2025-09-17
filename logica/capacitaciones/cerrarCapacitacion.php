<?php
//header('refresh:3; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class asistencia extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		$result=$conexion->consultar("UPDATE TbCapacitaciones SET Ejecutado=TRUE Where IdCapacitacion=$_GET[idcap]");
		if(!$result)
		{
			echo"<center><h2>¡¡¡ERROR!!! No se ejecuto la Edición</h2>
			<a href='javascript:window.history.back()'>Volver</a></center>";
		}
		echo"<center><h2>la Capacitación se cerro.</h2></center>";
		?><script language="javascript">setTimeout("self.close();",2000)</script>
		'<script> opener.document.location.reload(); </script><?php
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }

 
 
}
$pagina = new asistencia();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>