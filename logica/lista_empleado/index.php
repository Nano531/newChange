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
		  echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/lista_empleado/'>Lista de Empleados</a></span><br/>";
		echo"<h2>Lista de Empleados $_GET[opc]</h2><hr/><br/>";
		echo"<a href='index.php' class='link2'>Todos</a> <a href='index.php?opc=Retirados' class='link2'>Retirados</a> "; if($_SESSION[idusuario]!='125') {echo" <a href='index.php?opc=Mercico' class='link2'>Empleados Mercico</a> <a href='index.php?opc=Temporal' class='link2'>Empleados Temporal</a>";} 
		echo"<br/><br/><hr/>";
		
		echo"<div style='overflow:scroll; height:300px; padding:20px'>";
		switch ($_GET[opc]) {
			case 'Retirados': $this->retirados($conexion); break;
			case 'Temporal': $this->temporal($conexion); break;
			case 'Mercico': $this->mercico($conexion); break;
			default:$this->porDefecto($conexion); break;
				
				break;
		}
		
		
	
		
		echo"</div>";
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
  function retirados($conexion)
  {
  	$result=$conexion->consultar("SELECT * FROM Userinfo Where Retirado=True Order by Name Asc");
	$i=1;
		echo"<table class='tab'>
		<tr><th>Num.</th><th>Documento identidad</th><th>Apellidos y Nombres</th><th>Empresa</th></tr>";
		while($fila=$conexion->MostrarRegistrosAssoc($result))
		{
			    echo"<tr><td>$i</td><td>$fila->IDCard</td><td>$fila->Name</td><td>";  $this->empresa($fila->Mercico); echo"</td><td><a href='".$this->nivel."logica/ver_empleado/index.php?idu=$fila->Userid'>Ver detalle</a></td><tr/>";
				$i++;
		}
		echo"</table>";
  }
  function temporal($conexion)
  {
  	$result=$conexion->consultar("SELECT * FROM Userinfo Where Mercico=False and Retirado=False Order by Name Asc");
	$i=1;
		echo"<table class='tab'>
		<tr><th>Num.</th><th>Documento identidad</th><th>Apellidos y Nombres</th><th>Empresa</th></tr>";
		while($fila=$conexion->MostrarRegistrosAssoc($result))
		{
			
			
				echo"<tr><td>$i</td><td>$fila->IDCard</td><td>$fila->Name</td><td>";  $this->empresa($fila->Mercico); echo"</td><td><a href='".$this->nivel."logica/ver_empleado/index.php?idu=$fila->Userid'>Ver detalle</a></td><tr/>";
				$i++;
			
		}
		echo"</table>";
  }
  function mercico($conexion)
  {
  	$result=$conexion->consultar("SELECT * FROM Userinfo Where Mercico=True and Retirado=False Order by Name Asc");
  	$i=1;
		echo"<table class='tab'>
		<tr><th>Num.</th><th>Documento identidad</th><th>Apellidos y Nombres</th>"; if($_SESSION[idusuario]!='125') echo"<th>Empresa</th></tr>";
		while($fila=$conexion->MostrarRegistrosAssoc($result))
		{
				
			echo"<tr><td>$i</td><td>$fila->IDCard</td><td>$fila->Name</td><td>"; if($_SESSION[idusuario]!='125') {$this->empresa($fila->Mercico);} echo"</td><td><a href='".$this->nivel."logica/ver_empleado/index.php?idu=$fila->Userid'>Ver detalle</a></td><tr/>";
				$i++;	
		}
		echo"</table>";
  }
function porDefecto($conexion)
{
	$result=$conexion->consultar("SELECT * FROM Userinfo Where Retirado=False Order by Name Asc");
	$i++;
		echo"<table class='tab'>
		<tr><th>Num.</th><th>Documento identidad</th><th>Apellidos y Nombres</th>"; if($_SESSION[idusuario]!='125') echo"<th>Empresa</th></tr>";
		while($fila=$conexion->MostrarRegistrosAssoc($result))
		{
			
			
				echo"<tr><td>$i</td><td>$fila->IDCard</td><td>$fila->Name</td>";  if($_SESSION[idusuario]!='125') {$this->empresa($fila->Mercico);} echo"<td><a href='".$this->nivel."logica/ver_empleado/index.php?idu=$fila->Userid'>Ver detalle</a></td><tr/>";
				$i++;
			
		}
		echo"</table>";
		
}
  function empresa($id)
  {
  	if($id==True) echo "<td>Mercico</td>"; 
	else echo "<td>Temporal</td>";
  }
  
}
$pagina = new empleado();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>