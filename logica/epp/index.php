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
	//echo"<span class='ruta'><a href='index.php?idu=$_GET[idu]'>EPP</a> |<br/>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo"<h2>Asignar Elementos de Progtección Personal</h2><hr/><br/>";
		
		echo "<form action='asignar.php' method='post'>";
		echo "<table>";
		echo "<tr><td>Elemento</td>";  $this->elementosPP($conexion); echo"<td><a href='agregarEPP.php?idu=$_GET[idu]'>[ Agregar Elemento ]</a></td></tr>";
		echo "<tr><td>Cantidad</td><td><input type='text' size='2' name='cant' value='1'/></td></tr>";
		echo "<tr><td>Fecha Entrega</td><td><input type='date'  name='fecha'/></td></tr>";
		echo "<tr><td>Comentario</td><td><textarea name='comment'></textarea></td></tr>";
		echo "</table>
		<input type='hidden' value='$_GET[idu]' name='idu'/>
		<input type='submit' value='Asignar' class='btn' name='asignar'/>";
		echo "</form>";
		//var_dump(isset($_GET[epp]));
		//$sql="INSERT INTO HistDotacion (Userid, IdETC, Talla, Cantidad, FechaEntrega, Comentario) Values('52378941', 17, 0, 1, #2014/03/10#, '((PRUEBA MAS))S')";
		//$result=$conexion->consultar($sql);
		//if($result) echo "Hecho";

		/*if(isset($_GET['asignar']))  
		{
			$this->asignar($conexion);
		}*/
		//$this->asignar($conexion);
		$this->verHistorial($conexion);
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 function asignar($conexion)
 {
 	if(!$_GET[cant] || !$_GET[fecha] || !$_GET[comment])
 	{
 		echo"<center><h2>DATOS INCOMPLETOS</center>";
 	}
 	else
 	{
 		$fecha=$_GET[fecha];
 		$sql="INSERT INTO HistDotacion (Userid, IdETC, Talla, Cantidad, FechaEntrega, Comentario)
					Values('$_GET[idu]', $_GET[epp], 0, $_GET[cant], #$fecha#, '$_GET[comment]')";
	 	$result=$conexion->consultar($sql);
	 	//$cd=odbc_insert_id($conexion);
	 	var_dump($cd);
	 	if ($result) {
	 		# code...
	 		echo"<center><h2>SE ASIGNÓ CORRECTAMENTE EL ELEMENT0</center>";
	 	}
 	}
 	
 }
 function elementosPP($conexion)
 {
 	$sql="SELECT * FROM Colores INNER JOIN (Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) ON Colores.IdColor = ElemTipoColor.idColor WHERE (((Elemento.EPP)=True)); ";
 	echo "<td><select name='epp'>";
 	$result=$conexion->consultar($sql);
 	while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
 		# code...
 		echo "<option value='$reg->IdETC'>$reg->NomElemento $reg->NomColor</option>";
 	}
 	echo "</select></td>";

 }

 function verHistorial($conexion)
 {
 	$codigo="SELECT * FROM Colores INNER JOIN (TbTalla INNER JOIN (Userinfo INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON Userinfo.Userid = HistDotacion.Userid) ON TbTalla.IdTalla = HistDotacion.Talla) ON Colores.IdColor = ElemTipoColor.idColor 
				WHERE (((Userinfo.Userid)='$_GET[idu]')) AND (((Elemento.EPP)=True)) Order by FechaEntrega Asc;";
			 
	$result=$conexion->consultar($codigo);
	echo"<br/><table class='TbDota'>
			<tr><th>Periodo</th><th>Fecha entrega</th><th>Elemento</th><th>Talla</th><th>Color</th><th>Cantidad</th><th>Comentario</th></tr>";
			while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
				$mes=substr($reg->FechaEntrega, 5,2);
				if($mes>='01' && $mes<='03') $trim="Primer Periodo"; 
				else if($mes>'03' && $mes<='06') $trim="Segundo Periodo";
				else if($mes>'06' && $mes<='09') $trim="Tercer Periodo";
				else $trim="Cuarto Periodo";
				
				echo"<tr><td rowspan=''>$trim</td><td>"; $this->fecha($reg->FechaEntrega); echo"</td><td>$reg->NomElemento</td><td>$reg->NomTalla</td><td>$reg->NomColor</td><td>$reg->Cantidad $reg->Unidad</td><td>$reg->Comentario</td></tr>";
					}
			echo"</table>";
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
  function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	echo $fecha;
	
}

 
}
$pagina = new agregardotacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>