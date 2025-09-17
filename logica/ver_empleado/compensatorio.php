<?php
//header('refresh:5; url=../../index.php');
session_start();
require('../../presentacion/popUp.class.php');
require('../../datos/gestor.php');
class ausentismo extends PopUp
{
	function mostrarEstilo()
	{
		echo"<link rel='stylesheet' name='estilos' type='text/css' href='".$this->nivel."presentacion/css/popUp2.css'/>";
	
	}
	
	function mostrarContenido()
	{
		$conexion=new gestorDB();
		$conexion->conectar();
	
		echo"<div id='texto'>";
	
		
			if($_SESSION['tipo']=='Root' || $_SESSION['tipo']=='Admin')
			{
				echo "<h2>Compensatorios - "; echo $nom=$this->nombreEmp($_GET[idu], $conexion); echo"</h2><hr/>";
			
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
				
				$hoy=date("m");
				$year=date("Y");
				//echo $year;
				// echo "SELECT * FROM Checkinout WHERE (((Checkinout.Userid)='$_GET[idu]') AND ((Checkinout.IdDia)=1) AND ((Checkinout.CheckType)='E'));";
				 $tam1=$this->contarES($conexion, "SELECT * FROM Checkinout WHERE (((Checkinout.Userid)='$_GET[idu]') AND ((Checkinout.IdDia)=1) AND ((Checkinout.CheckType)='E')) UNION ALL (SELECT * FROM HistoInOut WHERE (((HistoInOut.Userid)='$_GET[idu]') AND ((HistoInOut.IdDia)=1) AND ((HistoInOut.CheckType)='E')));");
				
				if($tam1==0)
				{
					echo"<center><h3 style='border: 2px solid #2C89A0; padding:20px'>No registra Domingos Trabajados</h3>
					</center>";
				}
				
				$result1=$conexion->consultar("SELECT YEAR(CheckTime) as year FROM HistoInOut WHERE (((HistoInOut.Userid)='$_GET[idu]') AND ((HistoInOut.IdDia)=1) AND ((HistoInOut.CheckType)='E')) GROUP BY YEAR(CheckTime) UNION ALL (SELECT YEAR(CheckTime) FROM Checkinout WHERE (((Checkinout.Userid)='$_GET[idu]') AND ((Checkinout.IdDia)=1) AND ((Checkinout.CheckType)='E')) GROUP BY YEAR(CheckTime));");
				
				echo "<h1>$_GET[ano]</h1>";
				
				echo "<select id='acomp' onChange='cambio()'>
				<option>----</option>";
				
				while ($reg=$conexion->MostrarRegistrosAssoc($result1)) 
				{
					echo "<option>$reg->year</option>";
				}
				echo "</select><br>
				
				<script type='text/javascript'>
					var id=".$_GET[idu].";
					
					function cambio()
					{
						var ac=document.getElementById('acomp').value;
						window.location.href = 'compensatorio.php?idu=".$_GET[idu]."&ano='+ac+'';
					}				
				</script>";
				
				if ($_GET[ano]==("20".date('y')))
				{
					$tabla="Checkinout";
					$year=$_GET[ano];
				}
				else
				{
					$tabla="HistoInOut";
					$year=$_GET[ano];
				}
				
				$cmeses=$conexion->consultar("SELECT MONTH(CheckTime) as mes FROM $tabla WHERE (((Userid)='$_GET[idu]') AND ((IdDia)=1) AND ((Year(CheckTime))=$year) AND ((CheckType)='E')) GROUP BY MONTH(CheckTime);");
				while ($reg=$conexion->MostrarRegistrosAssoc($cmeses)) 
				{
					$mesc=$reg->mes;
				
					// for ($i=1; $i <= $hoy; $i++) { //$hoy es el mes actual
					// echo "SELECT * FROM $tabla WHERE Userid='$_GET[idu]' AND IdDia=1 AND Month(CheckTime)=$mesc  AND  Year(CheckTime)='$year' AND CheckType='E';";
					$cons=$conexion->consultar("SELECT * FROM $tabla WHERE Userid='$_GET[idu]' AND IdDia=1 AND Month(CheckTime)=$mesc  AND  Year(CheckTime)='$year' AND CheckType='E';");
					$tam=$this->contarES($conexion, "SELECT * FROM $tabla WHERE (((Userid)='$_GET[idu]') AND ((IdDia)=1) AND ((Month(CheckTime))=$mesc)  AND  ((Year(CheckTime))='$year') AND ((CheckType)='E'));");
					
					if($tam!=0)
					{
						echo "<div id='comp'>";
							echo "<h3>".$mes[$mesc-1]."</h3>";
							echo "<table class='TbComp'>
							<tr><th>#</th><th>Domingos</th></tr>";
							$n=1;
							$aux=0;
							$compensatorio=0;

							while ($registro=$conexion->MostrarRegistrosAssoc($cons)) 
							{
								$hora=substr($registro->CheckTime, 11);
								
								if($hora >= "05:00" && $hora<= "22:00")
								{
									$dia=substr($registro->CheckTime,8,2);
									//var_dump($dia);
									// echo "dia:$dia, aux: $aux <br>";
									if($dia==$aux) 
										$compensatorio=$compensatorio+1;
									else 
										$compensatorio=0;
										
									echo "<tr><td>$n</td><td>$registro->CheckTime</td></tr>";
									$aux=$dia+7;
										
								}
								else 
								{
									echo "<tr><td>$n</td><td>$registro->CheckTime</td></tr>"; 
								}
								$n++;
							} 
							
							echo "</table><br/>";
								// var_dump($compensatorio);
								
							
							if($compensatorio >=2)
							{
								// echo "<br>entro<br>";
								$fecha=$this->Asignacion($conexion, $mesc-1);
								if($fecha=="")
								{
									echo "<center><span style='color:red; font-size:12px; padding:5px; border:2px solid red; '>compensatorio sin asignar</span><br/><br/>
									<a href='../ausentismo/index.php?idu=$_GET[idu]'>[ Asignar ]</a></center>";
								}
								else 
								{
									echo "<center><span style='color:green; font-size:12px; padding:5px; border:2px solid green; '>Compensatorio asignado</span> <br/><br/>";
									echo "fecha: ".$fecha=substr($fecha,0,10); echo"</center>";
								}
							}
								
						echo "</div>";
					}
				}
			}	
			// else 
			// {
				// echo"<center><h2>No esta autorizado para entrar a esta secciÃ³n</h2>
				// <a href='javascript:window.history.back()'>Regresa a la pagina anterior</a></center>";
			// }
		echo"</div>";
	}

	function Asignacion($con, $mes)
	{
	
		$sql="Select * from HistAusentismo Where Userid='$_GET[idu]' AND IdAusencia=4 AND MesCompensatorio=$mes";
		$result=$con->consultar($sql);

		while ($reg=$con->MostrarRegistrosAssoc($result)) {
			$fecha=$reg->Fecha_ini;
			
		}
		return $fecha;
	}

	function fecha($fecha)
	{
		$fecha = str_replace("00:00:00", "", $fecha);
		echo $fecha;
		
	}

	function contarES($con,$cod)
	{
		$result=$con->consultar($cod);
		$i=0;
		while ($reg=$con->MostrarRegistrosAssoc($result)) 
		{
			$i++;
		}
		return $i;
	}
 
	function Entradas($id, $type)
	{
		if($type=="E")
		{
			echo "$id";
		}
	}
 
	function Salidas($id, $type)
	{
		if($type=="S")
		{
			echo "$id";
		}
	}
 
	function nombreEmp($id, $con)
	{
		$result=$con->consultar("Select * from Userinfo Where Userid='$id'");
		
		while ($reg=$con->MostrarRegistrosAssoc($result)) 
		{
			return $reg->Name;
		}
		
	}
 
}
$pagina = new ausentismo();
$pagina -> SetTitulo('Sistema SociodemogrÃ¡fico');
$pagina ->SetNivel("../../");
$pagina -> Mostrar();

?>