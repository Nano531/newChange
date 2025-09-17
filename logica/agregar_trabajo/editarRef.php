<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class editarTrabajo extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo "<h3>Editar Trabajo</h3>
		<p>Los campos señalados con (*) son obligatorios</p>";
		$codigo="SELECT  * FROM TbEmpresas INNER JOIN HistLaboral ON TbEmpresas.IdEmpresa = HistLaboral.IdEmpresa Where HistLaboral.Userid='$_GET[idu]' AND HistLaboral.IdEmpresa=$_GET[emp];";
		$result=$conexion->consultar($codigo);
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
		echo"<form action='procesarEditar.php' method='post'>";
		echo "<table>
		<tr><td>Empresa:</td><td><input type='text' name='emp' size='35' value='$reg->NomEmpresa'/> (*)</td></tr>
		<tr><td>Contacto:</td><td><input type='text' name='cont' size='35' value='$reg->Contacto'/> </td></tr>
		<tr><td>Telefono:</td><td><input type='text' name='tel' size='35' value='$reg->Telefono'/> </td></tr>
		<tr><td>Desde:</td><td><input type='date' name='ini' value='$reg->FechaIni'/> (*)</td></tr>
		<tr><td>hasta:</td><td><input type='date' name='fin' value='$reg->FechaFin'/> (*)</td></tr>
		<tr><td>Comentario:</td><td><textarea name='comen' rows='5' cols='25'>$reg->Comentario</textarea></td></tr>
		</table>
		<input type='hidden' value='$reg->IdEmpresa' name='idemp'/>
		<input type='hidden' value='$_GET[idu]' name='idu'/>
		<input type='submit' value='Editar' class='btn'/>";
		echo "</form>";
		}
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
    echo"</div>";
	  
  }

 
 
}
$pagina = new editarTrabajo();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>