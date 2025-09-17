<?php
/**
 * 
 */
class updateLaboral {
	
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
    public function verUpdate()
	{
		$sql1="UPDATE $this->tabla SET ";
		$sql2=$this->comparativos();
		$sqlCompleto=$sql1.$sql2.$this->condicion;
		return $sqlCompleto;
	}
	public function comparativos()
	{
		$tam=$this->tamañoArray();
		$tam1=count($this->noNulos);
		
		$sql2=""; 
		for ($i=0; $i < $tam; $i++) {
			
			$campo=$this->campos[$i];
			$noNulos=$this->noNulos[$i];
			$n=$tam+1;
			 
			if($campo=='EmployDate' || $campo=='FechaRetiro')
				{
					
					$sql2=$sql2.$campo." = #".$noNulos."#, ";
				}
			else {
				if($campo=='Sede') {$sql2=$sql2.$campo." = '".$noNulos."', ";}
				else if($campo=='EmailCorp') {$sql2=$sql2.$campo." = '".$noNulos."', ";}
				else $sql2=$sql2.$campo." = ".$noNulos.", ";
			}
			
		}
		$sql2 = substr($sql2, 0, strlen($sql2) - 2).' ';
		return $sql2;
		
	}
}

?>