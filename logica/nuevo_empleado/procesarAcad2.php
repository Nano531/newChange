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
		else if($_POST[sinterminar]==True && !isset($_POST[grado]))
		{
			//var_dump($_POST[sinterminar]==True && $_POST[grado]=='');
			echo"<center><h2>Si el nivel educativo es 'Sin Terminar', debe escribir el ultimo grado o semestre cursado.</h2>
			<a href='javascript:window.history.back()'>Intentalo de nuevo</a></center>";
		}
		else {
		

			$titulo=$_POST[tit];
			$inst=$_POST[inst];
			$nivel=$_POST[nivel];
			$year=$_POST[year];
			$ciudad=$_POST[ciudad];
			var_dump($_POST[sinterminar]);
			if($_POST[sinterminar]==True) 
				{
					$grado="$_POST[grado]";
				}

			else if($_POST[sinterminar]==False) {
				$grado='Terminado';
			}
			var_dump($grado);
			//echo "((($grado)))";
			
			if($year=="") $year="N-A";
			if($ciudad=="") $ciudad="N-A";
			$sql="UPDATE TbInfAcademica SET Nivel=$nivel, Titulo='$titulo', Institucion='$inst', Year='$year', Ciudad='$ciudad', SinTerminar=$_POST[sinterminar], Grado='$grado'
			Where IdInfAcad=$_POST[idacad]";
			var_dump($sql);
			$result=$conexion->consultar($sql);
			if(!$result)
			{
				echo"<h2>Error, el ingreso no fue exitoso</h2>";
			} 
			else
			{
				echo "<h2>La información se actualizó corectamente</h2>";
				//echo'<script language="javascript">setTimeout("self.close();",2000)</script>
					//<script> opener.document.location.reload(); </script>';
			} 
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