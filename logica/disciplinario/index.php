<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class disciplinario2 extends PopUp
{
	function mostrarEstilo()
  {
  	echo"<link rel='stylesheet' name='estilos' type='text/css' href='".$this->nivel."presentacion/css/popUp2.css'/>";
	
  }
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
		
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		 echo "<h3>Asignar Disciplinario - "; echo $nom=$this->nombreEmp($_GET[idu], $conexion); echo"</h3>";
		 echo "<form action='procesardisc.php' method='post'>";
		 echo "<table>";
		 echo "<tr><td>Medida disciplinaria: </td><td>"; $this->disciplinaria($conexion); echo" (*)</td></tr>";
		 echo "<tr><td>Motivo: </td><td><input type='text' name='motivo'/></td></tr>";
		 echo "<tr><td>Fecha: </td><td><input type='date' name='fecha'/></td></tr>";
		  echo "<tr><td>Tiempo(Min): </td><td><input type='text' name='tiempo' size='2' value='0'/> Minutos</td></tr>";
		 echo "<tr><td>Observaciones: </td><td><textarea name='obs' cols='25' rows='5' ></textarea></td></tr>";
		 
		 echo "</table>";
		 echo "<input type='hidden' value='$_GET[idu]'  name='idu'/>
		 <input type='submit' value='Asignar' class='btn'/>";
		 echo "</form>";
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 function nombreEmp($id, $con)
 {
 	$result=$con->consultar("Select * from Userinfo Where Userid='$id'");
 	
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 return $reg->Name;
	 }
 	
 }
function disciplinaria($con)
{
	$result=$con->consultar("Select * from Disciplinario");
 	echo "<select name='disc'>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo "<option value='$reg->IdDisciplinario'>$reg->NomDisc</option>";
	 }
 	echo "</select>";
}
}
$pagina = new disciplinario2();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>