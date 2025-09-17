<?php
header('refresh:5; url=../../index.php');

session_start();
require('../../presentacion/pagina.class.php');
require('../../datos/gestor.php');
class checklogin extends pagina
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	echo"<div id='caja1'>";
  
	 // username and password sent from form
      $myusername=$_POST['nick'];
      $mypassword=$_POST['pass'];
       // To protect MySQL injection (more detail about MySQL injection)
       // echo $_POST[email];
      $sql="SELECT * FROM TbUsuarios  WHERE NomUsuario='$myusername' and Clave='$mypassword'"; 
	//echo $sql;  
	$result=$conexion->consultar($sql);	  
	  $n=$this->Nregistros($conexion, $sql);
	 
     if($n==0 )
	  {
		 echo "<center>      
		  <h2>Ocurrió un error verificando el usuario y la contraseña</span></h2>";
		  
		  echo"<p>Revise la información que esta ingresando.</p>
		  <a href='$this->nivel'>Intentalo nuevamente</a>
		  </center>";
	  
      }
	  
	  else
	  {
		 while($row=$conexion->MostrarRegistrosAssoc($result))
		{
		
			 		
					echo"<center>
					 <img src='".$this->nivel."presentacion/img/logoprin.png'/>
					 <h1>Bienvenido<h1/>
					 <h2>Señor(a) $row->NomUsu $row->ApeUsu</h2>"; 
					
					 echo"<p>El sistema lo redireccionará en <span id='minutos' style='color:red; font-size:16px; font-weight:bold'>5</span> Segundos  a la página principal.</p>            
					 </center>     
					";
					 $_SESSION['idusuario']=$row->IdUsuario;
					 $_SESSION['tipo']=$row->TipoUsuario;
					//$this->redirecc($row->NomUsu);
				
		
		 
		}
	  }
      
      
   
	  echo"</div>";
  }
  function Nregistros($con, $sql)
  {
  	 
  	$i=0;
  	$result=$con->consultar($sql);
  	while($registro=$con->MostrarRegistrosAssoc($result))
	  {
	  	$i++;
	  }
	 return $i;
  }
  function mostrarJs()
  {
  	?>
  	<script language="javascript" type="text/javascript">
	//<![CDATA[

// 1000 = 1 segundo

var mins = 4;

var segs = 4;

var s;

function minutos(){

document.getElementById("minutos").innerHTML=mins;

if(mins == 0){

var dm = clearInterval(m);

s = setInterval('segundos()', 1000);

}

mins--;

}

 

function segundos(){

document.getElementById("segundos").innerHTML=segs;

if(segs == 0){

location.reload();

var ds = clearInterval(s);

}

segs--;

}

 

var m = setInterval('minutos()', 1000);

 

//]]>

	</script>
  	<?php
  }
function redirecc($usu)
{
	
	?>
	<script language="JavaScript" type="text/javascript">
	var pagina="../../logica/empleado/index.php?usu=";
	function redireccionar() 
	{
	location.href=pagina;
	} 
	setTimeout ("redireccionar()", 5000);

	</script>
                  
	<?php
}
}
$pagina = new checklogin();
$pagina -> SetTitulo('SisPlan');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>