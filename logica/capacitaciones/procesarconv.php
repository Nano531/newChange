<?php
header('refresh:0; url=convocatoria.php?idcap='.$_POST[idcap]."&letra=".$_POST[letra]);
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class procesarconv extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		
		
	
		$convocados=$_POST[inv];
		$tam=count($convocados);
		for($i=0; $i<$tam; $i++)
		{
			$sql="Insert Into UserCapacitacion (Userid, IdCapacitacion, Asistio) VALUES ('$convocados[$i]', $_POST[idcap], FALSE)";
			$result=$conexion->consultar($sql);
			if(!$result) echo "<center><h2>La información se registro correctamente</h2>
								<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			
		}
		echo "<center><h2>P R O C E S A N D O . . .</h2></center>";
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
     echo "<div style='clear:both'></div>";
	  echo"</div>";
  }

}
$pagina = new procesarconv();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>