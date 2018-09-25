<?php 
require("classes/cls.constants.php"); include("classes/cls.paths.php"); 

/* ============================================================================== 
/*	SPAM BLOCK! 
/* ------------------------------------------------------------------------------ */
//if($_SERVER['REQUEST_METHOD'] !== 'POST') {  echo 'invalid request'; exit; }

/* ============================================================================== */

$cat_id 	= (array_key_exists('cat_id', $request)) ? $request['cat_id'] : 24; 
$cum_total	= '';
$sel_ops 	= array_map("clean_request", $_POST);
//$sel_ops 	= $request;

//displayArray($sel_ops);

/*   
$cont_sector_id = (array_key_exists('fsec', $request)) ? $request['fsec'] : '';*/


$dt_search_combo = $dispDt->dg_search_combo($cat_id, $cum_total, $sel_ops);
//$dt_story_farm = $dispDt->dg_story_farm_crop_plain($cat_id, $cum_total, $sel_ops);

//displayArray($dt_story_farm);


//displayArray($dt_search_combo);
$tb_search_combo = autoTable($dt_search_combo);
echo $tb_search_combo;
?>

