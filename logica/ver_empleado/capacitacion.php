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
	echo "<h3>Lista de Capacitaciones </h3>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		 echo "<form action='' method='get'>
		 <input type='hidden' value='$_GET[idu]' name='idu'/>
		 desde: <input type='date' name='fechaini'/> hasta: <input type='date' name='fechafin'/><input type='submit' value='Buscar'/>
		 </form><br/>";
		
		
		 if(isset($_GET[fechaini]) || isset($_GET[fechafin]))
		 {
		 	if($_GET[fechaini] > $_GET[fechafin])
			{
				echo "<center><h2 style='border:2px solid red; padding:10px'>Imposible ejecutar la consulta. La fecha $_GET[fechaini] es mayor que $_GET[fechafin]</h2></center>";
				 
		$sql="SELECT * FROM TbCapacitaciones INNER JOIN (Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid) ON TbCapacitaciones.IdCapacitacion = UserCapacitacion.IdCapacitacion 
		WHERE (((Userinfo.Userid)='$_GET[idu]')) Order by FechaCapacitacion Desc";
			}
			 else if(!$_GET[fechaini] || !$_GET[fechafin]){
				echo "<center><h2 style='border:2px solid red; padding:10px'>Los campos de fecha no pueden estar Vacios</h2></center>";
				 
		$sql="SELECT * FROM TbCapacitaciones INNER JOIN (Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid) ON TbCapacitaciones.IdCapacitacion = UserCapacitacion.IdCapacitacion 
		WHERE (((Userinfo.Userid)='$_GET[idu]')) Order by FechaCapacitacion Desc";
			}
			
			else {
				$sql="SELECT * FROM Userinfo INNER JOIN (TbCapacitaciones INNER JOIN UserCapacitacion ON TbCapacitaciones.IdCapacitacion = UserCapacitacion.IdCapacitacion) ON Userinfo.Userid = UserCapacitacion.Userid
WHERE (((Userinfo.Userid)='$_GET[idu]')  AND ((TbCapacitaciones.FechaCapacitacion)>=#$_GET[fechaini]#) AND ((TbCapacitaciones.FechaCapacitacion)<=#$_GET[fechafin]#))
ORDER BY TbCapacitaciones.FechaCapacitacion DESC;";
			}
		 	
		 }
		else {
	$sql="SELECT * FROM TbCapacitaciones INNER JOIN (Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid) ON TbCapacitaciones.IdCapacitacion = UserCapacitacion.IdCapacitacion 
		WHERE (((Userinfo.Userid)='$_GET[idu]')) Order by FechaCapacitacion Desc";
			}
		 echo "<a href='capacitacion.php?idu=$_GET[idu]'>Ver todos</a><br/><br/>";
		$result=$conexion->consultar($sql);
		
		
		echo "<table class='TbDota'>";
		echo "<tr><th>Fecha / Hora</th><th>Nombre</th><th>Expositor</th><th>Duración</th><th>Asitio</th><th>Calificación</th></tr>";
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
			echo "<tr valign='top'><td width='120'>$reg->FechaCapacitacion</td><td><b>$reg->NomCapacitacion</b></td><td>$reg->Expositor</td><td>$reg->Duracion horas</td><td>"; $this->Asistencia($reg->Asistio);echo"</td><td>"; $this->calificacion($reg->Calificable, $reg->Calificacion, $conexion); echo"</td><tr>";
		}
		echo "</table>";
		
		 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
  function calificacion($calificable, $calificacion, $con)
  {
  	if($calificable==True) echo "$calificacion";
	else echo "N-A";
  }
 
 function Asistencia($id)
 {
 	if($id==True) echo "Si";
	else echo "No";
 }
 
}
$pagina = new capacitacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>