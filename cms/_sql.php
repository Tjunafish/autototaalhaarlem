<?
class sql {
	
	public static $DEBUG;
	
	static function connect($host,$user,$pass,$db){
		mysql_connect($host,$user,$pass) or die("Could not establish MySQL connection");
		mysql_select_db($db) or die("Could not establish database connection");
	
	}
	
	static function num($table,$conditions="",$override=false){
	
		$conditions = preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$conditions),1);
		$query 		= "SELECT * FROM `".$table."` ".$conditions;
		
		if($override)
			$result = mysql_query($override);
		else
			$result = mysql_query($query);
		
		if(self::$DEBUG == true)	
			echo ($override == "")? "[DEBUG (sql->num): ".$query."]" :
									"[DEBUG (sql->num]) OVERRIDE: ".$override."]";

		return mysql_num_rows($result);
	
	}
	
	static function fetch($type,$table,$conditions="",$override=false){
				
		$conditions = preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$conditions),1);	
		$query 		= "SELECT * FROM `".$table."` ".$conditions;		
	
		
		$result 	= mysql_query($query);
		
		if($override)
			$result = mysql_query(preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$override),1));
		
		$return = array();
		
		switch($type){
		
			case "array":
			while($row = mysql_fetch_array($result))
				$return[] = $row;	
			break;
			
			case "assoc":
			while($row = mysql_fetch_assoc($result))
				$return[] = $row;	
			break;
			
			case "object":
			$return = mysql_fetch_object($result);
			break;
			
			case "row":
			$return = mysql_fetch_row($result);
			break;
			

		}
		
		if(self::$DEBUG == true)	
			echo ($override == "")? "[DEBUG (sql->fetch[".$type."]): ".$query."]" :
									"[DEBUG (sql->fetch[".$type."]) OVERRIDE: ".$override."]";
		
		return $return;
	
	}
	
	static function insert($table,$info){
	
		$query = "INSERT INTO `".$table."`";
		
		foreach($info as $title => $value){
			
			$titles .= "`".$title."`,";
			$values .= "'".self::escape($value)."',";
			
		}
		
		$titles = substr($titles,0,-1);
		$values = substr($values,0,-1);
		
		$query .= "(".$titles.") VALUES (".$values.")";
		
		mysql_query($query);
		
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->insert[".$table."]): ".$query."]";
	
	}
	
	static function update($table,$info,$conditions=""){
		
		$conditions = preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$conditions),1);
		
		$query = "UPDATE `".$table."` SET";
		
		foreach($info as $title => $value)			
			$query .= " `".$title."` = '".self::escape($value)."',";
		
		$query = substr($query,0,-1)." ".$conditions;
		
		mysql_query($query);
		
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->update[".$table."]): ".$query."]";
	
	}
	
	static function exists($table,$info,$case=false){
	
		$query = "SELECT * FROM `".$table."` WHERE";
		
		foreach($info as $title => $value)	
			$query .= " `".$title."` = ".
					  ($case ? 'binary ' : '').
					  "'".self::escape($value)."' &&";

		$query = substr($query,0,-3);
		
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->exists[".$table."]): ".$query."]";
		
		if(mysql_num_rows(mysql_query($query)) > 0)
			return true;
		else
			return false;		
	
	}
	
	static function del($table,$info){
	
		$query = "DELETE FROM `".$table."` WHERE";
		
		foreach($info as $title => $value)		
			$query .= " `".$title."` = '".self::escape($value)."' &&";

		$query = substr($query,0,-3);
		
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->del[".$table."]): ".$query."]";
		
		mysql_query($query);
	
	}
	
	static function escape($string){
		
		if(get_magic_quotes_gpc()) 
			$string = stripslashes($string); 
		
		return mysql_real_escape_string($string);
	
	}
	
	static function getColumns($table){
			
		$query = 'SHOW FULL COLUMNS FROM `'.$table.'`';
   		$result = mysql_query($query);
    	while($row = mysql_fetch_array($result))
			$return[$row['Field']] = $row;	
		return $return;
		
	}
	
}
?>
