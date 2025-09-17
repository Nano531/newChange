<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class Buscar_empleado extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."/logica/nuevo_empleado/'>Nuevo de empleado</a></span><br/><br/>";
		echo"<h2>Nuevo empleado</h2><hr/>
		<p>Los campos señalados con (*) son OBLIGATORIOS diligenciarlos.</p>";
		echo"<form action='agregarEmp.php' method='post' class='formulario' name='forma'>";
		echo "<h3>Datos personales</h3><hr style='border:2px solid #fff'/><br/>
		<table >
		<tr><td>Documento de identidad:</td><td><input type='text' id='iden' name='iden' size='35'/><span class='aster'>(*)</span><span id='txtHint'></span></td>
		</tr>
		<tr><td>Nombres:</td><td><input type='text' name='nom' size='35'/><span class='aster'>(*)</span></td></tr>
		<tr><td>Apellidos:</td><td><input type='text' name='ape' size='35'/><span class='aster'>(*)</span></td></tr>
		<tr><td>Correo electrónico:</td><td><input type='email' name='email' size='35'/></td></tr>
		<tr><td>Genero:</td><td><input type='radio' name='sex' value='Masculino' style='border:none'/> Masculino <input type='radio' name='sex' value='Femenino' style='border:none' checked /> Femenino</td></tr>
		<tr><td>Cabeza de familia:</td><td><input type='radio' name='cabeza' value='True' style='border:none'/> Si <input type='radio' name='cabeza' value='False' style='border:none' checked /> No</td></tr>
		<tr><td>Madre soltera:</td><td><input type='radio' name='madre' value='True' style='border:none'/> Si <input type='radio' name='madre' value='False' style='border:none' checked /> No</td></tr>
		<tr><td>Discapacitado:</td><td><input type='radio' name='disc' value='True' style='border:none' id='disc' onChange='desbloquear();'/> Si <input type='radio' name='disc' value='False' style='border:none' id='disc' onChange='desbloquear();' checked /> No <input type='text' name='tipodisc' id='tipodisc' placeholder=' Tipo discapacidad' disabled/> </td></tr>
		<tr><td>Fecha de nacimiento:</td><td><input type='date' name='fechaNac' /> (yyyy-mm-dd) </td></tr>
		<tr><td>Lugar de nacimiento:</td><td><input type='text' name='ciudadNac' size='35'/></td></tr>
		<tr><td>Telefono fijo:</td><td><input type='text' name='tel' size='35'/></td></tr>
		<tr><td>Telefono móvil:</td><td><input type='text' name='movil' size='35'/></td></tr>
		<tr><td>Dirección:</td><td><input type='text' name='dir' size='35'/></td></tr>
		<tr><td>Barrio:</td><td><input type='text' name='barrio' size='35'/></td></tr>
		<tr><td>Localidad:</td><td>"; $this->localidad($conexion); echo"</td></tr>
		<tr><td>Ciudad residencia:</td><td><input type='text' name='ciudadRes' size='35'/></td></tr>
		<tr><td>Vía principal:</td><td><input type='text' name='viaPrin' size='35'/></td></tr>
		<tr><td>Medio de transporte:</td><td>"; $this->medioTrans($conexion); echo"</td></tr>
		<tr><td>Tiempo de llegada:</td><td><input type='text' name='llegada' value='0' size='3'> Minutos</td></tr>
		<tr><td>Estrato:</td><td>"; $this->estrato(); echo"</td></tr>
		<tr><td>Estado civil</td><td>"; $this->estadoCivil($conexion); echo"</td></tr>
		<tr><td>Comunidades</td><td>"; $this->comunidades($conexion); echo"</td></tr>
		<tr><td>Grupo sanguineo:</td><td>"; $this->GrupoSanguineo($conexion); echo"</td></tr>
		<tr><td>Tipo de vivienda:</td><td><input type='radio' name='tipoViv' value='Arriendo' style='border:none'   id='titoviv' onChange='Activar();' checked/> Arriendo<br/> 
										  <input type='radio' name='tipoViv' value='Propio' style='border:none' id='titoviv' onChange='Activar();'/>Propio <br/>
										  <input type='radio' name='tipoViv' value='Familiar' style='border:none' id='titoviv' onChange='Activar();'/>Familiar<br/>
										  </td></tr>
		<tr><td>La vivienda la adquirio con Mercico</td><td><select name='conMercico' id='seleccion'><option value='True'>Si</option><option value='False' selected>No</option></select></td></tr>
		<tr><td>Valor del arriendo (Pesos):</td><td><input type='text' name='VrArriendo' value='0' size='10'/></td></tr>
		</table><br/>
		<input type='submit' value='Registrar' class='btn' style='height:40px'/>";
		echo"</form>";
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
	  echo"</div>";

  }

  function comunidades($con)
  {
  	$sql="Select * from Comunidades";
  	$result=$con->consultar($sql);
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		  echo "<input type='checkbox' name='comunidad[]' value='$reg->IdComunidad' /> $reg->NomComunidad<br/>";
	  }
  }
  function estrato()
  {
  	echo "<select name='estrato'>
  	";
  	
  	for ($i=1; $i <=8 ; $i++) { 
		  echo"<option value='$i'>$i</option>";
	  }  	 	
  	echo"</select>";
  }
  function medioTrans($con)
  {
  	$result=$con->consultar("Select * from TbMedioTransp");
  	echo"<select name='trans'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		echo"<option value='$reg->MedioTrans'>$reg->MedioTrans</option>";
		 } 
	echo "</select>";
  }
  function localidad($con)
  {
  	$result=$con->consultar("Select * from TbLocalidades");
  	echo"<select name='local'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		echo"<option value='$reg->IdLocalidad'>$reg->NomLocalidad</option>";
		 } 
	echo "</select>";
  }
  function GrupoSanguineo($con)
  {
  	$result=$con->consultar("Select * from TbGrupoSanguineo");
  	echo"<select name='rh'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		echo"<option value='$reg->IdGrSanguineo'>$reg->GrupoSanguineo</option>";
		 } 
	echo "</select>";
  }
  function estadoCivil($con)
  {
  	$result=$con->consultar("Select * from TbEstadoCivil");
  	echo"<select name='estCivil'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		echo"<option value='$reg->NomEstadoCivil'>$reg->NomEstadoCivil</option>";
		 } 
	echo "</select>";
	  
  }
  function dia()
  {
  	echo "<select name='dia'>
  	";
  	
  	for ($i=1; $i <=31 ; $i++) { 
		  echo"<option value='$i'>$i</option>";
	  }  	 	
  	echo"</select>";
  }
  function mes()
  {
  	$mes[0]="Enero";
  	$mes[1]="Febrero";
  	$mes[2]="Marzo";
  	$mes[3]="Abril";
  	$mes[4]="Mayo";
  	$mes[5]="Junio";
  	$mes[6]="Julio";
  	$mes[7]="Agosto";
  	$mes[8]="Septiembre";
  	$mes[9]="Octubre";
  	$mes[10]="Noviembre";
  	$mes[11]="Diciembre";
	echo"<select name='mes'>";
	for ($i=0; $i <12 ; $i++) {
		$m=$i+1; 
		echo"<option value='$m'>$mes[$i]</option>";
	}
	echo "</select>";
	
  }
  
  function año()
  {
  	$year = date("Y");
	echo "<select name='year'>";
	for ($i=1930; $i < $year ; $i++) { 
		echo "<option value='$i'>$i</option>";
	}
  	echo"</select>";
  }
  function mostrarJs()
  {
  	?>
<script>
	function desbloquear()
	{
		
		if(document.forma.disc[0].checked)
		{
			alert("Ingrese el tipo de discapacidad en el campo 'Tipo Discapacidad'");
			document.getElementById("tipodisc").disabled=!document.getElementById("tipodisc").disabled;
		} 
		else
		{
			document.getElementById("tipodisc").disabled=true;
		}
	}
	
	function Activar()
	{
		if(document.getElementById("titpoviv").value='Propio')
		{
			alerta("desactivar");
		}		
	}
	
</script>
<?php
  }
}
$pagina = new Buscar_empleado();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>
