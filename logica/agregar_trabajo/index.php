<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class trabajo extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		$sql="SELECT Distinct NomEmpresa FROM TbEmpresas";
		$result=$conexion->consultar($sql);
		echo "<h3>Ingresar Referencias Laborales</h3>
		<h3 style='color:#2c89a0;' class='oldCont'> <span class='link'><a href='#' onClick='crear();' style='text-decoration:none'>[ Crear empresa+ ]</a></span></h3>
		<h3 style='color:#2c89a0;' class='newCont'> <span class='link'><a href='#' onClick='seleccionar();' style='text-decoration:none'>[ Selec. empresa+ ]</a></span></h3>
		<p>Los campos señalados con (*) son obligatorios</p>
		<form action='procesarTrabajo.php' method='post'>";
		
		echo "<table class='oldCont'>
			<tr><td style='width: 72px;'>Empresa:</td><td>";$this->getEmpresas($conexion, $result); echo " (*)</td></tr>
			<tr><td>Contacto:</td><td><select name='cont2' id='cont2' style='width: 90%;'></select></td></tr>
		</table>";
		
		echo "<table class='newCont'>
			<tr><td style='width: 72px;'>Empresa:</td><td><input type='text' name='emp' size='35'/> (*)</td></tr>
			<tr><td>Contacto:</td><td><input type='text' name='cont' size='35'/> </td></tr>
			<tr><td>Telefono:</td><td><input type='text' name='tel' size='35'/> </td></tr>
		</table>";
		
		echo "<table>
		
		
		<tr><td>Desde:</td><td><input type='date' name='ini' /> (*)</td></tr>
		<tr><td>hasta:</td><td><input type='date' name='fin' /> (*)</td></tr>
		<tr><td>Comentario:</td><td><textarea name='comen' rows='5' cols='25'></textarea></td></tr>
		</table>
		<input type='hidden' value='$_GET[idu]' name='idu'/>
		<input type='hidden' value='false' name='newEmp' id='newEmp'/>
		<input type='submit' value='Registrar' class='btn'/>";
		echo "</form>";
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
    echo"</div>";
?>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"
    type="text/javascript"></script>
	<script>
		function getContacto() {
			var str='';
			var val=document.getElementById('emp2');
			for (i=0;i< val.length;i++) { 
				if(val[i].selected){
					str += val[i].value + ','; 
				}
			}         
			var str=str.slice(0,str.length -1);				
			
			$.ajax({          
					type: "GET",
					url: "obtenerContactos.php",
					data:'emp='+str,
					success: function(data){
						console.log('data ',data);
						$("#cont2").html(data);
					}
			});
		}
		
		function crear() {
			document.getElementById('newEmp').value = true;
			var className = document.getElementsByClassName('oldCont');
			for(var index=0;index < className.length;index++){
				console.log(' className[index] ',className[index]);
				  className[index].style.display = "none";
			}
			
			var className = document.getElementsByClassName('newCont');
			for(var index=0;index < className.length;index++){
					console.log(' className[index] ',className[index]);
				  className[index].style.display = "block";
			}
		}
		
		function seleccionar() {
			document.getElementById('newEmp').value = false;
			var className = document.getElementsByClassName('newCont');
			for(var index=0;index < className.length;index++){
				console.log(' className[index] ',className[index]);
				  className[index].style.display = "none";
			}
			
			var className = document.getElementsByClassName('oldCont');
			for(var index=0;index < className.length;index++){
					console.log(' className[index] ',className[index]);
				  className[index].style.display = "block";
			}
		}
	</script>
	
	<style>
		.newCont {display:none}
	</style>
<?php   
  }

	function getEmpresas($con, $result)
	{
		echo "<select name='emp2' id='emp2' style='width: 90%;' onChange='getContacto();'>
			 <option>----</option>";
			 while($fila=$con->MostrarRegistrosAssoc($result))
			{
				echo "<option value='".$fila->NomEmpresa."'>".$fila->NomEmpresa."</option>";
			}
		echo "</select>";
	}
}
$pagina = new trabajo();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>