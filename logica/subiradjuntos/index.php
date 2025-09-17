<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class procesar_docu extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
		if(isset($_SESSION['idusuario']))
		{
		
		$id=$_POST['idu'];
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
	
	$filesize = $_FILES['documento']['size'];
	$filename = trim($_FILES['documento']['name']);
	$filename = ereg_replace(" ", "", $filename);
	
	$hh=date("H")+8;
	
	// var_dump($_FILES);
	// phpinfo();
	
		// echo "<br>".$filesize;
		if($filesize > 0)
		{
			if((ereg(".doc", $filename)) || (ereg(".pdf", $filename)) || (ereg(".xls", $filename))|| (ereg(".xlsx", $filename)) || (ereg(".png", $filename)))
			{
				$fecha = date("d-m-y"); 
				
				
				$uploaddir = "../adjuntos/$id";
				$filename=$fecha."_".$id."_".$filename;
				if (!file_exists($uploaddir)) mkdir($uploaddir,0777);
					$uploadfile = $uploaddir."/".$filename;
				// echo $uploadfile;
				
				if (move_uploaded_file($_FILES['documento']['tmp_name'], $uploadfile))
				{
					$result=$conexion->consultar("Insert Into Adjuntos (url, NomAdj, TipoAdj, Userid) VALUES('$uploadfile', '$filename', '$_POST[tipo]', '$id')");
					echo "<center><h2>El archivo se cargó exitosamente.</h2>
						<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
					chown($uploadfile, "Usuario");
					chmod($uploadfile,0777);
					
										
				}
				else
				{
					echo"<center><h2>Hubo un error en la conexión</h2>
						<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
				}
			}
			else
			{
				echo"<center><h2>Solo se reciben imagenes en formato pdf, doc, xls. No se pudo agregar</h2>
					<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			}	
			
		}
		else
		{
			echo"<center><h2>No ha seleccionado una imagen. Campo vacio</h2>	
			<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
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
$pagina = new procesar_docu();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>