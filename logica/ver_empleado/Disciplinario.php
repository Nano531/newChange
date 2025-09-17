<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class disciplinario extends PopUp
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
	echo "<h3>Historial disciplinario <span class='link' ><a href='../disciplinario/index.php?idu=$_GET[idu]'  style='text-decoration:none'>[ Agregar + ]</a></h3><hr/><br/>";
	
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		
		$this->FiltrosBusqueda($conexion);
		if(isset($_GET[fechaini]) || $_GET[fechafin])
		{
			if($_GET[fechaini] > $_GET[fechafin])
			{
				echo "<center><h2 style='border:2px solid red; padding:10px'>Imposible ejecutar la consulta. La fecha $_GET[fechaini] es mayor que $_GET[fechafin]</h2></center>";
				$codigo="SELECT * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario
WHERE (((HistDisciplinario.Userid)='$_GET[idu]')) ORDER BY HistDisciplinario.FechaEvento DESC;";
			}
			 else if(!$_GET[fechaini] || !$_GET[fechafin]){
				echo "<center><h2 style='border:2px solid red; padding:10px'>Los campos de fecha no pueden estar Vacios</h2></center>";
				$codigo="SELECT * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario
WHERE (((HistDisciplinario.Userid)='$_GET[idu]')) ORDER BY HistDisciplinario.FechaEvento DESC;";
			}
			else
			{
				$codigo="SELECT * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario 
				WHERE (((HistDisciplinario.FechaEvento)>=#$_GET[fechaini]#) AND ((HistDisciplinario.Userid)='$_GET[idu]') AND ((HistDisciplinario.FechaEvento)<=#$_GET[fechafin]#))
 				ORDER BY HistDisciplinario.FechaEvento DESC;";
			}
		}

		
		else
		{
			$codigo="SELECT * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario
			WHERE (((HistDisciplinario.Userid)='$_GET[idu]')) ORDER BY HistDisciplinario.FechaEvento DESC;";
		}
		
		echo "<a href='Disciplinario.php?idu=$_GET[idu]'>Ver todos</a> <br/><br/>";
		 $result=$conexion->consultar($codigo);
		 echo "<table class='TbDota'>
		 <tr><th>Fecha</th><th>Novedad disciplinaria</th><th>Motivos</th><th>Tiempo (Min)</th><th>Observaciones</th></tr>";
		 while ($reg=$conexion->MostrarRegistrosAssoc($result)) {
			 	echo "<tr valign='top'><td>$reg->FechaEvento</td><td><a href='../disciplinario/editarDisc.php?idu=$_GET[idu]&dis=$reg->IdHistDisc'>$reg->NomDisc</a></td><td>$reg->Motivos</td><td>$reg->tiempo</td><td>$reg->Observaciones</td></tr>";
			 
			
		 }
		  echo"</table><br/>";
	}
	else {	
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
 function filtrosBusqueda($con)
 {
 	echo "<form action='Disciplinario.php' method='get'>
 	<input type='hidden' name='idu' value='$_GET[idu]'/>
 	Desde: <input type='date' name='fechaini'/> hasta: <input type='date' name='fechafin'/> <input type='submit' value='Buscar'/>
 	</form><br/>";
	
	
 }
}
$pagina = new disciplinario();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>