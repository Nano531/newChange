<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class convocatoria extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		echo"<span class='ruta'><a href='".$this->nivel."index.php'>Inicio</a> | <a href='".$this->nivel."logica/capacitaciones/'>Capacitaciones</a> | <a href='#'>Convocatoria</a><br/><br/></span>";
		
		$this->menuAlfa();
		if(isset($_GET[area])) $sql="SELECT * FROM TbCargos INNER JOIN Userinfo ON TbCargos.IdCargo = Userinfo.IdCargo WHERE (((Userinfo.CentroCosto)=$_GET[area])) AND (((Userinfo.Retirado)=False));";
		else $sql=$this->crearSQL();
		//echo $sql;
		
		
		if($this->Cerrado($_GET[idcap], $conexion)==0)
		{
			$this->NombreCapacitacion($conexion);		

		echo "<div id='listatotal'>";
		$this->porAreas($conexion);
		$result=$conexion->consultar($sql);
		echo "<a name='result'></a>
		<form action='procesarconv.php' method='post'>";
		echo "<div style=' width:350px; float:left; margin: 0 20px 0 0'><table class='listaCapa' >";
		echo "<tr style='text-align:left;'><th>Apellidos / Nombre</th><th>Cargo</th><td><input type='checkbox' id='sel' onclick='marcar(this);'/></td></tr>";
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
			echo"<tr style='font-size:12px;  '><td><a href='../ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a></td><td >$reg->NomCargo</td><td width='10'> "; 
			$this->verificarInput($reg->Userid, $_GET[idcap], $conexion);
			echo"</td>
			</tr>";
			
		}
		echo "</table></div>";
		echo "
		<input type='hidden' value='$_GET[idcap]' name='idcap'/>
		<input type='hidden' value='$_GET[letra]' name='letra'/>
		<input type='submit' value='Convocar'  style='float:left;'/>
		
		</form>";
		echo "</div>";
		echo "<div id='listacon'>";
		echo "<h3>Lista de convocados</h3>";
		$result2=$conexion->consultar("SELECT * FROM Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid Where UserCapacitacion.IdCapacitacion=$_GET[idcap] Order by Name");
		
		echo "<div style='overflow:scroll; height:300px'>
		<table class='listaCapa'>";
		$n=1;
		while ($fila=$conexion->MostrarRegistrosAssoc($result2)) {
			echo "<tr><td>$n</td><td><a href='../ver_empleado/index.php?idu=$fila->Userid'>$fila->Name</a></td></tr>";
			$n++;
		}
		echo "</table></div><br/>
		<center><a href='#' onClick='confirmar($_GET[idcap]);' style='background:#2C89A0; padding:10px 20px 10px 20px; margin: 0 0 20px 0; color:#fff' >Confirmar asistencia</a></center><br/><br/>";
		echo "<center><form action='../reportes/capacitacionExcel.php' method='get'>
		  		<input type='hidden' value='$_GET[idcap]' id='idcap' name='idcap'/>
		  		<input type='submit' value='Descargar Excel'/>
		  	</form></center>";
		}
		else
		{
			echo"<center><h2>"; $this->NombreCapacitacion($conexion); echo"</h2>
			<p>Capacitación cerrada</p>
			<a href='index.php'>Ir a la lista de capacitaciones</a></center>";
		}
		echo "</div>";
	
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
     echo "<div style='clear:both'></div>";
	  echo"</div>";
  }
function porAreas($con)
{
	$sql="SELECT * FROM CentroCosto;";
	$result=$con->consultar($sql);
	echo "<hr/><a name='porarea'></a>
	<form action='convocatoria.php#result' method='get' style=' width:400px; padding:10px 0 10px 0' >
	<h3>Filtrar los Empleados por Centro de Costo.</h3>
	<select name='area'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		if($reg->IdCentroCosto==$_GET[area]) echo "<option value='$reg->IdCentroCosto' selected>$reg->NomCentro</option>";
		else echo "<option value='$reg->IdCentroCosto'>$reg->NomCentro</option>";
	}
	echo "</select><input type='submit' value='Buscar'/>
	<input type='hidden' value='$_GET[idcap]' name='idcap'/>
	</form><br/><hr/><br/>";
}
function nombreCapacitacion($con)
 {
 	$result=$con->consultar("Select * From TbCapacitaciones Where IdCapacitacion=$_GET[idcap]");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<h2>$reg->NomCapacitacion</h2>
		<p>Por $reg->Expositor<br/>
		Duración: $reg->Duracion horas</p>";
	}
 }
function verificarInput($idu, $idcap, $con)
 {
 	// echo "$idu -- $idcap";
 	$result=$con->consultar("Select * from UserCapacitacion Where Userid='$idu' AND IdCapacitacion=$idcap");
	$n=0;
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$n++;
	}
	if($n==0)echo"<input type='checkbox' name='inv[]' value='$idu' />";
	else echo "<span>Convocado</span>";
 }

function crearSQL()
 {
 	switch ($_GET[letra]) {
			
			case 'A': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'a%' AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'B': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'b%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'C': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'c%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'D': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'd%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'E': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'e%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'F': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'f%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'G': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'g%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'H': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'h%' AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'I': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'i%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'J': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'j%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'K': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'k%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'L': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'l%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'M': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'm%' AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'N': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'n%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'Ñ': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'ñ%' AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'O': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'o%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'P': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'p%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'Q': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'q%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'R': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'r%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'S': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 's%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'T': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 't%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'U': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'u%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'V': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'v%'  AND Userinfo.Retirado=FALSE order by Name ASC"; break;
			case 'W': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'w%' AND Userinfo.Retirado=FALSE order by Name ASC "; break;
			case 'X': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'x%'  AND Userinfo.Retirado=FALSE order by Name ASC "; break;
			case 'Y': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'y%' AND Userinfo.Retirado=FALSE order by Name ASC "; break;
			case 'Z': $sql="select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo where Name like 'z	%' order by Name ASC"; break;
			default: $sql="Select * from Userinfo Inner join TbCargos On Userinfo.Cargo=TbCargos.IdCargo  Where Userinfo.Retirado=FALSE order by Name ASC "; break;
		}
		return $sql;
 }
function menuAlfa()
 {
 	$alfabeto= array("A","B", "C", "D","E","F","G","H","I","J","K","L", "M","N","Ñ","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	$tam=count($alfabeto);
	echo "<hr/>";
	for($i=0; $i<$tam; $i++)
	{
		echo "<a style='color:#000; padding:5px'href='convocatoria.php?idcap=$_GET[idcap]&letra=$alfabeto[$i]'>$alfabeto[$i]</a> ";
	
	}
 	 echo " <a style='color:#000' href='convocatoria.php?idcap=$_GET[idcap]'>[ Todos ]</a><hr/><br/>";
 }
 function Cerrado($id, $con)
{
	$result=$con->consultar("select * From TbCapacitaciones Where IdCapacitacion=$id");
	while ($nom=$con->MostrarRegistrosAssoc($result)) {
		if($nom->Ejecutado==TRUE) return 1;
		else return 0;
	}
}
function mostrarJs()
 {
 	?>
 	<script type="text/javascript">
    function marcar(source) 
    {
        checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
        for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
        {
            if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
    function confirmar(id)
		{ 
		var ventana = window.open("confirmar.php?idcap="+id,"Tabajo","Width=500,Height=600,scrollbars=yes");
		}
    
	</script>
 	<?php
 }
}
$pagina = new convocatoria();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>