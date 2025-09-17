<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class ausentismo extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	echo"<a href='javascript:window.history.back()'>[ Volver ]</a><br/><hr/><br/>";
	
		
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		if(!$_POST[ause] || !$_POST[fechaini] || !$_POST[fechafin] || !$_POST[horas])
		{
			echo"<center><h2>Es obligatorio diligenciar los campos señalados con (*)</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
		}
		else {
			if($_POST[obs]=="") $obs="Sin Comentarios";
			else $obs=$_POST[obs];
			$fechaini="$_POST[fechaini]";
			$fechafin="$_POST[fechafin]";
			if(isset($_POST[diag]))
			{
				echo "diagnostico";
				$sql="update HistAusentismo Set IdAusencia=$_POST[ause], Diagnostico='$_POST[diag]', Horas='$_POST[horas]', Fecha_ini=#$fechaini#,
				Fecha_fin=#$fechafin#, Observaciones='$obs' Where IdHistAus=$_POST[id_aus]";
				}
			else if(isset($_POST[mes]))
			{
				echo "$_POST[mes]";
				$sql="update HistAusentismo Set IdAusencia=$_POST[ause], Horas='$_POST[horas]', Fecha_ini=#$fechaini#,
				Fecha_fin=#$fechafin#, Observaciones='$obs' MesCompensatorio=$_POST[mes] Where IdHistAus=$_POST[id_aus]";

				}
			else {

				$sql="update HistAusentismo Set IdAusencia=$_POST[ause], Horas='$_POST[horas]', Fecha_ini=#$fechaini#,
				Fecha_fin=#$fechafin#, Observaciones='$obs' Where IdHistAus=$_POST[id_aus]";

				
			}
			 
			 $result=$conexion->consultar($sql);
			 if($result) echo"<center><h2>Los datos fueron Actualizados exitosamente</h2>";
		}
		
		
		 
	}
	else {
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