<?php
class gestorDB
{
var $db;
var $cn;
var $mapTable = [
    "HistAusentismo" => "IdHistAus",
    "Userinfo " => "Consecutivo",
    "UserComunidad" => "IdUserCom",
    "UserPower" => "Logid",
    "TbInfAcademica" => "IdInfAcad",
    "TbFamilia " => "IdFamilia",
    "HistLaboral" => "IdHistLab",
    "TbEmpresas" => "IdEmpresa",
    "HistDotacion" => "IdHistDot",
    "HistDisciplinario" => "IdHistDisc",
    "TbCapacitaciones" => "IdCapacitacion",
    "Adjuntos" => "IdAdjunto"
	];

function conectar()
{
	$this->db = "SQL";
	if($this->db == "Access")
	{
		$pass="860531849";
		$usuario="Admin";
		$this->cn = odbc_connect("cnGestion", $usuario, $pass);
	}
	else if($this->db == "SQL")
	{
		$usuario="sa";
		$pass="MercicoFactory860";
		$this->cn = odbc_connect("cnPeopleServ", $usuario, $pass);  //Prod - Briometrico
		//$this->cn = odbc_connect("cnPeople23", $usuario, $pass); //Test - BiometricoJC
	}
	//echo "$this->db";

	if ( !$this->cn )
	die("Fallo en la conexion a la base de datos :(");
}

 
/**Realiza una consulta sobre la BD*/
function consultar($codigo)
{
	if($this->db == "SQL")
	{
		$codigo = $this->trasformData($codigo);
	}
	// var_dump($codigo);
	$result = odbc_exec($this->cn, $codigo);
	return $result;
}

function MostrarRegistrosAssoc($codigo)
{
	return $fila = odbc_fetch_object($codigo);
}

function MostrarRegistrosArray($codigo)
{
	return $fila=odbc_fetch_array($codigo);
}
/**Cierra la conexion a la BD*/

function cerrarConexion()
{
	if($this->cn)
	mysql_close( $this->cn );
}
function ingresarRegistro($codigo)
{
	/*$resultado = mysql_query($codigo, $this->cn) or die(mysql_error($this->cn));
	$id = mysql_insert_id($this->cn); 
	return $id;*/	
}
function NumRegistros($resultado)
{
	$items = 0;
	while ($row = odbc_fetch_array($resultado)) 
	{
		$items++;                           
	}
	//echo "items $items ";
	return $items;
}

function trasformData($codigo)
{
	//echo "<br/><br/>$codigo<br/>";
	$aux = $codigo;
	
	$lastPos = 0; 
	while(strpos($aux,"#") != 0 && strpos($aux,"#") != $lastPos)
	{
		$lastPos = strpos($aux,"#");
		$aux = $this->trasformDateTime($aux);
	}
	
	$aux = str_replace("FALSE","'FALSE'",$aux);
	$aux = str_replace("False","'False'",$aux);
	$aux = str_replace("false","'false'",$aux);
	$aux = str_replace("TRUE","'TRUE'",$aux);
	$aux = str_replace("True","'True'",$aux);
	$aux = str_replace("true","'true'",$aux);
	
	$fields = explode(",",$aux);
	foreach ($fields as $field)
	{
		//echo "<br/>$field<br/>".(strcasecmp("false",$field) == 0).(strcasecmp("true",$field) == 0);
		/*if(strcasecmp("false",$field) == 0)
		{
			$aux = str_replace($field,"'FALSE'",$aux);
		}		
		if(strcasecmp("true",$field) == 0)
		{
			$aux = str_replace($field,"'TRUE'",$aux);
		}*/
		
		if(strlen($field) == 12 && strpos($field,'-') != 0)
		{
			$aux = str_replace($field,str_replace("-","",$field),$aux);
		}
	}
		
	if(strpos($aux,"Into") != 0 || strpos($aux,"INTO") != 0 || strpos($aux,"into") != 0)
	{
		$aux = $this->trasformInsert($aux);
	}
	//echo "<br/><br/>$aux";
	
	return $aux;
}

function trasformDateTime($text)
{
	if(strpos($text,"#") != 0)
	{
		$pos = strpos($text,"#")+1;
		$aux = substr($text,$pos);
		if(strpos($aux,"#") != 0)
		{
			$pos2 = strpos($aux,"#");
			$text2 = substr($text,$pos,$pos2);
			$text3 = substr($text,$pos-1,$pos2+2);
			$text4 = "'".str_replace('-','',$text2)."'";
			$result = str_replace($text3,$text4,$text);
						
			return $result;
		}
	}
	
	return $text;
}

function trasformDate($text)
{
	if(strpos($text,"-") != 0)
	{
		$pos = strpos($text,"-");
		$aux = substr($text,$pos);
		if(strpos($aux,"-") != 0)
		{
			$pos2 = strpos($aux,"#");
			$text2 = substr($text,$pos,$pos2);
			$text3 = substr($text,$pos-1,$pos2+2);
			$text4 = "'".str_replace('-','',$text2)."'";
			$result = str_replace($text3,$text4,$text);
						
			return $result;
		}
	}
	
	return $text;
}

function trasformInsert($insert)
{
	$index = strpos($insert,"(");
	$table = substr($insert,12,$index-13);
	$field = $this->mapTable[$table];
	//echo "============ $table, $field";
	// var_dump($table);
	// var_dump($field);
		
	if($field)
	{
		$maxIdSql = "SELECT Max($field) as maximo FROM $table";
		$result = odbc_exec($this->cn, $maxIdSql);
		$maxId   = odbc_result($result, "maximo")+1;
		//echo "============ $table, $field, $maxId";
		$insert = str_replace("$table(","$table($field, ",$insert);
		$insert = str_replace("$table (","$table ($field, ",$insert);
		$insert = str_replace("Values(","Values($maxId, ",$insert);
		$insert = str_replace("Values (","Values ($maxId, ",$insert);
		$insert = str_replace("VALUES (","VALUES ($maxId, ",$insert);
		$insert = str_replace("VALUES(","VALUES($maxId, ",$insert);
	}
	
	return $insert;
}

}
?>