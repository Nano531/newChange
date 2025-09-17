<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class procesarAcad extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{ 
		if(!$_POST[tit] || !$_POST[inst] || !$_POST[nivel])
		{
			echo"<center><h2>Información incompleta.Los datos señalados con (*) son obligatorios</h2>
			<a href='javascript:window.history.back()'>Intentalo de nuevo</a></center>";
		}
		else {
			
			$titulo=$_POST[tit];
			$inst=$_POST[inst];
			$nivel=$_POST[nivel];
			$year=$_POST[year];
			$ciudad=$_POST[ciudad];
			$grado=$_POST[grado];
			if($year=="") $year="N-A";
			if($ciudad=="") $ciudad="N-A";
			if($_POST[sinterminar]==1) 
			{
				echo $sinterminar;
				$sql="INSERT INTO TbInfAcademica (Userid, Nivel, Titulo, Institucion, Year, Ciudad, Terminado, NumNivel)
				VALUES('".$_POST[idu]."', ".$nivel.", '".$titulo."', '".$inst."', '".$year."', '".$ciudad."', TRUE, ".$grado.")";
			}
			else
			{
				$sql="INSERT INTO TbInfAcademica (Userid, Nivel, Titulo, Institucion, Year, Ciudad, Terminado, NumNivel)
				VALUES('".$_POST[idu]."', ".$nivel.", '".$titulo."', '".$inst."', '".$year."', '".$ciudad."', FALSE, ".$grado.")";
			}
			
			$result=$conexion->consultar($sql);
			if(!$result) echo"<h2>Error, el ingreso no fue exitoso</h2>";
			else echo "<h2>Se ingreso un nuevo item a la información académica</h2>";
		}
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 
 
}
$pagina = new procesarAcad();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>