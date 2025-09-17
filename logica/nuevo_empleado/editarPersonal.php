<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class editarPersonal extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
	$sql="SELECT * FROM Comunidades INNER JOIN UserComunidad ON Comunidades.IdComunidad = UserComunidad.IdComunidad WHERE (((UserComunidad.Userid)='$_GET[idu]'));";
	$res=$conexion->consultar($sql);
	
	$comunidades=array();
	while ($regis=$conexion->MostrarRegistrosAssoc($res)) 
	{
		$cmndd=$regis->NomComunidad;
		array_push($comunidades,$cmndd);
	}
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."/logica/lista_empleado/'>Lista de empleados</a> | <a href='javascript:window.history.back()'>Empleado</a> | Editar</span><br/><br/>";
		echo"<h2>Editar Datos personales</h2><hr/>";
		
		$sql="Select * from Userinfo where Userid='$_GET[idu]'";
		$result=$conexion->consultar($sql);
		while ($ed=$conexion->MostrarRegistrosAssoc($result)) {
			
			if($ed->VrArriendo=="") 
				$vrArriendo=0;
			else 
				$vrArriendo=str_replace(".0000","",$ed->VrArriendo);
				
			if($ed->TiempoLlegada=="") 
				$tiempo=0;
			else 
				$tiempo=$ed->TiempoLlegada;
			if($ed->Locker=="") 
				$nlocker=0;
			else 
				$nlocker=$ed->Locker;
			
			$fechaNac=$this->fecha($ed->Birthday);
			//echo "($fechaNac)";
			$año=substr($fechaNac, 0,4);
			$mes=substr($fechaNac, 5,2);
			$dia=substr($fechaNac, 8,2);
			//echo "$año-$mes-$dia";
			echo"<form action='procesarPersonal.php' method='post' class='formulario'>";
				echo "
				<table >
					<tr><td>Codigo Empleado:</td><td><span style='color:red; font-weight:bold'>$ed->Userid</span></td></tr>
					<tr><td>Apellidos y Nombres:</td><td><input type='text' name='nom' size='35' value='$ed->Name'/></td></tr>
					<tr><td>Correo electrónico:</td><td><input type='email' name='email' size='35' value='$ed->Email'/></td></tr>
					<tr><td>Genero:</td>"; $this->genero($ed->Sex); echo"</td></tr>
					<tr><td>Cabeza de familia:</td><td>"; $this->cabezaFamilia($ed->CabezaFam); echo"</td></tr>
					<tr><td>Madre soltera:</td><td>"; $this->madreSoltera($ed->MadreSoltera); echo"</td></tr>
					<tr><td>Discapacitado:</td><td>"; $this->discapacidad($ed->Discapacitado); echo" <input type='text' name='tipodisc' id='tipodisc' placeholder=' Tipo discapacidad' value='$ed->TipoDiscapacidad'/></td></tr>
					<tr><td>Fecha de nacimiento:</td><td><input type='date' name='fechaNac' value='$año-$mes-$dia'</td></tr>
					<tr><td>Lugar de nacimiento:</td><td><input type='text' name='ciudadNac' size='35' value='$ed->NativePlace'/></td></tr>
					<tr><td>Telefono fijo:</td><td><input type='text' name='tel' size='35' value='$ed->Telephone'/></td></tr>
					<tr><td>Telefono móvil:</td><td><input type='text' name='movil' size='35' value='$ed->Mobile'/></td></tr>
					<tr><td>Dirección:</td><td><input type='text' name='dir' size='35' value='$ed->Address'/></td></tr>
					<tr><td>Barrio:</td><td><input type='text' name='barrio' size='35' value='$ed->Barrio'/></td></tr>
					<tr><td>Localidad:</td><td>"; $this->localidad($conexion, $ed->Localidad); echo"</td></tr>
					<tr><td>Ciudad residencia:</td><td><input type='text' name='ciudadRes' size='35' value='$ed->Ciudad'/></td></tr>
					<tr><td>Vía principal:</td><td><input type='text' name='viaPrin' size='35' value='$ed->ViaPrincipal'/></td></tr>
					<tr><td>Medio de transporte:</td><td>"; $this->medioTrans($conexion, $ed->MedioTrans); echo"</td></tr>
					<tr><td>Tiempo de llegada:</td><td><input type='text' name='llegada' value='$tiempo' size='3'> Minutos</td></tr>
					<tr><td>Estrato:</td><td>"; $this->estrato($ed->Estrato); echo"</td></tr>
					<tr><td>Comunidades:</td><td>"; $this->comunidades($conexion,$comunidades); echo"</td></tr>
					<tr><td>Estado civil</td><td>"; $this->estadoCivil($conexion, $ed->Polity); echo"</td></tr>
					<tr><td>Grupo sanguineo:</td><td>"; $this->GrupoSanguineo($conexion, $ed->IdRh); echo"</td></tr>
					<tr><td>Tipo de vivienda:</td><td>"; $this->tipoViv($ed->TipoViv); echo"</td></tr>
					
					<tr><td>La vivienda la adquirio con Mercico:</td><td>"; $this->conMercico($ed->VivConMercico); echo"</td></tr>
					<tr><td>Locker:</td><td>"; $this->locker($conexion, $ed->Sex,$nlocker); echo"</td></tr>
					<tr><td>Valor del arriendo (Pesos):</td><td><input type='text' name='VrArriendo' size='10' value='$vrArriendo'/></td></tr>
					
				</table><br/>
				<input type='hidden' value='$ed->Userid' name='userid'/>
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
  
   function conMercico($id)
  {
  	if($id==TRUE) echo "<input type='radio' name='conMercico' value='True'   checked/> Si <input type='radio' name='conMercico' value='False' />No";
    else echo "<input type='radio' name='conMercico' value='True'  /> Si <input type='radio' name='conMercico' value='False'  checked />No";
  }
  function comunidades($con,$idcom)
  {
	  //echo "idcom === $idcom";
  	$sql="Select * from Comunidades";
	$result=$con->consultar($sql);
	while ($reg=$con->MostrarRegistrosAssoc($result)) 
	{
		//if($idcom==$reg->NomComunidad)
		if (in_array($reg->NomComunidad, $idcom))
			echo "<input type='checkbox' name='comunidad[]' value='$reg->IdComunidad' checked/> $reg->NomComunidad<br/>";
			//echo "<input type='radio' name='comunidad' value='$reg->IdComunidad' checked>$reg->NomComunidad<br/>";
		else
			echo "<input type='checkbox' name='comunidad[]' value='$reg->IdComunidad' /> $reg->NomComunidad<br/>";
			//echo "<input type='radio' name='comunidad' value='$reg->IdComunidad' />$reg->NomComunidad<br/>";
	}
  }
  function medioTrans($con, $id)
  {
  	$result=$con->consultar("Select * from TbMedioTransp");
  	echo"<select name='trans'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		if($id==$reg->MedioTrans) echo"<option value='$reg->MedioTrans' selected>$reg->MedioTrans</option>";
		else echo"<option value='$reg->MedioTrans'>$reg->MedioTrans</option>";
		 } 
	echo "</select>";
  }
  function discapacidad($id)
  {
  	if($id==TRUE) echo "<input type='radio' name='disc' value='True'  onChange='desbloquear();' checked/> Si <input type='radio' name='disc' value='False'  onChange='desbloquear();' />No";
    else echo "<input type='radio' name='disc' value='True'  onChange='desbloquear();'/> Si <input type='radio' name='disc' value='False'  onChange='desbloquear();' checked />No";
  }
  function madreSoltera($id)
  {
  	if($id==TRUE) echo "<input type='radio' name='madre' value='True' checked/> Si <input type='radio' name='madre' value='False'  />No";
    else echo "<input type='radio' name='madre' value='True' /> Si <input type='radio' name='madre' value='False' checked />No";
  }
  function tipoViv($valor)
  {
  	if($valor=="Arriendo")
  	{
		echo "<input type='radio' name='tipoViv' value='Arriendo' style='border:none' onchange='Activar()'  checked/> Arriendo<br/>
  	 	<input type='radio' name='tipoViv' value='Propio' style='border:none' />Propio<br/>
		<input type='radio' name='tipoViv' value='Familiar' style='border:none' />Familiar<br/>";	
	}
	else if($valor=="Propio")
	{
		echo "<input type='radio' name='tipoViv' value='Arriendo' style='border:none' onchange='Activar()'  /> Arriendo<br/>
  	 	<input type='radio' name='tipoViv' value='Propio' style='border:none' checked/>Propio<br/>
		<input type='radio' name='tipoViv' value='Familiar' style='border:none' />Familiar<br/>";
	}
	else {
		echo "<input type='radio' name='tipoViv' value='Arriendo' style='border:none' onchange='Activar()'  /> Arriendo<br/>
  	 	<input type='radio' name='tipoViv' value='Propio' style='border:none' />Propio<br/>
		<input type='radio' name='tipoViv' value='Familiar' style='border:none' checked/>Familiar<br/>";
	}
  	
  }
  function estrato($est)
  {
  	echo "<select name='estrato'>
  	<option value='0'>N-A</option>";
  
  	for ($i=1; $i <=8 ; $i++) {
  		 if($est==$i) echo"<option value='$i' selected>$i</option>";
		  echo"<option value='$i'>$i</option>";
	  }  	 	
  	echo"</select>";
  }
  function localidad($con, $loc)
  {
  	$result=$con->consultar("Select * from TbLocalidades Order By NomLocalidad");
  	echo"<select name='local'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) 
	{
  		if($loc==$reg->IdLocalidad) 
			echo"<option value='$reg->IdLocalidad' selected>$reg->NomLocalidad</option>";
  		else
			echo"<option value='$reg->IdLocalidad'>$reg->NomLocalidad</option>";
	} 
	echo "</select>";
  }
  function GrupoSanguineo($con, $rh)
  {
  	$result=$con->consultar("Select * from TbGrupoSanguineo");
  	echo"<select name='rh'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		if($rh==$reg->IdGrSanguineo) echo"<option value='$reg->IdGrSanguineo' selected>$reg->GrupoSanguineo</option>";
  		echo"<option value='$reg->IdGrSanguineo'>$reg->GrupoSanguineo</option>";
		 } 
	echo "</select>";
  }
  function estadoCivil($con, $est)
  {
  	$result=$con->consultar("Select * from TbEstadoCivil");
  	echo"<select name='estCivil'>";
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  			if($est==$reg->NomEstadoCivil) echo"<option value='$reg->NomEstadoCivil' selected>$reg->NomEstadoCivil</option>";
  		echo"<option value='$reg->NomEstadoCivil'>$reg->NomEstadoCivil</option>";
		 } 
	echo "</select>";
	  
  }
  function locker($con,$sexo,$locker)
  {
	if($locker==0)
	{
		$locker="Sin asignar";
		$lboton="Asignar";
	}
	else
	{
		$lboton="Cambiar";
	}
		
	echo "<label id='lab'>$locker</label>";
	
	if($sexo=="Masculino")
		$max=150;
	else
		$max=220;
		
	echo"<select id='loc' name='loc' style='display:none;'>";	
		echo"<option value=''></option>";
	$result=$con->consultar("SELECT Name,Locker FROM Userinfo Where Locker is not null AND Sex='$sexo' order by Locker;");
	for($i=1;$i<=$max;$i++)
	{
		// $nlocker= $reg->Locker;
		$aux=0;
		while ($reg=$con->MostrarRegistrosAssoc($result)) 
		{
			if($i==$reg->Locker)
			{
				// echo " i:$i,Locker: $nlocker";
				$aux++;
			}	
		}
		if($aux==0)
		{
			// echo "$nlocker";
			echo"<option value='$i'>$i</option>";
		}
		
	}
	echo "</select>";
	echo "<label>   </label><input type='button' value='$lboton' id='bot1' onClick='cmblocker()'/>";
  }
  function dia($dia)
  {
  	echo "<select name='dia'>
  	";
  	
  	for ($i=1; $i <=31 ; $i++) {
  		  if($dia==$i) echo"<option value='$i' selected>$i</option>";	
		  else echo"<option value='$i'>$i</option>";
	  }  	 	
  	echo"</select>";
  }
  function mes($month)
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
		if($month-1 == $i) echo"<option value='$m' selected>$mes[$i]</option>";
		else echo"<option value='$m'>$mes[$i]</option>";
	}
	echo "</select>";
	
  }
  
  function año($año)
  {
  	$year = date("Y");
	echo "<select name='year'>";
	for ($i=1930; $i < $year ; $i++) {
		if($año==$i) echo "<option value='$i' selected>$i</option>";
		else echo "<option value='$i'>$i</option>";
	}
  	echo"</select>";
  }
  function genero($gen)
  {
  	if($gen=="Femenino")
	{
		echo "<td><input type='radio' name='sex' value='Masculino' style='border:none'/> Masculino <input type='radio' name='sex' value='Femenino' style='border:none' checked /> Femenino";
	}
	else {
		echo "<td><input type='radio' name='sex' value='Masculino' style='border:none' checked/> Masculino <input type='radio' name='sex' value='Femenino' style='border:none'  /> Femenino";
	}
  }
  public function cabezaFamilia($valor)
  {
      if($valor==TRUE)
	  {
	  	echo "<input type='radio' name='cabeza' value='True' style='border:none' checked/> Si <input type='radio' name='cabeza' value='False' style='border:none'  /> No";
	  }
	  else {
		  echo "<input type='radio' name='cabeza' value='True' style='border:none' /> Si <input type='radio' name='cabeza' value='False' style='border:none' checked /> No";

	  }
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
		
		function cmblocker()
		{
			document.getElementById("lab").style.display='none';
			document.getElementById("bot1").style.display='none';
			document.getElementById("loc").style.display='';
		}
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
$pagina = new editarPersonal();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>