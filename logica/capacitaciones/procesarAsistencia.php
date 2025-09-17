<?php
//header('refresh:5; url=../../index.php');
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
		if(isset($_POST[asis]))
		{
			$asistencia=$_POST[asis];
			$cal=$_POST[cal];
			$tam=count($asistencia);
			for ($i=0; $i < $tam; $i++) {	
		    if($cal[$i]!="")
			{
				 $result=$conexion->consultar("UPDATE UserCapacitacion SET Asistio=True, Calificacion=$cal[$i] WHERE (((UserCapacitacion.Userid)='$asistencia[$i]') AND ((UserCapacitacion.IdCapacitacion)=$_POST[idcap]));");
			}
			else {
				 $result=$conexion->consultar("UPDATE UserCapacitacion SET Asistio=True WHERE (((UserCapacitacion.Userid)='$asistencia[$i]') AND ((UserCapacitacion.IdCapacitacion)=$_POST[idcap]));");
			
			}
				 if(!$result) echo "<center><h2>¡¡¡ERROR!!! al ejecutar la Edición</h2>
				 <a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
		}
			echo "<center><h2>Confirmada la asistencia de los empleados.</h2>
				 <a href='javascript:window.history.back()'>Volver y confirmar</a> - <a onclick='jaascript:window.close();' href='#'>Cerrar la ventana</a></center>";
		}
		else {
			echo "<center><h2>Debe seleccionar al menos un empleado</h2>
				 <a href='javascript:window.history.back()'>Volver</a></center>";
		}
		
	}
	else if($_GET[close]=="True")
	{
		echo "cerrar";
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