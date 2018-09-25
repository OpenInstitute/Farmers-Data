<?php
// GLOBAL VARIABLES AND SELECTS

class drop_downs extends master
{
	var $tbl;
	var $col1;
	var $col2;
	var $col3;
	var $query;
	var $crit;
	var $crit2;
	var $line2;
	
	public $location_array = array(1=>"Kenya",2=>"Tanzania",3=>"South Sudan");
	
	
	function dropper_sel_title($tbl, $col1, $col2, $crit = "", $crit2 = "") 
	{ 
		////$this->connect() or trigger_error('SQL', E_USER_ERROR);
				
		$line = "";
		
		$result = $this->dbQuery("SELECT $col1, $col2, `published` FROM $tbl where `published`=1 ".$crit2." order by $col2");
			
			//$line .= '<option value="" selected></option>';
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[1])>=1)
				{
					$isSelected	= "";					
					$fielditem   = clean_output($qry_data[1]);
					
					if(is_array($crit)){
						if(in_array($fielditem, $crit)) { $isSelected = " selected";} 						
					}
					else
					{	if($crit <> "") { 
						 	if($fielditem == $crit) { $isSelected = " selected "; }
						 }
					} 
					
					//if($crit == $fielditem) { $isSelected = " selected "; } else { $isSelected = ""; }
					$line .= '<option value="'.$fielditem.'" '.$isSelected.'>'.$fielditem.'</option>'; // $selected
				}
			}
		return $line;
	}				
	
	
	function dropper_cat_parent($cat_id, $crit = "") 
	{ 
		////$this->connect() or trigger_error('SQL', E_USER_ERROR);
				
		$line = "";
		$sq = "SELECT `id`, `report_item_title`, `report_item_form_field` FROM `afp_conf_activity_reporting_titles` WHERE `report_item_form_field` = 'group_header' and `report_category_id` = ".quote_smart($cat_id)." or `report_item_form_field` = 'multi_row' and `report_category_id` = ".quote_smart($cat_id)." ";
		$result=$this->dbQuery($sq);
			
			$line .= '<option value="" selected></option>';
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[1])>=1){
					$field_id  = $qry_data[0];			
					$fielditem = ucwords(trim(html_entity_decode(stripslashes($qry_data[1]))));
					
					if($crit == $field_id) { $isSelected = " selected "; } else { $isSelected = ""; }
					$line .= '<option value="'.$qry_data[0].'" '.$isSelected.'>'.$fielditem.'</option>'; // $selected
				}
			}
		return $line;
	}	
	
	
	function dropper_cat_type() 
	{ 
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
				
		$out = array();
		$sq = "SELECT `form_field`, `form_field_label` FROM `afp_conf_activity_reporting_fields` WHERE `published` = '1' order by `seq`";
		$result=$this->dbQuery($sq);
			
			
			
			while($qry_data = $this->fetchRow($result))
			{
				$out[$qry_data['form_field']] = $qry_data['form_field_label'];
				
			}
		return $out;
	}	
	
	
	function dropper_text($tbl, $col1, $col2, $crit) 
	{ 		
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$res_string = "";
		if(strlen($crit) > 0)
		{
			$qry= "SELECT $col1, $col2 FROM $tbl where  $col1 = '".$crit."' "; //published = 1 and
			//echo $qry; exit;
			$result = $this->dbQuery($qry);
			if($this->recordCount($result)) 
			{	
				while($qry_data = $this->fetchRow($result))
				{
					$res_string .= $qry_data[1];
				}
			}
		}
		return $res_string;
	}											
	
	
	function dropper_select($tbl, $col1, $col2, $crit = "", $crit2 = "", $no_select = "Select") 
	{ 
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		//echo $crit; exit;
		$line = ''; 
		//if(strlen($crit) == 0) { $line = "<option value=\"\">".$no_select."</option>";	} 
		$line = '<option value="" >'.$no_select.'</option>';
		
		// order by `seq`
		$result=$this->dbQuery("SELECT $col1, $col2, `published` FROM $tbl where `published`= 1 ".$crit2." order by seq"); //	
		while($qry_data = $this->fetchRow($result))
		{
			$selected = "";
			
			if(strlen($qry_data[1])>=1)
			{
				if(is_array($crit)){
					$optVal = $qry_data[0];
					if(in_array($optVal, $crit)) { $selected = ' selected="selected" ';} 					
				}
				else
				{
					if($crit == $qry_data[0]) { $selected = ' selected="selected" '; }
				}
				
				$fielditem = strip_tags(trim(html_entity_decode(stripslashes($qry_data[1]))));
				$line .= '<option value="'.$qry_data[0].'" '.$selected.'>'.$fielditem.'</option>'; 
			}
		}
		return $line;
	}	
	//dropper_select
	





	/* ****************************************
	 @Select Country
	****************************************** */ 
	
	function selectCountry($crit) {
		$country = '';
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		//$rs_data= $this->dbQuery('SELECT `id`, `country` FROM `siteprfx_reg_countries` WHERE `id`='.$crit.'');
		$rs_data= $this->dbQuery("SELECT `id`, `country` FROM `siteprfx_reg_countries` WHERE `id`=".quote_smart($crit)." or `iso_code_2`=".quote_smart($crit)."  or `iso_code_1`=".quote_smart($crit)." ");
		if($this->recordCount($rs_data) ==1 ){
			$cn_data = mysql_fetch_row($rs_data);
			$country = $cn_data[1];
		}
	
		return $country;
	}
	
	
	

/******************************************************************
@begin :: DIRECTORY CATS DROP DOWN
********************************************************************/	

function selectConfTypes($cat=1, $crit = "", $multiple = 0, $title = '')
	{ 
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		if($multiple == 0) {
		$out = "<option value=''>$title</option>";
		}
		/*if($crit == "" and $firstDefault <> "blank"){
		//$out = "<option value='' selected></option>";	//selected
		}*/
		
		$sq = "SELECT `conf_data_id` , `conf_data_title` FROM `siteprfx_conf_types_data` WHERE (`published` =1 AND `conf_type_id` = ".quote_smart($cat).") ORDER BY `conf_data_title` ASC, `seq` ASC;";
		
		$result=$this->dbQuery($sq);
			
			while($qry_data = mysql_fetch_row($result))
			{
				
				if(strlen($qry_data[1])>=1){
					$selected="";
					if(is_array($crit)){
						if(in_array($qry_data[0], $crit)) { $selected = " selected";} 						
					}
					elseif($crit <> "") { 
						if($qry_data[0] == $crit) { $selected=" selected "; }
					} 
				}
				
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
			
			return $out;
	}
	
	
	

/******************************************************************
@begin :: STAFF FOR PARTNER
********************************************************************/

function getStaff_Partner($id_partner, $crit = "")  
{ 
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	$line = ""; 
	$no_select = 'Select';
	$line = "<option value=\"\" >".$no_select."</option>";
	
	$critPartner = "";
	if($id_partner <> 1) { $critPartner = " and `id_partner` = ".quote_smart($id_partner)." "; }
	
	$result=$this->dbQuery("SELECT `id`, concat(firstname,' ',lastname) FROM `afp_conf_person_list` where `published`=1 $critPartner");	//
	while($qry_data = $this->fetchRow($result))
	{
		if(strlen($qry_data[1])>=1)
		{
			if(is_array($crit)){
				$optVal = $qry_data[0];
				if(in_array($optVal, $crit)) { $selected = " selected";} else { $selected = ""; }						
			}
			else
			{
				if($qry_data[0] == $crit) { $selected=" selected"; } else {$selected="";} 
			}
			
			$fielditem = trim(html_entity_decode(stripslashes($qry_data[1])));
			$line .= '<option value="'.$qry_data[0].'" '.$selected.'>'.$fielditem.'</option>'; 
		}
	}
	return $line;

}



/******************************************************************
@begin :: STAFF FOR PROJECT
********************************************************************/

function getStaff_Project($project_id, $crit = "", $show_id = 1)  
{ 
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	$line = ""; 
	$no_select = 'Select';
	
	$line = "<option value=\"\" >".$no_select."</option>";
	
	
	$sq = "SELECT `afp_vw_account_list`.`id` , `afp_vw_account_list`.`name` , `afp_vw_account_list`.`partner_initial` FROM `afp_vw_account_list` INNER JOIN `afp_projects_team` ON (`afp_vw_account_list`.`id` = `afp_projects_team`.`user_id`) WHERE (`afp_vw_account_list`.`published` =1 AND `afp_projects_team`.`project_id` = ".quote_smart($project_id).") ORDER BY `afp_vw_account_list`.`partner_initial`, `afp_vw_account_list`.`name`; ";
	
	$result=$this->dbQuery($sq);	//
	while($qry_data = @$this->fetchRow($result))
	{
		if(strlen($qry_data[1])>=1)
		{
			
			$optTitle = trim(html_entity_decode(stripslashes($qry_data['name'])));
			$optPartner = '';
			
			if($_SESSION['exp_member']['u_type_id'] == 1) {}
			$optPartner = trim(html_entity_decode(stripslashes($qry_data['partner_initial']))) . ' - '; 
			
			$optVal   = $qry_data['id'];
				if($show_id == 0) 
				{ $optVal = $optTitle; $crit = trim(html_entity_decode(stripslashes($crit))); }
			
			$selected = '';
			
			if(is_array($crit)){
				//$optVal = $qry_data[0];
				if(in_array($optVal, $crit)) { $selected = ' selected';} else {  }						
			}
			else
			{
				if($optVal == $crit) { $selected=' selected = "selected" '; } 
			}
			
			//$fielditem = trim(html_entity_decode(stripslashes($qry_data[1])));
			$line .= '<option value="'.$optVal.'" '.$selected.'>'.$optPartner.''.$optTitle.'</option>'; 
		}
	}
	return $line;

}






/******************************************************************
@begin :: PROJECTS FOR PARTNER
********************************************************************/

function getPartner_Project($partner_id = '', $crit = '')  
{ 
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	$out = array(); 
	$no_select = 'Select';
	
	
	$out[] = "<option value=\"\" >".$no_select."</option>";
	
	$sq_crit = "";
	if($partner_id <> 1) {
$sq_crit = " WHERE (`afp_projects_base_links`.`id_partner` = ".quote_smart($partner_id).")
    OR (`afp_projects_base_associates`.`id_associate` = ".quote_smart($partner_id)." ) ";	
	}

$sq_proj_list = "SELECT
    `afp_projects_base`.`id`
    , `afp_projects_base`.`project_name`
    , `afp_projects_base`.`published`
    , `afp_projects_base_links`.`id_partner`
	, `afp_projects_base_associates`.`id_associate`
FROM
    `afp_projects_base`
    INNER JOIN `afp_projects_base_links` 
        ON (`afp_projects_base`.`id` = `afp_projects_base_links`.`id_project`)
    LEFT JOIN `afp_projects_partners` 
        ON (`afp_projects_base_links`.`id_partner` = `afp_projects_partners`.`id_partner`)
	LEFT JOIN `afp_projects_base_associates` 
        ON (`afp_projects_base`.`id` = `afp_projects_base_associates`.`id_project`)
	$sq_crit
GROUP BY `afp_projects_base_links`.`id_project`;";
	
	$result = $this->dbQuery($sq_proj_list);	//
	if($this->recordCount($result)>=1)
	{ 
		while($qry_data = $this->fetchRow($result))
		{
			if(strlen($qry_data[1])>=1)
			{
				$project_id   = $qry_data['id'];
				$project_name = clean_output($qry_data['project_name']);
				
				$selected = '';
				if(is_array($crit)){
					if(in_array($project_id, $crit)) { $selected = ' selected';} else {  }						
				}
				else
				{
					if($project_id == $crit) { $selected=' selected '; } 
				}
				
				$out[] = '<option value="'.$project_id.'" '.$selected.'>'.$project_name.'</option>'; 
			}
		}
	}
	//exit;
	return implode("", $out);

}








/******************************************************************
@begin :: PROJECT BRIEF
********************************************************************/

function getProjectAuthorized($project_id, $partner_id)  
{ 
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$sq_check = "SELECT
    `afp_projects_base`.`id`
    , `afp_projects_base_links`.`id_partner`
    , `afp_projects_base_associates`.`id_associate`
    , `afp_projects_base`.`published`
FROM
    `afp_projects_base`
    LEFT JOIN `afp_projects_base_links` 
        ON (`afp_projects_base`.`id` = `afp_projects_base_links`.`id_project`)
    LEFT JOIN `afp_projects_base_associates` 
        ON (`afp_projects_base`.`id` = `afp_projects_base_associates`.`id_project`)
WHERE (`afp_projects_base`.`id` = ".quote_smart($project_id)."
    AND `afp_projects_base_links`.`id_partner` = ".quote_smart($partner_id)."
    AND `afp_projects_base`.`published` =1)
    OR (`afp_projects_base`.`id` = ".quote_smart($project_id)."
    AND `afp_projects_base_associates`.`id_associate` = ".quote_smart($partner_id)."
    AND `afp_projects_base`.`published` =1)
GROUP BY `afp_projects_base`.`id`;";

	$rs_check = $this->dbQuery($sq_check);
	$rs_count = $this->recordCount($rs_check);

	return $rs_count;
}


function getProjectBrief($project_id)  
{ 
	
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; $qr_act_targets = ""; $out_data = "";
	
	$target_arr = array(); $target_summary = array(); 
	
	$target_total = 0; $act_target_total = 0;
	
	if($project_id <> "") 
	{ 
		
		// @@--PROJECT BRIEF QUERY
		
		$qr_data = "SELECT
    `afp_projects_base`.`id`
    , `afp_projects_base`.`project_name`
    , UNIX_TIMESTAMP(`afp_projects_base`.`project_start_date`)
    , UNIX_TIMESTAMP(`afp_projects_base`.`project_finish_date`)
    , `afp_projects_base`.`project_index`
    , `afp_projects_base`.`project_locations`
    , `afp_projects_base`.`project_targets`
    , `afp_projects_base`.`project_people`
    , `afp_projects_base`.`project_head`
    , `afp_projects_base`.`expected_outcomes`
    , `afp_projects_base`.`project_resultareas`
    , `afp_vw_proj_activity_count`.`activities`
    , COUNT(`afp_vw_proj_activity_reports`.`activity_id`) AS `activities_reported`
	, `afp_projects_base`.`project_tools`
	, `afp_projects_base`.`project_status`
	, `afp_projects_base`.`published`
	, `afp_projects_base`.`project_description`
	, `afp_projects_partners`.`partner_title`
FROM
    `afp_projects_base`
    LEFT JOIN `afp_vw_proj_activity_count` 
        ON (`afp_projects_base`.`id` = `afp_vw_proj_activity_count`.`project_id`)
    LEFT JOIN `afp_vw_proj_activity_reports` 
        ON (`afp_projects_base`.`id` = `afp_vw_proj_activity_reports`.`project_id`)
	LEFT JOIN `afp_projects_base_links` 
        ON (`afp_projects_base`.`id` = `afp_projects_base_links`.`id_project`)
    LEFT JOIN `afp_projects_partners` 
        ON (`afp_projects_base_links`.`id_partner` = `afp_projects_partners`.`id_partner`)
WHERE (`afp_projects_base`.`id` = '".$project_id."')
GROUP BY `afp_projects_base`.`id`;";
//echo $qr_data; exit;
		
		// @@--ACTIVITY TARGETS NUMBERS
		
		$qr_act_targets = "SELECT `project_id`, `report_field_type`, `activity_details`, SUM(`activity_numbers`) FROM `afp_projects_workplans_activity_reporting` WHERE (`project_id` = '".$project_id."' AND `report_field_type` ='group_select') GROUP BY `project_id`, `report_field_type`, `activity_details`;";
		//echo $qr_act_targets;
	}
	
	
	if($qr_data <> "") 
	{ 
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data) == 1)
		{
			$cn_data = $this->fetchRow($rs_data);
			
			$p_targets		= @unserialize($cn_data[6]); 
			$p_outcomes	   = @unserialize($cn_data[9]); 
			$p_tools	      = @unserialize($cn_data[13]); 
			$p_locations	  = @unserialize($cn_data[5]); 
			
			// @@--PROJECT TARGETS NUMBERS
			
			if(is_array($p_targets))
			{
				/*for($t=0; $t < count($p_targets); $t++) {
					$target_id 	= key($p_targets[$t]);
					$target_val   = $p_targets[$t][$target_id];
					$target_arr[$target_id]['targ'] = $target_val;
					$target_total += $target_val;
				}*/
				foreach($p_targets as $t_key => $t_arr) {
					//$target_id 	= key($p_targets[$t]);
					$target_val   = $t_arr['number'];
					$target_arr[$t_key]['targ'] = $target_val;
					$target_total += $target_val;
				}
			}
			
			$target_summary['targ'] = $target_total;
			
			
			$p_name			  = clean_output($cn_data['project_name']);
			$p_description       = clean_output($cn_data['project_description']);
			$p_partner		   = clean_output($cn_data['partner_title']);
			
			$p_date_start		= date("m/d/Y", $cn_data[2]);
			$p_date_end		  = date("m/d/Y", $cn_data[3]);
			
			$p_date_start_year   = date("Y", $cn_data[2]);
			$p_date_start_month  = date("m", $cn_data[2]);
			
			$p_days_current	  = getDays($cn_data[2],time()); 	
			$p_days_total		= getDays($cn_data[2], $cn_data[3]); 
			
			$p_activities_no_report = $cn_data[11] - $cn_data[12];
			
			$p_status			= $cn_data[14];
			$p_active			= $cn_data[15];
			
			$out_data = array(
				'p_id'    	  => ''.$cn_data[0].'',
				'p_name' 		=> ''.$p_name.'',
				'p_description' => ''.$p_description.'',
				'p_days_num'    => ''.$p_days_total.'',
				'p_days_leo'    => ''.$p_days_current.'',
				'p_index'       => ''.trim($cn_data[4]).'',
				'p_resultareas'       => ''.trim($cn_data[10]).'',
				'p_activities_num'       => ''.trim($cn_data[11]).'',
				'p_activities_with_report'       => ''.trim($cn_data[12]).'',
				'p_activities_no_report'       => ''.$p_activities_no_report.'',
				'p_outcomes'       => $p_outcomes,
				'p_date_start'       => $p_date_start,
				'p_date_end'       => $p_date_end,
				'p_locations'       => $p_locations
				,'p_status'       => $p_status
				,'p_active'       => $p_active				
				,'p_start_year'       => $p_date_start_year
				,'p_start_month'       => $p_date_start_month
				,'p_tools'       => '' //$p_tools
				,'p_partner'       => ''.$p_partner.''
			);
		}
		
	}
	
	/* ========================================================
		@ PROJECT TARGETS
	***********************************************************/
	
	if($qr_act_targets <> "") 
	{ 
		
		$rs_act_targets = $this->dbQuery($qr_act_targets);
		if($this->recordCount($rs_act_targets) > 0)
		{
			while($cn_act_targets = $this->fetchRow($rs_act_targets))
			{			
				$act_target_id	 = $cn_act_targets[2]; 
				$act_target_num	= $cn_act_targets[3]; 
				
				// @@--ACTIVITY TARGETS NUMBERS
				
				if(array_key_exists($act_target_id, $target_arr))
				{
					$act_target_perc = @($act_target_num / $target_arr[$act_target_id]['targ']) * 100;
					$target_arr[$act_target_id]['curr'] = $act_target_num;
					$target_arr[$act_target_id]['perc'] = displayDecimal($act_target_perc, '');
					
					$act_target_total += $act_target_num;
				}	
				
				// @@--ACTIVITY TARGETS NUMBERS (not defined on project)
				else	
				{
					$target_arr[$act_target_id]['targ'] = 0;
					$target_arr[$act_target_id]['curr'] = $act_target_num;
					$target_arr[$act_target_id]['perc'] = 0;
				}						
			}
			
			$act_target_total_perc  = ($act_target_total / $target_total) * 100;
			$target_summary['curr'] = $act_target_total;
			$target_summary['perc'] = displayDecimal($act_target_total_perc);
		}
		
		array_sort_by_column($target_arr, 'perc');
		
		$out_data['p_targets'] 		 = $target_arr;
		$out_data['p_targets_summary'] = $target_summary;			
	}
	
	
	/* ========================================================
		@ PROJECT TOOLS
	***********************************************************/
	
	$sq_btools = "SELECT `id_theme`, `id_project`, `tool_code`, `tool_link` FROM `afp_projects_base_tools`  WHERE (`id_project` = ".quote_smart($project_id).") ; ";	
	$rs_btools = $this->dbQuery($sq_btools);	
	if($this->recordCount($rs_btools)) 
	{
		while($cn_btools = $this->fetchRow($rs_btools))
		{
			$tool_code = $cn_btools['tool_code']; 
			$tool_link = $cn_btools['tool_link']; 
			$out_data['p_tools'][$tool_code] = $tool_link;
		}
	}
		
	
	return $out_data;
}
	



function getHighestMetTarget($project_id)  
{ 
	
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; $out_data = "";
	
	if($project_id <> "") 
	{ 
		$qr_data = "SELECT `afp_projects_workplans_activity_reporting`.`workplan_entry_id` AS `activity_id`
		, `afp_projects_workplans`.`entry_title`
    , `afp_projects_workplans_activity_reporting`.`activity_details`
    , SUM(`afp_projects_workplans_activity_reporting`.`activity_numbers`) AS `target_met` , `afp_projects_workplans`.`report_category_id`   
FROM
    `afp_projects_workplans_activity_reporting`
    INNER JOIN `afp_projects_workplans` 
        ON (`afp_projects_workplans_activity_reporting`.`workplan_entry_id` = `afp_projects_workplans`.`id`)
WHERE (`afp_projects_workplans_activity_reporting`.`project_id` = '".$project_id."'
    AND `afp_projects_workplans_activity_reporting`.`report_field_type` ='group_select')
GROUP BY `activity_id`, `afp_projects_workplans_activity_reporting`.`activity_details`
ORDER BY `target_met` DESC limit 0,1;";	
		
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data))
		{
			while($cn_data = $this->fetchRow($rs_data))
			{
				$report_category   = $this->dropper_text('afp_conf_activity_reporting_categories','id','report_category', $cn_data[4]);
				$out_data = array(
					'act_id'        => ''.$cn_data[0].'',
					'act_title' 	 => ''.trim(html_entity_decode(stripslashes($cn_data[1]))).'',
					'act_target_group'    => ''.trim(html_entity_decode(stripslashes($cn_data[2]))).'',
					'act_target_num'    => ''.$cn_data[3].'',
					'act_category'    => ''.$report_category.''
				);
			}
		}
		
	}
		
	return $out_data;
}



function getMemberTargetsTotals($project_id, $user_id)  
{ 
	//echo $project_id; exit;
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; 		$out_data = "";
	$target_total = 0; 	$act_target_total = 0;
	$target_summary = "";
	$target_arr = "";
	
	if($project_id <> "") 
	{ 
		$qr_data = "SELECT
			`id`
			, `entry_title`
			, `entry_targets`
			, `entry_supervisor_id`
		FROM
			`afp_projects_workplans`
		WHERE (`project_id` = '".$project_id."'
			AND `entry_supervisor_id` = '".$user_id."');";	
		//echo $qr_data; exit;
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data))
		{
			while($cn_data = $this->fetchRow($rs_data))
			{
				$p_targets		= @unserialize($cn_data[2]); 			
				//displayArray($p_targets); exit;
				// @@--USER'S ACTIVITIES TARGETS NUMBERS				
				if(is_array($p_targets))	
				{
					for($t=0; $t < count($p_targets); $t++) {
						$target_id 	= key($p_targets[$t]); //echo strlen($target_id).'<hr>';
						if(strlen($target_id) > 0)
						{
						$target_val   = $p_targets[$t][$target_id];
						$target_arr[$target_id]['targ'] = $target_val;
						$target_arr[$target_id]['curr'] = 0;
						$target_arr[$target_id]['perc'] = 0;
						$target_total += $target_val;
						}
					}
					
					$target_summary['targ'] = $target_total;
					$target_summary['curr'] = 0;
					$target_summary['perc'] = 0;
				}
				
			}
		}
		
	//displayArray($target_arr); exit;	
		
	$qr_act_targets = "SELECT
    `afp_projects_workplans_activity_reporting`.`project_id`
    , `afp_projects_workplans_activity_reporting`.`report_field_type`
    , `afp_projects_workplans_activity_reporting`.`activity_details`
    , SUM(`afp_projects_workplans_activity_reporting`.`activity_numbers`) AS `targets_met`
    , `afp_projects_workplans`.`entry_supervisor_id`
FROM
    `afp_projects_workplans_activity_reporting`
    INNER JOIN `afp_projects_workplans` 
        ON (`afp_projects_workplans_activity_reporting`.`workplan_entry_id` = `afp_projects_workplans`.`id`)
WHERE (`afp_projects_workplans_activity_reporting`.`project_id` = '".$project_id."'
    AND `afp_projects_workplans_activity_reporting`.`report_field_type` ='group_select'
    AND `afp_projects_workplans`.`entry_supervisor_id` = '".$user_id."')
GROUP BY `afp_projects_workplans_activity_reporting`.`project_id`, `afp_projects_workplans_activity_reporting`.`report_field_type`, `afp_projects_workplans_activity_reporting`.`activity_details`, `afp_projects_workplans`.`entry_supervisor_id`;";
		//echo $qr_act_targets; exit;	
			$rs_act_targets = $this->dbQuery($qr_act_targets);
			if($this->recordCount($rs_act_targets) > 0)
			{
				while($cn_act_targets = $this->fetchRow($rs_act_targets))
				{			
					$act_target_id	 = $cn_act_targets[2]; 
					$act_target_num	= $cn_act_targets[3]; 
					
					// @@--ACTIVITY TARGETS NUMBERS
					
					if(@array_key_exists($act_target_id, $target_arr))
					{
						$act_target_perc = ($act_target_num / $target_arr[$act_target_id]['targ']) * 100;
						if($act_target_perc >= 100) 
							{ $act_target_perc = 100; } else 
							{ $act_target_perc = displayDecimal($act_target_perc, ''); }
							
						$target_arr[$act_target_id]['curr'] = $act_target_num;
						$target_arr[$act_target_id]['perc'] = $act_target_perc;
						
						$act_target_total += $act_target_num;
					}	
					
					// @@--ACTIVITY TARGETS NUMBERS (not defined on project)
					else	
					{
						$target_arr[$act_target_id]['targ'] = 0;
						$target_arr[$act_target_id]['curr'] = $act_target_num;
						$target_arr[$act_target_id]['perc'] = 0;
					}						
				}
				
				$act_target_total_perc  = @($act_target_total / $target_total) * 100;
				$target_summary['curr'] = $act_target_total;
				$target_summary['perc'] = displayDecimal($act_target_total_perc);
			
				array_sort_by_column($target_arr, 'perc');
			}
			
			//array_sort_by_column($target_arr, 'perc');
			
			$out_data['p_targets'] 		 = $target_arr;
			$out_data['p_targets_summary'] = $target_summary;
				
		//}
	
	
	
	
	
	}
		
	return $out_data;
}






function getMemberTargetsHighest($project_id, $user_id)  
{ 
	
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; $out_data = "";
	
	if($project_id <> "") 
	{ 
		$qr_data = "SELECT
    `afp_projects_workplans_activity_reporting`.`workplan_entry_id` AS `activity_id`
    , `afp_projects_workplans`.`entry_title`
    , `afp_projects_workplans_activity_reporting`.`activity_details`
    , SUM(`afp_projects_workplans_activity_reporting`.`activity_numbers`) AS `targets_met`
    , `afp_projects_workplans`.`entry_supervisor_id`
FROM
    `afp_projects_workplans_activity_reporting`
    INNER JOIN `afp_projects_workplans` 
        ON (`afp_projects_workplans_activity_reporting`.`workplan_entry_id` = `afp_projects_workplans`.`id`)
WHERE (`afp_projects_workplans_activity_reporting`.`project_id` = '".$project_id."'
    AND `afp_projects_workplans_activity_reporting`.`report_field_type` ='group_select'
    AND `afp_projects_workplans`.`entry_supervisor_id` = '".$user_id."')
GROUP BY `afp_projects_workplans_activity_reporting`.`project_id`, `afp_projects_workplans_activity_reporting`.`report_field_type`, `afp_projects_workplans_activity_reporting`.`activity_details`, `afp_projects_workplans`.`entry_supervisor_id`
ORDER BY `targets_met` DESC  limit 0,2 ;";	
		//echo $qr_data;
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data))
		{
			while($cn_data = $this->fetchRow($rs_data))
			{
				//$report_category   = $this->dropper_text('afp_conf_activity_reporting_categories','id','report_category', $cn_data[4]);
				$out_data[] = array(
					'act_id'        => ''.$cn_data[0].'',
					'act_title' 	 => ''.trim(html_entity_decode(stripslashes($cn_data[1]))).'',
					'act_target_group'    => ''.trim(html_entity_decode(stripslashes($cn_data[2]))).'',
					'act_target_num'    => ''.$cn_data[3].''
					//,'act_category'    => ''.$report_category.''
				);
			}
		}
		
	}
		
	return $out_data;
}






function getActivitiesBrief($project_id)  
{ 
	
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; $out_data = "";
	
	if($project_id <> "") 
	{ 
		$qr_data = "SELECT
			`id`
			, `project_name`
			, UNIX_TIMESTAMP(`project_start_date`)
			, UNIX_TIMESTAMP(`project_finish_date`)
			, `project_index`
			, `project_locations`
			, `project_targets`
			, `project_people`
			, `project_head`
			, `expected_outcomes`
			FROM
			`afp_projects_base`
			WHERE (`id` = '".$project_id."');";	
		
	}
	
	if($qr_data <> "") 
	{ 
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data) == 1)
		{
			$cn_data = mysql_fetch_row($rs_data);
			
			$p_days_current		= getDays($cn_data[2],time()); 	
			$p_days_total		= getDays($cn_data[2], $cn_data[3]); 
			
			
			$out_data = array(
				'p_name' 		=> ''.trim(html_entity_decode(stripslashes($cn_data[1]))).'',
				'p_days_num'    => ''.$p_days_total.'',
				'p_days_leo'    => ''.$p_days_current.'',
				'p_index'       => ''.trim($cn_data[4]).''
			);
		}
		
	}
		
	return $out_data;
}



function getActReportCategories($category_id)  
{ 
	
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; $out_data = "";
	
	if($category_id <> "") 
	{ 
		$qr_data = "SELECT `id`, `report_item_title`, `report_item_form_field` FROM    `afp_conf_activity_reporting_titles` where `report_category_id` = '".$category_id."' order by `id`;";	
		
	}
	
	if($qr_data <> "") 
	{ 
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data))
		{
			while($cn_data = $this->fetchRow($rs_data))
			{
				$out_data[$cn_data[0]] = array(
					'item_title'   => ''.trim(html_entity_decode(stripslashes($cn_data[1]))).'',
					'item_field'   => ''.trim(html_entity_decode(stripslashes($cn_data[2]))).''
				);
			}
		}
		
	}
		
	return $out_data;
}



function getActivitiesListBrief($project_id, $list_cat, $disp_type = "table")  
{ 
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_result = ""; $act_data = ""; $act_date_label = "Due"; $crit_staff = "";
	
	
	$us_id		 = $_SESSION['exp_member']['u_id'];
	$us_type_id 	= $_SESSION['exp_member']['u_type_id'];
	
	if($us_type_id == 4 or $us_type_id == 5)  
	{
		$crit_staff = " and (`entry_supervisor_id` = '".$us_id."') ";
	}
		
	
	
if($project_id <> "") 
{ 
		
		if($list_cat == "current")
		{
			$qr_result = "SELECT `id`, `project_id`, `entry_index`, `entry_title`, `planned_start_date`, `planned_finish_date`, `country_id`, `entry_supervisor_id` FROM `afp_vw_proj_activity_list_current` WHERE (`project_id` = '".$project_id."') $crit_staff;";
	
		}
		elseif($list_cat == "upcoming")
		{
			$qr_result = "SELECT `id`, `project_id`, `entry_index`, `entry_title`, `planned_start_date`, `planned_finish_date`, `country_id`, `entry_supervisor_id` FROM `afp_vw_proj_activity_list_upcoming` WHERE (`project_id` = '".$project_id."') $crit_staff limit 0, 5;";
	
		}
		elseif($list_cat == "overdue")
		{
			$qr_result = "SELECT `id`, `project_id`, `entry_index`, `entry_title`, `planned_start_date`, `planned_finish_date`, `country_id`, `entry_supervisor_id` FROM `afp_vw_proj_activity_list_overdue` WHERE (`project_id` = '".$project_id."') $crit_staff limit 0, 10;";
	
		}
		
	
	$rs_result = $this->dbQuery($qr_result);	
	
	if($this->recordCount($rs_result))
	{
		while($cn_result = $this->fetchRow($rs_result))
		{
			$act_id		   = $cn_result[0];
			$act_index		= $cn_result[2];
			$act_title 		= trim(html_entity_decode(stripslashes($cn_result[3])));
			$act_start   	    = $cn_result[4];
			$act_due		  = $cn_result[5];
			$act_country	  = $cn_result[6];
			$act_officer	  = $cn_result[7];
			$act_officer_name = "";
			$act_location	 = "";
			
			if($act_country <> 0) { $act_location = $this->location_array[$act_country]; }
			if($list_cat == "upcoming") { $act_due = $act_start; $act_date_label = "Start"; }
			if($list_cat == "overdue") { if($act_start >  $act_due) { $act_due = $act_start; } }
			
			$act_due		= date("M d Y",strtotime($act_due));
			
			if($us_type_id <> 4 and $us_type_id <> 5)  
			{
				$act_officer_arr  = $this->getMemberBrief($act_officer);
				$act_officer_name = $act_officer_arr['mem_name'];
			}
			
			
			
			if($disp_type == "table")
			{
				$act_data .= '<tr>
					<td>'.$act_index.' '.$act_title.'</td>
					<td>'.$act_due.'</td>
					<td>'.$act_location.'</td>
					<td>'.$act_officer_name.'</td>
					</tr>'; 
			}
			else
			{
				
				$act_page = 'workplans.php?project_id='.$project_id;
				
				if($list_cat == "overdue") { $act_page = 'wpreports.php?project_id='.$project_id.'&acti_id='.$act_id;}
				
				//$act_date_label = '';//'<em>'.$act_date_label.':</em> ';
				
				if($act_location <> "")
				{ $act_location = ' &nbsp; | &nbsp; <em>'.$act_location.'</em>'; }
				
				if($act_officer_name <> "")
				{ $act_officer_name = ' &nbsp; | &nbsp; <em><a href="workplans_staff.php?project_id='.$project_id.'&staff_id='.$act_officer.'">'.$act_officer_name.'</a></em>'; }
				
				$act_name  = $act_title; //smartTruncate($act_title, 55, '...', true);
				
				$act_data .= '<li>
					<a href="'.$act_page.'">'.$act_index.' '.$act_name.'</a>
					<span class="hint">'.$act_date_label.': <em>'.$act_due.'</em> '.$act_location.' '.$act_officer_name.'</span>
					</li>'; 
					//
			}
		}
		
	}
}
		
	return $act_data;
}

/******************************************************************
@begin :: PROJECT BRIEF
********************************************************************/




	
	
/******************************************************************
@begin :: PROJECT Workplan Types
********************************************************************/
	
function dropperWPEntryTypes($entry_type_id = "") 
{ 
	$crit = $entry_type_id;
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	if(strlen($crit)==0) { echo "<option value='' selected></option>"; }
	
	$out = "";
	
	$qry_links = "SELECT `id`, `title` FROM  afp_conf_workplan_entry_types WHERE (`id` < 4) ";	
	
	$con_links = $this->dbQuery($qry_links);
	
	while($res_links = $this->fetchRow($con_links))
	{
		$st='';
		$link_id	= $res_links['id'];
		$link_name	= $res_links['title'];
		
		if($crit == $link_id) {$st='selected';}
		
		$out .= "<option value='$link_id' $st>".$link_name."</option>";
	}
		
	return $out;
}



function dropperWorkplanParent($id_project, $wp_id="", $type_parent = "") 
{ 
	$crit = $wp_id;
	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$entry_type_parent = " and (`entry_type_id` < 5) ";
	
	
	if(strlen($type_parent)<> "") { $entry_type_parent = "  and (`entry_type_id` = '$type_parent') "; }
	
	$out = "<option value='' selected></option>";
	
	$qry_links = "SELECT `id`, `entry_title`, `entry_index` FROM  `afp_projects_workplans` WHERE (`project_id` = '$id_project') and (`published` =1) $entry_type_parent ";	
	//echo $qry_links; exit;
	$con_links = $this->dbQuery($qry_links);
	
	while($res_links = $this->fetchRow($con_links))
	{
		$st='';
		$link_id	= $res_links['id'];
		$link_name	= $res_links['entry_title'];
		$link_index	= $res_links['entry_index'];
		
		if($link_index <>'') { $link_name = $link_index .' '.$link_name; }
		
		if($crit == $link_id) {$st=' selected';}
		
		$out .= "<option value='$link_id' $st>".$link_name."</option>";
	}
		
	return $out;
}
	
/******************************************************************
@end :: PROJECT Workplan Types
********************************************************************/


	
	


/******************************************************************
@begin :: STAFF SPECIFIC
********************************************************************/
	
function getMemberBrief($c_id ="", $c_order = "")  
{ 

	//$this->connect() or trigger_error('SQL', E_USER_ERROR);
	
	$qr_data = ""; $out_data = "";
	
	if($c_id <> "") 
	{ 
		$qr_data = "SELECT
`afp_conf_person_list`.`id`
, concat_ws(' ',`afp_conf_person_list`.`firstname`, `afp_conf_person_list`.`lastname`) as `staff_name`
, `afp_conf_person_list`.`email`
, `afp_conf_person_roles`.`title` AS `staff_role`
, `afp_conf_person_list`.`published`
FROM
`afp_conf_person_list`
LEFT JOIN `afp_conf_person_roles` 
	ON (`afp_conf_person_list`.`job_role_id` = `afp_conf_person_roles`.`id`)  WHERE (`afp_conf_person_list`.`id` = '".$c_id."')";	
		
	}
	elseif($c_order <> "") 
	{ 
$qr_data = "SELECT `afp_conf_person_list`.`id` , `afp_conf_person_list`.`rf_type_account`, `afp_conf_person_list`.`rf_code`, `afp_conf_person_list`.`rf_firstname`, `afp_conf_person_list`.`rf_lastname`, `afp_conf_person_list`.`rf_email`, `afp_conf_person_list`.`rf_phone`, `bew_orders_detailed`.`id` FROM `bew_orders_detailed` INNER JOIN `afp_conf_person_list` ON (`bew_orders_detailed`.`id_client` = `afp_conf_person_list`.`id`) WHERE (`bew_orders_detailed`.`id` = '".$c_order."'); ";	
		
	}
	
	if($qr_data <> "") 
	{ 
	
		$rs_data=$this->dbQuery($qr_data);
		if($this->recordCount($rs_data) == 1)
		{
			$cn_data = mysql_fetch_row($rs_data);
			$out_data = array(
				'mem_name' 	=> ''.trim(html_entity_decode(stripslashes($cn_data[1]))).'',
				'mem_email'    => ''.trim(html_entity_decode(stripslashes($cn_data[2]))).'',
				'mem_id'       => ''.trim($cn_data[0]).''
			);
		}
		
	}
		
	return $out_data;
}
	
/******************************************************************
@end :: STAFF SPECIFIC
********************************************************************/	
	


	/******************************************************************
	@begin :: STAFF TEAMS
	********************************************************************/

	function getTeamMembers ($users_full, $sel_array)
	{
		
		$members = '';
		
		if(is_array($sel_array)) 
		{
			foreach($sel_array as $key => $user)
			{
				$members .= '<li><a href="#">'. $users_full[$user]["mem_name"] . '</a> &nbsp; ';				
			}
		}
		
		if(trim($members) <> '') { $members = '<ul>'. $members .'</ul>'; } else { $members = CONST_NOTAVAILABLE; }
		
		return $members;
		
	}	

	/******************************************************************
	@end :: STAFF TEAMS
	********************************************************************/

	
	
	
	
	/******************************************************************
	@begin :: TARGET GROUPS
	********************************************************************/
	
	function getTargets_AllXXXX ($project_id = "", $crit = "", $show_id = 1)
	{
		$line   = '<option value=""></option>';
		
		
		//$sq 	 = "SELECT `id`, `title` FROM `afp_conf_project_targets` where `published` = 1 order by `title`";
			$sqCrit = '';	
			$sqJoin = ' LEFT ';
		if($project_id <> '') {
			$sqJoin = ' INNER ';
			$sqCrit = " WHERE (`afp_projects_base_indicators`.`id_project` = ".quote_smart($project_id).") ";
		}
		
		$sq = "SELECT
    `afp_conf_project_targets`.`id`
    , `afp_conf_project_targets`.`title`
    , `afp_projects_base_indicators`.`id_project`
    , `afp_projects_base_indicators`.`indicator_number`
FROM
    `afp_conf_project_targets`
    $sqJoin JOIN `afp_projects_base_indicators` 
        ON (`afp_conf_project_targets`.`id` = `afp_projects_base_indicators`.`id_record_theme_indicator`) $sqCrit ;";
		//echo $sq; exit;
		
		$result = $this->dbQuery($sq);	//
		
		while($qry_data = $this->fetchRow($result))
		{
			if(strlen($qry_data[1])>=1)
			{
				$crit 	 = trim(html_entity_decode(stripslashes($crit)));
				$optTitle = trim(html_entity_decode(stripslashes($qry_data['title'])));
				$optVal   = $qry_data['id_target'];
					if($show_id == 0) 
					{ $optVal = $optTitle; }
				
				
				if(is_array($crit)){
					if(in_array($optVal, $crit)) { $selected = " selected";} else { $selected = ""; }						
				}
				else
				{
					if($crit == $optVal) { $selected=" selected = \"selected\" "; } else {$selected="";} 
				}
				
				$line .= '<option value="'.$optVal.'" '.$selected.'>'.$optTitle.'</option>'; 
			}
		}
		return $line;
		
	}
	
	function getTargetsById ($selTargetId = "")
	{
		$targets = '<option value=""></option>';
		
		$qry_links = "SELECT `id`, `title`, `level` FROM `afp_conf_project_targets` where `published` = 1 order by `level`, `title`";	
		$con_links = $this->dbQuery($qry_links); //
		
		while($res_links = mysql_fetch_row($con_links))
		{
			$st		 = '';
			$tg_id	  = $res_links[0];
			$tg_name	= $res_links[1];
			$tg_level	= $res_links[2];
			
			if($selTargetId == $tg_id) {$st=' selected';}
			
			$targets .= "<option value='$tg_id' $st>(".$tg_level.") ".$tg_name."</option>";
		}
			
		return $targets;
		
	}
	
	function getTargetGroups ($selTarget = "")
	{
		$targets = '<option value=""></option>';
		
		$qry_links = "SELECT `id`, `title` FROM `afp_conf_project_targets` where `published` = 1 order by `title`";	
		$con_links = $this->dbQuery($qry_links); //
		
		while($res_links = mysql_fetch_row($con_links))
		{
			$st		 = '';
			$tg_id	  = $res_links[0];
			$tg_name	= $res_links[1];
			
			if($selTarget == $tg_name) {$st=' selected';}
			
			$targets .= "<option value='$tg_name' $st>".$tg_name."</option>";
		}
			
		return $targets;
		
	}	

	/******************************************************************
	@end :: TARGET GROUPS
	********************************************************************/
	
	
	
	
	
	
	
	/* ========================================================
		@ DROP-DOWN ARRAYS
	***********************************************************/
		
	function dropper_array_sel($array_item)
	{ 
		$out 		= "";
		if(is_array($array_item))
		{
			$arr 	    = $array_item; 	
			$out    = "<option value='".$arr['id']."' selected>".$arr['title']."</option>";
		}
		else
		{
			$out    = "<option value='".$array_item."' selected>".$array_item."</option>";
		}
		
		/*if (array_key_exists($crit, $array_item)) {
			$out    = "<option value='".$crit."' selected>".$array_item[$crit]."</option>";
		}*/		
		//echo key($array_item); exit;
		
		return $out;			
	}
	
	function dropper_array_all($array_item, $crit = '')
	{ 
		$out 			 = "";
		$selected		= "";
		$arr_list 		= $array_item; 
		
		//asort($arr_list);		
		foreach ($arr_list  as $key => $value) 						
		{		
			//if(is_array($sel_array)){ if(in_array($key, $sel_array)) { $selected = " selected";} else { $selected = ""; } }
			//if($key == $crit) { $selected=" selected "; } else {}
			$out .= "<option value='".$key."' ".$selected.">".$value['title']."</option>";				
		}		
		return $out;			
	}
	
	
	
	
	function buildTargetPeriod($p_start, $p_end, $p_type = 'y')
	{
		$out = '';
		
		if($p_type == 'y')
		{
			$p_from = date('Y', $p_start); 
			$p_to = date('Y', $p_end); 
			
			for($i = $p_from; $i <= $p_to; $i++)
			{
				$out .= '<option>'. $i .'</option>';
			}
		}
		return $out;
	}
	
	
	
	
	
}


$ddSelect	= new drop_downs;
?>