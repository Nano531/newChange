<?php
/**
 * 
 */
class updateTrab {
	
	var $tabla='TbEmpresas';
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
		$sql2=""; 
		for ($i=0; $i < $tam; $i++) {
			
			$campo=$this->campos[$i];
			$noNulos=$this->noNulos[$i];
			
			if(($i+1)==$tam) $sql2=$sql2.$campo." = '".$noNulos."' ";
			else $sql2=$sql2.$campo." = '".$noNulos."', ";
			
			
		}
		return $sql2;
		
	}
}

?>