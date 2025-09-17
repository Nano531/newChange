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
	echo"<span class='ruta'><a href='../ver_empleado/ausentismo.php?idu=$_GET[idu]'>Ausentismo</a> | Asignar ausentismo</span><br/><br/>";
	
		
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		 echo "<h3>Asignar ausentismo - "; echo $nom=$this->nombreEmp($_GET[idu], $conexion); echo"</h3>";
		 echo "<form action='procesarausentismo1.php' method='post' id='formAus'>";
		 echo "<table>";
		 echo "<tr><td>Tipo de ausencia: </td><td>"; $this->tipoAusencia($conexion); echo" (*)</td><td><a href='newAusencia.php?idu=$_GET[idu]'>[ Nuevo Item ]</a></td></tr>";
		 echo "<tr><td>Diagnostico: </td><td colspan='2'>"; $this->Diagnostico($conexion); echo"</td></tr>";
		  echo "<tr><td>Mes del compensatorio: </td><td colspan='2'>"; $this->compensatorio($conexion); echo"</td></tr>";
		 echo "<tr><td>Fecha / hora de inicio: </td><td><input type='date' name='fechaini'/> <input type='time' name='horaini' value='07:00'/>(*)</td></tr>";
		 echo "<tr><td>Fecha / hora de finalización: </td><td><input type='date' name='fechafin' /> <input type='time' name='horafin' value='07:00'/> (*)</td></tr>";
		 echo "<tr><td>Tiempo: </td><td><input type='text' name='horas' size='3' value='8'/> Horas (*)</td></tr>";
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
function mostrarJs()
{
	?>
<script>
	function mostrar()
	{
		var elem=document.getElementById("ausencia").value;
		console.log(elem);
		if(document.getElementById("ausencia").value=="7" || document.getElementById("ausencia").value=="8")
		{
			alert("Ahora debe seleccionar un diganostico");
			document.getElementById("diagnostico").disabled=false;
		} 
		else if(document.getElementById("ausencia").value=="4")
		{
			alert("Por favor seleccione el mes de los domingos trabajados");
			document.getElementById("mes").disabled=!document.getElementById("mes").disabled;
		} 
		else
		{
			document.getElementById("diagnostico").disabled=true;
		}
		
	}
	
</script>
	<?php
}
function compensatorio($con)
{
		$mes[0]="Enero";
	  	$mes[1]="Febrero";
	  	$mes[2]="Marzo";
	  	$mes[3]="Abril";
	  	$mes[4]="Mayo";
	  	$mes[5]="Junio";
	  	$mes[6]="Julio";
	  	$mes[7]="Agosto";
	  	$mes[8]="Septiembre";
	  	$mes[9]="Octubre";
	  	$mes[10]="Noviembre";
	  	$mes[11]="Diciembre";
	  	echo "<select name='mes' id='mes' disabled>";
	  	for ($i=0; $i < 12 ; $i++) { 
			  echo "<option value='$i'>$mes[$i]</option>";
		  }
	  	echo "</select>";
}
 function Diagnostico($con)
 {
 	$result=$con->consultar("Select * from Diagnostico");
 	echo "<select name='diag' id='diagnostico' disabled>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		$nom= substr($reg->NomDiagnostico, 0, 40);
		 echo "<option title='$reg->NomDiagnostico' value='$reg->CodDiagnostico'>$reg->CodDiagnostico - $nom (...)</option>";
	 }
 	echo "</select>";
 }
 function nombreEmp($id, $con)
 {
 	$result=$con->consultar("Select * from Userinfo Where Userid='$id'");
 	
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 return $reg->Name;
	 }
 	
 }
 function tipoAusencia($con)
 {
 	$result=$con->consultar("Select * from Ausencias");
 	echo "<select name='ause' id='ausencia' onchange='mostrar();'>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo "<option value='$reg->IdAusencia'>$reg->NomAusencia</option>";
	 }
 	echo "</select>";
 }
 
 
}
$pagina = new ausentismo();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>