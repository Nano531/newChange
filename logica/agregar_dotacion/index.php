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
	echo"<span class='ruta'><a href='javascript:window.history.back()'>Dotacion</a> | Asignar dotacion<br/><br/>";
	
	if(isset($_SESSION['idusuario']))
	{
		echo"<h2>Asignar dotacion</h2><hr/><br/>";
		$result=$conexion->consultar("Select * from Userinfo Where Userid='$_GET[idu]'");
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
			
			if($reg->Dotacion==FALSE)
			{
				echo"<center><h2>No se le asigna dotación al empleado</h2>
				<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			}
			else if(!$reg->TallaCamisa || !$reg->TallaPantalon || !$reg->TallaCalzado)
			{
				echo"<center><h2>La información de las tallas esta incompleta</h2>
				<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			}
			else if(!$reg->TipoDotacion)
			{
				echo"<center><h2>No se le ha asignado Tipo de dotacion</h2>
				<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			}
			else {
				echo "<h3>$reg->Name</h3>";
				
				$this->TipoDotacion($reg->TipoDotacion, $reg->TallaCamisa, $reg->TallaPantalon, $reg->TallaCalzado, $conexion );
			}
		}
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
         
	  echo"</div>";
	  
  }
function mostrarJs()
 {
 	?>
 	<script type="text/javascript">
    function marcar(source) 
    {
        checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
        for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
        {
            if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
   
    
	</script>
 	<?php
 }
function tipoDotacion($id, $cam, $pan, $cal, $con)
{
	
	$result=$con->consultar("SELECT  * FROM TipoDotacion INNER JOIN (Elemento INNER JOIN (Colores INNER JOIN ElemTipoColor ON Colores.IdColor = ElemTipoColor.idColor) ON Elemento.IdElemento = ElemTipoColor.Elemento) ON TipoDotacion.IdTipoDotacion = ElemTipoColor.TipoDotacion 
	WHERE (((TipoDotacion.IdTipoDotacion)=$id) AND ((Elemento.EPP)=False));");
	
	echo "<form action='procesardotacion.php' method='post' style='font-size:14px'>
	<input type='hidden' value='$_GET[idu]' name='idu'/>";
	echo "<p>Dotación asignada:</p>";
	echo "<table style='border:2px solid #ccc; padding:10px; margin: 0 0 10px 0'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<tr><td><input type='hidden' value='$reg->IdETC' name='elem[]'/><b>$reg->NomElemento color $reg->NomColor</b></td><td><input type='text' name='cant[]' value='0' size='3'/> $reg->Unidad</td><td>";
		 if($reg->tipoEle=="Camisa") { echo "<input type='hidden' value='$cam' name='talla[]'/>";  }
		 else if($reg->tipoEle=="Pantalon") { echo "<input type='hidden' value='$pan' name='talla[]'/>"; }
		 else  { echo "<input type='hidden' value='$cal' name='talla[]'/> ";  } echo"</tr>";
	}
	
	echo "</table>";
	echo "<table>";
	echo "<tr><td>Fecha:</td><td><input type='date' name='fecha'/></td></tr>";
	echo "<tr><td>Comentario:</td><td><textarea name='coment' cols='25' rows='5'></textarea></td></tr>";
	echo "</table>";
	echo "<input type='submit' value='Asignar' class='btn'/>";
	echo "</form>";
	
}
function Tallas($con)
 {
 	$result=$con->consultar("Select * from TbTalla");
	echo"<select name='talla'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo"<option value='$reg->IdTalla'>$reg->IdTalla $reg->NomTalla</option>";
	}
	echo"</select>";
 }
 
}
$pagina = new agregardotacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>