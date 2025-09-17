<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class editar_ausencia extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo"<h2>Editar Item Ausentismo</h2><hr/>";
		$sql="SELECT *
		FROM Userinfo INNER JOIN (Diagnostico RIGHT JOIN (Ausencias RIGHT JOIN HistAusentismo ON Ausencias.IdAusencias = HistAusentismo.IdAusencia) ON Diagnostico.Codigo = HistAusentismo.Diagnostico) ON Userinfo.Userid = HistAusentismo.Userid
		WHERE (((HistAusentismo.IdHistAus)=$_GET[item]));";
		//echo $sql;
		$result=$conexion->consultar($sql);
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
			# code...
			echo"<a href='../reportes/'>[ Volver ]</a><br/><hr/><br/>";
			echo "<form action='procesaredicion.php' method='post'>";
			echo "<table>";
			echo "<tr><td>Tipo de Ausencia: </td>"; $this->tipo_ausencia($reg->IdAusencia, $conexion); echo"</tr>";
			echo "<tr><td>Diagnostico: </td>"; $this->Diagnostico($reg->IdAusencia, $reg->Diagnostico, $conexion); echo"</tr>"; 
			echo "<tr><td>Mes Compensatorio: </td>"; $this->compensatorio($reg->IdAusencia,$reg->MesCompensatorio, $conexion); echo"</tr>"; 
			 echo "<tr><td>Fecha / hora de inicio: </td><td><input type='text' name='fechaini' value='$reg->Fecha_ini'/></td></tr>";
			 echo "<tr><td>Fecha / hora de finalización: </td><td><input type='text' name='fechafin' value='$reg->Fecha_fin'/> </td></tr>";
			 echo "<tr><td>Tiempo: </td><td><input type='text' name='horas' size='3' value='$reg->Horas'/> Horas (*)</td></tr>";
			 echo "<tr><td>Observaciones: </td><td><textarea name='obs' cols='25' rows='5' >$reg->Observaciones</textarea></td></tr>";
			echo "</table>";
			echo "<input type='hidden' value='$_GET[item]' name='id_aus'/>
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

  function compensatorio($idAus, $id, $con)
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

	  	if($idAus==4) echo "<td><select name='mes' id='mes'>";
		else  echo "<td><select name='mes' id='mes' disabled>";

	  	for ($i=0; $i < 12 ; $i++) { 
	  		if($id == $i) echo "<option value='$i' selected>$mes[$i]</option>";
			else  echo "<option value='$i'>$mes[$i]</option>";
		  }
	  	echo "</select></td>";
}

  function Diagnostico($idAus, $id, $con)
 {
 	$result=$con->consultar("Select * from Diagnostico");
 	//echo $id;
 	if($idAus==7 || $idAus==8){ echo "<td><select name='diag' id='diagnostico'>"; } else {echo "<td><select name='diag' id='diagnostico' disabled>";}
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		$nom= substr($reg->NomDiagnostico, 0, 40);

		if($id== $reg->Codigo) echo "<option title='$reg->NomDiagnostico' value='$reg->Codigo' selected>$reg->Codigo - $nom (...)</option>";
		//else if($id==NULL) echo "<option title='NO ASIGNADO' value='1000' selected>$reg->N-A</option>";
		else echo "<option title='$reg->NomDiagnostico' value='$reg->Codigo'>$reg->Codigo - $nom (...)</option>";
	 }
 	echo "</select></td>";
 }


  function tipo_ausencia($id, $con)
  {

  	$result=$con->consultar("Select * from Ausencias order by NomAusencia");
 	echo "<td><select name='ause' id='ausencia' onChange='mostrar();'>";
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 if($id==$reg->IdAusencias) echo "<option value='$reg->IdAusencias' selected>$reg->NomAusencia</option>";
		 else echo "<option value='$reg->IdAusencias'>$reg->NomAusencia</option>";

	 }
 	echo "</select></td>";

  }

  function mostrarJs()
{
	?>
<script>
	function mostrar()
	{
		var elem=document.getElementById("ausencia").value;
		if(document.getElementById("ausencia").value=="7")
		{
			document.getElementById("diagnostico").disabled=true;
			alert("Ahora debe seleccionar un diganostico");
			document.getElementById("diagnostico").disabled=!document.getElementById("diagnostico").disabled;
		} 
		else if(document.getElementById("ausencia").value=="8" )
		{
			document.getElementById("diagnostico").disabled=true;
			alert("Ahora debe seleccionar un diganostico");
			document.getElementById("diagnostico").disabled=!document.getElementById("diagnostico").disabled;
		}
		else if(document.getElementById("ausencia").value=="4")
		{
			document.getElementById("diagnostico").disabled=true;
			alert("Por favor seleccione el mes de los domingos trabajados");
			document.getElementById("mes").disabled=!document.getElementById("mes").disabled;
		} 
		else
		{
			document.getElementById("diagnostico").disabled=true;
		}
		
	}
	
</script>
	<?php
}
}
$pagina = new editar_ausencia();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>