<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class reactivar extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	if(isset($_SESSION['idusuario']))
	{
		if(isset($_GET[fechaIng]))$this->Resultados($conexion);
		else {
			echo "<h3>Reactivar Empleado</h3>";
			echo "<form action='reactivar.php' method='get'>";
			echo "<input type='hidden' value='$_GET[idu]' name='idu'/>";
			echo "Fecha de ingreso: <input type='date' name='fechaIng'/>";		
			echo "<input type='submit' value='Reactivar'/>
			</form>";
		}
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
    echo"</div>";
	  
  }

function Resultados($con)
{
	if ($_GET[fechaIng]!="") {
		$result=$con->consultar("UPDATE Userinfo SET Retirado=False, Deptid=1, EmployDate=#$_GET[fechaIng]#, FechaRetiro=NULL where Userid='$_GET[idu]'");
		
		if(!$result)
		{
			echo "<center><h2>No se pudo Reactivar el Empleado</h2>
			<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
		}
		else {
			echo "<center><h2>El empleado Cambio su estado: Activo</h2>
		<a href=''>Cerrar</a></center>";
		} 
	}
	else {
		echo "<center><h2>El campo fecha de ingreso no puede estar Vacia</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
}
  
 
 
}
$pagina = new reactivar();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>