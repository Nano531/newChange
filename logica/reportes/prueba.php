<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class prueba extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	$sql="select * from CentroCosto order by IdCentroCosto";
	$sql="update Userinfo Set CentroCosto=17 Where Userid='12342349'";
	//$sql="insert into CentroCosto (IdCentroCosto, NomCentro) VALUES (17, 'Terceros')";
	$result=$conexion->consultar($sql);
	if($result) echo "se inserto o actualizo";
	
  	//var_dump($sql);
	//var_dump($result);
	/*while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
		echo "$reg->NomCentro : $reg->IdCentroCosto<br/>";
	}*/

	//var_dump($aa);
  	
     
      //idu=12342349&
   
	 
  }

  
}
$pagina = new prueba();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>