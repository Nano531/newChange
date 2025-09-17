<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class procesar_trabajo extends PopUp
{
	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		/*echo " newEmp $_POST[newEmp]";
		echo " emp $_POST[emp]<br/>";
		echo " ini $_POST[ini]<br/>";
		echo " fin $_POST[fin]<br/>";
		echo " cont2 $_POST[cont2]<br/>";
		echo " bln ".($_POST[newEmp] == "true")."<br/>";*/
		if(
			($_POST[newEmp] == "true" && (!$_POST[emp] || !$_POST[ini] || !$_POST[fin])) ||
			($_POST[newEmp] == "false" && (!$_POST[cont2] || !$_POST[ini] || !$_POST[fin]))
		)
		{
			echo"<center><h2>Datos incompletos</h2>
			<a href='javascript:window.history.back()'>Intentalo nuevamente</a></center>";
		}
		else {
			if($_POST[newEmp] == "true")
			{
				if(!$_POST[cont]) $cont="N-A";
				else $cont=$_POST[cont];
				if(!$_POST[tel]) $tel="N-A";
				else $tel=$_POST[tel];
				if(!$_POST[comen]) $comen="Sin comentarios";
				else $comen=$_POST[comen];
				
				$codigo1="INSERT INTO TbEmpresas (NomEmpresa, Contacto, Telefono) VALUES ('$_POST[emp]', '$cont', '$tel')";
				$result1=$conexion->consultar($codigo1);
				
				$idemp=$this->empresa($conexion);
				//echo $idemp;
			}
			else{
				$idemp = $_POST[cont2];
			}
			 //echo $_POST[emp];		    
			
			$codigo2="INSERT INTO HistLaboral (Userid, IdEmpresa, FechaIni, FechaFin, Comentario) VALUES('$_POST[idu]', $idemp, #$_POST[ini]#, #$_POST[fin]#, '$comen')";
			//echo "($codigo2)";
			$result2=$conexion->consultar($codigo2);
			
			if(!$result2)
			{
				echo"<center><h2>No se pudo ejecutar la Inserción</h2>
				<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			}
			else {
				echo"<center><h2>La información se ingreso correctamente</h2>
			<a href='index.php?idu=$_POST[idu]'>Agregar otro</a></center>";
			}
		}
		
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
    echo"</div>";
	  
  }
  function empresa($con) 
  {
  	$result=$con->consultar("Select Top 1 IdEmpresa From TbEmpresas order by IdEmpresa Desc");
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$id=$reg->IdEmpresa;
	}
	return $id;
  }
 
}
$pagina = new procesar_trabajo();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>