<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
require('../clasesSQL/InsertUser.php');
class Agregar_empleado extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{
		if($this->validaciones())
		{
			$userid=$this->validarDocumento($_POST[iden]);
			//echo "($userid)";
			$name=$this->validarNombreCompleto($_POST[nom], $_POST[ape]);
			$comunidad=$_POST[comunidad];
			$tamaño=count($comunidad);
			if($tamaño==0)
			{
				echo"<center><h2>Debe seleccionar al menus un campo en Comunidades</h2>
				<a href='javascript:window.history.back()'>Inténtalo de nuevo</a></center>";
			}
			else {
				
				$userCod = $userid;
				$userid = (int) (substr($userid,strlen($userid)-6,strlen($userid)));
				
				$auxSize = 5;
				while(!$this->UserInfoRepetido($userid))
				{
					$userid = (int) (substr($userid,strlen($userid)-$auxSize,strlen($userid)));
					$auxSize--;
				}
				
					
				$editar=new InsertUser();
				$editar->validarNulos($userid, "Userid"); 
				$editar->validarNulos(1, "Deptid"); 
				$editar->validarNulos($_POST[iden], "UserCode"); 
				$editar->validarNulos($name, "Name"); 
				$editar->validarNulos($_POST[iden], "IDCard");
				$editar->validarNulos($_POST[iden], "Cardnum");
				$editar->validarNulos($_POST[email], "Email");  
				$editar->validarNulos($_POST[sex], "Sex"); 
				$editar->validarNulos($_POST[cabeza], "CabezaFam"); 
				$editar->validarNulos($_POST[madre], "MadreSoltera"); 
				$editar->validarNulos($_POST[disc], "Discapacitado"); 
				$editar->validarNulos($_POST[tipodisc], "TipoDiscapacidad"); 
				$editar->validarNulos($_POST[estCivil], "Polity"); 
				$editar->validarNulos($_POST[fechaNac], "Birthday"); 
				$editar->validarNulos($_POST[ciudadNac], "NativePlace"); 
				$editar->validarNulos($_POST[movil], "Mobile"); 
				$editar->validarNulos($_POST[trans], "MedioTrans");

				$editar->validarNulos($_POST[viaPrin], "ViaPrincipal");
				$editar->validarNulos($_POST[estrato], "Estrato");
				$editar->validarNulos($_POST[rh], "idRh");

				$editar->validarNulos($_POST[llegada], "TiempoLlegada");
				$editar->validarNulos($_POST[tel], "Telephone"); 
				$editar->validarNulos($_POST[dir], "Address"); 
				$editar->validarNulos($_POST[barrio], "Barrio"); 
				$editar->validarNulos($_POST[local], "Localidad"); 
				$editar->validarNulos($_POST[ciudadRes], "Ciudad"); 
				$editar->validarNulos($_POST[tipoViv], "TipoViv"); 
				$editar->validarNulos($_POST[conMercico], "VivConMercico");
				$editar->validarNulos(TRUE, "IsAtt"); 
				$editar->validarNulos(TRUE, "Isovertime"); 
				$editar->validarNulos(TRUE, "Isrest"); 
				$editar->validarNulos(strval(0), "Retirado");
				$editar->validarNulos(strval(0), "Mercico");
				$editar->validarNulos(strval(0), "Dotacion");
				$editar->validarNulos(strval(0), "FondoEmpleado");
				$editar->validarNulos($_POST[VrArriendo], "VrArriendo"); 
				
				$sql=$editar->verInsert();
				//echo "$sql";
				$result=$conexion->consultar($sql);
				if(!$result)
				{
					echo"<center><h2>El empleado $name se ingreso correctamente</h2>";
					echo"Execution failed:\n";
					echo"   State: ".odbc_error($conexion)."\n";
					echo"   Error: ".odbc_errormsg($conexion)."\n";
				}
				else{
					for($i=0; $i<$tamaño; $i++)
					{
						$sql="Insert into UserComunidad (Userid, IdComunidad) VALUES('$userid', $comunidad[$i])";
						$result=$conexion->consultar($sql);
						$flag = true;
						if(!$result)
						{
							echo"<center><h2>El empleado $name se ingreso correctamente</h2>";
							echo"Execution failed:\n";
							echo"   State: ".odbc_error($conexion)."\n";
							echo"   Error: ".odbc_errormsg($conexion)."\n";
							$flag = false;
							break;
						}
					}
					
					if($flag)
					{
						$sql2="Insert Into UserPower (Userid, ClientNumber, PowerFlag) VALUES ('$userid', '1', 28)";
						$result2=$conexion->consultar($sql2);
						if(!$result2)
						{
							echo"<center><h2>El empleado $name se ingreso correctamente</h2>
							<a href='".$this->nivel."logica/ver_empleado/index.php?idu=$userid'>Ver empleado</a> <a href=''>Nuevo empleado</a></center>";
						}
					}
				}
				
			}			
		} 
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
  }
function convertirFecha($dia, $mes, $year)
{
	$tmDia=strlen($dia);
	$tmMes=strlen($mes);
		if($tmDia==1) $dia="0".$dia;
		if($tmMes==1) $mes="0".$mes;
	$fecha=$dia."/".$mes."/".$dia;
	
	return $fecha;
}
function validarNombreCompleto($nom, $ape)
{
	$name=$ape." ".$nom;
	return $name;
}
function validaciones()
{
	 if(!$_POST[iden])
		{
			echo"<center><h2>El Campo documento de identidad NO PUEDE ESTAR VACIO !!!</h2>
		 	<a href='javascript:window.history.back()'>Inténtalo de nuevo</a></center>";
		}
	else if(!is_numeric($_POST[iden]))
		{	
		 	echo"<center><h2>El documento de identidad ingresado no es un valor numérico</h2>
		 	<a href='javascript:window.history.back()'>Inténtalo de nuevo</a></center>";
		}
	else if(!$_POST[nom])
	{
		echo"<center><h2>El Nombre ingresado NO PUEDE ESTAR VACIO !!!</h2>
		 	<a href='javascript:window.history.back()'>Inténtalo de nuevo</a></center>";
	}
	else if(!$_POST[ape])
	{
		echo"<center><h2>Los Apellidos ingresados NO PUEDEN ESTAR VACIOS !!!</h2>
		 	<a href='javascript:window.history.back()'>Inténtalo de nuevo</a></center>";
	}
 else if(!$this->docuRepetido($_POST[iden]))
    {
    	echo"<center><h2>Ya existe un empleado con este documento de identidad</h2>
		 	<a href='javascript:window.history.back()'>Inténtalo de nuevo</a></center>";
    }

	else return true;
	
}
function docuRepetido($id)
{
	echo" ==>> docuRepetido id $id ==>> ";
	$con =new gestorDB();
	$con->conectar();
	$result=$con->consultar("Select * from Userinfo Where IDCard='$id'");
	
	$n = $con->NumRegistros($result);
	//echo "cant reg $n";
		
	if($n==0)return TRUE;
	else return FALSE;
	
}
function UserInfoRepetido($id)
{
	echo" ==>> UserInfoRepetido id $id ==>> ";
	$con =new gestorDB();
	$con->conectar();
	$result=$con->consultar("Select * from Userinfo Where Userid='$id'");
	
	$n = $con->NumRegistros($result);
	//echo "cant reg $n";
	
	if($n==0)return TRUE;
	else return FALSE;
	
}
function validarDocumento($id)
{
	if(is_numeric($id))
	{
		$tamDoc=strlen($id);
		$cortar=$tamDoc-8;
		//echo "($cortar)";
		if($cortar > 0)
		{
			$userid=intval(substr($id, $cortar));
			return $userid;
		}
		else return $id;		
	}
}

 
 
}
$pagina = new Agregar_empleado();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();
?>