<?
if(get_magic_quotes_runtime()){
    
	echo 'Turn magic quotes off before proceeding.';
    return;

}

// --------------------------------------
// _sql.php
// sql interaction class
// --------------------------------------
 
class sql {
 
	// Set DEBUG to true to return queries used
	public static $DEBUG = false;
	public static $host;
	public static $user;
	public static $pass;
	public static $db;
 
	public static $select = "SELECT *";
 
	// Connect, select database and set charset
	static function connect(){

		if(!self::$host || !self::$user || !self::$pass || !self::$db)
			die("MySQL connection data missing.");
 
		mysql_connect(self::$host,self::$user,self::$pass) 
					 or die("Could not establish <b>MySQL</b> connection");
 
		mysql_select_db(self::$db) 
					 or die("Could not establish <b>database</b> connection");
 
		mysql_set_charset('utf8');
 
	}
 
	// Fix conditional string for duplicate "WHERE" or starting "&&"
	static function clean_where($input){
 
		return preg_replace("/&&/","WHERE",str_replace("WHERE","&&",$input),1);
 
	}
 
	// Reset select
	static function reset_select(){
 
		self::$select = "SELECT *";
 
	}
 
	// Get number of rows from $table with $conditions
	static function num($table,$conditions=""){
 
		$query 		= self::$select." FROM `".$table."` ".self::clean_where($conditions);
		$result 	= mysql_query($query);
 
		if(self::$DEBUG == true)		
			echo "[DEBUG (sql->num[".$type."]): ".$query."]";
 
		return mysql_num_rows($result);
 
	}
 
	// Return array, assoc, object or row from $table with $conditions or a fresh query with $override
	static function fetch($type,$table,$conditions="",$override=""){
 
		$query 		= self::$select." FROM `".$table."` ".self::clean_where($conditions);		
		$result 	= mysql_query($query);
 
		if($override != "")
			$result = mysql_query(self::clean_where($override));
 
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
			if($override == "")
				echo "[DEBUG (sql->fetch[".$type."]): ".$query."]";
			else
				echo "[DEBUG (sql->fetch[".$type."]) OVERRIDE: ".self::clean_where($override)."]";
 
		return $return;
 
	}
 
	// Insert $info [array($field=>$value)] into $table
	static function insert($table,$info){
 
		$query = "INSERT INTO `".$table."`";
 
		foreach($info as $title => $value){
 
			$titles .= "`".$title."`,";
			$values .= "'".self::escape($value)."',";
 
		}
 
		$titles = substr($titles,0,-1);
		$values = substr($values,0,-1);
 
		$query .= "(".$titles.") VALUES (".$values.")";
 
		mysql_query(self::clean_where($query));
 
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->insert[".$table."]): ".$query."]";
 
	}
 
	// Update $table with $info [array($field=>$value)] where $conditions
	static function update($table,$info,$conditions=""){
 
		$query = "UPDATE `".$table."` SET";
 
		foreach($info as $title => $value)			
			$query .= " `".$title."` = '".self::escape($value)."',";
 
		$query  = substr($query,0,-1);		
		$query .= " ".self::clean_where($conditions);
 
		mysql_query($query);
 
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->update[".$table."]): ".$query."]";
 
	}
 
	// Does a field with $info [array($field=>$value)] exist in $table?
	static function exists($table,$info){
 
		$query = "SELECT * FROM `".$table."` WHERE";
 
		foreach($info as $title => $value)		
			$query .= " `".$title."` = '".self::escape($value)."' &&";
 
		$query = substr($query,0,-3);
 
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->exists[".$table."]): ".$query."]";
 
		if(mysql_num_rows(mysql_query($query)) > 0)
			return true;
		else
			return false;		
 
	}
 
	// Delete field with $info [array($field=>$value)] from $table
	static function del($table,$info){
 
		$query = "DELETE FROM `".$table."` WHERE";
 
		foreach($info as $title => $value)		
			$query .= " `".$title."` = '".self::escape($value)."' &&";
 
		$query = substr($query,0,-3);
 
		if(self::$DEBUG == true)			
			echo "[DEBUG (sql->del[".$table."]): ".$query."]";
 
		mysql_query($query);
 
	}
 
	// Escape $string
	static function escape($string){
 
		return mysql_real_escape_string($string);
 
	}
 
	// Get field information from $table
	static function get_columns($table){
 
		$query = 'SHOW FULL COLUMNS FROM `'.$table.'`';
   		$result = mysql_query($query);
 
    	while($row = mysql_fetch_array($result))
			$return[$row['Field']] = $row;	
 
		return $return;
 
	}
 
	// Turn debugging on or off
	static function toggle_debug(){
 
		if(self::$DEBUG == true)
			self::$DEBUG = false;
		else
			self::$DEBUG = true;
 
	}
 
}
?>