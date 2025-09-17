<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
require('../clasesSQL/updateUserinfo.php');
class procesarPersonal extends pagina
{
	
	
	function mostrarContenido()
	{
		$conexion=new gestorDB();
		$conexion->conectar();
		
		echo"<div id='texto'>";
		if(isset($_SESSION['idusuario']))
		{
			$fechaNac=$this->convertirFecha($_POST[dia], $_POST[mes], $_POST[year]);
			if($_POST[tipodisc]=="") $tipodisc=NULL;
			else if($_POST[disc]==False) $tipodisc=NULL;
			else $tipodisc=$_POST[tipodisc];
			if(isset($_POST[comunidad]))
			{
				$comunidad=$_POST[comunidad];
				$tam=count($comunidad);
				echo $tam;
				$sql1="Delete From UserComunidad Where Userid='$_POST[userid]'";
				$result1=$conexion->consultar($sql1);
				for($i=0; $i<$tam; $i++)
				{
					$sql="Insert Into UserComunidad (Userid, IdComunidad) VALUES('$_POST[userid]',$comunidad[$i])";
					$result=$conexion->consultar($sql);
				}
			}
			$editar=new updateUserinfo();
			$editar->SetCondicion("Where Userid='$_POST[userid]'");
			$editar->validarNulos($_POST[nom], "Name"); 
			$editar->validarNulos($_POST[email], "Email"); 
			$editar->validarNulos($_POST[sex], "Sex"); 
			$editar->validarNulos($_POST[cabeza], "CabezaFam");
			$editar->validarNulos($_POST[disc], "Discapacitado");
			$editar->validarNulos($tipodisc, "TipoDiscapacidad");
			$editar->validarNulos($_POST[madre], "MadreSoltera");
			$editar->validarNulos($_POST[fechaNac], "Birthday");  
			$editar->validarNulos($_POST[ciudadNac], "NativePlace"); 
			$editar->validarNulos($_POST[tel], "Telephone");
			$editar->validarNulos($_POST[movil], "Mobile"); 
			$editar->validarNulos($_POST[dir], "Address"); 
			$editar->validarNulos($_POST[barrio], "Barrio"); 
			$editar->validarNulos($_POST[local], "Localidad"); 
			$editar->validarNulos($_POST[ciudadRes], "Ciudad"); 
			$editar->validarNulos($_POST[viaPrin], "ViaPrincipal"); 
			$editar->validarNulos($_POST[trans], "MedioTrans"); 
			$editar->validarNulos($_POST[llegada], "TiempoLlegada"); 
			$editar->validarNulos($_POST[estrato], "Estrato"); 
			$editar->validarNulos($_POST[estCivil], "Polity"); 
			$editar->validarNulos($_POST[rh], "idRh"); 
			$editar->validarNulos($_POST[tipoViv], "TipoViv"); 
			$editar->validarNulos($_POST[conMercico], "VivConMercico"); 
			$editar->validarNulos($_POST[loc], "Locker"); 
			$editar->validarNulos($_POST[VrArriendo], "VrArriendo"); 
			
			$sql=$editar->verUpdate();
			 // echo "(($sql))";
			/*echo $editar->tamañoArray();
			for ($i=0; $i < $editar->tamañoArray() ; $i++) {
				$campo=$editar->campos[$i];
				//echo "($campo)<br/>";
			}
			
			
			$sql="UPDATE Userinfo SET Name = '$_POST[nom]', Email = '$_POST[email]', Sex = '$_POST[sex]', MujerCabezaFam = $_POST[cabeza], 
			Birthday = $fechaNac, NativePlace = '$_POST[ciudadNac]', Telephone = '$_POST[tel]', Mobile = '$_POST[movil]', Address = '$_POST[dir]', 
			Barrio = '$_POST[barrio]', Localidad = $_POST[local], Ciudad = '$_POST[ciudadRes]', ViaPrincipal = '$_POST[viaPrin]', Estrato = $_POST[estrato], 
			Polity = '$_POST[estCivil]', Rh = $_POST[rh], Arriendo = $_POST[arriendo], VrArriendo = $_POST[VrArriendo] WHERE Userid='$_POST[userid]'";*/
			$result=$conexion->consultar($sql);
			if(!$result) {
				echo "<center><h2> E R R O R - No se pudo Ejecutar la acción</h2>
				<a href='".$this->nivel."logica/ver_empleado/index.php?idu=$_POST[userid]'>Ver empleado</a></center>";
			}
			else 
			{
				echo"<center><h2>La información del Empleado se Actualizó exitosamente</h2>
			<a href='".$this->nivel."logica/ver_empleado/index.php?idu=$_POST[userid]'>Ver empleado</a></center>"; 
			}
					
			
			
		}
		else {
			echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
			<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
		}
		 
		  
	   
		  echo"</div>";
	}
	function fecha($fecha)
	{
		$fecha = str_replace("00:00:00", "", $fecha);
		return $fecha;
		
	}
	function convertirFecha($dia, $mes, $year)
	{
		$tmDia=strlen($dia);
		$tmMes=strlen($mes);
			if($tmDia==1) $dia="0".$dia;
			if($tmMes==1) $mes="0".$mes;
		$fecha=$year."/".$mes."/".$dia;
		
		return $fecha;
	}
}
$pagina = new procesarPersonal();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>