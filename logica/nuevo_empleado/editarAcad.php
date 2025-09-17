<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class infoAcad extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		echo "<h3>Agregar información académica</h3><hr/>";
		echo"<p>Los campos señalados con (*) son OBLIGATORIOS diligenciarlos.</p>";
		$sql="SELECT * FROM TbNivelEdu INNER JOIN TbInfAcademica ON TbNivelEdu.IdNivel = TbInfAcademica.Nivel
			WHERE (((TbInfAcademica.IdInfAcad)=$_GET[idacad]))";

	    //echo $sql;
		$result=$conexion->consultar($sql);
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
		
		echo"<form action='procesarAcad2.php' method='post' >";
		echo"<input type='hidden' value='$_GET[idacad]' name='idacad'/>";
		echo"<table>";
		echo"<tr><td>Titulo:</td><td><input type='text' name='tit' size='25'value='$reg->Titulo' class='textbox2'/>(*) </td></tr>";
		$this->sinterminar($reg->SinTerminar, $reg->Grado);
		echo"<tr><td>Institución:</td><td><input type='text' name='inst' size='25' value='$reg->Institucion'class='textbox2'/>(*) </td></tr>";
		echo"<tr><td>Nivel educativo</td><td>"; $this->NivelAcad($reg->IdNivel, $conexion); echo "(*)</td></tr>";
		echo"<tr><td>Año:</td><td><input type='text' name='year' value='$reg->Year'size='5'/> </td></tr>";
		echo"<tr><td>Ciudad:</td><td><input type='text' name='ciudad' value='$reg->Ciudad' size='25'/> </td></tr>";
		echo"</table><br/>";
		echo "<input type='submit' value='Editar' class='btn'/>";
		echo"</form>";
		}
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
  function sinterminar($id, $grado)
  {
  	echo"<tr><td>Sin terminar:</td><td>
  	<select name='sinterminar'>"; 
  	if($id==True)
  	{
  		echo "<option value='True' selected>Si</option>
		<option value='False' >No</option>";
  	}
	else
	{
		echo "<option value='True'>Si</option>
		<option value='False' selected>No</option>";
	}
	echo"
	</select> Grado:<input type='text' name='grado' id='grado' size='10' value='$grado'/> </td></tr>";
	
  	
  }
   function NivelAcad($id, $con)
  {
  	$result=$con->consultar("Select * from TbNivelEdu");
  	echo"<select name='nivel'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdNivel) echo "<option value='$reg->IdNivel' selected>$reg->NomNivel</option>";
		echo "<option value='$reg->IdNivel'>$reg->NomNivel</option>";
	}
	echo"</select>";
  }
  
 
}
$pagina = new infoAcad();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>