<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class ausentismo extends PopUp
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
   echo"<span class='ruta'><a href='javascript:window.history.back()'>Ausentismo</a> | Asignar ausentismo</span><br/><br/>";
   if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
   {
     if(!$_POST[ause] || !$_POST[fechaini] || !$_POST[fechafin] || !$_POST[horas] || !$_POST[horaini] || !$_POST[horafin])
     {
	echo"<center><h2>Es obligatorio diligenciar los campos señalados con (*)</h2>
	<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
      }
      else
      {
	if($_POST[obs]=="") $obs="Sin Comentarios";
	else $obs=$_POST[obs];
	if(isset($_POST[diag])) $diagn=$_POST[diag];
	else $diagn="0";
	if(isset($_POST[mes])) $mesc=$_POST[mes];
	else $mesc=0;
	$fechaini="$_POST[fechaini] $_POST[horaini]";
	$fechafin="$_POST[fechafin] $_POST[horafin]";
	
	$fechaini = str_replace("-","",$fechaini);
	$fechafin = str_replace("-","",$fechafin);

	$sql="Insert Into HistAusentismo (Userid, IdAusencia, CodDiagnostico, Horas, Fecha_ini, Fecha_fin, Observaciones, MesCompensatorio )
	Values('$_POST[idu]', $_POST[ause],'$diagn', $_POST[horas], '$fechaini', '$fechafin', '$obs', $mesc)";
	$result=$conexion->consultar($sql);
	if($result) echo"<center><h2>Los datos fueron ingresados exitosamente</h2>";
 	else echo"<center><h1>ERROR</h1><br><h2>Los datos NO fueron ingresados</h2><br><h3>$sql</h3>";
	}	 
	}
	else
	{
	echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
	<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}   
	  echo"</div>";
	  
  }
}
$pagina = new ausentismo();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>