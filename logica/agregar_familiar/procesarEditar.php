<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
require('../clasesSQL/updateFam.php');
class procesar_editar extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	
	if(isset($_SESSION['idusuario']))
	{
		 
		$upd=new updateFam();
		$upd->SetCondicion("Where IdFamilia=$_POST[idfam]");
		$upd->validarNulos($_POST[docu], "Documento");
		$upd->validarNulos($_POST[tipoDocu], "TipoDocumento");
		$upd->validarNulos($_POST[nom], "Nombres");
		$upd->validarNulos($_POST[ape], "Apellidos");
		$upd->validarNulos($_POST[fechaNac], "FechaNacimiento");
		$upd->validarNulos($_POST[paren], "IdParentesco");
		$upd->validarNulos($_POST[dir], "Direccion");
		$upd->validarNulos($_POST[ciudad], "Ciudad");
		$upd->validarNulos($_POST[fijo], "Telefono");
		$upd->validarNulos($_POST[movil], "Celular");
		$sql=$upd->verUpdate();
		//echo $sql;
		$result=$conexion->consultar($sql);
		if(!$result){
			echo "<center><h2>¡¡¡ERROR!!! No se pudo Actualizar la información</h2>
			<a href='javascript:window.history.back()'>Volver</a></center>";
		} 
		else echo "<center><h2>La información se actualizó correctamente</h2>
		</center>";
		echo'<script language="javascript">setTimeout("self.close();",2000)</script>
		<script> opener.document.location.reload(); </script>';
		
		 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }

}
$pagina = new procesar_editar();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>