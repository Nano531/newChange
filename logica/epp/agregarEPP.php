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
	.res{ color:#2C89A0; border: 2px solid #2C89A0; padding:5px}
  	
  	</style>";
  }
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	echo"<span class='ruta'><a href='index.php?idu=$_GET[idu]'>EPP</a> | Agregar elemento<br/><br/>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		echo"<h2>Agregar Elementos de Progtección Personal</h2><hr/><br/>";
		echo "<form action='' method='get'>";
		echo "<table>";
		echo "<tr><td>Nombre del elemento:</td><td><input type='text' name='nomb'/></td></tr>";
		echo "<tr><td>Unidad:</td><td>
		<select name='uni'>
			<option value='Und'>Unidades</option>
			<option value='Pares'>Pares</option>
			<option value='Mts'>Metros</option>
			<option value='Cm'>Centímetros</option>
		</select>
		</td></tr>";
		echo "<tr><td>Color:</td><td>"; $this->colores($conexion); echo"</td></tr>";
		echo "</table>
		<input type='hidden' value='$_GET[idu]' name='idu'/>
		<input type='submit' value='Agregar' class='btn'/>";
		echo "</form>";

		if(isset($_GET[nomb])) $this->Resultados($conexion);
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
function Resultados($conexion)
{
	if(!$_GET[nomb] || !$_GET[uni] || !$_GET[color])
	{
		echo"<center><h2 class='res'>DATOS INCOMPLETOS</center>";
	}
	elseif ($this->busqueda($conexion, $_GET[nomb], $_GET[color])) {
		# code...
		echo"<center><h2 class='res'>El Elemento $_GET[nomb] ya Existe en nuestra Base de datos</center>";
	}
	else
	{
		$codEle=$this->codigoElemento($conexion);
		
		//echo $codEle;
		$insert="INSERT INTO Elemento(IdElemento, NomElemento, Unidad, EPP, tipoEle) VALUES($codEle, '$_GET[nomb]', '$_GET[uni]', true, 'EPP' )";
		echo $insert;
		$result=$conexion->consultar($insert);

		$codETC=$this->codigoETC($conexion);
		$insert2="INSERT INTO ElemTipoColor(IdETC, Elemento, IdColor, TipoDotacion) VALUES($codETC, $codEle, $_GET[color], 13)";
		//echo $insert2;
		$result2=$conexion->consultar($insert2);
		if($result && $result2)
			echo "<center><h2 class='res'>Elemento ingresado exitosamente</h2></center>";
	}
}

function busqueda($conexion, $nomb, $color)
{

	$sql="SELECT Elemento.NomElemento, ElemTipoColor.idColor
FROM Elemento INNER JOIN ElemTipoColor ON Elemento.IdElemento = ElemTipoColor.Elemento
WHERE (((Elemento.NomElemento)='$nomb') AND ((ElemTipoColor.idColor)=$color));";

//echo $sql;
$n=0;

	$result=$conexion->consultar($sql);
	while($row=$conexion->MostrarRegistrosAssoc($result))
	{
	  $n++;
	}

	if($n>0) return true;
	else return false;
}
function codigoElemento($conexion)
{
	$n=0;
	$sql="select * from Elemento";
	$result=$conexion->consultar($sql);
	while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
	$n=$reg->IdElemento;
	}
    return $n+1;

}
function codigoETC($conexion)
{
	$n=0;
	$sql="select * from ElemTipoColor";
	$result=$conexion->consultar($sql);
	while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
	$n=$reg->IdETC;
	}
    return $n+1;

}
function colores($conexion)
{
	$sql="Select * from Colores order by IdColor";
	$result=$conexion->consultar($sql);
	echo "<select name='color'>";
	while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
		# code...
		echo "<option value='$reg->IdColor'>$reg->NomColor</option>";
	}
	echo "</select>";
}
 
}
$pagina = new agregardotacion();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>