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
	if(isset($_SESSION['idusuario']))
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/capacitaciones/'>Capacitaciones</a> | "; if(isset($_GET[opc])){echo "<a href='#'>Nueva capacitación</a> ";}
		echo" </span><br/><br/>";
		echo "<h2>Modulo de Capacitaciones<span class='link' ><a href='index.php'  style='text-decoration:none'>[ Lista ]</a> <a href='index.php?opc=add'  style='text-decoration:none'>[ Agregar + ]</a></span></h2><hr/><br/>";
        if($_GET[opc]=="add" && ($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')) $this->agregarCapacitacion($conexion);
		else $this->listaCapacitacion($conexion);
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
function agregarCapacitacion($con)
{
	echo "<h3>Nueva capacitación</h3>";
	echo "<form action='addcapacitacion.php' method='post'>";
	echo "<table>
	<tr><td>Nombre capacitación: </td><td><input type='text' name='nom' size='35'/></td></tr>
	<tr><td>Nombre Expositor: </td><td><input type='text' name='exp' size='35'/></td></tr>´
	<tr><td>Duración: </td><td><input type='text' name='dur' size='5'/></td></tr>
	<tr><td>Calificable: </td><td><input type='radio' name='calif' value='True' />Si <input type='radio' name='calif' value='False' checked/>No</td></tr>
	<tr><td>Fecha: </td><td><input type='date' name='fecha'/> Hora: <input type='time' name='hora'/> </td></tr>
	</table><br/>
	<input type='submit' value='Agregar' class='btn'>";
	echo "</form>";
}

function listaCapacitacion($con)
{
	$promAsistio=0;
	$promNoAsistio=0;
	$i=0;

	/** CRISTIAN MEJIA - 02/03/2016 - START **/
	$where = '';
	if(isset($_GET["yearFilter"]) && $_GET["yearFilter"] != 'all')
		$where = 'WHERE YEAR(FechaCapacitacion) = '.$_GET["yearFilter"];

	// $sql="Select * From TbCapacitaciones order by fechaCapacitacion Desc";
	$sql="Select * From TbCapacitaciones $where order by fechaCapacitacion Desc";	
	$result=$con->consultar($sql);

	$sqlYears = "SELECT YEAR(FechaCapacitacion) AS Year FROM TbCapacitaciones GROUP BY YEAR(FechaCapacitacion) ORDER BY YEAR(FechaCapacitacion) DESC";
	$resultYears=$con->consultar($sqlYears);

	echo "<form action='index.php'>";
		echo "<b>Año a consultar: </b> <select id='yearFilter' name='yearFilter' onchange='this.form.submit()'>";
			echo "<option value='all'>Todos</option>";
			while ($reg=$con->MostrarRegistrosAssoc($resultYears)) 
			{
				if(isset($_GET["yearFilter"]) && $_GET["yearFilter"] == $reg->Year)
					echo "<option selected>".$reg->Year."</option>";
				else
					echo "<option>".$reg->Year."</option>";
			}
		echo "</select>";
	echo "</form>";
	/** CRISTIAN MEJIA - 02/03/2016 - END**/

	echo "<table class='listaCapa'>
	<tr style='text-align:left;'><th>Fecha / Hora</th><th width='100'>Nombre de la capacitación</th><th>Indicador de asistencia</th><th>Expositor</th></tr>";	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td>$reg->FechaCapacitacion</td><td><a href='convocados.php?opc=$reg->Ejecutado&id=$reg->IdCapacitacion&name=$reg->NomCapacitacion' style='text-decoration:underline; color:#000'>$reg->NomCapacitacion</a></td>
		<td>";
		if($reg->Ejecutado==True)
		{
			$Asistio=$this->indicador1($reg->IdCapacitacion, $con);
			$NoAsistio=$this->indicador2($reg->IdCapacitacion, $con);
			$promAsistio=$promAsistio+$Asistio; 
		 	$promNoAsistio=$promNoAsistio+$NoAsistio;
		 	$i++;
		}
		else {
			echo "Programado";
		}
		 
		echo"</td><td>$reg->Expositor</td><td>"; $this->Estado($reg->Ejecutado, $reg->IdCapacitacion); echo"</td></tr>";
		
	}
	$promAsistio=$promAsistio/$i;
	$promAsistio=round($promAsistio);
	$promNoAsistio=$promNoAsistio/$i;
	$promNoAsistio=round($promNoAsistio);
	echo "<h3 style='padding:10px 20px 10px 20px; border: 2px solid #2C89A0'>Promedio de asistencia: $promAsistio % ";
	echo "- Promedio de No asistencia: $promNoAsistio %</h3>";
	echo "</table>";
}
function indicador1($id, $con)
{
	$result=$con->consultar("Select * from UserCapacitacion Where IdCapacitacion=$id");
	$totalreg=0;
	$promAsistencia=0;
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$totalreg++;
		if($reg->Asistio==True) $Asistio[]=$reg->Userid;
		
	}
	if($totalreg==0){
		$porcAsistio=0;
	
	}
	else {
	$porcAsistio=(100*count($Asistio))/$totalreg;
	$porcAsistio=round($porcAsistio);
	
	
	}
echo "Asistencia: $porcAsistio % <br/>";
	return $porcAsistio;
}
function indicador2($id, $con)
{
	$result=$con->consultar("Select * from UserCapacitacion Where IdCapacitacion=$id");
	$totalreg=0;
	$promNoAsistencia=0;
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$totalreg++;
		if($reg->Asistio==FALSE) $NoAsistio[]=$reg->Userid;
		
	}
	if($totalreg==0){
		$porcNoAsistio=0;
	
	}
	else {
	$porcNoAsistio=(100*count($NoAsistio))/$totalreg;
	$porcNoAsistio=round($porcNoAsistio);
	
	
	}
echo "No Asistencia: $porcNoAsistio % <br/>";
	return $porcNoAsistio;
}
function Estado($id, $idcap)
{
	if($id==0) echo "<span style='color:red; padding:2px; font-weight:bold'>Programado  <a href='convocatoria.php?idcap=$idcap'>[ Convocar ]</a></span>";
	else  echo "<span >Ejecutado</span>";
}
}
$pagina = new capacitacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>