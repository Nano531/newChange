<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class agregardotacion extends PopUp
{
function mostrarEstilo()
  {
  	echo "<style>
  	body{ font-family:sans-serif; font-size:14px; background-color: #bad7e9 }
	#contenedor{width:800px; margin:0 auto; border:2px solid #ccc; border-radius:4px; padding: 30px; background-color:#fff}
	#menu {width:550px;  padding: 10px 20px 10px 20px; background-image:url(../img/fondo_menu.png);  background-repeat:repeat-x; margin:20px 0 20px 0; color:#FFFFFF}
	#menu a{ color:#fff; margin: 10px; text-decoration:none} 
	a{color:#006680; text-decoration:none}
	#pie{clear:both; text-align:center}
	hr{border: 2px solid #bad7e9}
	h2{ color:#ff2a2a }
	h3{color:#2c89a0;}
	.btn { padding:10px 20px 10px 20px; margin: 20px 0 0 0}
	.textbox{height:35px; border-radius:4px}
	.TbDota{ border:3px solid #164450; font-size:12px; width: 100%; }
	.TbDota th{text-align:left}
	.TbDota th{padding:5px; border:1px solid #2C89A0}
	.TbDota td{padding:5px;}
	.link{float:right; margin: 0 10px 0 0; font-size:12px}
	.ruta{font-size:12px} 
	.ruta a{color:#000; }
  	
  	</style>";
  }
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	//echo"<span class='ruta'><a href='index.php?idu=$_POST[idu]'>EPP</a> |<br/>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		
		if(!$_POST[cant] || !$_POST[fecha] || !$_POST[comment])
 	{
 		echo"<center><h2>DATOS INCOMPLETOS</h2>
 			<a href='index.php?idu=$_POST[idu]'>Volver</a>
 		</center>";
 	}
 	else
 	{
 		$fecha=$_POST[fecha];
 		$sql="INSERT INTO HistDotacion (Userid, IdETC, Talla, Cantidad, FechaEntrega, Comentario)
					Values('$_POST[idu]', $_POST[epp], 0, $_POST[cant], #$fecha#, '$_POST[comment]')";
	 	$result=$conexion->consultar($sql);
	 	//$cd=odbc_insert_id($conexion);
	 	//var_dump($cd);
	 	if ($result) {
	 		# code...
	 		echo"<center><h2>SE ASIGNO CORRECTAMENTE EL ELEMENT0</h2>
	 		<a href='index.php?idu=$_POST[idu]'>Volver</a>
	 		</center>";
	 	}
 	}
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }


 
}
$pagina = new agregardotacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>