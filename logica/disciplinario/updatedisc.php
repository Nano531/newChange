<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class procesardisc extends PopUp
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
		//echo $_POST['tiempo'];
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		if($_POST[motivo]=='') $motivo='N-A';
		else $motivo=$_POST[motivo];
		if ($_POST[fecha]=='') $fecha=date("Y-m-d");
		else $fecha=$_POST[fecha];
		if($_POST[obs]=='') $obs="N-A";
		else $obs=$_POST[obs];
		if($_POST['tiempo']=='') $tiempo=0;
		else $tiempo=$_POST['tiempo'];
		//echo $fecha;
		$sql="Update HistDisciplinario Set IdDisciplinario=$_POST[disc], FechaEvento=#$fecha#, tiempo=$tiempo, Motivos='$motivo', Observaciones='$obs' Where IdHistDisc=$_POST[dis]";
		/*$sql="Insert into HistDisciplinario (Userid, IdDisciplinario, FechaEvento, tiempo, Motivos, Observaciones) Values('$_POST[idu]', $_POST[disc],  #$fecha#, $_POST[tiempo], '$motivo', '$obs')";*/
		//echo $sql;
		$result=$conexion->consultar($sql);
		if(!$result)
		{
			echo "<h2>¡¡¡ Error !!! no se pudo Actualizar los datos</h2>";
		}
		else {
			echo "<h2>La información se Actualizó correctamente</h2>

			<a href='editarDisc.php'>Volver</a> | <a href='../ver_empleado/disciplinario.php?idu=$_POST[idu]'>Ver historial</a>";
		}
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }

}
$pagina = new procesardisc();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>