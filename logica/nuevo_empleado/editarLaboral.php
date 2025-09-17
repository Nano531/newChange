<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class editarLaboral extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."/logica/lista_empleado/'>Lista de empleados</a> | <a href='javascript:window.history.back()'>Empleado</a> | Editar</span><br/><br/>";
		echo"<h2>Editar Información laboral</h2><hr/>";
		
		$sql="Select * from Userinfo where Userid='$_GET[idu]'";
		$result=$conexion->consultar($sql);
		while ($ed=$conexion->MostrarRegistrosAssoc($result)) {
		
		$fechaNac=$this->fecha($ed->EmployDate);
		//echo "($fechaNac)";
		$año=substr($fechaNac, 0,4);
		$mes=substr($fechaNac, 5,2);
		$dia=substr($fechaNac, 8,2);
		
		echo"<form action='procesarLaboral.php' method='post' class='formulario'>";
		echo "
		<table >
		<tr><td>Tipo de contrato:</td><td><span style='color:red; font-weight:bold'>"; $this->tipoContrato($ed->IdTipoContrato, $conexion); echo"</td></tr>
		<tr><td>Cargo:</td><td>"; $this->cargo($ed->IdCargo, $conexion); echo"</td></tr>
		<tr><td>Fecha de ingreso:</td><td> <input type='date'  name='fechaIng' value='$año-$mes-$dia'/> <span>(yyyy-mm-dd)</span></td></tr>
		<tr><td>EPS:</td><td>"; $this->Eps($ed->IdEps, $conexion); echo"</td></tr>
		<tr><td>Fondo de pensiones:</td><td>"; $this->Afp($ed->IdAfp, $conexion); echo"</td></tr>
		<tr><td>Cesantias:</td><td>"; $this->Cesantias($ed->IdCesantias, $conexion); echo"</td></tr>
		<tr><td>ARP:</td><td>"; $this->Arp($ed->IdArp, $conexion); echo"</td></tr>
		<tr><td>Correo corporativo:</td><td>"; $this->correoCorp($ed->EmailCorp, $conexion); echo"</td></tr>
		<tr><td>Mercico:</td><td>"; $this->mercico($ed->Mercico, $conexion); echo"</td></tr>
		<tr><td>Fondo de Empelados:</td><td>"; $this->fondoEmpleado($ed->FondoEmpleado); echo"</td></tr>
		<tr><td>Aplica dotación:</td><td>"; $this->aplicaDota($ed->Dotacion); echo"</td></tr>
		<tr><td>Tipo dotación:</td><td>"; $this->TipoDota($ed->TipoDotacion, $conexion); echo"</td></tr>
		<tr><td>Centro de costo:</td><td>"; $this->centroCosto($ed->CentroCosto, $conexion); echo"</td></tr>
		<tr><td>Proceso:</td><td>"; $this->proceso($ed->Proceso, $conexion); echo"</td></tr>
		<tr><td>Ruta:</td><td>"; $this->ruta($ed->Sede, $conexion); echo"</td></tr>
		<tr><td>Escalafon:</td><td>"; $this->escalafon($ed->Escalafon, $conexion); echo"</td></tr>
		<tr><td>Talla camisa:</td><td>"; $this->TallaCam($ed->TallaCamisa, $conexion); echo"</td></tr>
		<tr><td>Talla pantalon:</td><td>"; $this->TallaPan($ed->TallaPantalon, $conexion); echo"</td></tr>
		<tr><td>Talla calzado:</td><td>"; $this->TallaCal($ed->TallaCalzado, $conexion); echo"</td></tr>
		</table><br/>
		<input type='hidden' value='$ed->Userid' name='userid'/>
		<input type='hidden' value='$ed->IdCargo' name='antcargoid'/>
		<input type='hidden' value='$ed->EmployDate' name='fec_ing'/>
		<input type='submit' value='Registrar' class='btn' style='height:40px'/>";
		echo"</form>";
		}
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
 
function escalafon($id, $con)
{
	$sql="Select * from Escalafon";
	$result=$con->consultar($sql);
	echo "<select name='escalafon'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdEscalafon) echo "<option value='$reg->IdEscalafon' selected>$reg->NomEscalafon</option>";
		else echo "<option value='$reg->IdEscalafon'>$reg->NomEscalafon</option>";
		
	}
	echo "</select>";
}
function proceso($id, $con)
{
	$sql="Select * from Procesos";
	$result=$con->consultar($sql);
	echo "<select name='proceso'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdProceso) echo "<option value='$reg->IdProceso' selected>$reg->NomProceso</option>";
		else echo "<option value='$reg->IdProceso'>$reg->NomProceso</option>";
		
	}
	echo "</select>";
}
function centroCosto($id, $con)
{
	$sql="Select * from CentroCosto";
	$result=$con->consultar($sql);
	echo "<select name='centro'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdCentroCosto) echo "<option value='$reg->IdCentroCosto' selected>$reg->NomCentro</option>";
		else echo "<option value='$reg->IdCentroCosto'>$reg->NomCentro</option>";
		
	}
	echo "</select>";
}
function fondoEmpleado($id)
{
	if($id==TRUE)
  	{
  		echo "<input type='radio' value='TRUE' name='fondo' style='border:none' checked/>Si <input type='radio' name='fondo' value='FALSE' style='border:none'/>No";
  	} 
	else {
		echo "<input type='radio' value='TRUE' name='fondo' style='border:none'/>Si <input type='radio' name='fondo' value='FALSE' style='border:none' checked/>No";
	}
}
function TallaCal($id, $con)
{
	$result=$con->consultar("Select * from TbTalla Where Tipo='Calzado'");
	echo "<select name='tallacal'>";
	echo "<option value='0'>N-A</option>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdTalla) echo "<option value='$reg->IdTalla' selected>$reg->NomTalla</option>";
		else echo "<option value='$reg->IdTalla'>$reg->NomTalla</option>";
	}
	echo "</select>";
}
function TallaPan($id, $con)
{
	$result=$con->consultar("Select * from TbTalla Where Tipo='Pantalon'");
	echo "<select name='tallapan'>";
	echo "<option value='0'>N-A</option>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdTalla) echo "<option value='$reg->IdTalla' selected>$reg->NomTalla</option>";
		else echo "<option value='$reg->IdTalla'>$reg->NomTalla</option>";
	}
	echo "</select>";
}
function TallaCam($id, $con)
{
	$result=$con->consultar("Select * from TbTalla Where Tipo='Camisa'");
	echo "<select name='tallacam'>";
	echo "<option value='0'>N-A</option>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdTalla) echo "<option value='$reg->IdTalla' selected>$reg->NomTalla</option>";
		else echo "<option value='$reg->IdTalla'>$reg->NomTalla</option>";
	}
	echo "</select>";
}
function TipoDota($id, $con)
{
	$result=$con->consultar("Select * from TipoDotacion");
	echo "<select name='tipodota'>";
	echo "<option value='0'>N-A</option>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($id==$reg->IdTipoDotacion) echo "<option value='$reg->IdTipoDotacion' selected>$reg->NomDotacion</option>";
		else echo "<option value='$reg->IdTipoDotacion'>$reg->NomDotacion</option>";
	}
	echo "</select>";
}
function aplicaDota($id)
{
	if($id==True) echo "<input type='radio' value='True' name='dotasino' checked/>Si <input type='radio' value='False' name='dotasino' />NO";
	else echo "<input type='radio' value='True' name='dotasino' />Si <input type='radio' value='False' name='dotasino' checked/>NO";
}
function Mercico($id, $con)
  {
  	if($id==TRUE)
  	{
  		echo "<input type='radio' value='TRUE' name='mercico' style='border:none' checked/>Si <input type='radio' name='mercico' value='FALSE' style='border:none'/>No";
  	} 
	else {
		echo "<input type='radio' value='TRUE' name='mercico' style='border:none'/>Si <input type='radio' name='mercico' value='FALSE' style='border:none' checked/>No";
	}
  }
   function Arp($id, $con)
  {
  	$result=$con->consultar("Select * from TbArp");
  	echo"<select name='arp'>";
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		if($id==$fila->IdArp) echo"<option value='$fila->IdArp' selected>$fila->NomArp</option>";
        else echo"<option value='$fila->IdArp'>$fila->NomArp</option>";
	}
	echo"</select>";
  }
   function Cesantias($id, $con)
  {
  	$result=$con->consultar("Select * from TbCesantias");
  	echo"<select name='ces'>";
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		if($id==$fila->IdCesantia) echo"<option value='$fila->IdCesantia' selected>$fila->NomCesantia</option>";
        else echo"<option value='$fila->IdCesantia'>$fila->NomCesantia</option>";
	}
	echo"</select>";
  }
  
  function Afp($id, $con)
  {
  	$result=$con->consultar("Select * from TbAfp");
	echo"<select name='afp'>";
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		if($id==$fila->IdAfp) echo"<option value='$fila->IdAfp' selected>$fila->NomAfp</option>";
        else echo"<option value='$fila->IdAfp'>$fila->NomAfp</option>";
	}
	echo"</select>";
  }
  function Eps($id, $con)
  {
  	$result=$con->consultar("Select * from TbEps");
	echo"<select name='eps'>";
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		if($id==$fila->IdEps) echo"<option value='$fila->IdEps' selected>$fila->NomEps</option>";
		else echo"<option value='$fila->IdEps'>$fila->NomEps</option>";
	}
	echo"</select>";
  }
  function cargo($id, $con)
  {
  	$result=$con->consultar("Select * from TbCargos where Vigente = true order by NomCargo");
	echo"<select name='cargo'>";
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		if($id==$fila->IdCargo) echo"<option value='$fila->IdCargo' selected>$fila->NomCargo</option>";
		else echo"<option value='$fila->IdCargo'>$fila->NomCargo</option>";
	}
	echo"</select>";
  }
  function tipoContrato($id, $con)
  {
  	$result=$con->consultar("Select * from TbTipoContrato");
	echo"<select name='contrato'>";
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		if($id==$fila->IdTipoContrato) echo"<option value='$fila->IdTipoContrato' selected>$fila->NomTipoContrato</option>";
		else echo"<option value='$fila->IdTipoContrato'>$fila->NomTipoContrato</option>";
	}
	echo"</select>";
  }
  function correoCorp($id, $con)
  {
	echo "<input value='$id' name='correoCorp'/>";
  }
  function ruta($id, $con)
  {
		echo "<input value='$id' name='ruta'/>";
  }
  function mostrarJs()
	{
		?>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 		 <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
  		$("#datepicker").datepicker();
  		$("#datepicker2").datepicker();
		});
	jQuery(function($){
		$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'yy/mm/dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);
	});   
		</script>
		<?php
	}
  function fecha($fecha)
	{
	$fecha = str_replace("00:00:00", "", $fecha);
    $fecha=str_replace("-", "/", $fecha);
	return $fecha;
	
	}
  
}
$pagina = new editarLaboral();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>