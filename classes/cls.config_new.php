<?php

/*@session_start();
@session_cache_limiter('private');
$cache_limiter = @session_cache_limiter();

@session_cache_expire(15);
$cache_expire = @session_cache_expire();
require_once('cls.condb.php');*/

$dbhost 		= 'localhost';
	
if ($_SERVER['HTTP_HOST'] == 'localhost') {

	$dbname 		= ''; 
	$dbusername 	= '';
	$dbuserpass 	= '';

 }
else{
	$dbname 		= ''; 
	$dbusername 	= '';
	$dbuserpass 	= '';
}



define('DB_HOST', 	   $dbhost);
define('DB_CHARSET', 	'utf8');
define('DB_NAME', 	   $dbname);	
define('DB_USER',      $dbusername);
define('DB_PASSWORD',  $dbuserpass);


define('DT_TABLE_EXCEL', 'dta_excel');

class master
{
  	public static $dbconn;
	public $result, $sql, $table_prefix, $tstart, $executedQueries, $queryTime, $dumpSQL, $queryCode;
	
	public static $menuBundle 	 = array();
	
	public static $contBundle 	= array();
	
	public static $listGallery = array();
	public static $listProfiles = array();
	
	public static $listResources	= array();
	public static $listRegions 		 = array();
	public static $listCounties 	    = array();
	
	 
	public function __construct() /*master*/
	{
		global $dbhost, $dbuser, $dbpassword, $dbname;
		$this->dbconfig['dbhost'] = DB_HOST; //$dbhost;
		$this->dbconfig['dbname'] = DB_NAME; //$dbname;
		$this->dbconfig['dbuser'] = DB_USER; //$dbuser;
		$this->dbconfig['dbpass'] = DB_PASSWORD; //$dbpassword;
	}
 
	private function destruct__ (){ //unset
		//unset ($this);
	}
 
  	public function getMicroTime() {
     list($usec, $sec) = explode(" ", microtime());
     return ((float)$usec + (float)$sec);
  	}

  	private function dbConnect() {
		$tstart = $this->getMicroTime();
		if(!isset(self::$dbconn)) {
			self::$dbconn = mysqli_connect($this->dbconfig['dbhost'], $this->dbconfig['dbuser'], $this->dbconfig['dbpass'], $this->dbconfig['dbname']) or die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		
		if(self::$dbconn === false) {
			die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		
		$tend = $this->getMicroTime();
		$totaltime = $tend-$tstart;
		if($this->dumpSQL) {
			$this->queryCode .= sprintf("Database connection was created in %2.4f s", $totaltime)."";
		}
		$this->queryTime = $this->queryTime+$totaltime;
		
		return self::$dbconn;
  	}


  	public function dbQuery($query) {
	  
		if(empty(self::$dbconn)) { $this->dbConnect(); } 
		$tstart = $this->getMicroTime();
		
		if(@!$result = mysqli_query(self::$dbconn, $query)) {
		  die("Execution of a query to the database failed. " .mysqli_error(self::$dbconn));
		}
		else {
		  $tend = $this->getMicroTime();
		  $totaltime = $tend-$tstart;
		  $this->queryTime = $this->queryTime+$totaltime;
		  $this->executedQueries = $this->executedQueries+1; 
		  
          $res_type = is_resource($result) ? get_resource_type($result) : gettype($result);
            
          if($res_type == 'object') {
			return $result;
		  } elseif($res_type == 'boolean') {
			return true;
		  } else {
			return false;
		  }
		}
  	}
  
  	public function dbQueryFetch($query, $key='') {
	  $rows = array();
	  $rs   = $this->dbQuery($query);
	  if($rs === false) { return false; }
	  
	  while ($row = mysqli_fetch_assoc($rs)) {
		$row_clean = array_map("clean_output", $row);
		if($key<>''){
			$tb_key 	= $row_clean[''.trim($key).''];
			$rows[$tb_key] = $row_clean;
		} else {
	    	$rows[] = $row_clean;
		}
	  }
	  return $rows;
  	}
	
  	public function dbQueryMulti($query) {
		foreach($query as $seq_post){
			$result = $this->dbQuery($seq_post);
		}
  	}
		
  	public function recordCount($rs) {
    	return mysqli_num_rows($rs);
  	}

  	public function fetchRow($rs, $mode='both') {
		if(($mode=='both') || ($mode == '')) {
		  return mysqli_fetch_array($rs, MYSQLI_BOTH);
		} elseif($mode=='num') {
		  return mysqli_fetch_row($rs);
		} elseif($mode=='assoc') {
		  return mysqli_fetch_assoc($rs);
		}
		else {
		  die("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
		}
  	}
  
 	public function affectedRows($rs) {
    	return mysqli_affected_rows(self::$dbconn);
  	}
 
	/*public function quote_si($value) {
		$connection = $this->dbConnect();
		if (is_array($value)) { $value = serialize($value); }
		$value = "'" . mysqli_real_escape_string($connection, $value) . "'";
		return $value;
	}*/
	
	public function quote_si($value, $uselike = 0) {
		$connection = $this->dbConnect();
		if (is_array($value)) { $value = serialize($value); }
		
		$likehash = "";
		if($uselike == 1) { $likehash = "%"; }
		$value = "'$likehash" . mysqli_real_escape_string($connection, $value) . "$likehash'";
		return $value;
	}
	
  	public function insertId($rs='') {
    return mysqli_insert_id(self::$dbconn);
  	}
 
  	public function errorNo() {
		$connection = $this->dbConnect();
		return mysqli_errno($connection);
  	}
  
	public function error() {
		$connection = $this->dbConnect();
		return mysqli_error($connection);
	}
	
  	public function freeResult($resultset) {
    	return mysqli_free_result($resultset);
  	}
 
  	public function serverVersion() {
    	return mysqli_get_server_info(self::$dbconn);
  	}
 
  	public function dbClose() {
		if(self::$dbconn) {
		  mysqli_close(self::$dbconn);
		}
  	}
  
  	public function tableStatus($tbname) {
		$sq = "SHOW TABLE STATUS LIKE ".$this->quote_si($tbname)."; ";
		$rs = current($this->dbQueryFetch($sq));
    	return $rs;
  	}
	
	
    /* MySQLi - Field Type to Text */
  	public static function fieldTypeText($type_id)
	{
		static $types;	
		if (!isset($types))
		{
			$types = array();
			$constants = get_defined_constants(true);
			foreach ($constants['mysqli'] as $c => $n) if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) $types[$n] = $m[1];
		}
	
		return array_key_exists($type_id, $types)? $types[$type_id] : NULL;
	}
 
 
 	/* MySQLi - Field Flag to Text */
	public static function fieldFlagText($flags_num)
	{
		static $flags;
	
		if (!isset($flags))
		{
			$flags = array();
			$constants = get_defined_constants(true);
			foreach ($constants['mysqli'] as $c => $n) if (preg_match('/MYSQLI_(.*)_FLAG$/', $c, $m)) if (!array_key_exists($n, $flags)) $flags[$n] = $m[1];
		}
	
		$result = array();
		foreach ($flags as $n => $t) if ($flags_num & $n) $result[] = $t;
		return implode(' ', $result);
	}



 
/* end class */

}




$cndb = new master();
$cndb->dumpSQL = true; /* boolean */

$request = array_map("clean_request", $_GET);
$cat_id = (isset($request['cat_id'])) ? $request['cat_id'] : 24; 
$loc_id = (isset($request['loc_id'])) ? $request['loc_id'] : ''; 



$adminConfig = array(
	'SITE_ALIAS' 	  => "",
	'SITE_FOLDER' 	  => "",
	'SITE_TITLE_LONG'  => "",	
	'SITE_TITLE_SHORT' => "",
	'SITE_DOMAIN_URI'  => "",
	'SITE_MAIL_SENDER' => "",
	'SITE_MAIL_TO_BASIC' => "",
	'SITE_MAIL_FROM_BASIC' => "",
	'SITE_LOGO' => "image/logo.png",
	'COLOR_BG_SITE' => "#FBF2DF",
	'COLOR_BG_HEADER' => "#FFF",
	'upload_max_filesize' => "5",
	'GALLTHMB_WIDTH' => "250",
	'GALLTHMB_HEIGHT' => "150",
	'GALLIMG_WIDTH' => "1200",
	'GALLIMG_HEIGHT' => "900",
	
	'SOCIAL_ID_FACEBOOK' => "",
	'SOCIAL_ID_TWITTER' => "",
	'SOCIAL_ID_TWITTER_WIDGET' => "",
	'SOCIAL_ID_YOUTUBE' => "#",
	'SOCIAL_ID_LINKEDIN' => "#",
	'SOCIAL_ID_GOOGLE' => "#",
	
	'_lists_date_format' => "%b %e %Y",
	'_lists_time_format' => "%l:%i %p",
	'MySQLDateFormat' => "%m/%d/%Y",
	'PHPDateFormat' => "n/j/Y",
	'PHPDateTimeFormat' => "m/d/Y, h:i a"
	
	,'ADM_STYLE_BG' => '#00C0CC'
);

if($_SERVER['HTTP_HOST'] == "localhost:8080") { 
	$adminConfig['SITE_FOLDER'] = "";
}


if($_SERVER['HTTP_HOST'] == "localhost:8080") { 
	$GLOBALS['SOCIAL_CONNECT']  = true; 
	$GLOBALS['NOTIFY_DEBUG']    = '1';
	$GLOBALS['NOTIFY_SUPPLIER'] = false;
	
	define('SITE_FOLDER', $adminConfig['SITE_FOLDER'].'/'  );	
	$domain_url 	 = $_SERVER['HTTP_HOST'].$adminConfig['SITE_ALIAS'].'/'; 	
	$domain_root    = $_SERVER['CONTEXT_DOCUMENT_ROOT'].SITE_FOLDER; //$_SERVER['DOCUMENT_ROOT']
} 
else{
	$GLOBALS['SOCIAL_CONNECT']  = true; 
	$GLOBALS['NOTIFY_DEBUG']    = '';
	$GLOBALS['NOTIFY_SUPPLIER'] = false;
	
	$domain_folder = ($adminConfig['SITE_FOLDER'] <> "") ? $adminConfig['SITE_FOLDER'].'/' : $adminConfig['SITE_FOLDER'];
	define('SITE_FOLDER', '/'.$domain_folder  );	
	$domain_url 	 = $_SERVER['HTTP_HOST']; 	
	$domain_root    = $_SERVER['DOCUMENT_ROOT'].SITE_FOLDER; 
}

$GLOBALS['PAGE_HAS_TABS'] 	 	    = false;
$GLOBALS['CONTENT_HAS_GALL'] 	 	 = false;
$GLOBALS['CONTENT_HAS_TABLE'] 		= false;
$GLOBALS['FORM_HAS_MASK'] 			= false;
$GLOBALS['EXISTS_MAILING_ACCOUNT']   = false;

$GLOBALS['FORM_MULTISELECT'] 		 = false;
$GLOBALS['FORM_MULTISELECT_LABEL']   = "";
$GLOBALS['FORM_JWYSWYG'] 		     = false;
$GLOBALS['CONTENT_SHOW_CALENDAR'] 	= false;

$adm_portal_id = 1; $pdb_prefix = 'admk_';
$GLOBALS['ADM_PT_PREFIX'] 	  = $pdb_prefix;
$GLOBALS['SYS_CONF'] 		   = $adminConfig;



$my_page_head=''; $my_alias_h1=''; $my_alias_h2=''; $cont_alias=''; $showContent='';
		
$ref_path  	= 	$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$ref_path  	= 	substr($ref_path,0,strrpos($ref_path,"/")); 
$ref_page  	= 	substr($_SERVER['REQUEST_URI'],strripos($_SERVER['REQUEST_URI'],"/" )+1);
$ref_ip	  = 	$_SERVER['REMOTE_ADDR'];
$this_page   = 	substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],"/" )+1);

$ref_qrystr  = "?" . $_SERVER['QUERY_STRING'];				

define('REF_PAGE', $ref_page );
define('REF_QSTR', $ref_qrystr );

define('SITE_DOMAIN_LIVE', 	   "http://".$_SERVER['HTTP_HOST'].SITE_FOLDER );	




$sec_ages = array(
	'18_40' 	=> 'between 18 to 40 years',
	'41_60' 	=> 'between 41 to 60 years',
	'61_plus' 	=> 'More than 60 years'
);

/* @@murage Edit : 20180809 */
$sec_ages_two = array(
	'hobby' 	=> 'between 18 to 39 years',
	'golden' 	=> 'between 40 to 55 years',
	'grand' 	=> 'More than 55 years'
);


?>
