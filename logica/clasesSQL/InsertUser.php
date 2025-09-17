<?php
/**
 * 
 */
class InsertUser {
	
	var $tabla='Userinfo';
	var $condicion;
	var $campos;
	var $noNulos;
	
	public function SetCondicion($nuevaCondicion)
	{
		$this->condicion=$nuevaCondicion;
	}
	public function validarNulos($valor, $campo)
	{
		if($valor!="")
		{			
			$this->campos[]=$campo;
			$this->noNulos[]=$valor;			
		}
	}
	public function tamañoArray()
	{
		$tm=count($this->noNulos);
		return $tm;
	}
    public function verInsert()
	{
		$sql1="INSERT INTO $this->tabla ";
		$sql2=$this->comparativos();
		$sql3=$this->valores();
		$sqlCompleto=$sql1.$sql2.$sql3;
		return $sqlCompleto;
	}
	public function comparativos()
	{
		$tam=$this->tamañoArray();
		$tam1=count($this->noNulos);
		
		$value=" ("; 
		for ($i=0; $i < $tam; $i++) {
			
			$campo=$this->campos[$i];
			
			if($campo=="VrArriendo") $value=$value.$campo.")";
			else $value=$value.$campo.", ";
		}
		return $value;
		
	}
	public function valores()
	{
		$tam=count($this->noNulos);	
		$value=" VALUES ("; 
		for ($i=0; $i < $tam; $i++) 
		{
			$campo=$this->campos[$i];
			// echo "--".$campo."--<br>";
			$noNulos=$this->noNulos[$i];
			if($campo=='Userid' || $campo=='Name' || $campo=='Email' || $campo=='Sex' || $campo=='NativePlace' || $campo=='Telephone' || $campo=='Mobile' || $campo=='Address' || $campo=='Barrio' || $campo=='Ciudad' || $campo=='ViaPrincipal'
			|| $campo=='Polity' || $campo=='MedioTrans' || $campo=='TipoDiscapacidad' || $campo=='TipoViv' || $campo=="Birthday")
			{
				//El campo es un estring
			   $value=$value."'$noNulos',";
			}
			
			else if($campo=="VrArriendo")
			 {
				//Ultimo campo y valor
				$value=$value."$noNulos)";
			}
			// else if($campo=="Birthday")
			// {
			// 	$noNulos=date("m-d-Y",strtotime($noNulos));
			// 	$value=$value."#$noNulos#,";
			// }
			
			else $value=$value."$noNulos,";
			
			
		}
		return $value;
	}

}

?>