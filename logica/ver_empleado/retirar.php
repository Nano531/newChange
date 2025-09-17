<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class retirar extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		// var_dump($_SESSION['tipo']);
		$result1=$conexion->consultar("Select Contacto From TbEmpresas Where NomEmpresa = 'Mercico Ltda' Group By Contacto");
		$result=$conexion->consultar("Select * from Userinfo Where Userid='$_GET[idu]'");
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) 
		{
			// echo $_GET[retirar];
			if(isset($_GET[retirar])) $this->resultado($conexion);
			else 
			{
				echo "<h3>Retirar Empleado</h3>";
				echo "<form action='retirar.php' method='get'>";
				echo"<table>
					<tr><td>Retirar:</td><td><input type='radio' name='retirar' value='True' checked/>Si <input type='radio' name='retirar' value='False'/>No</td></tr>
					<tr><td>Fecha de retiro:</td><td><input type='date' name='fechaRet' /> (dd/mm/aaaa)</td></tr>
					<tr><td>Comentario:</td><td><textarea name='coment' cols='25' rows='5'></textarea></td></tr>
					<tr><td>Jefe directo:</td><td>";

					echo "<select name='jefe' id='jefe' onchange='valideJefe()'>";
						$count = 0;
						// $array = array();
						while ($reg1=$conexion->MostrarRegistrosAssoc($result1)) 
						{
							$array[$count] = $reg1->Contacto;
							echo "<option>".$reg1->Contacto."</option>";
							$count++;
						}
						echo "<option value='otro'>Otro</option>";
					echo "</select>";
				echo "</table>
				<input type='hidden' value='$_GET[idu]' name='idu'/>
				<input type='hidden' value='$reg->EmployDate' name='fechaIng'/>
				<input type='submit' value='Retirar' class='btn'/>
				<input type='checkbox' id='isNew' name='isNew' Style='display:none'/>";
				echo"</form>";

				echo "<script>
					function valideJefe()
					{
						// alert('Entro');
						var jefe = document.getElementById('jefe').value;
						// alert(jefe);
						if(jefe == 'otro')
						{
							var person = prompt('Ingrese el nombre del jefe directo', '');
    
						    if (person != null) 
						    {
						        document.getElementById('jefe').innerHTML = '<option>'+person+'</option>';
						        document.getElementById('isNew').checked  = true;
						    }
						}
						jefe = document.getElementById('jefe').value;
						// alert(jefe);
					}
				</script>";
			}
		}
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
  
  function resultado($con)
  {
  	// echo "Entro resultado<br/>";
  	if($_GET[fechaIng]=="")
	{
		echo"<center><h2 style='border:2px solid red; padding:10px'>La fecha de ingreso de este empleado esta Vacia. Por lo tanto se debe editar la fecha de ingreso del empleado</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	} 
	else 
	{
		// echo "<br>GET ====>>> ".$_GET[jefe];
		// echo "<br>GET isNew ====>>> ".$_GET[isNew];
		if($_GET[isNew] == true)
		{
			$codigo0="INSERT INTO TbEmpresas (NomEmpresa, Contacto, Telefono) VALUES ('Mercico Ltda', '$_GET[jefe]', '3683730')";
			$result0=$con->consultar($codigo0);
			$codigoIdEmp = $con->consultar("Select IdEmpresa From TbEmpresas Where NomEmpresa = 'Mercico Ltda' AND Contacto = '$_GET[jefe]'");

			while ($regEmp=$con->MostrarRegistrosAssoc($codigoIdEmp)) 
			{
				$idEmp = $regEmp->IdEmpresa;
				// echo "<br> idEmp 0 ====>>> ".$idEmp;
			}
		}
		else
		{
			$result2=$con->consultar("Select IdEmpresa,Contacto From TbEmpresas Where NomEmpresa = 'Mercico Ltda' AND Contacto = '".$_GET[jefe]."'");
			while ($regEmp=$con->MostrarRegistrosAssoc($result2)) 
			{
				$idEmp = $regEmp->IdEmpresa;
			}
			// $idEmp = $_GET[jefe];
			// echo "<br> idEmp 1 ====>>> ".$idEmp;
		}

		// echo "<br>".$idEmp;
		// echo "<br> SQL1 <br> UPDATE Userinfo SET Retirado=$_GET[retirar], Deptid=15, FechaRetiro=#$_GET[fechaRet]# Where Userid='$_GET[idu]'";
		// echo "<br> SQL2 <br> INSERT INTO HistLaboral(Userid, IdEmpresa, FechaIni, FechaFin, Comentario) VALUES ('$_GET[idu]', $idEmp, #$_GET[fechaIng]#, #$_GET[fechaRet]#, '$_GET[coment]')";

		if(!$tmp=$con->consultar("UPDATE Userinfo SET Retirado=$_GET[retirar], Deptid=20, FechaRetiro=#$_GET[fechaRet]# Where Userid='$_GET[idu]'"))
		{
			echo "<br> ERROR 0 ==>>>>". odbc_errormsg();
		}
		else
		{
			$result1 = $tmp;
			// echo "$result1";
		}

		if(!$tmp=$con->consultar("INSERT INTO HistLaboral (Userid, IdEmpresa, FechaIni, FechaFin, Comentario) VALUES ('$_GET[idu]', $idEmp, #$_GET[fechaIng]#, #$_GET[fechaRet]#, '$_GET[coment]')"))
		{
			echo "<br> ERROR 1 ==>>>>". odbc_errormsg();
		}
		else
		{
			$result2 = $tmp;
			// echo "$result2";
		}

		// echo "<br>";
		// var_dump($tmp);
		// while ($regEmp=$con->MostrarRegistrosAssoc($tmp)) 
		// {
		// 	echo "<br>";
		// 	var_dump($regEmp);			
		// }
		// $result1=$con->consultar("UPDATE Userinfo SET Retirado=$_GET[retirar], Deptid=15, FechaRetiro=#$_GET[fechaRet]# Where Userid='$_GET[idu]'");
		// $result2=$con->consultar("INSERT INTO HistLaboral(Userid, IdEmpresa, FechaIni, FechaFin, Comentario) VALUES ('$_GET[idu]', $idEmp, #$_GET[fechaIng]#, #$_GET[fechaRet]#, '$_GET[coment]')");
		
		if(!$result1 && !$result2)
		{
			echo"<center><h2>No se pudo ejecutar esta acción</h2>
			<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
		}
		else {
			echo"<center><h2 style='border:2px solid red; padding:10px'>El Empleado se le cambio el Estado a RETIRADO</h2>
			<a href=''>Cerrar</a></center>";
		
		}
	}
  	
	
  }
 
 
}
$pagina = new retirar();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>