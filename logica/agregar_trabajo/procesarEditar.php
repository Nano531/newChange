<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
require('../clasesSQL/updateTrab.php');
require('../clasesSQL/updateTrab2.php');
class editarTrabajo2 extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		$upd=new updateTrab();
		$upd->SetCondicion("Where IdEmpresa=$_POST[idemp]");
		$upd->validarNulos($_POST[emp], "NomEmpresa");
		$upd->validarNulos($_POST[cont], "Contacto");
		$upd->validarNulos($_POST[tel], "Telefono");
		$sql=$upd->verUpdate();
		echo $sql;
		$upd2=new updateTrab2();
		$upd2->SetCondicion("Where IdEmpresa=$_POST[idemp] AND Userid='$_POST[idu]'");
		$upd2->validarNulos($_POST[ini], "FechaIni");
		$upd2->validarNulos($_POST[fin], "FechaFin");
		$upd2->validarNulos($_POST[comen], "Comentario");
		$sql2=$upd2->verUpdate();
		echo "(($sql2))";
		
		$result=$conexion->consultar($sql);
		$result2=$conexion->consultar($sql2);
		if($result && $result2)
		{
			echo"<center><h2>La información se edito con exito</h2></center>";
			echo'<script language="javascript">setTimeout("self.close();",2000)</script>
		<script> opener.document.location.reload(); </script>';
		} 
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
    echo"</div>";
	  
  }

 
 
}
$pagina = new editarTrabajo2();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>