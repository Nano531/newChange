<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class capacitacion extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		if(!$_POST[nom] || !$_POST[fecha] || !$_POST[exp] || !$_POST[hora] ||!$_POST[dur] || !$_POST[calif])
		{
			echo"<center><h2>Información incompleta. Todos los campos son obligatórios.</h2><br/>
		<a class='btn2' href='javascript:window.history.back()'>Volver</a></center><br/>";
		}
		else {
			$fecha=$_POST[fecha]." ".$_POST[hora];
		//echo $fecha;
		$sql="INSERT INTO TbCapacitaciones (NomCapacitacion, FechaCapacitacion, Expositor, Calificable, Duracion, Ejecutado) VALUES ('$_POST[nom]', #$fecha#, '$_POST[exp]', $_POST[calif], $_POST[dur], FALSE ) ";
		$result=$conexion->consultar($sql);
		if(!$result)
		{
			echo"<center><h2>No se pudeo ejecutar la Inserción de datos</h2><br/>
				<a class='btn2' href='javascript:window.history.back()'>Volver</a></center><br/>";
		}
		else {
			$idult=$this->ultLista($conexion);
			echo $idult;
			echo "<center><h2>La capacitación se Creo correctamente</h2>
			<a href='convocatoria.php?idcap=$idult'>Convocar empleados</a></center>";
		}
		}
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
  function ultLista($con)
  {
  	$result=$con->consultar("SELECT * FROM TbCapacitaciones");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$id=$reg->IdCapacitacion;
	}
	return $id;
  }
  
  function convocatoria($con)
  {
  	echo "<h2>Convocatoria</h2><hr/>
  	<p></p>";
  }
}
$pagina = new capacitacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>