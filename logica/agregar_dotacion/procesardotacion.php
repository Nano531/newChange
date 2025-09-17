<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class agregardotacion extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		
		if(!$_POST[cant])
		{
			echo "<center><h2>Información incompleta</h2><br/>
			<a href='javascript:window.history.back()' style='border:2px solid #2C89A0; padding:5px 10px 5px 10px'>Volver</a></center>";
		}
		else {
			
			$cant=$_POST[cant];
			$idetc=$_POST[elem];
			$talla=$_POST[talla];
			
			$fecha=$_POST[fecha];
			$coment=$_POST[coment];
			if($coment=="") $coment="N-A";
			$tam=count($cant);
			//echo $tam;
			if($fecha=='') $fecha=date("Y-m-d");
				
				for ($i=0; $i < $tam; $i++) { 
					if($cant[$i]=='') $cant=1;
					echo "($idetc[$i])";
					
					$result=$conexion->consultar("INSERT INTO HistDotacion (Userid, IdETC, Talla, Cantidad, FechaEntrega, Comentario)
					Values('$_POST[idu]', $idetc[$i], $talla[$i], $cant[$i], #$fecha#, '$coment')");
				}
				
							
			
			
			if(!$result)
			{
				echo "<center><h2>NO se pudo EJECUTAR la Inserción</h2><br/>
				<a href='javascript:window.history.back()' style='border:2px solid #2C89A0; padding:5px 10px 5px 10px'>Volver</a></center>";
		
			}
			else {
				echo "<center><h2>El Elemento se ingreso correctamente</h2><br/>
				<a href='javascript:window.history.back()' style='border:2px solid #2C89A0; padding:5px 10px 5px 10px'>Ingresa nuevo Item</a>
				<a href='../nuevo_empleado/dotacion.php?idu=$_POST[idu]' style='border:2px solid #2C89A0; padding:5px 10px 5px 10px'>Ver Historial</a></center>";
	
			}
		}
		 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
	function ultimaId($con)
	{
		$codigo="SELECT * FROM ElementoTalla";
		$result=$con->consultar($codigo);
		while ($reg=$con->MostrarRegistrosAssoc($result)) {
			$n=$reg->IdEleTal;
		}
		return $n;
	}
 
}
$pagina = new agregardotacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>