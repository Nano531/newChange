<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class procesarfoto extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if(isset($_SESSION['idusuario']))
	{ 
	$id=$_POST[idu];
	$this->uploader($id, $conexion);
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
   function uploader($id, $conexion)
  {
	$max=100000; 
	$filesize = $_FILES['picture']['size'];
	$filename = trim($_FILES['picture']['name']);

	$filename = ereg_replace(" ", "", $filename);
	echo $filename;
	$hh=date("H")+8;
	
	
	if($filesize < $max)
	{
		if($filesize > 0)
		{
			if((ereg(".jpg", $filename)) || (ereg(".gif", $filename)) || (ereg(".JPG", $filename))|| (ereg(".GIF", $filename)) || (ereg(".png", $filename)))
			{
				$fecha = date("d-m-y"); 
				
				
				$uploaddir = "../fotografias/$id";
				$filename=$fecha."_".$id."_".$filename;
				mkdir($uploaddir,0777);
				$uploadfile = $uploaddir."/".$filename;
				echo $uploadfile;
				
				if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile))
				{
					
					
					$result=$conexion->consultar("UPDATE Userinfo SET Foto='$uploadfile' where Userid='$id'");
					echo "<center><h2>La Fotografia Cargó Exitosamente.</h2></center>";
					chown($uploadfile, "Usuario");
					chmod($uploadfile,0777);
					
										
				}
				else
				{
					echo"<center><h2>Hubo un error en la conexión</h2></center>";
				}
			}
			else
			{
				echo"<center><h2>Solo se reciben imagenes en formato jpg y gif. No se pudo agregar</h2></center>";
			}	
			
		}
		else
		{
			echo"<center><h2>No ha seleccionado una imagen. Campo vacio</h2></center>";
		}
	}
	else
	{
		echo"<center><h2>La imagen que ha intentado adjuntar es mayor de 1.5 Mb, si desea cambie el tamaño del archivo y vuelva a intentarlo.</h2></center>";
	}
   }
 function generar_clave($longitud){ 
       $cadena="[^A-Z0-9]"; 
       return substr(eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())), 
       0, $longitud); 
} 
 
}
$pagina = new procesarfoto();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>