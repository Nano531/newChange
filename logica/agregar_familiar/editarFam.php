<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class editarfamiliar extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		$codigo="SELECT * FROM TbParentesco INNER JOIN TbFamilia ON TbParentesco.IdParentesco = TbFamilia.IdParentesco WHERE (((TbFamilia.IdFamilia)=$_GET[idfam]));";
		$result=$conexion->consultar($codigo);
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
		echo"<h3>Editar familiar</h3>";
		echo "<form action='procesarEditar.php' method='post'>";
		$fecha=$this->fecha($reg->FechaNacimiento);
		echo"<table>
		<tr><td>Tipo de documento:</td><td>"; $this->tipo_documento($conexion, $reg->TipoDocumento); echo"</td></tr>
		<tr><td>Documento:</td><td><input type='text' name='docu' value='$reg->Documento'/></td></tr>
		<tr><td>Nombres:</td><td colspan='3'><input type='text' name='nom' value='$reg->Nombres'/></td></tr>
		<tr><td>Apellidos:</td><td colspan='3'><input type='text' name='ape' value='$reg->Apellidos'/></td></tr>
		<tr><td>Fecha de nacimiento: </td><td><input type='date' name='fechaNac' value='$fecha'/>(dd/mm/aaaa)</td></tr>
		<tr><td>Parentesco:</td><td>"; $this->parentesco($conexion, $reg->IdParentesco); echo"</td></tr>
		<tr><td>Dirección:</td><td><input type='text' name='dir' value='$reg->Direccion'/></td></tr>
		<tr><td>Ciudad:</td><td><input type='text' name='ciudad' value='$reg->Ciudad'/></td></tr>
		<tr><td>Telefono fijo:</td><td><input type='text' name='fijo' value='$reg->Telefono'/></td></tr>
		<tr><td>Telefono móvil:</td><td><input type='text' name='movil' value='$reg->Celular'/></td></tr>
		</table>
		<input type='hidden' value='$_GET[idfam]' name='idfam' />
		<input type='submit' value='Registrar' class='btn'/><input type='reset' value='Limpiar' class='btn'/>";
		echo "</form>";	
		}
		
		 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 function parentesco($con, $id)
 {
 	$sql="Select * from TbParentesco";
 	$result=$con->consultar($sql);
 	echo "<select name='paren'>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		if($id==$reg->IdParentesco) echo"<option value='$reg->IdParentesco' selected>$reg->NomParentesco</option>";
		else echo"<option value='$reg->IdParentesco'>$reg->NomParentesco</option>";
	 }
 	echo"</select>";
 }
 function tipo_documento($con, $id)
 {
 	
 	switch ($id) {
		 case '0': echo"<select name='tipoDocu'>
					 	<option value='0' selected>Cédula de ciudadanía</option>
					 	<option value='1'>Tarjeta de identidad</option>
					 	<option value='2'>Registro civil</option>
					 	<option value='3'>Cédula de extranjería</option>
					 </select>"; break;
					 	
		case '1': echo"<select name='tipoDocu'>
					 	<option value='0'>Cédula de ciudadanía</option>
					 	<option value='1' selected>Tarjeta de identidad</option>
					 	<option value='2'>Registro civil</option>
					 	<option value='3'>Cédula de extranjería</option>
					 	</select>"; break;
		case '2': echo "<select name='tipoDocu'>
					 	<option value='0' >Cédula de ciudadanía</option>
					 	<option value='1'>Tarjeta de identidad</option>
					 	<option value='2' selected>Registro civil</option>
					 	<option value='3'>Cédula de extranjería</option>
					 	</select>";	break;
	   default: echo "<select name='tipoDocu'>
					 	<option value='0'>Cédula de ciudadanía</option>
					 	<option value='1'>Tarjeta de identidad</option>
					 	<option value='2'>Registro civil</option>
					 	<option value='3' selected>Cédula de extranjería</option>
					 	</select>"; break;
	 }
 }
 function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	return $fecha;
	
}
}
$pagina = new editarfamiliar();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>