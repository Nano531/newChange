<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class dotacion extends PopUp
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
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		$usu="SELECT * FROM Userinfo Where Userid='$_GET[idu]'";
		$result=$conexion->consultar($usu);
		while ($reg=$conexion->MostrarRegistrosAssoc($result)) 
		{
			$nombre=$reg->Name;
		}

		echo"<h3>Dotacion de $nombre <span class='link' ><a href='../agregar_dotacion/index.php?idu=$_GET[idu]'  style='text-decoration:none'>[ Agregar + ]</a></span></h3>";
		 echo "<form action='' method='get'>
		 <input type='hidden' value='$_GET[idu]' name='idu'/>
		 desde: <input type='date' name='fechaini'/> hasta: <input type='date' name='fechafin'/><input type='submit' value='Buscar'/>
		 </form><br/>";
		 echo "<form action='' method='get'>
		 <table>
		 <tr><td><input type='hidden' value='$_GET[idu]' name='idu'/></td><td>Busqueda por elemento</td><td>"; $this->Elementos($conexion); echo "</td><td><input type='submit' value='Enviar'/></td></tr>";
		echo "</table>";
		echo "";
		 echo"</form><br/>";
		 
		 if(isset($_GET[fechaini]) || isset($_GET[fechafin]))
		 {
		 	if($_GET[fechaini] > $_GET[fechafin])
			{
				echo "<center><h2 style='border:2px solid red; padding:10px'>Imposible ejecutar la consulta. La fecha $_GET[fechaini] es mayor que $_GET[fechafin]</h2></center>";
				$codigo="SELECT * FROM Colores INNER JOIN (TbTalla INNER JOIN (Userinfo INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON Userinfo.Userid = HistDotacion.Userid) ON TbTalla.IdTalla = HistDotacion.Talla) ON Colores.IdColor = ElemTipoColor.idColor 
				WHERE (((Userinfo.Userid)='$_GET[idu]')) AND (((Elemento.EPP)=False)) Order by FechaEntrega Asc;";
			}
			 else if(!$_GET[fechaini] || !$_GET[fechafin]){
				echo "<center><h2 style='border:2px solid red; padding:10px'>Los campos de fecha no pueden estar Vacios</h2></center>";
				$codigo="SELECT * FROM Colores INNER JOIN (TbTalla INNER JOIN (Userinfo INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON Userinfo.Userid = HistDotacion.Userid) ON TbTalla.IdTalla = HistDotacion.Talla) ON Colores.IdColor = ElemTipoColor.idColor 
				WHERE (((Userinfo.Userid)='$_GET[idu]')) AND (((Elemento.EPP)=False)) Order by FechaEntrega Asc;";
			}
			
			else {
				$codigo="SELECT * FROM Colores INNER JOIN (TbTalla INNER JOIN (Userinfo INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON Userinfo.Userid = HistDotacion.Userid) ON TbTalla.IdTalla = HistDotacion.Talla) ON Colores.IdColor = ElemTipoColor.idColor
			WHERE (((HistDotacion.FechaEntrega)>=#$_GET[fechaini]#) AND ((HistDotacion.Userid)='$_GET[idu]') AND ((HistDotacion.FechaEntrega)<=#$_GET[fechafin]#))
			AND (((Elemento.EPP)=False)) ORDER BY HistDotacion.FechaEntrega Asc;";
			}
		 	
		 }
		 else if (isset($_GET[elem])) {
					$codigo="SELECT  * FROM Userinfo INNER JOIN (TbTalla INNER JOIN ((Elemento INNER JOIN (Colores INNER JOIN ElemTipoColor ON Colores.IdColor = ElemTipoColor.idColor) ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON TbTalla.IdTalla = HistDotacion.Talla) ON Userinfo.Userid = HistDotacion.Userid 
					WHERE (((Userinfo.Userid)='$_GET[idu]') AND ((Elemento.IdElemento)=$_GET[elem])) AND (((Elemento.EPP)=False)) ORDER BY HistDotacion.FechaEntrega Asc;";
			}
		 else {
		 	$codigo="SELECT * FROM Colores INNER JOIN (TbTalla INNER JOIN (Userinfo INNER JOIN ((Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC) ON Userinfo.Userid = HistDotacion.Userid) ON TbTalla.IdTalla = HistDotacion.Talla) ON Colores.IdColor = ElemTipoColor.idColor 
				WHERE (((Userinfo.Userid)='$_GET[idu]')) AND (((Elemento.EPP)=False))Order by FechaEntrega Asc;";
			 
		 }
		echo "<a href='dotacion.php?idu=$_GET[idu]'>Ver todos</a><br/><br/>";

		$n=$this->nregistros($conexion, $codigo);
		if($n!=0)
		{
			//echo $codigo;	
			$result=$conexion->consultar($codigo);
			$tam=$this->tamañoSQL($conexion, $codigo);
			
			echo"<table class='TbDota'>
			<tr><th>Periodo</th><th>Fecha entrega</th><th>Elemento</th><th>Talla</th><th>Color</th><th>Cantidad</th><th>Comentario</th></tr>";
			while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
				$mes=substr($reg->FechaEntrega, 5,2);
				if($mes>='01' && $mes<='04') $trim="Primer Periodo"; 
				else if($mes>'04' && $mes<='08') $trim="Segundo Periodo";
				else $trim="Tercer Periodo";
				
				echo"<tr><td rowspan=''>$trim</td><td>"; $this->fecha($reg->FechaEntrega); echo"</td><td>$reg->NomElemento</td><td>$reg->NomTalla</td><td>$reg->NomColor</td><td>$reg->Cantidad $reg->Unidad</td><td>$reg->Comentario</td></tr>";
					}
			echo"</table>";
		}
		
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
function tamañoSQL($con, $codigo)
{
	$result=$con->consultar($codigo);
	$i=0;
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$i++;
	}
	return $i;
}
 function Elementos($con)
 {
 	$sql="SELECT Elemento.NomElemento, Elemento.IdElemento FROM (Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento) INNER JOIN HistDotacion ON ElemTipoColor.IdETC = HistDotacion.IdETC
	WHERE (((HistDotacion.Userid)='$_GET[idu]')) GROUP BY Elemento.NomElemento, Elemento.IdElemento;";
 	$result=$con->consultar($sql);
	echo "<select name='elem'>";
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		echo "<option value='$reg->IdElemento'>$reg->NomElemento</option>";
	}
	echo "</select>";
 }
 function nregistros($con, $sql)
 {
 	
		$result=$con->consultar($sql);
		$n=0;
		while($fila=$con->MostrarRegistrosAssoc($result))
		{
			$n++;
		}
		return $n;
 }
  function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	echo $fecha;
	
}
 
 
}
$pagina = new dotacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>