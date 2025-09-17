<?php
setlocale(LC_ALL,"es_ES");
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class reportes extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/reportes/'>Reportes</a>";
		echo" </span><br/><br/>";
		echo "<h2>Exportar reportes</h2>";
		$this->menu_reporte();
		switch ($_GET[rep]) {
			case '1': $this->reportAusencias($conexion); break; 
			case '2': $this->reportDotacion($conexion); break;
			case '3': $this->reportDisciplinario($conexion); break;
			case '4': $this->reportTallaje($conexion); break;
			case '5': $this->plantaPersonal($conexion); break;
			case '6': $this->reporteFamiliar($conexion); break;
			case '7': $this->reporteEpp($conexion); break;
			case '8': $this->retardos($conexion); break;
			case '9': $this->listadoLockers($conexion); break;
			default: $this->reportAusencias($conexion);	break;
		}
	}
	else 
	{
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
	  echo"</div>";
  }

  function reporteEpp($con)
  {
  	$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON Userinfo.Userid = HistDotacion.Userid WHERE (((Userinfo.Retirado)=False) AND ((Elemento.EPP)=True));";
  	$result=$con->consultar($sql);
	echo "<br/><h3>Relación de Elementos de Protección personal</h3>";
	echo "<form action='reportes2.php' method='post'><input type='hidden' value='2' name='opc'/><input type='hidden' value='$sql' name='sql'/><input type='submit' value='Descargar Excel'/></form><br/>";
	echo "<table class='TbDota'>
	<tr><th>Empleado</th><th>Elementos</th><th>Cantidad</th><th>Fecha de Entrega</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td><a href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a></td><td>$reg->NomElemento</td><td>$reg->Cantidad $reg->Unidad</td><td>$reg->FechaEntrega</td></tr>";
	}
	echo "</table>";
  }
  function reporteFamiliar($con)
  {

	$sql="SELECT TOP 5000 * FROM TbParentesco INNER JOIN (Userinfo INNER JOIN TbFamilia ON Userinfo.Userid = TbFamilia.Userid) 
	ON TbParentesco.IdParentesco = TbFamilia.IdParentesco WHERE (((TbParentesco.IdParentesco)=6)) AND (((Userinfo.Consecutivo)<>1)) AND (((Userinfo.Retirado)=False)) Order by Name;";
	//echo $sql;
	$result=$con->consultar($sql);
	echo "<br/><h3>Relación de Empleado / Hijos</h3>";

	echo "<form action='reportes2.php' method='post'><input type='hidden' value='6' name='opc'/><input type='hidden' value='$sql' name='sql'/><input type='submit' value='Descargar Excel'/></form><br/>";
	echo "<table class='TbDota'>
	<tr><th>Empleado</th><th>Hijo</th><th>Edad del hijo</th><th>Mercico</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td><a href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a></td><td>$reg->Nombres $reg->Apellidos</td><td>"; $edad=$this->antiguedad($reg->FechaNacimiento); echo"$edad</td><td>$reg->Mercico</td></tr>";
	}
	echo "</table>";
	
  }
   function reportTallaje($con)
 {
 	$sql="SELECT TOP 5000 * from userinfo Where TipoDotacion=1 AND Retirado=0";
 	$result=$con->consultar($sql);
 	echo "<br/><h3>Tallaje completo</h3>";
 	//echo "<form action='reportes2.php' method='post'><input type='hidden' value='4' name='opc'/><input type='hidden' value='$sql' name='sql'/><input type='submit' value='Descargar Excel'/></form><br/>";
 	echo "<form action='reportes2.php' method='post'><input type='hidden' value='4' name='opc'/><input type='hidden' value='$sql' name='sql'/><input type='submit' value='Descargar Excel'/></form><br/>";
	echo "<a href='index.php?rep=4'>Ver Todo</a><br/><br/>";
	echo "<table class='TbDota'>
	<tr><th>Nombres</th><th>Tipo de dotacion</th><th>Talla camisa</th><th>Talla pantalon</th><th>Talla calzado</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td><a href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a></td><td>"; $this->Tipodota2($reg->TipoDotacion, $con); echo"</td><td>"; $this->TbTalla($reg->TallaCamisa, $con); echo"</td><td>"; $this->TbTalla($reg->TallaPantalon, $con); echo"</td><td>"; $this->TbTalla($reg->TallaCalzado, $con); echo"</td></tr>";
	}
	echo "</table>";
	
 }
  function TbTalla($id, $con)
 {
 	$sql="SELECT TOP 5000 * from TbTalla Where IdTalla=$id";
 	$result=$con->consultar($sql);
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "$reg->NomTalla";
	}
 }
 function Tipodota2($id, $con)
 {
 	$sql="SELECT TOP 5000 * from TipoDotacion Where IdTipoDotacion=$id";
 	$result=$con->consultar($sql);
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "$reg->NomDotacion";
	}
 }
 
 
 function retardos($con)
 {
	$tabla="Checkinout";
	if(isset($_GET[year]))
	{
		if ($_GET['year']==date('Y'))
		{
			$tabla="Checkinout";
		}
		else if($_GET['year']>2012)
		{
			$tabla="HistoInOut";
		}
	}
	
	$sql="SELECT Userinfo.Userid,Userinfo.Consecutivo,Userinfo.Name, $tabla.CheckTime, CONVERT(DATE,$tabla.CheckTime) AS Fecha, (CONVERT(TIME,$tabla.CheckTime)) AS Hora, $tabla.CheckTime as llegada, TbTurnos.Entra, TbDias.NomDia, (DATEDIFF(ss,CONVERT(TIME,TbTurnos.Entra), CONVERT(TIME,$tabla.CheckTime))/-60) AS Retardo FROM Userinfo INNER JOIN (TbTurnos INNER JOIN (TbJornadaTurno INNER JOIN (TbDias INNER JOIN ($tabla INNER JOIN TbDiaJornadaTurno ON $tabla.IdDjT = TbDiaJornadaTurno.IdDjT) ON TbDias.IdDia = TbDiaJornadaTurno.IdDia) ON TbJornadaTurno.IdJt = TbDiaJornadaTurno.IdJt) ON TbTurnos.Idturno = TbJornadaTurno.Idturno) ON Userinfo.Userid = $tabla.Userid WHERE $tabla.CheckType='E' AND (DATEDIFF(ss,CONVERT(TIME,TbTurnos.Entra), CONVERT(TIME,$tabla.CheckTime))) > 60 AND Userinfo.Name<>'0' ";
 	
	if(isset($_GET[name]))
	{
		 $sql=$sql." AND Userinfo.Consecutivo=$_GET[name]";
	}
	if(isset($_GET[year]))
	{
		 $sql=$sql." AND YEAR($tabla.CheckTime)='$_GET[year]'";
	}
	else
	{
		$sql=$sql." AND YEAR($tabla.CheckTime)='".date('Y')."'";
	}
	if(isset($_GET[mes]))
	{
		 $sql=$sql." AND MONTH($tabla.CheckTime)='$_GET[mes]'";
	}
	else
	{
		$sql=$sql." AND MONTH($tabla.CheckTime)=".date('m')."";
	}
	
	if(isset($_GET[emp]) && $_GET[emp]!="N-A")
	{
		$sql=$sql." AND Mercico=$_GET[emp]";
	}
	
	if(isset($_GET[act]) && $_GET[act]!="N-A")
	{
		$sql=$sql." AND Retirado=$_GET[act]";
	}

	$sql=$sql.";";
 	 //echo $sql;	
 	
 	$result=$con->consultar($sql);
 	echo "<br/><h3>Reporte de retardos mensuales</h3>
	<table><tr><td>";
	$this->ano($con);
	echo "</td><td>";
	$this->meses($con);
	echo "</td><td>";
	$this->isMercico($con, "8");
	echo "</td><td>";
	$this->isActivo($con, "8");
	echo "</td><td>
		<input type='button' id='botReportes' onClick='ValFiltrosResportes()' value='Aplicar filtros'>
	</td></tr></table><br>";

	echo "<script type='text/javascript'>
			function ValFiltrosResportes()
 			{
 				var ano=document.getElementById	('year').value;
			 	var mes=document.getElementById('mes').value;
			 	var emp=document.getElementById('emp').value;
			 	var tipEmp=document.getElementById('act').value;

			 	url='index.php?rep=8';
			 	if(ano!='----')
			 	{
			 		url=url+'&year='+ano;
			 	}
			 	if(mes!='----')
			 	{
			 		url=url+'&mes='+mes;
			 	}
			 	if(emp!='N-A')
			 	{
			 		url=url+'&emp='+emp;
			 	}
			 	if(tipEmp!='N-A')
			 	{
			 		url=url+'&act='+tipEmp;
			 	}

			 	location.href=url;
 			}
		</script>";

	// $this->buscarFecha($con, "8");
 	echo '<form action="reportes2.php" method="post">
 		<input type="hidden" value="8" name="opc"/>
 		<input type="hidden" name="sql" value="'.$sql.'">
 		<input type="submit" value="Descargar Excel"/>
 	</form><br/>';
	echo "<a href='index.php?rep=8'>Ver Todo</a><br/><br/>";
	echo "<table class='TbDota' id='TbRetardos'>
		<tr><th>"; 
		$this->EmpleadoRetardos($con); 
		echo"</th><th>Fecha</th><th>Dia</th><th>Hora Entrada</th><th>Hora Llegada</th><th>Retardo</th><th>Tipo Ausencia</th></tr>";
		$suma=0;
		$min=0;
		$hora=0;
		$total=0;
		while ($reg=$con->MostrarRegistrosAssoc($result)) 
		{
			echo "<tr><td style='width:80px'>$reg->Name</td><td style='width:80px'>";
				echo date("d-M-Y", strtotime($reg->Fecha));
			echo "</td><td style='width:50px' align='center'>$reg->NomDia</td><td style='width:80px' align='center'>";
				echo date("H:i a",strtotime($reg->Entra));
			echo "</td><td style='width:80px' align='center'>";
				echo date("H:i a",strtotime($reg->llegada));
			echo "</td><td align='center' style='width:60px'>";
				echo $this->int_to_date($reg->Retardo);
			echo "</td><td style='width:120px' align='center'>";
				
				
				$sql1="SELECT Userinfo.Userid, Userinfo.Name, Ausencias.NomAusencia, Int(HistAusentismo.Fecha_ini) as Fecha_ini, HistAusentismo.Fecha_fin, HistAusentismo.Horas, HistAusentismo.Diagnostico, HistAusentismo.Observaciones FROM Ausencias INNER JOIN (Userinfo INNER JOIN HistAusentismo ON Userinfo.Userid = HistAusentismo.Userid) ON Ausencias.IdAusencias = HistAusentismo.IdAusencia WHERE ((Userinfo.Consecutivo)=$reg->Consecutivo)";
	
				// if(isset($_GET[name]))
				// {
					 // $sql1=$sql1." AND Userinfo.Consecutivo=$_GET[name]";
				// }
				if(isset($_GET[year]))
				{
					 $sql1=$sql1." AND YEAR(Fecha_ini)='$_GET[year]'";
				}
				else
				{
					$sql1=$sql1." AND YEAR(Fecha_ini)='".date('Y')."'";
				}
				if(isset($_GET[mes]))
				{
					 $sql1=$sql1." AND MONTH(Fecha_ini)='$_GET[mes]'";
				}
				else
				{
					$sql1=$sql1." AND MONTH(Fecha_ini)=".date('m')."";
				}
				// echo "<br><br>".$sql1."<br><br>";
				
				$result1=$con->consultar($sql1);	
				$aux=0;
				while ($reg1=$con->MostrarRegistrosAssoc($result1)) 
				{
					if($reg->Fecha==$reg1->Fecha_ini)
					{
						echo "<b>$reg1->NomAusencia</b>";
						$aux++;
					}
				}
				if ($aux==0)
				{
					echo "Retardo";
					$total = $total + $reg->Retardo;
					$min=$min+date("i",strtotime(substr($reg->Retardo,11,-3)));
					$hora=$hora+date("H",strtotime(substr($reg->Retardo,11,-3)));					
					// $suma=$suma+$reg->Retardo;
				}
				
				
				
				// $this->aus($con,$reg);
			echo "</td></tr>";
		}
	$nh=floor($min/60);
	$min=$min%60;
	$hora=$hora+$nh;
	$tammin=strlen($min);
	if($tammin==1)
		$min="0".$min;
		
	$total = $this->int_to_date($total);
	echo "<tr><th>TOTAL</TH><TH></TH><TH></TH><TH></TH><TH></TH><TH>$total</TH><TH></TH></table>";
	
	
	// echo date("H:i",$suma);
	
 }
 
 function listadoLockers($con)
 {
	$sql="SELECT Locker, Name,Sex,Retirado FROM Userinfo WHERE Locker Is Not Null ";
	if(isset($_GET[sexLocker]) && $_GET[sexLocker] != '0' )
	{
		$sql = $sql. "And Sex = '$_GET[sexLocker]' ";
	}

	if(isset($_GET[isActive]) && $_GET[isActive] != '2')
	{
		$sql = $sql. "And Retirado = $_GET[isActive] ";
	}
	$sql = $sql. "ORDER BY Locker";
	
 	// var_dump($sql);
 	$result=$con->consultar($sql);
 	echo "<br/><h3>Listado de Lockers Asignados</h3>";
	
	echo "<table class='TbDota'>
	<tr><th>Nª de Locker</th><th>Persona a Cargo</th><th>"; $this->getSexUserId($con); echo"</th><th>"; $this->getActiveUserId($con); echo"</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) 
	{
		$genero = $reg->Retirado == 0 ? 'Activo' : 'Retirado';
		echo "<tr><td>$reg->Locker</td><td>$reg->Name</td><td>$reg->Sex</td><td>$genero</td></tr>";
	}
	echo "</table>";
	
 }

  function getSexUserId($con)
  {
  		$sql = 'SELECT Sex FROM Userinfo WHERE Userinfo.Locker Is Not Null GROUP BY Sex';
  		$result=$con->consultar($sql);

  		echo "<form action='index.php'>
			<input type='hidden' value='9' name='rep'/>";
			if(isset($_GET[isActive])) echo "<input type='hidden' value='$_GET[isActive]' name='isActive'/>";
			echo "<select name='sexLocker' onchange='this.form.submit()' style='width:80px'>
				<option value='0'>Genero</option>";
				while ($reg=$con->MostrarRegistrosAssoc($result)) {
					if($_GET[sexLocker]==$reg->Sex) echo "<option value='$reg->Sex' selected>$reg->Sex</option>";
					else echo"<option value='$reg->Sex'>$reg->Sex</option>";
				}
			echo "</select>";
		echo "</form>";
  }

  function getActiveUserId($con)
  {
  		// $sql = 'SELECT Sex FROM Userinfo WHERE Userinfo.Locker Is Not Null GROUP BY Sex';
  		// $result=$con->consultar($sql);

  		echo "<form action='index.php'>
			<input type='hidden' value='9' name='rep'/>";
			if(isset($_GET[sexLocker])) echo "<input type='hidden' value='$_GET[sexLocker]' name='sexLocker'/>";
			echo "<select name='isActive' onchange='this.form.submit()' style='width:80px'>
				<option value='2'"; if(!isset($_GET[isActive]) || $_GET[isActive] == 2)echo" selected"; echo">Estado</option>
				<option value='0'"; if(isset($_GET[isActive]) && $_GET[isActive] == 0)echo" selected"; echo">Activo</option>
				<option value='1'"; if(isset($_GET[isActive]) && $_GET[isActive] == 1)echo" selected"; echo">Retirado</option>";
				
			echo "</select>";
		echo "</form>";
  }

  function meses($con)
  {
	echo "<form action='index.php'>";
		echo "<input type='hidden' value='8' name='rep'/>";
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}
		if(isset($_GET['year']))
		{
			echo "<input type='hidden' value='$_GET[year]' name='year'/>";
		}
		echo "Mes  <select name='mes' id='mes'>
			 <option>----</option>";
			if(isset($_GET['year']) && $_GET['year']!=date('Y') && $_GET['year']!='----')
			{
				$limite=12;
			}
			else
			{
				$limite=date('m');
			}
			for ($i=1; $i <= $limite ; $i++) { 
				if(isset($_GET['mes'] ) && $_GET['mes']==$i) echo "<option value='".$i."' selected>".$i."</option>";
				else echo "<option value='".$i."'>".$i."</option>";
			}
		echo "</select>
	</form>";
  }
  
  function ano($con)
  {
	echo "<form action='index.php'>";
		echo "<input type='hidden' value='8' name='rep'/>";
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}
		echo "Año  <select name='year' id='year'>
			 <option>----</option>"; 
			for ($i=2013; $i <= date('Y') ; $i++) { 
				if(isset($_GET['year'] ) && $_GET['year']==$i) echo "<option value='".$i."' selected>".$i."</option>";
				else echo "<option value='".$i."'>".$i."</option>";
			}
		echo "</select>
	</form>";
  }
  
  function aus($con,$fila)	
  {	
	$sql="SELECT Userinfo.Userid, Userinfo.Name, Ausencias.NomAusencia, Int(HistAusentismo.Fecha_ini) as Fecha_ini, HistAusentismo.Fecha_fin, HistAusentismo.Horas, HistAusentismo.Diagnostico, HistAusentismo.Observaciones FROM Ausencias INNER JOIN (Userinfo INNER JOIN HistAusentismo ON Userinfo.Userid = HistAusentismo.Userid) ON Ausencias.IdAusencias = HistAusentismo.IdAusencia WHERE ((Userinfo.Consecutivo)=$fila->Consecutivo)";
	
	if(isset($_GET[year]))
	{
		 $sql=$sql." AND YEAR(Fecha_ini)='$_GET[year]'";
	}
	else
	{
		$sql=$sql." AND YEAR(Fecha_ini)='".date('Y')."'";
	}
	if(isset($_GET[mes]))
	{
		 $sql=$sql." AND MONTH(Fecha_ini)='$_GET[mes]'";
	}
	else
	{
		$sql=$sql." AND MONTH(Fecha_ini)=".date('m')."";
	}
	// echo "<br><br>".$sql."<br><br>";
	
	$result=$con->consultar($sql);	
	$aux=0;
	while ($reg=$con->MostrarRegistrosAssoc($result)) 
	{
		// echo $fila->Fecha;
		// echo $reg->Fecha_ini;
		if($fila->Fecha==$reg->Fecha_ini)
		{
			echo $reg->NomAusencia;
			$aux++;
		}
	}
	if ($aux==0)
		echo "Retardo";
  }
 
   function reportDisciplinario($con)
 {
	$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario) ON Userinfo.Userid = HistDisciplinario.Userid";
	if(isset($_GET[name]) || isset($_GET[disc]) || isset($_GET[desde]) || isset($_GET[hasta]))
	{
		$sql=$sql." WHERE";
	}
	else if( isset($_GET[emp]) && $_GET[emp]!="N-A")
	{
		$sql=$sql." WHERE";
	}
	else if	(isset($_GET[act]) && $_GET[act]!="N-A")
	{
		$sql=$sql." WHERE";
	}
	
 	if(isset($_GET[name]))
	{
		 $sql=$sql." (((Userinfo.Consecutivo)=$_GET[name]))";
		 if(isset($_GET[disc]) || isset($_GET[desde]) || isset($_GET[hasta]) || isset($_GET[emp]) || isset($_GET[act])) 
			$sql=$sql." and ";
	} 
	if(isset($_GET[disc]))
	{
		$sql=$sql." (((HistDisciplinario.IdDisciplinario)=$_GET[disc]))";
		if(isset($_GET[desde]) || isset($_GET[hasta]) || isset($_GET[emp]) || isset($_GET[act]))
			$sql=$sql." and ";
	}
	if(isset($_GET[desde]) || isset($_GET[hasta]))
	{
		if($_GET[desde] > $_GET[hasta])
		{
			echo "<h2>La fecha de inicio NO DEBE SER MAYOR a la fecha final</h2>";
			$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario) ON Userinfo.Userid = HistDisciplinario.Userid";
		} 
		else if(!$_GET[desde] || !$_GET[hasta])
		{
			echo "<h2>Los campos de fecha no pueden estar Vacios</h2>";
			$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario) ON Userinfo.Userid = HistDisciplinario.Userid";
		}
		
		else {
			$sql=$sql." (((HistDisciplinario.FechaEvento)>=#$_GET[desde]#) AND ((HistDisciplinario.FechaEvento)<=#$_GET[hasta]#))";
		}
		
		if(isset($_GET[emp]))
		{
			if($_GET[emp]!="N-A")
				$sql=$sql." and ";
		}
		else if(isset($_GET[act]))
		{
			if($_GET[act]!="N-A")
				$sql=$sql." and ";
		}		
		
	}
	
	if(isset($_GET[emp]))
	{
		if($_GET[emp]!="N-A")
		{
			$sql=$sql." Mercico=$_GET[emp]";
			if(isset($_GET[act]))
				if($_GET[act]!="N-A")
					$sql=$sql." and ";
		}
	}
	
	if(isset($_GET[act]))
	{
		if($_GET[act]!="N-A")
			$sql=$sql." Retirado=$_GET[act]";
	}

	$sql=$sql.";";
 	// echo $sql;
 	
 	$result=$con->consultar($sql);
 	echo "<br/><h3>Historial completo Disciplinario</h3>";
	$this->isMercico($con, "3");
	$this->isActivo($con, "3");
	$this->buscarFecha($con, "3");
 	echo "<form action='reportes2.php' method='post'><input type='hidden' value='3' name='opc'/><input type='hidden' value='$sql' name='sql'/><input type='submit' value='Descargar Excel'/></form><br/>";
	echo "<a href='index.php?rep=3'>Ver Todo</a><br/><br/>";
	echo "<table class='TbDota'>
	<tr><th>"; $this->empleadoDisc($con); 
	echo"</th><th>Fecha</th><th>"; $this->Novedad($con); 
	echo"</th><th>Motivos</th><th>Observaciones</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td>$reg->Name</td><td>$reg->FechaEvento</td><td>$reg->NomDisc</td><td>$reg->Motivos</td><td>$reg->Observaciones</td></tr>";
	}
	echo "</table>";
	
 }
 function Novedad($con)
 {
 	$sql="SELECT Disciplinario.IdDisciplinario, Disciplinario.NomDisc FROM Userinfo INNER JOIN (Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario) ON Userinfo.Userid = HistDisciplinario.Userid Group by Disciplinario.IdDisciplinario, Disciplinario.NomDisc";
 	$result=$con->consultar($sql);
 	echo "<form action='index.php'>
		<input type='hidden' value='3' name='rep'/>";
		
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		if(isset($_GET[desde]) || isset($_GET[hasta]))
		{
			echo "<input type='hidden' value='$_GET[desde]' name='desde'/>";
			echo "<input type='hidden' value='$_GET[hasta]' name='hasta'/>";
		}
		
		echo "<select name='disc' onchange='this.form.submit()' style='width:80px'>
			<option value='0'>Novedad</option>";
			while ($reg=$con->MostrarRegistrosAssoc($result)) {
				if($_GET[disc]==$reg->IdDisciplinario) echo "<option value='$reg->IdDisciplinario' selected>$reg->NomDisc</option>";
				else echo"<option value='$reg->IdDisciplinario'>$reg->NomDisc</option>";
			}
		echo "</select>";
	echo "</form>";
 }
function EmpleadoDisc($con)
 {
 	$sql="SELECT Userinfo.Userid, Userinfo.Name, Userinfo.IDCard, Userinfo.Consecutivo FROM Userinfo INNER JOIN (Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario) ON Userinfo.Userid = HistDisciplinario.Userid ";
	$aux=0;
	
	if(isset($_GET[emp]) || isset($_GET[act]))
	{
		if(isset($_GET[emp]))
		{
			if ($_GET[emp]!="N-A")
			{
				$sql=$sql." where ";
				$aux=1;
			}else if(isset($_GET[act]))
			{
				if ($_GET[act]!="N-A")
				{
					$sql=$sql." where ";
				}
			}
		}else if(isset($_GET[act]))
		{
			if ($_GET[act]!="N-A")
			{
				$sql=$sql." where ";
			}
		}
	}
	
	if(isset($_GET[emp]))
	{
		if ($_GET[emp]!="N-A")
		{
			$sql=$sql."Mercico=$_GET[emp]";
			
		}
	}
	
	if(isset($_GET[act]))
	{
		if($_GET[act]!="N-A")
		{
			if(isset($_GET[emp]))
			{
				if ($_GET[emp]!="N-A")
				{
					$sql=$sql." and ";
					
				}
			}
			$sql=$sql."Retirado=$_GET[act]";
		}
	}
	$sql=$sql." Group by Userinfo.Userid, Userinfo.Name, Userinfo.IDCard, Userinfo.Consecutivo order by name;";
	
	// echo "<br>".$sql;
	
 	$result=$con->consultar($sql);
 	echo "<form action='index.php'>
		<input type='hidden' value='3' name='rep'/>";
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}		
		if(isset($_GET['disc']))
		{
			echo "<input type='hidden' value='$_GET[disc]' name='disc'/>";
		}
		
		if(isset($_GET[desde]) || isset($_GET[hasta]))
		{
			echo "<input type='hidden' value='$_GET[desde]' name='desde'/>";
			echo "<input type='hidden' value='$_GET[hasta]' name='hasta'/>";
		}
		echo "<select name='name' onchange='this.form.submit()' style='width:80px'>
			<option value='0'>Empleado</option>";
			while ($reg=$con->MostrarRegistrosAssoc($result)) {
				if($_GET[name]==$reg->Consecutivo) echo "<option value='$reg->Consecutivo' selected>$reg->Name</option>";
				else echo"<option value='$reg->Consecutivo'>$reg->Name</option>";
			}
		echo "</select>";
	echo "</form>";
 }
 
 function EmpleadoRetardos($con)
 {
	$tabla="Checkinout";
	if(isset($_GET[year]))
	{
		if ($_GET['year']==date('Y'))
		{
			$tabla="Checkinout";
		}
		else if($_GET['year']>2012)
		{
			$tabla="HistoInOut";
		}
	}
	
	$sql="SELECT Userinfo.Consecutivo,Userinfo.Name FROM Userinfo INNER JOIN (TbTurnos INNER JOIN (TbJornadaTurno INNER JOIN (TbDias INNER JOIN ($tabla INNER JOIN TbDiaJornadaTurno ON $tabla.IdDjT = TbDiaJornadaTurno.IdDjT) ON TbDias.IdDia = TbDiaJornadaTurno.IdDia) ON TbJornadaTurno.IdJt = TbDiaJornadaTurno.IdJt) ON TbTurnos.Idturno = TbJornadaTurno.Idturno) ON Userinfo.Userid = $tabla.Userid WHERE $tabla.CheckType='E' AND ((($tabla.CheckTime-INT($tabla.CheckTime))- TbTurnos.Entra))>0.00417 AND Userinfo.Name<>'0' ";
	
	if(isset($_GET[year]))
	{
		$sql=$sql." AND YEAR($tabla.CheckTime)='$_GET[year]'";
	}
	if(isset($_GET[mes]))
	{
		$sql=$sql." AND MONTH($tabla.CheckTime)='$_GET[mes]'";
	}
	if(isset($_GET[emp]) && $_GET[emp]!="N-A")
	{
		$sql=$sql." AND Mercico=$_GET[emp]";
	}
	
	if(isset($_GET[act]) && $_GET[act]!="N-A")
	{
		$sql=$sql." AND Retirado=$_GET[act]";
	}

	$sql=$sql." Group by Userinfo.Consecutivo,Userinfo.Name Order by Userinfo.Name;";
	
	// echo "<br>".$sql;
	
 	$result=$con->consultar($sql);
 	echo "<form action='index.php'>
		<input type='hidden' value='8' name='rep'/>";
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}
		if(isset($_GET['year']))
		{
			echo "<input type='hidden' value='$_GET[year]' name='year'/>";
		}
		if(isset($_GET['mes']))
		{
			echo "<input type='hidden' value='$_GET[mes]' name='mes'/>";
		}
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}		
		if(isset($_GET['disc']))
		{
			echo "<input type='hidden' value='$_GET[disc]' name='disc'/>";
		}
		
		// if(isset($_GET[desde]) || isset($_GET[hasta]))
		// {
			// echo "<input type='hidden' value='$_GET[desde]' name='desde'/>";
			// echo "<input type='hidden' value='$_GET[hasta]' name='hasta'/>";
		// }
		echo "<select name='name' onchange='this.form.submit()'>
			<option value='0'>Empleado</option>";
			while ($reg=$con->MostrarRegistrosAssoc($result)) {
				if($_GET[name]==$reg->Consecutivo) 
					echo "<option value='$reg->Consecutivo' selected>$reg->Name</option>";
				else 
					echo"<option value='$reg->Consecutivo'>$reg->Name</option>";
			}
		echo "</select>";
	echo "</form>";
 }
 
 function reportAusencias($con)
 {
 	if(isset($_GET[name]))
	{
		
		 $sql="SELECT  * FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid
WHERE (((Userinfo.Consecutivo)=$_GET[name]));";
	} 
	else if(isset($_GET[aus]))
	{
		$sql="SELECT  * FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid 
		WHERE (((HistAusentismo.IdAusencia)=$_GET[aus]));";
	}
	else if(isset($_GET[desde]) || isset($_GET[hasta]))
	{
		if($_GET[desde] > $_GET[hasta])
		{
			echo "<h2>La fecha de inicio NO DEBE SER MAYOR a la fecha final</h2>";
			$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid;";
		} 
		else if(!$_GET[desde] || !$_GET[hasta])
		{
			echo "<h2>Los campos de fecha no pueden estar Vacios</h2>";
			$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid;";
		}
		
		else {
			$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid
		WHERE (((HistAusentismo.Fecha_ini)>=#$_GET[desde]#) AND ((HistAusentismo.Fecha_ini)<=#$_GET[hasta]#)) Order by HistAusentismo.Fecha_ini Desc;";
		}
	}
	else
	{
		$sql="SELECT Top 5000 * FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid ORDER BY HistAusentismo.IdHistAus Desc;";	
	}
 	//var_dump($sql);
 	$result=$con->consultar($sql);
 	echo "<br/><h3>historial completo de Ausencias</h3>";
	$this->buscarFecha($con, "1");
 	echo "<form action='reportes2.php' method='post'><input type='hidden' value='$sql' name='sql'/><input type='hidden' value='1' name='opc'/><input type='submit' value='Descargar Excel'/></form><br/>";
	echo "<a href='index.php?rep=1'>Ver Todo</a><br/><br/>";
	echo "<table class='TbDota'>
	<tr><th>Cedula</th><th>"; $this->Empleados($con); echo"</th><th>"; $this->Ausencia($con); echo"</th><th>Diagnostico</th><th>horas</th><th>Desde</th><th>Hasta</th><th>Mes compensado</th><th>observaciones</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td><a href='../ausentismo/editar_ausentismo.php?item=$reg->IdHistAus'>$reg->IDCard</a></td><td>$reg->Name</td><td>$reg->NomAusencia</td><td>$reg->Diagnostico</td><td>$reg->Horas</td><td>$reg->Fecha_ini</td><td>$reg->Fecha_fin</td><td>$reg->MesCompensatorio</td><td width='150'>$reg->Observaciones</td></tr>";
	}
	echo "</table>";
	
 }
 function buscarFecha($con, $i)
 {
	echo "<form action='index.php'>";		
		echo "<input type='hidden' value='$i' name='rep'/>";
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		
		if(isset($_GET['disc']))
		{
			echo "<input type='hidden' value='$_GET[disc]' name='disc'/>";
		}
				
		echo "Desde: <input type='date' name='desde'/> Hasta: <input type='date' name='hasta'/> 
		<input type='submit' value='Buscar'/>
 	</form><br/>";
 }
 
 function isMercico($con, $i)
 {
	echo "<form action='index.php'>";
		
		echo "<input type='hidden' value='$i' name='rep'/>";
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		
		if(isset($_GET['disc']))
		{
			echo "<input type='hidden' value='$_GET[disc]' name='disc'/>";
		}
		if(isset($_GET[desde]) || isset($_GET[hasta]))
		{
			echo "<input type='hidden' value='$_GET[desde]' name='desde'/>";
			echo "<input type='hidden' value='$_GET[hasta]' name='hasta'/>";
		}

		if($i==8)		
			echo "Empresa: <select name='emp' id='emp' style='width:80px'>";
		else
			echo "Empresa: <select name='emp' id='emp' onchange='this.form.submit()' style='width:80px'>";
		
			if(isset($_GET['emp']))
			{
				if($_GET['emp']=='N-A')
				{
					echo "<option value='N-A' selected>Todas</option>
					<option value='true'>Mercico</option>
					<option value='false'>Temporal</option>";
				}
				else if($_GET['emp']=='true')
				{
					echo "<option value='N-A'>Todas</option>
					<option value='true' selected>Mercico</option>
					<option value='false'>Temporal</option>";
				}
				else
				{
					echo "<option value='N-A'>Todas</option>
					<option value='true'>Mercico</option>
					<option value='false' selected>Temporal</option>";
				}
			}
			else
			{
				echo "<option value='N-A'>Todas</option>
				<option value='true'>Mercico</option>
				<option value='false'>Temporal</option>";
			}
		echo "</select>
 	</form>
	";
 }
 
 function isActivo($con, $i)
 {
	echo "<form action='index.php'>";
		
		echo "Tipo empleados: <input type='hidden' value='$i' name='rep'/>";
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		
		if(isset($_GET['disc']))
		{
			echo "<input type='hidden' value='$_GET[disc]' name='disc'/>";
		}
		if(isset($_GET[desde]) || isset($_GET[hasta]))
		{
			echo "<input type='hidden' value='$_GET[desde]' name='desde'/>";
			echo "<input type='hidden' value='$_GET[hasta]' name='hasta'/>";
		}

		if($i==8)		
			echo "<select name='act' id='act' style='width:80px'>";
		else
			echo "<select name='act' id='act' onchange='this.form.submit()' style='width:80px'>";
		
			
			if(isset($_GET['act']))
			{
				if($_GET['act']=='N-A')
				{
					echo "<option value='true or Retirado=false' selected>Todos</option>
					<option value='false'>Activos</option>
					<option value='true'>Retirados</option>";
				}
				else if($_GET['act']=='false')
				{
					echo "<option value='N-A'>Todos</option>
					<option value='false' selected>Activos</option>
					<option value='true'>Retirados</option>";
				}
				else
				{
					echo "<option value='N-A'>Todos</option>
					<option value='false'>Activos</option>
					<option value='true' selected>Retirados</option>";
				}
			}
			else
			{
				echo "<option value='N-A'>Todos</option>
					<option value='false'>Activos</option>
					<option value='true'>Retirados</option>";
			}
		echo "</select>
 	</form>";
 }
 
 function mes($con, $i)
 {
	echo "<form action='index.php'>";
		
		echo "Mes: 
		<input type='hidden' value='$i' name='rep'/>";
		if(isset($_GET['emp']))
		{
			echo "<input type='hidden' value='$_GET[emp]' name='emp'/>";
		}
		if(isset($_GET['act']))
		{
			echo "<input type='hidden' value='$_GET[act]' name='act'/>";
		}
		if(isset($_GET['name']))
		{
			echo "<input type='hidden' value='$_GET[name]' name='name'/>";
		}
		if(isset($_GET[desde]) || isset($_GET[hasta]))
		{
			echo "<input type='hidden' value='$_GET[desde]' name='desde'/>";
			echo "<input type='hidden' value='$_GET[hasta]' name='hasta'/>";
		}		
		echo "<select name='act' onchange='this.form.submit()' style='width:80px'>";
			
			if(isset($_GET['act']))
			{
				if($_GET['act']=='N-A')
				{
					echo "<option value='true or Retirado=false' selected>Todos</option>
					<option value='false'>Activos</option>
					<option value='true'>Retirados</option>";
				}
				else if($_GET['act']=='false')
				{
					echo "<option value='N-A'>Todos</option>
					<option value='false' selected>Activos</option>
					<option value='true'>Retirados</option>";
				}
				else
				{
					echo "<option value='N-A'>Todos</option>
					<option value='false'>Activos</option>
					<option value='true' selected>Retirados</option>";
				}
			}
			else
			{
				echo "<option value='N-A'>Todos</option>
					<option value='false'>Activos</option>
					<option value='true'>Retirados</option>";
			}
		echo "</select>
 	</form><br/>";
 }
 
 function Ausencia($con)
 {
 	$sql="SELECT Ausencias.IdAusencias, Ausencias.NomAusencia FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid
			GROUP BY Ausencias.IdAusencias, Ausencias.NomAusencia;";
 	$result=$con->consultar($sql);
 	echo "<form action='index.php'>
 	<input type='hidden' value='1' name='rep'/>";
	echo "<select name='aus' onchange='this.form.submit()' style='width:80px'>
	<option value='N-A'>Ausencia</option>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($_GET[aus]==$reg->IdAusencias) echo "<option value='$reg->IdAusencias' selected>$reg->NomAusencia</option>";
		else echo"<option value='$reg->IdAusencias'>$reg->NomAusencia</option>";
	}
	echo "</select>";
	echo "</form>";
 }
 function Empleados($con)
 {
 	$sql="SELECT Userinfo.Name, HistAusentismo.Userid, Userinfo.IDCard, Userinfo.Consecutivo
	FROM Userinfo INNER JOIN (Ausencias INNER JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Userinfo.Userid = HistAusentismo.Userid
	GROUP BY Userinfo.Name, HistAusentismo.Userid, Userinfo.IDCard, Userinfo.Consecutivo Order by Name;";
 	$result=$con->consultar($sql);
	
 	echo "<form action='index.php'>";
			
		echo "<input type='hidden' value='1' name='rep'/>";
		echo "<select name='name' onchange='this.form.submit()' style='width:80px'>
			<option value='0'>Empleado</option>";
			while ($reg=$con->MostrarRegistrosAssoc($result)) {
				if($_GET[name]==$reg->Consecutivo) 
					echo "<option value='$reg->Consecutivo' selected>$reg->Name</option>";
				else 
					echo"<option value='$reg->Consecutivo'>$reg->Name</option>";
			}
		echo "</select>";
	echo "</form>";
 }
 
 function numeroEmpleadosPorcentro($con)
 {
 	//$sql="SELECT TOP 5000 * from Userinfo Where CentroCosto=$_GET[id] AND Mercico=$_GET[tip] AND Retirado=False";
	$sql="SELECT TOP 5000 * FROM TbCargos INNER JOIN Userinfo ON TbCargos.IdCargo = Userinfo.IdCargo
	WHERE (((Userinfo.CentroCosto)=$_GET[id]) AND ((Userinfo.[Mercico])=$_GET[tip])  AND ((Userinfo.Retirado)=False));";

		//echo $sql;
	$result=$con->consultar($sql);
	$cont=0;
	echo "<div style='height:150px; overflow: scroll; border: 2px solid #ccc; margin: 10px 0 20px 0'>
	<ul>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo"<li><a href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a> - $reg->NomCargo</li>";
	}
	echo "</ul>
	</div>";
 }
  function plantaPersonal($con)
  {
  	echo "<h3>Planta personal</h3>";
  	if(isset($_GET[tip]) && isset($_GET[id])) $this->numeroEmpleadosPorcentro($con);
  	echo "<table class='TbDota'>";
	echo "<tr><th>Centro de costo</th><th>Mercico</th>"; if($_SESSION[idusuario]!=3){echo "<th>Temporal</th>";} echo "<th width='50px'>Totales</th></tr>";
	$this->ResumenCentroCosto($con);
	echo "</table>";
  }
  function ResumenCentroCosto($con)
  {
  	$acumMer=0;
	$acumTem=0;
  	$sql="SELECT CentroCosto FROM Userinfo Where Retirado=False AND Userid<>'0' Group by CentroCosto";
  	$result=$con->consultar($sql);
  	$i=1;
  	while ($reg=$con->MostrarRegistrosAssoc($result)) {
  		if($i%2 == 0) $style='background:#ccc;';
  		else $style='background:#fff';
		  echo "<tr style='$style'>"; 
		  if($reg->CentroCosto != 3 && $reg->CentroCosto != 12){echo "<td>";if(isset($reg->CentroCosto)){$this->centroCosto($reg->CentroCosto, $con);} echo"</td><td>"; if(isset($reg->CentroCosto)){$canMer=$this->CantMercico($con, $reg->CentroCosto,"True"); echo "<a href='index.php?rep=5&tip=True&id=$reg->CentroCosto'>$canMer Empleado(s)</a>";} echo "</td><td>"; 
		  if(isset($reg->CentroCosto) && $_SESSION[idusuario]!=3){$canTem=$this->CantMercico($con, $reg->CentroCosto,"False"); echo "<a href='index.php?rep=5&tip=False&id=$reg->CentroCosto'>$canTem Empleado(s)</a>";}
		  if(isset($reg->CentroCosto)){$Suma=$canMer + $canTem;} echo"</td><td>$Suma</td></tr>";
		  $acumMer=$acumMer+$canMer;
		  $acumTem=$acumTem+$canTem;
		  $granTotal=$acumTem+$acumMer;
		  $i++;
		}
	  }
	  echo "<tr><td><b>Sub total</b></td><td><b>$acumMer</b></td><td><b>$acumTem</b></td><td style='font-size:16px; color:red'><b>$granTotal</b></td></tr>";

	  echo "<tr><td>";echo $this->centroCosto(3, $con);echo "</td><td>";
	  
	  $canMer=$this->CantMercico($con, 3,"True"); 
	  $acumMer+=$canMer;
	  echo "<a href='index.php?rep=5&tip=True&id=3'>$canMer Empleado(s)</a>";echo "</td><td>";

	  $canTem=$this->CantMercico($con, 3,"False"); 
	  $acumTem+=$canTem;
	  echo "<a href='index.php?rep=5&tip=False&id=3'>$canTem Empleado(s)</a></td><td>";

	  $Suma=$canMer + $canTem;
	  $granTotal+=$Suma;
	  echo $Suma."</td></tr>";

	  echo "<tr><td>";echo $this->centroCosto(12, $con);echo "</td><td>";
	  
	  $canMer=$this->CantMercico($con, 12,"True");
	  $acumMer+=$canMer;
	  echo "<a href='index.php?rep=5&tip=True&id=12'>$canMer Empleado(s)</a>";echo "</td><td>";

	  $canTem=$this->CantMercico($con, 12,"False"); 
	  $acumTem+=$canTem;
	  echo "<a href='index.php?rep=5&tip=False&id=12'>$canTem Empleado(s)</a></td><td>";

	  $Suma=$canMer + $canTem;
	  $granTotal+=$Suma;
	  echo $Suma."</td></tr>";

	  echo "<tr><td><b>Total</b></td><td><b>$acumMer</b></td><td><b>$acumTem</b></td><td style='font-size:16px; color:red'><b>$granTotal</b></td></tr>";	  

  }
  
  function CantMercico($con, $id, $mer)
  {
  	$sql="SELECT TOP 5000 * from Userinfo Where Retirado=False AND CentroCosto=$id AND Mercico=$mer AND CentroCosto <> 17";
	$result=$con->consultar($sql);
	$cont=0;
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$cont++;
	}
	return $cont;
	//echo "<a href='index.php?rep=5&tip=$mer&id=$id'>$cont Empleado(s)</a>";
  }
  function CentroCosto($id, $con)
  {
  	
  	$sql='SELECT TOP 5000 * FROM CentroCosto WHERE (((CentroCosto.IdCentroCosto)='.$id.'));';
  	
	$result=$con->consultar($sql);
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "$reg->NomCentro";
	}
  }
  function reportDotacion($con)
 {
 	if(isset($_GET[name]))
	{		
		 $sql="SELECT  * FROM Userinfo INNER JOIN (TipoDotacion INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion) ON Userinfo.Userid = HistDotacion.Userid
		WHERE (((Userinfo.Consecutivo)=$_GET[name]));";
	} 
	
	else if(isset($_GET[dota]))
	{
		$sql="SELECT TOP 5000 * FROM TbTalla INNER JOIN (Userinfo INNER JOIN (TipoDotacion INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion) ON Userinfo.Userid = HistDotacion.Userid) ON TbTalla.IdTalla = HistDotacion.Talla
			WHERE (((TipoDotacion.IdTipoDotacion)=$_GET[dota]));";
	}
	else if(isset($_GET[elem]))
	{
		$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (TipoDotacion INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion) ON Userinfo.Userid = HistDotacion.Userid 
		WHERE (((Elemento.IdElemento)=$_GET[elem]));";
	}
		else if(isset($_GET[desde]) || isset($_GET[hasta]))
	{
		if($_GET[desde] > $_GET[hasta])
		{
			echo "<h2>La fecha de inicio NO DEBE SER MAYOR a la fecha final</h2>";
			$sql="SELECT TOP 5000 * FROM TipoDotacion INNER JOIN (Userinfo INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON Userinfo.Userid = HistDotacion.Userid) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion;";
		} 
		else if(!$_GET[desde] || !$_GET[hasta])
		{
			echo "<h2>Los campos de fecha no pueden estar Vacios</h2>";
			$sql="SELECT TOP 5000 * FROM TipoDotacion INNER JOIN (Userinfo INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON Userinfo.Userid = HistDotacion.Userid) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion;";
		}
		
		else {
			$sql="SELECT TOP 5000 * FROM Userinfo INNER JOIN (TipoDotacion INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion) ON Userinfo.Userid = HistDotacion.Userid 
			WHERE (((HistDotacion.FechaEntrega)>=#$_GET[desde]#) AND ((HistDotacion.FechaEntrega)<=#$_GET[hasta]#));";
		}
		
	}
	else
	{
		$sql="SELECT TOP 5000 * FROM TipoDotacion INNER JOIN (Userinfo INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON Userinfo.Userid = HistDotacion.Userid) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion;";	
	}
 	
 	
	$result=$con->consultar($sql);
 	echo "<br/><h3>historial completo de Dotación</h3>";
 	$this->buscarFecha($con, "2");
 	echo "<form action='reportes2.php' method='post'><input type='hidden' value='2' name='opc'/><input type='hidden' value='$sql' name='sql'/><input type='submit' value='Descargar Excel'/></form><br/>";
	echo "<a href='index.php?=2'>Ver Todo</a><br/><br/>";
	echo "<table class='TbDota'>
	<tr><th>Fecha</th><th>"; $this->EmpleadoDota($con); echo"</th><th>"; $this->TipoDota($con); echo"</th><th width='70'>"; $this->Elementos($con); echo"</th><th>Talla</th><th width='20'>Cantidades</th><th>Comentarios</th></tr>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td>$reg->FechaEntrega</td><td>$reg->Name</td><td>$reg->NomDotacion</td><td>$reg->NomElemento</td><td>$reg->NomTalla</td><td width='20'>$reg->Cantidad</td><td>$reg->Comentario</td></tr>";
	}
	echo "</table>";
	
 }
 
 function Elementos($con)
 {
 	$sql="SELECT Elemento.IdElemento, Elemento.NomElemento FROM Userinfo INNER JOIN (TipoDotacion INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion) ON Userinfo.Userid = HistDotacion.Userid GROUP BY Elemento.IdElemento, Elemento.NomElemento;";
	
 	$result=$con->consultar($sql);
 	echo "<form action='index.php'>
 	<input type='hidden' value='2' name='rep'/>";
	echo "<select name='elem' onchange='this.form.submit()' >
	<option value='0'>Elemento</option>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($_GET[elem]==$reg->IdElemento) echo "<option value='$reg->IdElemento' selected>$reg->NomElemento</option>";
		else echo"<option value='$reg->IdElemento'>$reg->NomElemento</option>";
	}
	echo "</select>";
	echo "</form>";
 }
 function TipoDota($con)
 {
 	$sql="SELECT TipoDotacion.IdTipoDotacion, TipoDotacion.NomDotacion
	FROM Userinfo INNER JOIN (TipoDotacion INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion) ON Userinfo.Userid = HistDotacion.Userid
	GROUP BY TipoDotacion.IdTipoDotacion, TipoDotacion.NomDotacion;";
 	$result=$con->consultar($sql);
	
 	echo "<form action='index.php'>
		<input type='hidden' value='2' name='rep'/>";
		echo "<select name='dota' onchange='this.form.submit()' >
			<option value='0'>Tipo Dotacion</option>";
			while ($reg=$con->MostrarRegistrosAssoc($result)) {
				if($_GET[dota]==$reg->IdTipoDotacion) echo "<option value='$reg->IdTipoDotacion' selected>$reg->NomDotacion</option>";
				else echo"<option value='$reg->IdTipoDotacion'>$reg->NomDotacion</option>";
			}
		echo "</select>";
	echo "</form>";
 }
 function EmpleadoDota($con)
 {
 	$sql="SELECT HistDotacion.Userid, Userinfo.Name, Userinfo.IDCard, Userinfo.Consecutivo FROM Userinfo INNER JOIN HistDotacion ON Userinfo.Userid = HistDotacion.Userid Group by HistDotacion.Userid,  Userinfo.Name, Userinfo.IDCard, Userinfo.Consecutivo order by Name;";
 	$result=$con->consultar($sql);
	
 	echo "<form action='index.php'>
		<input type='hidden' value='2' name='rep'/>";
		echo "<select name='name' onchange='this.form.submit()' style='width:80px'>
			<option value='0'>Empleado</option>";
			while ($reg=$con->MostrarRegistrosAssoc($result)) {
				if($_GET[name]==$reg->Consecutivo) echo "<option value='$reg->Consecutivo' selected>$reg->Name</option>";
				else echo"<option value='$reg->Consecutivo'>$reg->Name</option>";
			}
		echo "</select>";
	echo "</form>";
 }
 function formularios($con)
 {
 	echo "<form action='index.php' method='get'>";
	$restult=$con->consultar("SELECT TOP 5000 * from Ausencias");
	//echo "<select name='aus'>";
	while ($fila=$con->MostrarRegistrosAssoc($restult)) {
		echo $reg->NomAusencia;
	}
	//echo "</select>";
	echo "</form>";
 }
 function menu_reporte()
 {
 	echo "<hr/>
 	<a href='index.php?rep=1' class='linkmenu'>Ausencias</a> | <a href='index.php?rep=2' class='linkmenu'>Dotación</a> |  <a href='index.php?rep=7' class='linkmenu'>Reporte EPP</a> | <a href='index.php?rep=3' class='linkmenu'>Disciplinario</a> | <a href='index.php?rep=4' class='linkmenu'>Tallaje</a> | <a href='index.php?rep=5' class='linkmenu'>Planta personal</a> | <a href='index.php?rep=6' class='linkmenu'>Reporte familia</a> | <a href='index.php?rep=8' class='linkmenu'>Retardos</a> | <a href='index.php?rep=9' class='linkmenu'>Lockers</a>
 	<hr/>";
 }
 function int_to_date($cant)
 {
	$cant = $cant * -1;
 	if($cant < 60)
	{
		if($cant < 10)
		{
			$salida = "00:0$cant";
		}
		else{
			$salida = "00:$cant";
		}
	}else
	{
		//$horas = intdiv($cant, 60);
		
		$horas = floor($cant/60);
		$minutos = $cant - ($horas * 60);
		
		if($horas < 10)
		{
			$horas = "0$horas";
		}
		if($minutos < 10)
		{
			$minutos = "0$minutos";
		}
		$salida = "$horas:$minutos";
	}
	return $salida;
 }
 function mostrarJs()
 {
 	?>
 	<script>
 		function open(id)
		{ 
		var ventana = window.open("empCentro.php?emp="+id,"reactivar","Width=500,Height=400,scrollbars=yes");
		}
 	</script>
 	<?php
 }
}
$pagina = new reportes();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>	