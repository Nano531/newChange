<?php
require("pagina.class.php");
class PopUp extends pagina
{
	  
  function mostrarEstilo()
  {
  	echo"<link rel='stylesheet' name='estilos' type='text/css' href='".$this->nivel."presentacion/css/popUp.css'/>";
	
  }
    
 function mostrarMenu()
  {
  	echo"<div id='menu'>";
	
	
	echo"</div>";
	
	
  }
  function Mostrar()
  {
    echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
          <html xmlns='http://www.w3.org/1999/xhtml'>\n
          <head>\n";
          $this -> mostrarTitulo();
          $this -> mostrarMetaTags();
          $this->mostrarHacks();
		  
    echo ' </head>
          
          <body onBlur="focus()">';
   
    
    
    echo "<div id='contenedor'>";
    $this->mostrarCabecera();
    $this->mostrarMenu();
    $this->mostrarContenido();
    $this->mostrarPie();
  	echo"</div>"; 
    echo "</body>
          </html>";
  }
}
 
?>