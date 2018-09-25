<?php
ini_set("display_errors", "off"); 
//ini_set("short_open_tag", "off");
require_once('cls.formats.php');
require('cls.sessions.php');

require('cls.config_new.php');	

/*require_once('cls.data.ggli.php');*/
require_once('cls.data.ggli_excel.php');

/*require_once('cls.data.themes.php');
require_once('cls.data.php');
require_once('cls.datareports.php');
require('cls.displays.php');*/	
require('cls.select.php');
require('cls.post.php');

require("cls.functions_misc.php"); 



if (isset($_REQUEST['pg'])){$pg=$_REQUEST['pg']; } else { $pg='dashboard';}

if (isset($_REQUEST['d'])){$dir=$_REQUEST['d']; } else {$dir=$pg;}

if(isset($_REQUEST['qst']) and is_numeric($_REQUEST['qst'])) {$qst=$_REQUEST['qst'];} else {$qst=NULL;}
if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])) {$id=$_REQUEST['id'];} else {$id=NULL;}

if(isset($_REQUEST['op'])) {$op=$_REQUEST['op'];} else {$op="list";}

if(isset($_REQUEST['staff_id']) and is_numeric($_REQUEST['staff_id'])) {$staff_id=$_REQUEST['staff_id'];} else {$staff_id= '';}
if($us_type_id == 4 or $us_type_id == 5)  { $staff_id	= $us_id; }

if(isset($_REQUEST['dur_id'])) {$dur_id = $_REQUEST['dur_id'];} else {$dur_id= '';}
if(isset($_REQUEST['month'])) {$dur_month = $_REQUEST['month'];} else {$dur_month= '';}

if(isset($_REQUEST['rsa_id']) and is_numeric($_REQUEST['rsa_id'])) {$rsa_id=$_REQUEST['rsa_id'];} else {$rsa_id= '';}
if(isset($_REQUEST['repsub_parent'])) {$repsub_parent = $_REQUEST['repsub_parent'];} else {$repsub_parent= '';}
if(isset($_REQUEST['acti_id']) and is_numeric($_REQUEST['acti_id'])) {$acti_id=$_REQUEST['acti_id'];} else {$acti_id=NULL;}

if(isset($_REQUEST['entry_type']) and is_numeric($_REQUEST['entry_type'])) {$entry_type=$_REQUEST['entry_type'];} else {$entry_type=NULL;}

if(isset($_REQUEST['rsess'])) {$rsess = $_REQUEST['rsess'];} else {$rsess = '';}

if(isset($_REQUEST['thm'])) {$thm = $_REQUEST['thm'];} else {$thm = '';}
if(isset($_REQUEST['theme']) and is_numeric($_REQUEST['theme'])) {$theme=$_REQUEST['theme'];} else {$theme='';}

if(isset($_REQUEST['theme_conf']) and is_numeric($_REQUEST['theme_conf'])) {
	$theme_conf=$_REQUEST['theme_conf'];
	/*$_SESSION['AFP_THEME_CONF'] = $theme_conf;*/
} else { 
	/*if(isset($_SESSION['AFP_THEME_CONF'])) { $theme_conf = $_SESSION['AFP_THEME_CONF']; } else {}*/ $theme_conf=1;
}

if(isset($_REQUEST['indic_id']) and is_numeric($_REQUEST['indic_id'])) {$indic_id=$_REQUEST['indic_id'];} else {$indic_id= '';}
if(isset($_REQUEST['rpt_id'])) {$rpt_id=$_REQUEST['rpt_id'];} else {$rpt_id= '';}


$ref_back = 'index.php'; 
if(isset($_SERVER['HTTP_REFERER'])){
	$ref_back 	= str_replace(THIS_DOMAIN, "", $_SERVER['HTTP_REFERER']); 
	
}

$ref_path  	= substr($_SERVER['REQUEST_URI'],strripos($_SERVER['REQUEST_URI'],"/" )+1);
$ref_page	= substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],"/" )+1);
$ref_qstr 	= "?" . $_SERVER['QUERY_STRING'];
$this_page   = $ref_page;
//echo $ref_back;


//$ddSelect	= new drop_downs;




//$dropGroups = $ddSelect->getTargetGroups();

if($this_page <> 'dashone.php')
{
	
}


if (isset($_SESSION['exp_member'])) 
{ 
	//$usacc_rights = $dispData->build_RoleRights($us_type_id);
	//$dispData->user_rights = $usacc_rights;
	
	$usacc_rights = $_SESSION['exp_member']['u_rights'];
	
	if(isset($_REQUEST['project_id']) and is_numeric($_REQUEST['project_id'])) 
	{
		$project_id = $_REQUEST['project_id'];
		if (!isset($_COOKIE['_act']['me'][$us_id])) 
		{
		$exp_hour = time()+ (60*60*24*7);
		setcookie("_act[me][".$us_id."][project_id]", $project_id, $exp_hour, THIS_SITE_NAME);
		}
	} 
	else 
	{ 	
		//displayArray($_COOKIE['_act']['me'][$us_id]['project_id']);
		if (isset($_COOKIE['_act']['me'][$us_id])) 
		{  $project_id = $_COOKIE['_act']['me'][$us_id]['project_id']; }
		else
		{  $project_id = '';  } 
	}
	
	
	
	
	
	//echo $project_id; exit;
	if($project_id <> '')
	{
		if($u_id_partner <> 1)
		{
		$proj_authorized   = $ddSelect->getProjectAuthorized($project_id, $u_id_partner);
		if($proj_authorized == 0)
		{
			//header("Location: index.php?qst=28");
		}
		}
		
		$dynaData->getResources($project_id);
		 
		
		
		$proj_summary   = $ddSelect->getProjectBrief($project_id);
		
		$_SESSION['project_summary'] = $proj_summary;
		
		$project_name 		 = $proj_summary['p_name'];
		$project_description  = $proj_summary['p_description'];
		$project_day_num 	  = $proj_summary['p_days_num'];
		$project_day_today 	= $proj_summary['p_days_leo'];
		$project_index 		= $proj_summary['p_index'];
		$project_resareas 	 = $proj_summary['p_resultareas'];
		$project_acts_num 	 = $proj_summary['p_activities_num'];
		$project_acts_with_rpt 	= $proj_summary['p_activities_with_report'];
		$project_acts_no_rpt	 = $proj_summary['p_activities_no_report'];
		$project_outcomes	 = $proj_summary['p_outcomes'];
		$project_targets	  = $proj_summary['p_targets'];
		$project_targets_summary	  = $proj_summary['p_targets_summary'];
		$project_start	  = $proj_summary['p_date_start'];
		$project_finish	  = $proj_summary['p_date_end'];
		$project_locations	  = $proj_summary['p_locations'];	
		
		$project_start_year	  = $proj_summary['p_start_year'];
		$project_start_month	  = $proj_summary['p_start_month'];
		
		
		$target_first = current($project_targets);
		$target_second = next($project_targets);
		
		$highest_act_id    = '';
		$highest_act_name  = '';
		$highest_act_category  = '';
		$highest_group_name  = '';
		$highest_group_total  = '';
			
		$highest_target   = $ddSelect->getHighestMetTarget($project_id);
		if(is_array($highest_target))
		{
			$highest_act_id    = $highest_target['act_id'];
			$highest_act_name  = $highest_target['act_title'];
			$highest_act_category  = $highest_target['act_category'];
			$highest_group_name  = $highest_target['act_target_group'];
			$highest_group_total  = $highest_target['act_target_num'];
		}
		
		
		
		$officer_target_numbers   = $ddSelect->getMemberTargetsTotals($project_id, $us_id); 
		//displayArray($officer_target_numbers); //exit;
		$officer_target_highest   = $ddSelect->getMemberTargetsHighest($project_id, $us_id);
		//displayArray($officer_target_highest); exit;
		
		$highest_member_perc = 0;
		$highest_member_act_one = ' __ ';
		$highest_member_act_two = ' __ ';
		
		
		if( is_array($officer_target_numbers) and array_key_exists('perc', $officer_target_numbers))
			{
			$highest_member_perc  = $officer_target_numbers['p_targets_summary']['perc']; 
			}
		
		if(is_array($officer_target_highest)){
		$highest_member_act_one  = $officer_target_highest[0]['act_title'] .' ('.$officer_target_highest[0]['act_target_group'].' - '.$officer_target_highest[0]['act_target_num'].')';
		$highest_member_act_two  = ''; //$officer_target_highest[1]['act_title'] .' ('.$officer_target_highest[1]['act_target_group'].' - '.$officer_target_highest[1]['act_target_num'].')';
		}
		
	}

}



//displayArray($_SESSION);
//exit;

$CONF_ME['wp_types'] = array(1=> 'Outcome', 2=>'Output', 4=>'Activity (Primary)', 5=>'Activity (Secondary)');
$CONF_ME['rpt_fieldtypes'] = $ddSelect->dropper_cat_type(); 
	


function isSerialized($value)
{
   return preg_match('^([adObis]:|N;)^', $value);
}


function autoLower($str){
	if(is_array($str)) { 
		return array_map("autoLower",$str);
	}
	elseif(strlen($str) > 0){ 
		$str =strtolower($str);
	}
	return $str;
}

function autoTableBlanks($val, $groupa = '', $groupa_val = ''){ //displayArray($val); //exit;
	$out = ''; //'<td>&nbsp;</td>';
	//$keys = (is_array($val) ) ? count($val)-1 : 1;
	
	if(is_array($val) ){
		$out = '<td>&nbsp;</td>';
		$keys = array_keys($val);

		foreach($keys as $v){
			//$out_cell = ($v == $groupa) ? $groupa_val : '&nbsp;';
			$out_cell = $groupa_val;
			if($v <> 'records'){
			$out .= '<td>'. $out_cell .'</td>';
			}
		}
	}
	
	return $out;
}

function autoTable($arrVal, $grouper = 'location'){
	
	$col_keys 		= array_keys(current($arrVal));
	$col_keys_num 	= count($col_keys);
	$row_num 		= count($arrVal);
		//displayArray($arrVal[0]);
		//displayArray($row_num);
		$arrValFirst = array();
		$arrValFirst = current($arrVal);
		//displayArray($arrValFirst);
	
		$act_rpt_row = array();
		$act_rpt_list = '';
		$act_loc_total  = array();
		$act_records_total  = 0;
	
		$act_records_loc	= '';
		$act_records_loc_b	= '';
			
		$i  = 1;
		foreach($arrVal as $t => $rw)
		{
			if($grouper<>'' and !array_key_exists($rw[$grouper], $act_loc_total) ) {
				$act_loc_total[$rw[$grouper]] = 0;
			}
			
			if( $act_records_loc <> '' and $act_records_loc <> $rw[$grouper] ){
				//$act_rpt_list .= '<tr class="sub-group">'.autoTableBlanks($rw, $grouper, $act_records_loc).'<td class="bold"> '.$act_loc_total[$act_records_loc].'</td></tr>';
			}
			
			$act_rpt_row = array();
			$act_rpt_row_recs = 0;
			
			foreach($col_keys as $col_id)
			{
				if($col_id == $grouper){
					if($act_records_loc <> $rw[$col_id]){
						$act_records_loc = $rw[$col_id];
					}
				}
				
				if($col_id == 'records'){
					$act_rpt_row_recs	= intval($rw[$col_id]);
					$act_records_total += intval($rw[$col_id]);
					$act_loc_total[$rw[$grouper]] += intval($rw[$col_id]);
				}
				
				if(array_key_exists($col_id, $rw)){
					$cell_val_arr = array();
					if(isSerialized($rw[$col_id])){
						$cell_val_arr = @unserialize($rw[$col_id]);//displayArray();
						$cell_val 	 = ( is_array($cell_val_arr) and count($cell_val_arr) >= 2) ? implode('; ', $cell_val_arr) : @current($cell_val_arr);						
					} else {
						$cell_val 		= $rw[$col_id];
					}
					
					$act_rpt_row[] = '<td>'. $cell_val .'</td>';
				} else {
					$act_rpt_row[] = '<td>&nbsp;</td>';
				}
			}
			
			$rw_grouper = ($grouper <> '') ? $rw[$grouper] : '';
			$act_rpt_list .= '<tr><td data-loc="'.$rw_grouper.'" data-loc-recs="'.$act_rpt_row_recs.'">'. $i .'</td>'. implode('', $act_rpt_row) .'</tr>';
			
			if( $i == $row_num){
				/*$act_rpt_list .= '<tr class="sub-group">'.autoTableBlanks($rw, $grouper, $act_records_loc).'<td class="bold"> '.$act_loc_total[$act_records_loc].'</td></tr>';*/
			}
			
			$i += 1;
			
			
		}


		//$col_names_a = array_keys(current($tb_result));
		$col_names_b = array_map("clean_title",$col_keys); 
		$col_names = implode('</th><th>', $col_names_b);
		$col_number = count($col_keys);
		
	$foot_total = (array_key_exists('records', $arrValFirst)) ? ' <td>'.$act_records_total.'&nbsp;</td>' : '';
	//<th colspan="'. ($col_number) .'">&nbsp;</th>
	$deTable = '<div class="padd20">
		<table class="table display dataTable table-responsiveX" id="gg_data_tb">
		 <thead><tr><th>#</th><th>'.$col_names.'</th></tr></thead>
		  <tbody>'.$act_rpt_list .'</tbody>
		  <tfoot><tr class="group">'.autoTableBlanks($arrValFirst).''.$foot_total.'</tr></tfoot>
		</table>
		</div>';
	
	return $deTable;
	
}		



function autoTable_One($arrVal, $grouper = 'location'){
	
	$col_keys = array_keys(current($arrVal));
	$col_keys_num = count($col_keys);
		//displayArray($col_keys);
		//displayArray($arrVal);
		$act_rpt_row = array();
		$act_rpt_list = '';
		$act_loc_total  = array();
		$act_records_total  = 0;
	
		$act_records_loc	= '';
		$act_records_loc_b	= '';
			
		$i  = 1;
		foreach($arrVal as $t => $rw)
		{
			if( !array_key_exists($rw['location'], $act_loc_total) ) {
				$act_loc_total[$rw['location']] = 0;
			}
			
			if($act_records_loc <> '' and $act_records_loc <> $rw['location']){
				
				/*$act_rpt_list .= '<tr style="background:#fff2f2"><td colspan="'.count($col_keys).'">&nbsp</td><td class="bold"> '.$act_loc_total[$act_records_loc].' </td></tr>';*/
				$act_rpt_list .= '<tr style="background:#fff2f2">'.autoTableBlanks($col_keys_num).'<td class="bold"> '.$act_loc_total[$act_records_loc].' </td></tr>';
				//$i += 1;
			}
			
			$act_rpt_row = array();
			foreach($col_keys as $col_id)
			{
				if($col_id == 'location'){
					if($act_records_loc <> $rw[$col_id]){
						$act_records_loc = $rw[$col_id];
					}
				}
				
				if($col_id == 'records'){
					$act_records_total += intval($rw[$col_id]);
					$act_loc_total[$rw['location']] += intval($rw[$col_id]);
				}
				
				if(array_key_exists($col_id, $rw)){
					$cell_val = $rw[$col_id];
					$act_rpt_row[] = '<td>'. $cell_val .'</td>';
				} else {
					$act_rpt_row[] = '<td>&nbsp;</td>';
				}
			}
			//$act_rpt_list .= '<tr><td>'. implode('</td><td>', $rw) .'</td></tr>';
			$act_rpt_list .= '<tr><td>'. $i .'</td>'. implode('', $act_rpt_row) .'</tr>';
			$i += 1;
		}


		//$col_names_a = array_keys(current($tb_result));
		$col_names_b = array_map("clean_title",$col_keys); 
		$col_names = implode('</th><th>', $col_names_b);
		$col_number = count($col_keys);
		
	//<th colspan="'. ($col_number) .'">&nbsp;</th>
	$deTable = '<div class="padd20">
		<table class="table display dataTable table-responsiveX">
		 <thead><tr><th>#</th><th>'.$col_names.'</th></tr></thead>
		  <tbody>'.$act_rpt_list .'</tbody>
		  <tfoot><tr>'.autoTableBlanks($col_keys_num).'<td>'.$act_records_total.'&nbsp;</td></tr></tfoot>
		</table>
		</div>';
	
	return $deTable;
	
}		


?>
