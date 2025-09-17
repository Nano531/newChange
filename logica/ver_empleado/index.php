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
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/lista_empleado/'>Lista de empleados</a> | <a href='javascript:location.reload()'>Empleado</a></span><br/><br/>";
		
        if(isset($_GET['idu'])) $this->verEmpleado($conexion);
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
  function verEmpleado($conexion)
  {
  	
		$resultado=$conexion->consultar("SELECT  * FROM Userinfo WHERE (((Userinfo.Userid)='$_GET[idu]'))");
		while($fila=$conexion->MostrarRegistrosAssoc($resultado))
		{
			
			echo"<div id='datosIzq'>";
			
				if($fila->Foto!="") echo"<center><br/><img id='fotouser' src='$fila->Foto'/><br/>
					<a href='#' onClick='subirFoto($_GET[idu]);' style='font-size:12px'>[ Cambiar ]</a>
					<a href='#' onClick='eliminarFoto($_GET[idu]);' style='font-size:12px'>[ Eliminar ]</a></center>";
				else 
					echo"<img src='".$this->nivel."presentacion/img/no_foto.png'/><br/><center><a href='#' onClick='subirFoto($_GET[idu]);' style='font-size:12px'>Subir Fotografía</a></center>";
				
				if($fila->Retirado==TRUE)
				{
					echo"<center><a href='#' onClick='reactivar($_GET[idu]);' style='font-size:12px'>[ Reactivar ]</a></center>";
				}
				else
				{
					echo "<center><a href='#' onClick='retirar($_GET[idu]);' style='font-size:12px'>[ Retirar ]</a></center>";
				}
				echo"<hr/>";
				
				echo"<div style='clear:both'></div>";
			echo"</div>";
			
			echo"<a name='inf'></a>";
			echo"<div id='datosCen'>
				<h2 class='tituloNom'>$fila->Name <span style='float:right;'><a href='javascript:location.reload()'><img src='../../presentacion/img/actualizar.png'/></a></span><span style='float:right;padding-right: 10px;'>".$this->empresa($conexion, $_GET['idu'])."</span></h2>";
				
				//echo"<hr/>";
				if($_SESSION['tipo']!='Consulta'){
					echo"<div class='menu1'><a href='index.php?idu=$_GET[idu]&pp=per#inf' class='linkmenu'>Inf. Personal</a> | <a href='index.php?idu=$_GET[idu]&pp=lab#inf' class='linkmenu'>Inf. Laboral</a> | <a href='index.php?idu=$_GET[idu]&pp=acad#inf' class='linkmenu'>Inf. Académica</a> | <a href='index.php?idu=$_GET[idu]&pp=Fam#inf' class='linkmenu'> Grupo familiar </a> | <a href='index.php?idu=$_GET[idu]&pp=Trab#inf' class='linkmenu'> Hist. Trabajo </a>| <a href='index.php?idu=$_GET[idu]&pp=adj#inf' class='linkmenu'>Adjuntos</a></div>";
				//echo"<hr>";
				echo"<div class='menu3'><a href='#' onClick='dotacion($fila->Userid);' class='linkmenu'>Hist. de dotación</a> | <a href='#' onClick='epp($fila->Userid);' class='linkmenu'>Hist. Elem. Prot. Personal</a> | <a href='#' onClick='capacitacion($fila->Userid);' class='linkmenu'>Hist. de capacitaciones</a> | <a href='#' onClick='ausentismo($fila->Userid);' class='linkmenu'>Hist. Ausentismo</a></div>";
				//echo"<br><hr>";
				echo"<div class='menu4'><a href='#' onClick='Disciplinario($fila->Userid);' class='linkmenu'>Hist. Disciplinario</a> | <a href='#' onClick='compensatorios($fila->Userid);' class='linkmenu'>Compensatorios</a> | <a href='../disciplinario/informe_general.php?idu=$_GET[idu]' class='linkmenu'>Estadisticas de Retardo</a></div>";
				}
				
			echo"</div>";
			echo"<div id='datosCen2'>";
			
			if($fila->Retirado==TRUE) {echo"<hr/><div id='datos1' style='background-image:url(../../presentacion/img/retirado.png); background-repeat:no-repeat; background-size:100%'>";}
			else {echo "<hr/><br/><div id='datos1'>";}	
			
			$this->empresa($conexion, $_GET['idu']);
			switch ($_GET['pp']) {
			
				case 'lab': echo"<h3 style='color:#2c89a0;'>Información Laboral <span class='link'><a href='../nuevo_empleado/editarLaboral.php?idu=$_GET[idu]'>[ Editar ]</a></span></h3>";
							
							$diftot=0;
							// echo "SELECT * FROM TbEmpresas INNER JOIN HistLaboral ON TbEmpresas.IdEmpresa = HistLaboral.IdEmpresa WHERE (((HistLaboral.Userid)='$_GET[idu]')) AND TbEmpresas.NomEmpresa='Mercico Ltda';";
							$res=$conexion->consultar("SELECT * FROM TbEmpresas INNER JOIN HistLaboral ON TbEmpresas.IdEmpresa = HistLaboral.IdEmpresa WHERE (((HistLaboral.Userid)='$_GET[idu]')) AND TbEmpresas.NomEmpresa='Mercico Ltda';");
							$año=0;
							$mes=0;
							$dia=0;
							while ($reg=$conexion->MostrarRegistrosAssoc($res)) 
							{
								$año=$año+$this->año($reg->FechaIni,$reg->FechaFin);
								$mes=$mes+$this->mes($reg->FechaIni,$reg->FechaFin);
								$dia=$dia+$this->dia($reg->FechaIni,$reg->FechaFin);
								
							}
							$auxa=$this->año($fila->EmployDate,date("Y-m-d"));
							$auxm=$this->mes($fila->EmployDate,date("Y-m-d"));
							$auxd=$this->dia($fila->EmployDate,date("Y-m-d"));
							
							if($fila->Retirado==FALSE)
							{
								$año=$año+$auxa;
								$mes=$mes+$auxm;
								$dia=$dia+$auxd;								
							}
							
							// echo "año:$auxa,mes:$auxm,dia:$auxd<br>";
							// echo "año:$año,mes:$mes,dia:$dia<br>";
							
							$auxdia=floor($dia/30);
							$dia=$dia-($auxdia*30);
							$mes=$mes+$auxdia;
							
							$auxmes=floor($mes/12);
							$mes=$mes-($auxmes*12);
							$año=$año+$auxmes;
							$aux=0;
							$aux2=0;

 							echo"<table style='width: 100%;'>";
							echo"<tr><th colspan='4' style='background-color: gainsboro;'>Mercico</th></tr>";
							echo"<tr>
								<th>Tipo de contrato:</th><td>";  if($fila->IdTipoContrato=='') {echo"N-A";} else { $this->contrato($fila->IdTipoContrato, $conexion); }echo"</td>
								<th>Cargo:</th><td>";  if($fila->IdCargo=='') {echo"N-A";} else {$this->Cargo($fila->IdCargo, $conexion);} echo"</td>
							</tr>";							
							
							// echo "<tr><th>Duracion contrato:</th><td>$año año(s) $mes mes(es) $dia dia(s)<td>";
							//Modificacion 30/09/2015 - CRISTIAN MEJIA - START
							$res=$conexion->consultar("SELECT * FROM TbEmpresas INNER JOIN HistLaboral ON TbEmpresas.IdEmpresa = HistLaboral.IdEmpresa WHERE (((HistLaboral.Userid)='$_GET[idu]')) AND TbEmpresas.NomEmpresa='Mercico Ltda';");
							// echo "SELECT * FROM TbEmpresas INNER JOIN HistLaboral ON TbEmpresas.IdEmpresa = HistLaboral.IdEmpresa WHERE (((HistLaboral.Userid)='$_GET[idu]')) AND TbEmpresas.NomEmpresa='Mercico Ltda';";
							
							while ($reg=$conexion->MostrarRegistrosAssoc($res)) 
							{
								if($aux2 == 0)
								{
									echo"<tr>";
								}
								$aux2++;
								
								$aux++;
								$año1=$this->año($reg->FechaIni,$reg->FechaFin);
								$mes1=$this->mes($reg->FechaIni,$reg->FechaFin);
								$dia1=$this->dia($reg->FechaIni,$reg->FechaFin);

								echo "<th>Duracion	contrato $aux:</th><td>$año1 año(s) $mes1 mes(es) $dia1 dia(s)</td>";
								if($aux2 == 2)
								{
									echo"</tr>";
									$aux2 = 0;
								}
							}
							//Modificacion 30/09/2015 - CRISTIAN MEJIA - END

							echo"<tr>
								<th>Fecha de ingreso:</th><td>";  if($fila->EmployDate=='') {echo"N-A";} else {$this->fecha($fila->EmployDate);} echo"</td>";
								echo "<th>Antiguedad:</th><td>$año año(s) $mes mes(es) $dia dia(s)</td>";
							echo"</tr>";
							if($fila->Retirado==TRUE) echo "<tr><th>Fecha de retiro:</th><td>"; $this->fecha($fila->FechaRetiro); echo"</td></tr>";
							
							echo"<tr>
								<th>Correo corporativo:</th><td>$fila->EmailCorp</td>";
								echo"<th>Sede:</th><td>$fila->Sede</td>";
							echo"</tr>";
							
							echo"<tr>
								<th>EPS:</th><td>";  if($fila->IdEps=='') {echo"N-A";} else {$this->eps($fila->IdEps, $conexion);} echo"</td>";
								echo"<th>ARP:</th><td>";  if($fila->IdArp=='') {echo"N-A";} else{$this->arp($fila->IdArp, $conexion);} echo"</td>";
							echo"</tr>";
							echo"<tr>
								<th>Fondo de pensiones:</th><td>";  if($fila->IdAfp=='') {echo"N-A";} else {$this->fondoPensiones($fila->IdAfp, $conexion);} echo"</td>";
								echo"<th>Fondo de cesantias:</th><td>";  if($fila->IdCesantias=='') {echo"N-A";} else {$this->fondoCesantias($fila->IdCesantias, $conexion);} echo"</td>";
							echo"</tr>";
							echo "<tr><th>Fondo de Empleados:</th>"; $this->fondo($fila->FondoEmpleado); echo"</tr>";
							echo "<tr>
								<th>Centro de Costo:</th>"; $this->centroCosto($fila->CentroCosto, $conexion);
								echo "<th>Proceso:</th>"; if(isset($fila->Proceso)){$this->proceso($fila->Proceso, $conexion);}
							echo "</tr>";
							echo "<tr>
								<th>Escalafon:</th>"; if(isset($fila->Escalafon)){$this->escalafon($fila->Escalafon, $conexion);}
								echo "<th>Locker:</th><td>$fila->Locker</td>";
							echo"</tr>";
							echo"<tr><th colspan='4' style='background-color: gainsboro;'>Información de dotación</th></tr>";
							echo "<tr><th>Dotación:</th>"; $this->reqDota($fila->Dotacion); echo"</tr>";
							if($fila->Dotacion==TRUE)
							{
								echo"<tr>
									<th>Tipo de dotación</th>"; if($fila->TipoDotacion)$this->tipoDota($fila->TipoDotacion, $conexion);
									echo "<th>Talla camisa:</th>"; $this->TallaCam($fila->TallaCamisa, $conexion);
								 echo"</tr>";
								echo "<tr>
									<th>Talla pantalon:</th>"; $this->TallaPan($fila->TallaPantalon, $conexion);
									echo "<th>Talla calzado:</th>"; $this->TallaCal($fila->TallaCalzado, $conexion); 
								echo"</tr>";
							}
							
							echo"</table>";
					
					break;
				case 'acad': $this->infoAcad($fila->Userid, $conexion);
					break;
				case 'Fam': $this->familiares($conexion, $fila->Userid);
					break;
				case 'Trab': $this->Trabajo($conexion, $fila->Userid);
					break;
				case 'adj': $this->Adjuntos($conexion, $fila->Userid);
					break;	
				default: 
						echo"<h3 style='color:#2c89a0;'>Información personal <span class='link'><a href='../nuevo_empleado/editarPersonal.php?idu=$_GET[idu]'>[ Editar ]</a></span></h3>
						<table style='width: 100%;'>
						<tr><th colspan='4' style='background-color: gainsboro;'>Información basica</th></tr>
						<tr>
							<th>Documento:</th><td>$fila->IDCard</td>
							<th>Lugar de nacimiento:</th><td>"; if($fila->NativePlace=='') {echo"N-A";} else { echo $fila->NativePlace;} echo"</td>
						</tr>
						<tr>
							<th>Fecha de nacimiento:</th><td>"; if($fila->Birthday=='') {echo"N-A";} else {$this->fecha($fila->Birthday);} echo"</td>
							<th>Años:</th><td>"; if($fila->Birthday=='') {echo"N-A";} else { echo $this->añosCumplidos($fila->Birthday);} echo"</td>
						</tr>
						<tr>
							<th>Genero:</th><td>"; if($fila->Sex=='') {echo"N-A";} else { echo $fila->Sex;} echo"</td>
							<th>RH:</th><td>"; if($fila->IdRh=='') {echo"N-A";} else {$this->FactorRh($fila->IdRh, $conexion);} echo"</td>
						</tr>
						<tr>
							<th>Telefono fijo:</th><td>$fila->Telephone</td>
							<th>Telefono móvil</th><td>$fila->Mobile</td>
						</tr>
						<tr>
							<th>Correo electrónico:</th><td><a href='mailto:$fila->Email'>$fila->Email</a></td>
						</tr>
						<tr>
							<th>Estado Civil:</th><td>"; if($fila->Polity=='') {echo"N-A";} else { echo $fila->Polity;} echo"</td>";
							echo"<th>Nivel Educativo</th><td>"; $this->nivelEducativo($fila->Userid, $conexion); echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<th>Cabeza de Familia</th><td>"; $this->CabFamilia($fila->CabezaFam); echo"</td>";
							echo"<th>Madre soltera</th><td>"; $this->CabFamilia($fila->MadreSoltera); echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<th>Discapacitado</th><td>"; $this->CabFamilia($fila->Discapacitado); echo"</td>";
							if($fila->Discapacitado==True)echo "<th>Tipo de discapacidad:</th><td>$fila->TipoDiscapacidad</td>";
						echo"</tr>";
						echo"<tr><th colspan='4' style='background-color: gainsboro;'>Información de Residencia</th></tr>";
						echo"<tr>";
							echo"<th>Dirección:</th><td>$fila->Address</td>
							<th>Barrio:</th><td>$fila->Barrio</td>
						</tr>
						<tr>
							<th>Localidad:</th><td>";  if($fila->Localidad=='') {echo"N-A";} else {$this->localidad($fila->Localidad, $conexion);} echo"</td>
							<th>Ciudad residencia:</th><td>$fila->Ciudad</td>
						</tr>
						<tr>
							<th>Vía principal:</th><td>$fila->ViaPrincipal</td>
							<th>Med. Transporte:</th><td>$fila->MedioTrans</td>
						</tr>
						<tr>
							<th>Tiempo de llegada:</th><td>"; if(isset($fila->TiempoLlegada)) echo "$fila->TiempoLlegada Min."; echo"</td>
							<th>Estrato:</th><td>$fila->Estrato</td>
						</tr>
						<tr>
							<th>Comunidades</th><td>"; $this->Comunidades($conexion); echo"</td>
						</tr>
						
						
						<tr>
							<th>Tipo de vivienda:</th><td>$fila->TipoViv</td>";
							if($fila->TipoViv=="Familiar" || $fila->TipoViv=="Propio") 
							{
								echo "<th style='width: 30%;'>La vivienda la adquirio con Mercico:</th><td>"; $this->conMercico($fila->VivConMercico); echo "</td>";
							}
							if($fila->TipoViv=="Arriendo")
							{
								$valor= number_format($fila->VrArriendo);
								echo"<th>Valor de Arriendo:</th><td>Si ($ $valor)</td>";
							}
						echo"</tr>";
						//echo "<tr><th>Locker:</th><td>$fila->Locker</td></tr>";
						echo"</table>";
						break;
			}
						
			echo"</div>";
			
			/*echo"<div id='datos2'>";
			
			echo"<div id='menuItem'>
			<h3 style='color:#fff; background:#006680; padding:10px 20px 10px 20px; margin:0 0 10px 0; text-align:center'>Menu ítems</h3>
			<a href='#' onClick='dotacion($fila->Userid);'>Hist. de dotación</a><br/>
			<a href='#' onClick='epp($fila->Userid);'>Hist. Elem. Prot. Personal</a><br/>
			<a href='#' onClick='capacitacion($fila->Userid);'>Hist. de capacitaciones</a><br/>
			
			<a href='#' onClick='ausentismo($fila->Userid);'>Hist. Ausentismo</a><br/>
			<a href='#' onClick='Disciplinario($fila->Userid);'>Hist. Disciplinario</a><br/>
			<a href='#' onClick='compensatorios($fila->Userid);'>Compensatorios</a><br/>
			<a href='../disciplinario/informe_general.php?idu=$_GET[idu]'>Estadisticas de Retardo</a><br/>
			
			<br/></div>
			";
			
			
			echo"</div>";*/
			echo"</div>";
			echo"<div style='clear:both'></div>";
		}
  }
function escalafon($id, $con)
{
	$sql="Select * from Escalafon Where Idescalafon=$id";
	$result=$con->consultar($sql);
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<td>$reg->NomEscalafon</td>";
	}
}
function proceso($id, $con)
{
	$sql="Select * from Procesos Where IdProceso=$id";
	$result=$con->consultar($sql);
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<td>$reg->NomProceso</td>";
	}
}
function centroCosto($id, $con)
{
	if($id=='') $id=0;
	
	$sql="Select * From CentroCosto Where IdCentroCosto=$id";
	$result=$con->consultar($sql);
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		echo "<td>$reg->NomCentro</td>";
	}
}
function nivelEducativo($id, $con)
{
	$sql="SELECT * FROM TbNivelEdu INNER JOIN TbInfAcademica ON TbNivelEdu.IdNivel = TbInfAcademica.Nivel
WHERE (((TbInfAcademica.Userid)='$id'));";
$result=$con->consultar($sql);
$mayor=0;
while ($reg=$con->MostrarRegistrosAssoc($result)) {
if($reg->IdNivel >$mayor) $mayor=$reg->IdNivel; 
//if($reg->SinTerminar==True) $SinTerminar="Sin terminar <br/>($reg->Grado)";}
}
$sqlniv="Select * from TbNivelEdu where IdNivel=$mayor";
// echo "Select * from TbNivelEdu where IdNivel=$mayor";
$result2=$con->consultar($sqlniv);
while ($reg=$con->MostrarRegistrosAssoc($result2))
{
	echo "$reg->NomNivel";
}

}
function Comunidades($con)
{
	$sql="SELECT * FROM Comunidades INNER JOIN UserComunidad ON Comunidades.IdComunidad = UserComunidad.IdComunidad WHERE (((UserComunidad.Userid)='$_GET[idu]'));";
	$result=$con->consultar($sql);
	echo "<ul>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<li>$reg->NomComunidad </li>";
	}
}
function conMercico($id)
{
	if($id==True) echo "SI";
	else echo "NO";
}
function fondo($id)
{
	if($id==True) echo "<td>SI</td>";
	else echo "<td>NO</td>";
}
function CabFamilia($id)
{
	if($id==TRUE) echo "Si";
	else echo "No";
}
function certificado($con, $id)
{
	$result=$con->consultar("Select * from Adjuntos Where Userid='$id' AND TipoAdj='Certificado Paz y Salvo'");
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo "<a href='$reg->url'>Descargar Paz y Salvo</a><br/><br/>";
	}
}
function TallaCal($id, $con)
{
	$result=$con->consultar("Select * from TbTalla Where IdTalla=$id");
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo "<td>$reg->NomTalla</td>";
	}
	
}
function TallaPan($id, $con)
{
	$result=$con->consultar("Select * from TbTalla Where IdTalla=$id");
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo "<td>$reg->NomTalla</td>";
	}
	
}
function TallaCam($id, $con)
{
	$result=$con->consultar("Select * from TbTalla Where IdTalla=$id");
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 echo "<td>$reg->NomTalla</td>";
	}
	
}
function tipoDota($id, $con)
{
	$result=$con->consultar("SELECT TipoDotacion.NomDotacion FROM TipoDotacion WHERE (((TipoDotacion.IdTipoDotacion)=$id))");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<td>$reg->NomDotacion</td>";
	}
}
function reqDota($id)
{
	if($id==True) echo "<td>SI</td>";
	else echo "<td>NO</td>";
}
function Adjuntos($con, $id)
{

	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo"<br/><br/><form action='../subiradjuntos/index.php' method='POST' enctype='multipart/form-data'>";
			echo"<select name='tipo'>
				<option value='Hoja de vida'>Hoja de vida</option>
				<option value='Cedula'>Cedula</option>
				<option value='Visita domiciliaria'>Visita domiciliaria</option>
				<option value='Afiliaciones ARP'>Afilicaciones ARP</option>
				<option value='Afiliaciones EPS'>Afilicaciones EPS</option>
				<option value='Afiliacion Caja Comp.'>Afilicacion Caja Comp.</option>
				<option value='Afiliacion Fondo Pensiones'>Afilicacion Fondo Pensiones</option>
				<option value='Afiliacion cesantias'>Afilicacion cesantias</option>
				<option value='Contrato'>Contrato</option>
				<option value='Referencias'>Referencias</option>
				<option value='Estudio de Seguridad'>Estudio de Seguridad</option>
				<option value='Certificado Paz y salvo'>Certificado Paz y Salvo</option>
			</select><br/><br/>
			<input type='file' name='documento'/> <input type='submit' value='Cargar' /> 
			<input type='hidden' name='idu' value='$id'/>";
		echo"</form><br/>
		<hr/>";
	}
	
		echo"<h3>Documentos adjuntos</h3>";
		$result=$con->consultar("Select * from Adjuntos Where Userid='$id'");
		echo "<ul>";
		while ($reg=$con->MostrarRegistrosAssoc($result)) {
			echo "<li><a href='$reg->url' title='$reg->NomAdj'>$reg->TipoAdj</a></li>";
		}
		echo "</ul>";
}
function Trabajo($con, $id)
{
	if(isset($_GET[idlab]) && ($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin'))
	{
		$sql="Delete From HistLaboral Where idHistLab=$_GET[idlab]";
		
		$result=$con->consultar($sql);
		
		
	}
	echo"<h3 style='color:#2c89a0; '> Historial de Trabajo <span class='link' ><a href='#' onClick='agregarTrabajo($id);' style='text-decoration:none'>[ Agregar + ]</a></span></h3>";
	$result=$con->consultar("SELECT * FROM TbEmpresas INNER JOIN HistLaboral ON TbEmpresas.IdEmpresa = HistLaboral.IdEmpresa
WHERE (((HistLaboral.Userid)='$id'));");
while ($reg=$con->MostrarRegistrosAssoc($result)) {
	echo "<span style='color:#000;  font-weight:normal; color:#555' ><h4>$reg->NomEmpresa <span class='link3'><a href='#' onClick='editarReferencia($reg->IdEmpresa,$id);' style='text-decoration:none'>[ Editar ]</a> <a href='index.php?idu=$_GET[idu]&pp=$_GET[pp]&idlab=$reg->idHistLab&emp=$reg->IdEmpresa#inf'>[ X ]</a></span></h4>
	<b>Contacto:</b> $reg->Contacto<br/>
	<b>Teléfono:</b> $reg->Telefono<br/>
	<b>Fecha de inicio:</b> "; $this->fecha($reg->FechaIni); echo "<br/>
	<b>Fecha finalización:</b> ";  $this->fecha($reg->FechaFin); echo"<br/>
	<b>Comentarios:</b> $reg->Comentario</span><br>";
	echo "<b>Duracion:</b> $dif $med";
	$this->duracion($reg->FechaIni,$reg->FechaFin);
	echo "</span><br>";
}
	
}

function daysDifference($endDate, $beginDate){
$date_parts1=explode("-", $beginDate);
$date_parts2=explode("-", $endDate);
$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
return $end_date - $start_date;
}


function infoAcad($id, $con)
{
	if(isset($_GET[idac]) && ($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin'))
	{
		$sql="Delete From TbInfAcademica Where IdInfAcad=$_GET[idac]";
		$result=$con->consultar($sql);
		
		
	}
	echo"<h3 style='color:#2c89a0; '>Información académica <span class='link' ><a href='#' onClick='agregarAcad($id);' style='text-decoration:none'>[ Agregar + ]</a></span></h3>";
	echo"<div id='academico' >";
	$result=$con->consultar("SELECT * FROM TbNivelEdu INNER JOIN TbInfAcademica ON TbNivelEdu.IdNivel = TbInfAcademica.Nivel
WHERE (((TbInfAcademica.Userid)='$id'));");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo"<h4 style='color:#555'>$reg->Institucion <span class='link3'><a href='#' onClick='editarAcad($reg->IdInfAcad);' style='text-decoration:none'>[ Editar ]</a> <a href='index.php?idu=$_GET[idu]&pp=$_GET[pp]&idac=$reg->IdInfAcad#inf'>[ X ]</a></span></h4>
		<b>Titulo: </b> $reg->Titulo<br/>";
		if($reg->SinTerminar==TRUE)echo"<b>Sin terminar:</b> SI ($reg->Grado)<br/>";
		echo"<b>Nivel académico: </b> $reg->NomNivel </br>
		<b>Ciudad: </b> $reg->Ciudad</br>
		<b>Año: </b>$reg->Year<br/>";
	}
	echo "</div>";
}

function mostrarJs()
{
	?>
		<script>
		function Disciplinario(id)
		{ 
			//if (window.showModalDialog) {
			//		window.showModalDialog("disciplinario.php?idu="+id,"Disciplinario","dialogWidth:700px; dialogHeight:600px");
			//}
			var left = Number((screen.width/2)-(700/2));
			var tops = Number((screen.height/2)-(600/2));

			var ventana = window.open("disciplinario.php?idu="+id,"Ausentismo","Width=700,Height=600,scrollbars=yes,top="+tops+", left="+left);
			
		}
		function compensatorios(id)
		{ 
			//if (window.showModalDialog) {
			//		window.showModalDialog("compensatorio.php?idu="+id+"&ano=","Compensatorio","dialogWidth:700px; dialogHeight:600px");
			//}
			var left = Number((screen.width/2)-(700/2));
			var tops = Number((screen.height/2)-(600/2));

			var ventana = window.open("compensatorio.php?idu="+id,"Ausentismo","Width=700,Height=600,scrollbars=yes,top="+tops+", left="+left);
			
		}
		function ausentismo(id)
		{ 
			//if (window.showModalDialog) {
			//		window.showModalDialog("ausentismo.php?idu="+id,"Ausentismo","dialogWidth:700px; dialogHeight:600px");
			//}
			var left = Number((screen.width/2)-(700/2));
			var tops = Number((screen.height/2)-(600/2));

			var ventana = window.open("ausentismo.php?idu="+id,"Ausentismo","Width=700,Height=600,scrollbars=yes,top="+tops+", left="+left);

		}
		function capacitacion(id)
		{ 
			//if (window.showModalDialog) {
			//		window.showModalDialog("capacitacion.php?idu="+id,"Historial","dialogWidth:700px; dialogHeight:600px");
			//}
			var left = Number((screen.width/2)-(700/2));
			var tops = Number((screen.height/2)-(600/2));

			var ventana = window.open("capacitacion.php?idu="+id,"Historial","Width=700,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function editarReferencia(emp,id)
		{ 
			//if (window.showModalDialog) {
			//		window.showModalDialog("../agregar_trabajo/editarRef.php?idu="+id+"&emp="+emp,"Historial","dialogWidth:500px; dialogHeight:600px");
			//}
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(600/2));

			var ventana = window.open("../agregar_trabajo/editarRef.php?idu="+id+"&emp="+emp,"Tabajo","Width=500,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function agregarTrabajo(id)
		{ 
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(600/2));

			var ventana = window.open("../agregar_trabajo/index.php?idu="+id,"Historial","Width=500,Height=600,scrollbars=yes,top="+tops+", left="+left);
			/*if (window.showModalDialog) {
					window.showModalDialog("../agregar_trabajo/index.php?idu="+id,"Historial","dialogWidth:500px; dialogHeight:600px");
			}*/
		
		}
		function reactivar(id)
		{ 
			var left = Number((screen.width/2)-(500/2));	
			var tops = Number((screen.height/2)-(400/2));

		var ventana = window.open("../ver_empleado/reactivar.php?idu="+id,"reactivar","Width=500,Height=400,scrollbars=yes,top="+tops+", left="+left);
		}
		function retirar(id)
		{ 
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(600/2));

		var ventana = window.open("../ver_empleado/retirar.php?idu="+id,"retirar","Width=500,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function editarFam(id)
		{ 
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(600/2));

		var ventana = window.open("../agregar_familiar/editarFam.php?idfam="+id,"InfoFamiliar","Width=500,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function agregarFamiliar(id)
		{ 
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(700/2));

		var ventana = window.open("../agregar_familiar/index.php?idu="+id,"Familiares","Width=500,Height=700,scrollbars=yes,top="+tops+", left="+left);
		}
		function dotacion(id)
		{ 
			var left = Number((screen.width/2)-(900/2));
			var tops = Number((screen.height/2)-(600/2));

		var ventana = window.open("../nuevo_empleado/dotacion.php?idu="+id,"InfoAcademica","Width=900,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function epp(id)
		{ 
			var left = Number((screen.width/2)-(900/2));
			var tops = Number((screen.height/2)-(600/2));

		var ventana = window.open("../epp/index.php?idu="+id,"EPP","Width=900,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function agregarAcad(id)
		{ 
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(600/2));

		var ventana = window.open("../nuevo_empleado/agregarAcad.php?idu="+id,"InfoAcademica","Width=500,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		function editarAcad(id)
		{ 
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(600/2));

		var ventana = window.open("../nuevo_empleado/editarAcad.php?idacad="+id,"InfoAcademica","Width=500,Height=600,scrollbars=yes,top="+tops+", left="+left);
		}
		
		function subirFoto(id)
		{
			var left = Number((screen.width/2)-(500/2));
			var tops = Number((screen.height/2)-(400/2));

			var ventana = window.open("../nuevo_empleado/subirfoto.php?idu="+id,"Foto","Width=500,Height=400,scrollbars=yes,top="+tops+", left="+left);
		}

		function eliminarFoto(id)
		{
			window.location='../nuevo_empleado/eliminarfoto.php?idu='+id;

			// var ventana = window.open("../nuevo_empleado/eliminarfoto.php?idu="+id,"Foto","Width=500,Height=400,scrollbars=yes,top="+tops+", left="+left);	
		}
	</script>
	<?php
}
function arp($id, $con)
  {
  	
  	$result=$con->consultar("Select * from TbArp where IdArp=$id");
	
	
	while($fila=$con->MostrarRegistrosAssoc($result))
	{
		
		echo"$fila->NomArp";
		
	}
	
  }
function menuItem()
{
	echo"<a class='menu2' href=''>Cargar HV</a><br/>
	<a class='menu2' href=''>Cargar Paz y Salvo</a><br/>
	";
}
function Estado($id, $con)
{
	$result=$con->consultar("SELECT * FROM TbEstadoContrato WHERE (((TbEstadoContrato.Idestado)=$id))");
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($reg->NomEstado=="Cancelado")
			{
				echo "<center><span style='color:red; padding:10px 40px 10px 40px; border: 3px solid red; text-align:center'>$reg->NomEstado</span></center>";
			}
		else if($reg->NomEstado=="Terminado") 
		{
			echo "<center><span style='color:blue; padding:10px 40px 10px 40px; border: 3px solid blue; text-align:center'>$reg->NomEstado</span></center>";
		}
		else 
		{
			echo "<center><span style='color:green; padding:10px 40px 10px 40px; font-size:16px; text-align:center'>$reg->NomEstado</span></center>";
		}

	}
}
function familiares($con, $id)
{
	if(isset($_GET[idfam]) && ($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin'))
	{
		$sql="Delete From TbFamilia Where IdFamilia=$_GET[idfam]";
		$result=$con->consultar($sql);
		
		
	}
	echo"<h3 style='color:#2c89a0; '>Grupo Familiar <span class='link' ><a href='#' onClick='agregarFamiliar($id);' style='text-decoration:none'>[ Agregar + ]</a></span></h3>";
		$result=$con->consultar("SELECT * FROM TbParentesco INNER JOIN TbFamilia ON TbParentesco.IdParentesco = TbFamilia.IdParentesco WHERE (((TbFamilia.Userid)='$id'))");
			echo"<span style='color:#555'>";
		while ($reg=$con->MostrarRegistrosAssoc($result)) {
		   echo "<h4>$reg->Nombres $reg->Apellidos ($reg->NomParentesco) 
		   <span class='link3'><a href='#' onClick='editarFam($reg->IdFamilia);' style='text-decoration:none'>[ Editar ]</a> <a href='index.php?idu=$_GET[idu]&pp=$_GET[pp]&idfam=$reg->IdFamilia#inf'>[ X ]</a></span></h4>
		   <b>Documento: </b>$reg->Documento<br/>
		   <b>Fecha de nacimiento: </b>"; $this->fecha($reg->FechaNacimiento); echo"<br/>
		   <b>Edad:</b>"; $edad=$this->antiguedad($reg->FechaNacimiento); echo" $edad <br/>";
		   echo"<b>Dirección: </b>$reg->Direccion<br/>
		   <b>Telefono: </b>$reg->Telefono<br/>
		   <b>Telefono móvil: </b>$reg->Celular<br/>
		   <b>Ciudad: </b>$reg->Ciudad<br/>";
		}
		echo"</span>";
}
function empresa($con, $id)
{
	$sql="SELECT * FROM Userinfo WHERE (((Userinfo.Userid)='$id'))";
	$result=$con->consultar($sql);
	
	$n=$this->Nregistros($con, $sql);
	
	if($n==0) echo"<span style='color:red; font-size:28px'>No existe en el huellero o no se ha definido empresa</span>";
	else {
		while ($reg=$con->MostrarRegistrosAssoc($result)) {
			
			 if($reg->Mercico==1) return 'Mercico';
			 else return 'Temporal';
			
			}
		}
	
}
function Cargo($id, $con)
{
	$result=$con->consultar("Select * from TbCargos Where IdCargo=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomCargo;
	}
}
function fondoCesantias($id, $con)
{
	$result=$con->consultar("Select * from TbCesantias Where IdCesantia=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomCesantia;
	}
}
function fondoPensiones($id, $con)
{
	$result=$con->consultar("Select * from TbAfp Where IdAfp=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomAfp;
	}
}
function eps($id, $con)
{
	$result=$con->consultar("Select * from TbEps Where IdEps=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomEps;
	}
}
function localidad($id, $con)
{
	$result=$con->consultar("Select * from TbLocalidades Where IdLocalidad=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomLocalidad;
	}
}
function contrato($id, $con)
{
	$result=$con->consultar("Select * from TbTipoContrato Where IdTipoContrato=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomTipoContrato;
	}
}
function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	$fecha = str_replace(" .000", "", $fecha);
	echo $fecha;
}
function año($fi,$ff)
{
	$fechaEmp=strtotime($fi);
	$fechaEmp=strtotime($ff);
		
	$añoi=substr($fi, 0,4);
	$mesi=substr($fi, 5,2);
	$diai=substr($fi, 8,2);
	
	$añof=substr($ff, 0,4);
	$mesf=substr($ff, 5,2);
	$diaf=substr($ff, 8,2);
		
	$añot=$añof-$añoi;
	$mest=$mesf-$mesi;
	$diat=$diaf-$diai;
	
	/*
	echo "fi $fi<br/>";
	echo "ff $ff<br/>";
	echo "añoi $añoi<br/>";
	echo "mesi $mesi<br/>";
	echo "diai $diai<br/>";
	echo "añof $añof<br/>";
	echo "mesf $mesf<br/>";
	echo "diaf $diaf<br/>";
	echo "añot $añot<br/>";
	echo "mest $mest<br/>";
	echo "diat $diat<br/>";
	*/
	
	$difm=0;
	
	// if($añot>1)
	// {
		// echo "1<br>";
		$difa=$añot-1;
	// }
	
	if($mest<0)
	{
		$difm=12-$mesi+$mesf-1;
	}else
	{
		$difa=$difa+1;
		$difm=$mest-1;
	}
	
	if($diat<0)
	{
		$difd=30-$diai+$diaf;
	}
	else
	{
		$difm=$difm+1;
		$difd=$diat;
	}
	
	return $difa;
}

function mes($fi,$ff)
{
	$añoi=substr($fi, 0,4);
	$mesi=substr($fi, 5,2);
	$diai=substr($fi, 8,2);
	
	$añof=substr($ff, 0,4);
	$mesf=substr($ff, 5,2);
	$diaf=substr($ff, 8,2);
	
	$añot=$añof-$añoi;
	$mest=$mesf-$mesi;
	$diat=$diaf-$diai;
	
	$difm=0;
	
	// if($añot>1)
	// {
		// echo "1<br>";
		$difa=$añot-1;
	// }
	
	if($mest<0)
	{
		$difm=12-$mesi+$mesf-1;
	}else
	{
		$difa=$difa+1;
		$difm=$mest-1;
	}
	
	if($diat<0)
	{
		$difd=30-$diai+$diaf;
	}
	else
	{
		$difm=$difm+1;
		$difd=$diat;
	}
	
	return $difm;
}

function dia($fi,$ff)
{
	$añoi=substr($fi, 0,4);
	$mesi=substr($fi, 5,2);
	$diai=substr($fi, 8,2);
	
	$añof=substr($ff, 0,4);
	$mesf=substr($ff, 5,2);
	$diaf=substr($ff, 8,2);
	
	$añot=$añof-$añoi;
	$mest=$mesf-$mesi;
	$diat=$diaf-$diai;
	
	$difm=0;
	
	// if($añot>1)
	// {
		// echo "1<br>";
		$difa=$añot-1;
	// }
	
	if($mest<0)
	{
		$difm=12-$mesi+$mesf-1;
	}else
	{
		$difa=$difa+1;
		$difm=$mest-1;
	}
	
	if($diat<0)
	{
		$difd=30-$diai+$diaf;
	}
	else
	{
		$difm=$difm+1;
		$difd=$diat;
	}
	
	return $difd;
}

function duracion($fi,$ff)
{
	$añoi=substr($fi, 0,4);
	$mesi=substr($fi, 5,2);
	$diai=substr($fi, 8,2);
	
	$añof=substr($ff, 0,4);
	$mesf=substr($ff, 5,2);
	$diaf=substr($ff, 8,2);
	
	$añot=$añof-$añoi;
	$mest=$mesf-$mesi;
	$diat=$diaf-$diai;
	
	$difm=0;
	
	// if($añot>1)
	// {
		// echo "1<br>";
		$difa=$añot-1;
	// }
	
	if($mest<0)
	{
		$difm=12-$mesi+$mesf-1;
	}else
	{
		$difa=$difa+1;
		$difm=$mest-1;
	}
	
	if($diat<0)
	{
		$difd=30-$diai+$diaf;
	}
	else
	{
		$difm=$difm+1;
		$difd=$diat;
	}
	// $difm=$difm+($difd/30);
	// echo "años:$difa, meses:$difm, dias:$difd<br>";
	
	if($difa>0)
		echo "$difa año(s) ";
	
	if($difm>0)
		echo "$difm mes(es) ";
	
	if($difd>0)
		echo "$difd dia(s)";
	
}
function FactorRh($id, $con)
{
	$result=$con->consultar("Select * from TbGrupoSanguineo Where IdGrSanguineo=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->GrupoSanguineo;
	}
}
function Nregistros($con, $sql)
{
  	 
  	$i=0;
  	$result=$con->consultar($sql);
  	while($registro=$con->MostrarRegistrosAssoc($result))
	  {
	  	$i++;
	  }
	 return $i;
}
function EstadoCivil($id, $con)
{
	$result=$con->consultar("Select * from TbEstadoCivil Where IdEstadoCivil=$id");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo $reg->NomEstadoCivil;
	}
}

}
$pagina = new Buscar_empleado();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>