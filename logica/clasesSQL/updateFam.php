<?php
/**
 * 
 */
class updateFam {
	
	var $tabla='TbFamilia';
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
		
		$tam=count($this->noNulos);
		
		$sql2=""; 
	 
		for ($i=0; $i < $tam; $i++) {
			
			$campo=$this->campos[$i];
			$noNulos=$this->noNulos[$i];
		
			if($i!=$tam-1)
			{
				if($campo=='TipoDocumento' || $campo=='IdParentesco' || $campo=='Documento')
				{
					$sql2=$sql2.$campo." = ".$noNulos.", ";
				}
				else if($campo=='FechaNacimiento') $sql2=$sql2.$campo." = #".$noNulos."#, ";
				
				else $sql2=$sql2.$campo." = '".$noNulos."', ";
			}
			else {
				$sql2=$sql2.$campo." = '".$noNulos."' ";
			}
			
			
			
		}
		return $sql2;
		
	}
}

?>