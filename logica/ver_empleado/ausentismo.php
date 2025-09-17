<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class capacitacion extends PopUp
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
	echo "<h3>Ausentismo - "; echo $nom=$this->nombreEmp($_GET[idu], $conexion); echo" <span class='link' ><a href='../ausentismo/index.php?idu=$_GET[idu]'  style='text-decoration:none'>[ Agregar + ]</a></h3><hr/><br/>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		$this->FiltrosBusqueda($conexion);
		if(isset($_GET[fechaini]) || $_GET[fechafin])
		{
			if($_GET[fechaini] > $_GET[fechafin])
			{
				echo "<center><h2 style='border:2px solid red; padding:10px'>Imposible ejecutar la consulta. La fecha $_GET[fechaini] es mayor que $_GET[fechafin]</h2></center>";
				$codigo="SELECT * FROM Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencia = HistAusentismo.IdAusencia
						WHERE (((HistAusentismo.Userid)='$_GET[idu]')) ORDER BY Fecha_ini Desc;";
			}
			 else if(!$_GET[fechaini] || !$_GET[fechafin]){
				echo "<center><h2 style='border:2px solid red; padding:10px'>Los campos de fecha no pueden estar Vacios</h2></center>";
				$codigo="SELECT * FROM Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencia = HistAusentismo.IdAusencia
				WHERE (((HistAusentismo.Userid)='$_GET[idu]')) ORDER BY Fecha_ini Desc;";
			}
			else
			{
				$codigo="SELECT * FROM Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencia = HistAusentismo.IdAusencia 
				WHERE (((HistAusentismo.Fecha_ini)>=#$_GET[fechaini]#) AND ((HistAusentismo.Userid)='$_GET[idu]') AND ((HistAusentismo.Fecha_ini)<=#$_GET[fechafin]#))
 				ORDER BY HistAusentismo.Fecha_ini DESC;";
			}
		}
		else if(isset($_GET[aus]))
		{
			$codigo="SELECT * FROM Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencia = HistAusentismo.IdAusencia WHERE (((HistAusentismo.Userid)='$_GET[idu]')) AND  (((HistAusentismo.IdAusencia)=$_GET[aus])) ORDER BY HistAusentismo.Fecha_ini DESC;";
		}
		else {
			$codigo="SELECT * FROM Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencia = HistAusentismo.IdAusencia
					WHERE (((HistAusentismo.Userid)='$_GET[idu]')) ORDER BY Fecha_ini Desc;";
		}
		 
		 echo "<a href='ausentismo.php?idu=$_GET[idu]'>Ver todos</a><br/><br/>";
		 $result=$conexion->consultar($codigo);
		 echo "<table class='TbDota'>
		 <tr><th>Desde</th><th>Hasta</th><th>Nombre</th><th>Diagnostico</th><th>Horas</th><th>Dias</th><th>Observaciones</th></tr>";
		 while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
		 	if($reg->Horas < 8) $dias=1;
			else $dias=$reg->Horas/8;
		 	
			 	echo "<tr valign='top'><td>$reg->Fecha_Ini</td><td>$reg->Fecha_Fin</td><td>$reg->NomAusencia</td><td>"; $this->Diagnostico($conexion, $reg->CodDiagnostico); echo"</td><td>$reg->Horas</td><td>$dias</td><td>$reg->Observaciones</td></tr>";
			 
			
		 }
		  echo"</table><br/>";
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 function Diagnostico($con, $id)
 {
 	$result=$con->consultar("Select * from Diagnostico Where CodDiagnostico='$id'");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "$reg->NomDiagnostico - $reg->Codigo";
	}
 }
  function nombreEmp($id, $con)
 {
 	$result=$con->consultar("Select * from Userinfo Where Userid='$id'");
 	
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 return $reg->Name;
	 }
 	
 }
 function filtrosBusqueda($con)
 {
 	echo "<form action='ausentismo.php' method='get'>
 	<input type='hidden' name='idu' value='$_GET[idu]'/>
 	Desde: <input type='date' name='fechaini'/> hasta: <input type='date' name='fechafin'/> <input type='submit' value='Buscar'/>
 	</form><br/>";
	echo "<form action='ausentismo.php' method='get'>";
		$this->ausenciasList($con);
		echo "<input type='hidden' value='$_GET[idu]' name='idu'/>
		<input type='submit' value='Buscar'/>";
 		echo"</form><br/>";
	
 }
function ausenciasList($con)
{
	$codigo="SELECT  HistAusentismo.IdAusencia, Ausencias.NomAusencia FROM Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencia = HistAusentismo.IdAusencia
		WHERE (((HistAusentismo.Userid)='$_GET[idu]')) GROUP BY Ausencias.NomAusencia,  HistAusentismo.IdAusencia;";
    $result=$con->consultar($codigo);
	echo "<select name='aus'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<option value='$reg->IdAusencia'>$reg->NomAusencia</option>";
	}
	echo "</select>";
}
}
$pagina = new capacitacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>