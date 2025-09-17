<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class agregarfamiliar extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		
		echo"<h3>Agregar familia</h3>";
		echo "<form action='procesarfamiliar.php' method='post'>";
		echo"<table>
		<tr><td>Tipo de documento:</td><td>"; $this->tipo_documento($conexion); echo"</td></tr>
		<tr><td>Documento:</td><td><input type='text' name='docu'/></td></tr>
		<tr><td>Nombres:</td><td colspan='3'><input type='text' name='nom'/></td></tr>
		<tr><td>Apellidos:</td><td colspan='3'><input type='text' name='ape'/></td></tr>
		<tr><td>Fecha de nacimiento:</td><td><input type='date' name='fechaNac'/>(dd/mm/aaaa)</td></tr>
		<tr><td>Parentesco:</td><td>"; $this->parentesco($conexion); echo"</td></tr>
		<tr><td>Dirección:</td><td><input type='text' name='dir'/></td></tr>
		<tr><td>Ciudad:</td><td><input type='text' name='ciudad'/></td></tr>
		<tr><td>Telefono fijo:</td><td><input type='text' name='fijo'/></td></tr>
		<tr><td>Telefono móvil:</td><td><input type='text' name='movil'/></td></tr>
		</table>
		<input type='hidden' value='$_GET[idu]' name='idu' />
		<input type='submit' value='Registrar' class='btn'/><input type='reset' value='Limpiar' class='btn'/>";
		echo "</form>";
		 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 function parentesco($con)
 {
 	$sql="Select * from TbParentesco";
 	$result=$con->consultar($sql);
 	echo "<select name='paren'>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo"<option value='$reg->IdParentesco'>$reg->NomParentesco</option>";
	 }
 	echo"</select>";
 }
 function tipo_documento($con)
 {
 	echo "<select name='tipoDocu'>
 	<option value='0'>Cédula de ciudadanía</option>
 	<option value='1'>Tarjeta de identidad</option>
 	<option value='2'>Registro civil</option>
 	<option value='3'>Cédula de extranjería</option>
 	</select>";
 }
 
}
$pagina = new agregarfamiliar();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>