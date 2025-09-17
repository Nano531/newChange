<?php

date_default_timezone_set('America/Bogota');
class pagina
{
	var $titulo;
     var $palabrasClave="";
     var $autor="J.L. PeÃ±aranda - jolupesu@gmail.com";
     var $descripcion="People Mananger";
	 var $nivel;
	
function SetTitulo($nuevoTitulo)
  {
    $this->titulo=$nuevoTitulo;
  }
function SetPalabrasClave($nuevasPalabrasClave)
  {
    $this->palabrasClave=$nuevasPalabrasClave;
  }

function SetNivel($nuevoNivel)
  {
    $this->nivel=$nuevoNivel;
  }
function Mostrar()
  {
    echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
          <html xmlns='http://www.w3.org/1999/xhtml'>\n
          <head>
		  <link rel='icon' href='".$this->nivel."presentacion/img/favicon.ico' type='image/ico'>\n";
          $this -> mostrarTitulo();
          $this -> mostrarMetaTags();
          $this->mostrarHacks();
		  
    echo ' </head>
          
          <body>';
   
    
    
    echo "<div id='contenedor'>";
    $this->mostrarCabecera();
    $this->mostrarMenu();
    $this->mostrarContenido();
    $this->mostrarPie();
  	echo"</div>"; 
    echo "</body>
          </html>";
  }
function mostrarTitulo()
  {
  	echo"<title>", $this->titulo,"</title>";
  }
function mostrarMetaTags()
  {
  	echo"<meta name='autor' content=", $this -> autor," />";
    echo"<meta name='Descripcion' content=", $this -> descripcion," />";
	$this->mostrarEstilo();
	$this->mostrarJs();
	$this->mostrarDesc();
	
	echo'<meta name="keywords" content="', $this->palabrasClave,'"/>';
    echo'<meta http-equiv="Content-Type" content="text/html; charset= utf-8" />';
    echo'<meta http-equiv="X-UA-Compatible" content="" ie="7/" />';
    
  }
function mostrarEstilo()
  {
  	echo"<link rel='stylesheet' name='estilos' type='text/css' href='".$this->nivel."presentacion/css/style.css'/>";
  }
function mostrarJs()
  {
	  
  }
function mostrarDesc()
  {
  	echo'<meta property="og:description" content="" /><meta property="og:title" content="People Mananger" />';
  }
function mostrarCabecera()
  {
  	echo"<div id='cabecera' >";
	echo"<img src='".$this->nivel."presentacion/img/logogestion.png' style='float:left'/>";
	echo"<img src='".$this->nivel."presentacion/img/logoprin.png'/>
	<div class='lineaPrin'></div>";
	echo"</div>";
  }
function mostrarFecha()
  {
  	echo'<div id="date">';
	 echo' 
  <script type="text/javascript">
        var dias = new Array(6);
        var meses = new Array(11);
        var $hoy = new Date();
        var mes = $hoy.getMonth();
        var fecha = $hoy.getDate();
        var diaSemana = $hoy.getDay();
        var año = $hoy.getYear();
        if (año <= 200){
        año = año + 1900;
        }
        $hoy = null;
        
        dias[0] = "Domingo";
        dias[1] = "Lunes";
        dias[2] = "Martes";
        dias[3] = "Mi&eacute;rcoles";
        dias[4] = "Jueves";
        dias[5] = "Viernes";
        dias[6] = "Sabado";
        
        meses[0] = "Enero";
        meses[1] = "Febrero";
        meses[2] = "Marzo";
        meses[3] = "Abril";
        meses[4] = "Mayo";
        meses[5] = "Junio";
        meses[6] = "Julio";
        meses[7] = "Agosto";
        meses[8] = "Septiembre";
        meses[9] = "Octubre";
        meses[10] = "Noviembre";
        meses[11] = "Diciembre";
        
        document.write(dias[diaSemana]+", " + fecha +" de " + meses[mes] + " del " + año);
          </script> 
</div>
  
  ';
  }
function mostrarMenu()
  {
  	echo"<div id='menu'>";
	echo"<a href='".$this->nivel."index.php'>Inicio</a> | ";
	if(isset($_SESSION['idusuario']))
	{
		echo"<a href='".$this->nivel."logica/sesion/logout.php' style='color:yellow'>Cerrar sesión</a> | ";
	}
	
	echo"</div>";
	
	
  }
function antiguedad($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	$hoy=date("Y-m-d");
			$hoy=strtotime($hoy);
			$fechaEmp=strtotime($fecha);
			$resultado=($hoy-$fechaEmp);
			$resultado=$resultado/31536000;
			
			$resultado=floor($resultado)." años";
			return $resultado;
	
}
function añosCumplidos($birthday)
{
	$hoy=date("Y-m-d");
			$hoy=strtotime($hoy);
			$fechaEmp=strtotime($birthday);
			$resultado=($hoy-$fechaEmp);
			$resultado=$resultado/31536000;
			
			$resultado=floor($resultado)." años";
			return $resultado;
}
function mostrarHacks()
  {
  	?>
  	<!--[if IE 7 ]>]>
		<style type="text/css">
		
		</style>
	<![endif]-->
  	<?php
  }
function mostrarSesion()
  {
  	
	echo"<div id='cajaSesion'>
	<form action='logica/sesion/' method='post' class='form1'>
	<table>
	<tr><td>Usuario:</td><td><input type='text' name='nick' size='15' class='inputsesion'/></td></tr>
	
	<tr><td>Contraseña:</td><td><input type='password' name='pass'size='15' class='inputsesion'/></td></tr>
	</table><br/>
	<input type='submit' name='boton' class='btn' value='Entrar'/>
	</form>
	</div>
	";
	
}
function mostrarContenido()
  {
  	if(isset($_SESSION['idusuario']))
	{
		echo "<div id='menuProg'>";
		echo"<center><table>";
		if($_SESSION['tipo']!='Consulta'){
			echo "<tr><td><a href='logica/nuevo_empleado/'><img src='".$this->nivel."presentacion/img/add_user.png'/></a></td>";
		}
		
		echo "<td><a href='logica/Buscar_empleado'><img src='".$this->nivel."presentacion/img/ver_user.png'/></a></td>";
		echo "<td><a href='logica/lista_empleado/'><img src='".$this->nivel."presentacion/img/list_user.png'/></a></td>";
		echo "<td><a href='logica/capacitaciones/'><img src='".$this->nivel."presentacion/img/capacitaciones.png'/></a></td>";
		echo "<td><a href='logica/reportes/'><img src='".$this->nivel."presentacion/img/report.png'/></a></td>";
		echo "<td><a href='logica/cumpleanos/'><img src='".$this->nivel."presentacion/img/torta.png'/></a></td></tr>";
		echo "<tr>"; 
			if($_SESSION['tipo']!='Consulta'){
				echo "<td style='text-align:center'><a href='logica/nuevo_empleado/'>Agregar empleado</a></td>";
			}
			echo "<td style='text-align:center'><a href='logica/Buscar_empleado'>Buscar empleado</a></td>";
			echo "<td style='text-align:center'><a href='logica/lista_empleado/'>Lista empleados</a></td>";
			echo "<td style='text-align:center'><a href='logica/capacitaciones/'>Capacitaciones</a></td>";
			echo "<td style='text-align:center'><a href='logica/reportes'>Reportes</a></td>";
			echo "<td style='text-align:center'><a href='logica/cumpleanos'>Cumpleaños</a></td>";
		echo "</tr>";
		
		echo"</table></center>";
		
		$this->cumpleaños();
		
		echo"</div>";
	}
	else {
		$this->mostrarIzquierda();
  		$this->mostrarDerecha();
	}
		
	

	
  }
  function fecha($fecha)
{
	$fecha = str_replace("00:00:00", "", $fecha);
	$fecha = str_replace(" .000", "", $fecha);
	return $fecha;
	
}
 function cumpleaños()
{
	require('datos/gestor.php');
	$con=new gestorDB();
	$con->conectar();
	$hoy=date("Y-m-d");
	$año=substr($hoy, 0,4);
	$mesActual=substr($hoy, 5,2);
	$diaActual=substr($hoy, 8,2);
	
	$sql="Select * From Userinfo Where((Month([Userinfo].[Birthday]))=$mesActual) AND ((Day([Userinfo].[Birthday]))=$diaActual) AND Retirado=False";
	
	$n=$this->nRegistros($sql, $con);
	$result=$con->consultar($sql);
	
	if($n!=0)
	{
		echo "<div id='cumple'>
		<h2>Felicitaciones - hoy cumplen años:</h2>";
	
 		while ($reg=$con->MostrarRegistrosAssoc($result)) {
 		$fecha=$this->fecha($reg->Birthday);
		$añosCumplidos=$this->añosCumplidos($reg->Birthday);
		 echo "<a style='font-size:16px' href='logica/ver_empleado/index.php?idu=$reg->Userid'>$reg->Name</a>($fecha)<br/>";
	 	}
	 	echo "</div>";
	}
	
	 
}
function nRegistros($sql, $con)
{
	$result=$con->consultar($sql);
	$n=0;
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$n++;
	}
	return $n;
}
function mostrarIzquierda()
  {
  	echo"<div id='izquierda'>";
  	$this->mostrarSesion();	
	echo"</div>";
	
  }
function mostrarDerecha()
  {
  	echo"<div id='derecha'>";
	echo"
	<h2 style='color:#164450'>Sistema de Gestion Humana</h2>
	<hr/>
	<p>Este Modulo, permite llevar un control detallado de la información sociodemográfico de la Empresa Mercico Mercantil Cupido de Colombia Ltda.</p><hr/>";
	echo"</div>";
  }
function mostrarPie()
  {
  	echo"<div id='pie'>";
  	echo '<br/><hr/> ©<script type="text/javascript">var año = $hoy.getYear();  document.write(año);</script> <a href="http://mercico.com">Mercico Ltda.</a> - <a href="#">Condiciones</a> - <a href="#">Política de contenidos</a> - <a href="#">Privacidad</a>';
   	echo"</div>";
  }
}

?>