<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class confirmar extends PopUp
{
  function mostrarContenido()
  {
  	$conexion=new gestorDB();
	$conexion->conectar();
	
  	echo"<div id='texto'>";
	if($_SESSION['tipo']=="Admin" || $_SESSION['tipo']=="Root")
	{
		$calificable=$this->Calificable($_GET[idcap], $conexion);
		$result2=$conexion->consultar("SELECT * FROM Userinfo INNER JOIN UserCapacitacion ON Userinfo.Userid = UserCapacitacion.Userid Where UserCapacitacion.IdCapacitacion=$_GET[idcap] Order by Name");
		echo"<form action='procesarAsistencia.php' method='post'>";
		echo "<h3>";$this->NombreCap($_GET[idcap], $conexion); echo"</h3><input type='submit' value='Confirmar' class='boton'/> <a href='cerrarCapacitacion.php?idcap=$_GET[idcap]' class='boton' >Cerrar capacitación</a><br/><hr/><br/>";
		echo"<table class='listaCapa'>";
		$n=1;
		echo "<tr style='text-align:left; padding: 0 0 25px 0;'><th>#</th><th style=''>Apellidos / Nombre</th><th>";if($calificable==1)echo"Calificación"; echo"</th><th><input type='checkbox' onclick='marcar(this);' /></th></tr>";
		while ($fila=$conexion->MostrarRegistrosAssoc($result2)) {
			echo "<tr><td>$n</td><td width='200' style='font-size:12px'>$fila->Name</td><td>"; if($calificable==1)$this->calificacion(); echo"</td><td>"; $this->verificarInput($fila->Userid, $_GET[idcap], $conexion); echo"</td></tr>";
			$n++;
		}
		echo "</table>
		<input type='hidden' value='$_GET[idcap]' name='idcap'/>
		
		</form>";
	}
	else {
		echo"<center><h2>No esta autorizado para entrar a esta sección</h2>
		<a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
	}
     
      
   
	  echo"</div>";
	  
  }
function calificable($id, $con)
{
	$result=$con->consultar("select * From TbCapacitaciones Where IdCapacitacion=$id");
	while ($nom=$con->MostrarRegistrosAssoc($result)) {
		if ($nom->Calificable==True) return 1;
		else return 0;
	}
}
 function calificacion()
 {
 	echo "<select name='cal[]'>";
	for ($i=0; $i <=5 ; $i++) { 
		echo "<option value='$i'>$i</option>";
	}
	echo "</select>";
 }
 function NombreCap($id, $con)
 {
 	$result=$con->consultar("select * From TbCapacitaciones Where IdCapacitacion=$id");
	while ($nom=$con->MostrarRegistrosAssoc($result)) {
		echo "$nom->NomCapacitacion";
	}
 }
  function verificarInput($idu, $idcap, $con)
 {
 	$result=$con->consultar("Select * from UserCapacitacion Where Userid='$idu' AND IdCapacitacion=$idcap AND Asistio=TRUE");
	$n=0;
	
	while ($reg=$con->MostrarRegistrosAssoc($result)) {
		$n++;
	}
	if($n==0)echo"<input type='checkbox' name='asis[]' value='$idu' />";
	else echo "<span>Asistio</span>";
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
 
}
$pagina = new confirmar();
$pagina -> SetTitulo('People Mananger');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>