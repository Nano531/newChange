<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../presentacion/pagina.class.php');
class logica extends pagina
{
  function mostrarContenido()
  {
  		echo"<div id='texto'>";
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
		 echo"</div>";
  }
  
}
$pagina = new logica();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../");
$pagina -> Mostrar();

?>