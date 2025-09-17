<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
?>

<script>
	function mostrar()
	{
		// alert ("Valor: "+document.getElementById("sinterminar").value)
		if(document.getElementById("sinterminar").value==0)
		{
			alert("Diga cual fue el último grado");
			document.getElementById("grado").readonly=false;
			document.getElementById("grado").value='';
		} 
		
		else
		{
			limite();
			document.getElementById("grado").readonly=true;
		}
		
	}
	
	function cambnivel()
	{
		if(document.getElementById("nivel").value=="1" || document.getElementById("nivel").value=="2")
		{
			document.getElementById("semgrado").value="Grado";
			limite();
		}else if(document.getElementById("nivel").value=="0")
		{
			document.getElementById("semgrado").value="";
			alert("Debe seleccionar algun nivel educativo");
		}else
		{
			document.getElementById("semgrado").value="Semestre";
			limite();
		}
	}
	
	function limite()
	{
		var limite=0;
		if(document.getElementById("nivel").value=="1")
		{
			limite=5
		}else if(document.getElementById("nivel").value=="2")
		{
			limite=11;
		}else if(document.getElementById("nivel").value=="3")
		{
			limite=4;
		}else if(document.getElementById("nivel").value=="4")
		{
			limite=6;
		}else if(document.getElementById("nivel").value=="5")
		{
			limite=10;
		}else if(document.getElementById("nivel").value=="6")
		{
			limite=3;
		}else if(document.getElementById("nivel").value=="4")//maestria
		{
			limite=11;
		}else if(document.getElementById("nivel").value=="8")//phd
		{
			limite=10;
		}
		document.getElementById("grado").value=limite;
	}
	
	function validar()
	{
		var limite=0;
		if(document.getElementById("nivel").value=="1")
		{
			limite=5
		}else if(document.getElementById("nivel").value=="2")
		{
			limite=11;
		}else if(document.getElementById("nivel").value=="3")
		{
			limite=4;
		}else if(document.getElementById("nivel").value=="4")
		{
			limite=6;
		}else if(document.getElementById("nivel").value=="5")
		{
			limite=10;
		}else if(document.getElementById("nivel").value=="6")
		{
			limite=3;
		}else if(document.getElementById("nivel").value=="4")//maestria
		{
			limite=11;
		}else if(document.getElementById("nivel").value=="8")//phd
		{
			limite=10;
		}
		
		if(isNaN(document.getElementById("grado").value))
		{
			alert("El valor debe ser numerico");
			document.getElementById("grado").value=1;
		}else
		{
			if(document.getElementById("nivel").value<0 || document.getElementById("grado").value>limite)
			{
				alert("Valor fuera de rango");
				document.getElementById("grado").value=1;
			}
		}
	}
	
</script>

<?php
class infoAcad extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		echo"<h3>Agregar información académica</h3><hr/>
		<p>Los campos señalados con (*) son OBLIGATORIOS diligenciarlos.</p>";
		echo"<form action='procesarAcad.php' method='post' id='formAus'>";
		echo"<input type='hidden' value='$_GET[idu]' name='idu'/>";
		echo"<table>";
		echo"<tr><td>Nivel educativo</td><td>";$this->NivelAcad($conexion); echo "(*)</td></tr>";
		echo"<tr><td>Titulo:</td><td><input type='text' name='tit' size='25' class='textbox2'/>(*) </td></tr>";
		echo"<tr><td>Terminado?:</td><td><select name='sinterminar' id='sinterminar' onchange='mostrar();'>
		<option value='1' selected>Si</option>
		<option value='0'>No</option>
		</select> Ultimo nivel:<input type='text' name='grado' id='grado' size='3' maxlength='2' value='0' onChange='validar()'/> 
		<input type='text' name='semgrado' id='semgrado' readonly='readonly' size='5'>";
		echo"<tr><td>Institución:</td><td><input type='text' name='inst' size='25' class='textbox2'/>(*) </td></tr>";		
		echo"<tr><td>Año:</td><td><input type='text' name='year' size='5'/> </td></tr>";
		echo"<tr><td>Ciudad:</td><td><input type='text' name='ciudad' size='25'/> </td></tr>";
		echo"</table><br/>";
		echo "<input type='submit' value='Agregar' class='btn'/>";
		echo"</form>";
		
		echo "<script> mostrar() </script>";
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
  function NivelAcad($con)
  {
  	$result=$con->consultar("Select * from TbNivelEdu");
  	echo"<select name='nivel' id='nivel' onchange='cambnivel()'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<option value='$reg->IdNivel'>$reg->NomNivel</option>";
	}
	echo"</select>";
  }
 function mostrarJs()
 {
 	?>
	<?php
 }
}
$pagina = new infoAcad();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>