<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class informe_general extends PopUp
{
	function mostrarEstilo()
  {
  	echo "<style>
  	body{ font-family:sans-serif; font-size:14px; background-color: #bad7e9 }
	#contenedor{width:950px; margin:0 auto; border:2px solid #ccc; border-radius:4px; padding: 30px; background-color:#fff}
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
	.Tb-informe{ border:3px solid #164450; font-size:12px; }
	.Tb-informe th{text-align:left}
	.Tb-informe th{padding:5px; border:1px solid #2C89A0}
	.Tb-informe td{padding:5px;}
  	
  	</style>";
  	echo '<script type="text/javascript" src="http://filamentgroup.github.com/EnhanceJS/enhance.js"></script>	';
  	?>
  		
  		<script type="text/javascript">
		// Run capabilities test
		enhance({
			loadScripts: [
				{src: 'js/excanvas.js', iecondition: 'all'},
				'js/jquery.js',
				'js/visualize.jQuery.js',
				'js/example.js'
			],
			loadStyles: [
				'css/visualize.css',
				'css/visualize-dark.css'
			]	
		});   
    </script>


  	<?php
  }	
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
		
	if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
	{
		$mes[0]="Enero";
	  	$mes[1]="Febrero";
	  	$mes[2]="Marzo";
	  	$mes[3]="Abril";
	  	$mes[4]="Mayo";
	  	$mes[5]="Junio";
	  	$mes[6]="Julio";
	  	$mes[7]="Agosto";
	  	$mes[8]="Septiembre";
	  	$mes[9]="Octubre";
	  	$mes[10]="Noviembre";
	  	$mes[11]="Diciembre";

	  	$horas_sabado_mes=0;
	  	$horas_sabado_total=0;
	  	$horas_normal_mes=0;
	  	$horas_normal_total=0;

	  	

	  	echo "<span>>> <a href='../ver_empleado/index.php?idu=".$_GET['idu']."'>Volver al inicio</a></span><br/>";
		 echo "<h3>Informe General - "; echo $nom=$this->nombreEmp($_GET[idu], $conexion); echo"</h3><hr/><br/>";
		
		 echo "<form action='informe_general.php' method='get'>
		 Selecciona el año  <select name='year'  onChange='this.form.submit()'>
		 <option>----</option>"; 
		 for ($i=2013; $i <= date('Y') ; $i++) { 
		 	if(isset($_GET['year'] ) && $_GET['year']==$i) echo "<option value='".$i."' selected>".$i."</option>";
		 	else echo "<option value='".$i."'>".$i."</option>";
		 }
		 echo"</select>
		 <input type='hidden' name='idu' value='".$_GET['idu']."' />
		 </form><br/>";
		echo "<table class='Tb-informe'>";
		echo "<tr><td></td><th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th><th>May</th><th>Jun</th><th>Jul</th><th>Ago</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th></tr>";
		//echo "<tr><th>Mes</th><th>Retardos Sabados (Min)</th><th>Retardos Lunes a viernes (Min)</th><th>Tiempo total (Min)</th></tr>";
		echo"<tr><th style='width:150px'>Retardos Sabados (Min)</th>";
		for($m=0; $m < 12; $m ++) {
			# code...
			$horas_sabados=$this->horas_sabados($m, $conexion);
			//$horas=$this->horas_normales($m, $conexion);
			if(isset($horas_sabados)) $sabados_array[$m]=$horas_sabados;
			//if(isset($horas)) $normales_array[$m]=$horas;

			$tiempo_mes=array('sabados' => $horas_sabados, 'normal' => $horas);
			// echo $horas_sabados;  echo"</td><td>"; echo $horas;
			echo "<td><a href='#'>$horas_sabados</a></td>";
			 $horas_sabado_normal=$horas_sabado_normal+$horas_sabados;
			 //$horas_normal_total=$horas_normal_total + $horas;
			 
		}
		echo "</tr>";
		//echo "<td>$horas_sabado_normal</td></tr>";
		echo"<tr><th style='width:150px'>Retardos Lun a vie (Min)</th>";
		for($m=0; $m < 12; $m ++) {
			# code...
			//$horas_sabados=$this->horas_sabados($m, $conexion);
			$horas=$this->horas_normales($m, $conexion);
			//if(isset($horas_sabados)) $sabados_array[$m]=$horas_sabados;
			//if(isset($horas)) $normales_array[$m]=$horas;
			
			

			//$tiempo_mes=array('sabados' => $horas_sabados, 'normal' => $horas);
			 //echo $horas_sabados;  echo"</td><td>"; echo $horas;
			echo "<td>$horas</td>";
			 //$horas_sabado_normal=$horas_sabado_normal+$horas_sabados;
			 $horas_normal_total=$horas_normal_total + $horas;

			 
		}
		echo "</tr>";
		//echo "<td>$horas_normal_total</td></tr>";
		$total_general=$horas_sabado_normal+$horas_normal_total;
		//echo "<tr><th>Totales</th><th>$horas_sabado_normal Minutos</th><th>$horas_normal_total Minutos</th><th>$total_general Minutos</th></tr>";
		echo "</table>";
		//echo "<a href='grafica.php?sabados=$sabados_array'>Grafica Sabados</a>";
		echo "<strong>Total Retardos del Sabados:</strong> $horas_sabado_normal Minutos<br/>
		<b>total de Retardos normales:</b> $horas_normal_total Minutos";
		
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
   
	  echo"</div>";
	  
  }

  function horas_sabados($m, $con)
  {
  	$codigo="SELECT HistDisciplinario.tiempo, * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario WHERE (((HistDisciplinario.tiempo) Is Not Null) AND ((HistDisciplinario.Userid)='$_GET[idu]')) ORDER BY HistDisciplinario.FechaEvento DESC; ";
  	//var_dump($codigo);
		$result=$con->consultar($codigo);
		while ($reg=$con->MostrarRegistrosAssoc($result)) 
		{
			$fecha=$this->fecha($reg->FechaEvento);
			
			$año=substr($fecha, 0,4);
			$mes=substr($fecha, 5,2);
			$dia=substr($fecha, 8,2);
			 if($m+1 == $mes && $año==$_GET['year'])
			 {
			 		$dia=$this->diaSemana($año, $mes, $dia);
			 		if($dia==6) $horas_sabados_mes=$horas_sabados_mes+$reg->tiempo;
			 }
			
		}
		
		
		return $horas_sabados_mes;


  }

  function horas_normales($m, $con)
	{
		$codigo="SELECT HistDisciplinario.tiempo, * FROM Disciplinario INNER JOIN HistDisciplinario ON Disciplinario.IdDisciplinario = HistDisciplinario.IdDisciplinario WHERE (((HistDisciplinario.tiempo) Is Not Null) AND ((HistDisciplinario.Userid)='$_GET[idu]')) ORDER BY HistDisciplinario.FechaEvento DESC; ";

		$result=$con->consultar($codigo);
		while ($reg=$con->MostrarRegistrosAssoc($result)) {
			$fecha=$this->fecha($reg->FechaEvento);
			
			$año=substr($fecha, 0,4);
			$mes=substr($fecha, 5,2);
			$dia=substr($fecha, 8,2);
			 if($m+1 == $mes && $año==$_GET['year'])
			 {
			 	//var_dump($fecha);
			 	//var_dump($reg->tiempo);
			 	$dia=$this->diaSemana($año, $mes, $dia);
			 		if($dia!=6) $horas_normales_mes=$horas_normales_mes+$reg->tiempo;
			 }
			
		}

		return $horas_normales_mes;
	}

 function nombreEmp($id, $con)
 {
 	$result=$con->consultar("Select * from Userinfo Where Userid='$id'");
 	
 	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		 return $reg->Name;
	 }
 	
 }

 function diaSemana($ano,$mes,$dia)
{
	// 0->domingo	 | 6->sabado
	$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
		return $dia;
}

}
$pagina = new informe_general();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>