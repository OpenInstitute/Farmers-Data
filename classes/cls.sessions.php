<?php
session_cache_limiter('private');
$cache_limiter = @session_cache_limiter();
session_cache_expire(1);
$cache_expire = @session_cache_expire();
session_start();
//ini_set("display_errors", "off");
date_default_timezone_set('Africa/Nairobi');

define('DOMAIN_NAME', 		"openinstitute.com" );
define('SITE_TITLE_SHORT',  "GGLI Data" );


define('THIS_SITE_ORG', 	  "Open Institute GGLI Data" );
define('THIS_SITE_TITLE',   "Open Institute GGLI Data" );
define('THIS_SITE_NAME',    "eplatform/me/" );	

define('THIS_DOMAIN', 	  "http://".$_SERVER['HTTP_HOST']."/".THIS_SITE_NAME );	
define('SITE_PATH', 		$_SERVER['DOCUMENT_ROOT']."/".THIS_SITE_NAME);	
define('SITE_LOGO', 		THIS_DOMAIN ."image/logo_act.png");

define('SITE_MAIL_TO_BASIC', 	 'portal.me@'.DOMAIN_NAME.'' ); 
define('SITE_MAIL_FROM_BASIC',  'no-reply@'.DOMAIN_NAME.'' ); 


define('UPL_PROJECT', 	 SITE_PATH ."file/uploads/"); 
define('DISP_PROJECT', 	THIS_DOMAIN ."file/uploads/");
define('UPL_AVATARS', 	 SITE_PATH ."image/avatars/"); 
define('DISP_AVATARS', 	THIS_DOMAIN ."image/avatars/");

define('GALLTHMB_WIDTH', 70);
define('GALLTHMB_HEIGHT', 50);

define('GALLIMG_WIDTH', 700);
define('GALLIMG_HEIGHT', 500);



define('CONST_NOTAVAILABLE', '<span class="hint">Not Available</span>');

/******************************************************************
@begin :: SESSIONS
********************************************************************/	

	$us_link_profile = '';
	$staff_name  = '';
	$us_id	   = '';
	$us_name	 = '';
	$us_email 	= '';
	$us_type_id  = '';
	$us_type  	 = '';
	$us_staff  	 = '';
	$u_id_partner  	 = '';
	
	$sess_checker = time();
	$thisPage	 = substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],"/" )+1);
	
if (!empty($_SESSION['exp_member'])) 
{ 
	$sess_expire 	 = $_SESSION['exp_member']['expires'];
	
	/*if($sess_checker >= $sess_expire)
	{
		if( $thisPage <> "amr_posts.php" ) {
		echo '<script type="text/javascript">location.href="'.THIS_DOMAIN.'amr_posts.php?signout=on";</script>'; exit;
		}
	}*/
		
	$us_id		= $_SESSION['exp_member']['u_id'];
	$us_fname		= $_SESSION['exp_member']['u_fname'];
	$us_lname		= $_SESSION['exp_member']['u_lname'];
	//$us_name		= $_SESSION['exp_member']['u_name'];
	$us_email 	= $_SESSION['exp_member']['u_email'];
	$us_type_id 	= $_SESSION['exp_member']['u_type_id'];
	$us_type  	= $_SESSION['exp_member']['u_type'];
	$u_id_partner  	= $_SESSION['exp_member']['u_id_partner'];
	$us_staff  	= $_SESSION['exp_member']['u_staff'];
	
	
	$us_lname_initial = '';
	if(trim($us_lname) <> '') { $us_lname_initial = ' ' . strtoupper(substr($us_lname,0,1)).'.'; }
	$us_name 	   = $us_fname; // . $us_lname_initial;
	
	$us_type_label = '<span class="txt11 txtbluelight"> &rsaquo; '.$us_type.' </span>';
	/*$us_name_long 	= preg_split("/ /", $us_name);
	if(strlen($us_name_long[0]) > 3) {	$us_name_first	= $us_name_long[0]; }
	if(strlen($us_name_long[1]) > 3) {	$us_name_last	= $us_name_long[1]; }*/

	
	$us_lbl_profile  = '<b>'.$us_name.'</b>  ';	
	$us_lnk_home = 'client.php?op=list&order_lt=recent';
	$us_link_profile = '<a href="#">'.$us_lbl_profile.'</a>  &nbsp;&iota;&nbsp;  <a href="'.THIS_DOMAIN.'amr_posts.php?signout=on"  style="color:#Fc0;"><b>Log Out</b></a>';
	
	
	
}



?>