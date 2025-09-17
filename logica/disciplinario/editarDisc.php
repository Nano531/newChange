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
		 echo "<h3>Editar Disciplinario - "; echo $nom=$this->nombreEmp($_GET[idu], $conexion); echo"</h3>";

		 $sql="SELECT * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario WHERE (((HistDisciplinario.IdHistDisc)=$_GET[dis]));";
		//echo $sql;
		 $result=$conexion->consultar($sql);
		 while ($reg=$conexion->MostrarRegistrosAssoc($result)) {

			$fecha=$this->fecha($reg->FechaEvento);
			$año=substr($fecha, 0,4);
			$mes=substr($fecha, 5,2);
			$dia=substr($fecha, 8,2);

		 	//echo $reg->tiempo;
		 	echo "<form action='updatedisc.php' method='post'>";
			echo "<table>";
			echo "<tr><td>Medida disciplinaria: </td><td>"; $this->disciplinaria($conexion, $reg->IdDisciplinario); echo" (*)</td></tr>";
			echo "<tr><td>Motivo: </td><td><input type='text' name='motivo' value='$reg->Motivos'/></td></tr>";
			echo "<tr><td>Fecha: </td><td><input type='text' name='fecha' value='$año-$mes-$dia'/></td></tr>";
			echo "<tr><td>Tiempo(Min): </td><td><input type='text' name='tiempo' size='2' value='$reg->tiempo'/> Minutos</td></tr>";
			echo "<tr><td>Observaciones: </td><td><textarea name='obs' cols='25' rows='5' >$reg->Observaciones</textarea></td></tr>";
			 
			echo "</table>";
			echo "<input type='hidden' value='$_GET[dis]'  name='dis'/>
			<input type='hidden' value='$_GET[idu]'  name='idu'/>
			<input type='submit' value='Asignar' class='btn'/>";
			echo "</form>";
		 }
		 
		
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
function disciplinaria($con, $id)
{
	$result=$con->consultar("Select * from Disciplinario");
 	echo "<select name='disc'>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {

 		if($id==$reg->IdDisciplinario) echo "<option value='$reg->IdDisciplinario' selected>$reg->NomDisc</option>";
		 else echo "<option value='$reg->IdDisciplinario'>$reg->NomDisc</option>";
	 }
 	echo "</select>";
}

function fecha($fecha)
{
	echo $fecha."<br/>";
	$fecha = str_replace("00:00:00", "", $fecha);
	echo $fecha."<br/>";
	$fecha=str_replace("-", "/", $fecha);
	$fecha=str_replace(".000", "", $fecha);
	echo $fecha."<br/>";
	return $fecha;
}

}
$pagina = new disciplinario2();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>