<?php

class data_arrays extends master
{
	
	public $menuLong = array();
	
	public $user_rights = array();
	public $user_list   = array();
	
	public $role_priv_list   = array();
	
	public $wpRS = array();
	public $wpOC = array();
	public $wpACP = array();
	public $wpACS = array();
	
	public $numActivityReports = array();
	
	
	
	var $menu;
	var $key;
	
	public $coom;
	public $coom_b;
	
	var $out;
	var $parent;
	var $h_lnk;
	var $h_cnt;
	var $sel_crit;
	var $com_active;
	
	
	/* ========================================================
	@START -- WORKPLAN RESULT AREA LIST
	***********************************************************/
	
	function siteMenu()
	{
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$prj_summary = $_SESSION['project_summary']; 
		$project_id  = $prj_summary['p_id'];
		
		$sq_mainlinks = 'SELECT
    `id`
    , `entry_type`
    , `entry_index`
    , `entry_title`
    , `planned_start_date`
    , `planned_finish_date`
    , `officer_name`
    , `country`
    , `report_category`
    , `entry_position`
    , `entry_sessions`
    , `project_id`
    , `entry_type_id`
    , `parent_entry_id`, `officer_id`
FROM
    `afp_vw_proj_activity_list_all` WHERE (`entry_type_id` =1) and (`published` =1)  and (`project_id` = '.$project_id.');';
		
		//echo $sq_mainlinks; exit;
		
		$rs_mainlinks=mysql_query($sq_mainlinks, $this->dbconnect);
		$rs_mainlinks_count=mysql_num_rows($rs_mainlinks);
		
		if($rs_mainlinks_count>=1)
		{
			$menu_loop=1;
			while($cn_mainlinks=mysql_fetch_array($rs_mainlinks))
			{
				if(strlen($cn_mainlinks[7]) >= 2 or $cn_mainlinks[7] == "#" ) 
				{ $link = $cn_mainlinks[7]; } else { $link = $cn_mainlinks[8]; }
				
				$id_activity     = $cn_mainlinks[0];
				$id_project 	  = $cn_mainlinks[11];
				$entry_title 	 = trim(html_entity_decode(stripslashes($cn_mainlinks[3])));
				$officer_name 	= trim(html_entity_decode(stripslashes($cn_mainlinks[6])));
				
				$index_prj	   = $prj_summary['p_index'];
				$my_index		= $index_prj.'.'.$menu_loop;
				
				$menuItem = array 
				(						
					'id'				 => 	''.$id_activity.'',
					'entry_type'		 => 	''.$cn_mainlinks[1].'',
					'entry_index'	    => 	''.$cn_mainlinks[2].'',
					'entry_title'	    => 	''.$entry_title.'',
					'planned_start'	  => 	''.$cn_mainlinks[4].'',
					'planned_end'		=> 	''.$cn_mainlinks[5].'',
					'officer_name'	   => 	''.$officer_name.'',
					'country'			=> 	''.$cn_mainlinks[7].'',
					'report_category'	=> 	''.$cn_mainlinks[8].'',
					'entry_position'	 => 	''.$cn_mainlinks[9].'',
					'entry_sessions'	 => 	''.$cn_mainlinks[10].'',
					'entry_type_id'	  => 	''.$cn_mainlinks[12].'',
					'entry_parent_id'	=> 	''.$cn_mainlinks[13].'',
					'officer_id'		 => 	''.$cn_mainlinks[14].'',
					'my_index'		   =>    ''.$my_index.''
				);
				
				/* Set Workplan of Active Project */
				master::$projectWPFull[$project_id][$id_activity] 	   = $menuItem;	
				
				/* Set Result Areas  */			
				master::$projectWPResultArea[$project_id][$id_activity] = $id_activity;
				
				
				$this->menuLong[$cn_mainlinks[0]] = $menuItem;				
				$this->wpRS[$cn_mainlinks[0]]     = $id_activity; 
				
				
				$sq_chld = "SELECT `id`, `parent_entry_id` FROM `afp_projects_workplans` WHERE (`parent_entry_id` = '".$id_activity."')  and (`published` =1) and (`entry_type_id` =2);";
				$rs_chld = mysql_query($sq_chld, $this->dbconnect);
			
				if(mysql_num_rows($rs_chld) >=1) {						
					$this->menuLong[$id_activity]['children'] = $this->siteMenuSub($id_activity, 2, $my_index); 
				}
				
				$menu_loop +=1;
			}
		}
		
		return $this->menuLong;
	}
	
	
	
	
	/* ========================================================
	@START -- WORKPLAN OUTCOMES AND ACTIVITIES LIST
	***********************************************************/
	
	function siteMenuSub($id_parent = NULL, $type_id, $custom_index)
	{
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		if($id_parent)
		{
			
			$project_id = $_SESSION['project_summary']['p_id'];
		
			$sq_sublinks = "SELECT
    `id`
    , `entry_type`
    , `entry_index`
    , `entry_title`
    , `planned_start_date`
    , `planned_finish_date`
    , `officer_name`
    , `country`
    , `report_category`
    , `entry_position`
    , `entry_sessions`
    , `project_id`
    , `entry_type_id`
    , `parent_entry_id`, `officer_id`
FROM
    `afp_vw_proj_activity_list_all` WHERE (`parent_entry_id` = '".$id_parent."') and (`entry_type_id` = ".$type_id.") and (`published` =1)   and (`project_id` = '".$project_id."') ;";
			
			$rs_sublinks=mysql_query($sq_sublinks, $this->dbconnect);
			$rs_sublinks_count=mysql_num_rows($rs_sublinks);
			
			if($rs_sublinks_count >=1)
			{
				
				$subMain = array();
				$sub_menu_loop =1;
				
				while($cn_sublinks=mysql_fetch_array($rs_sublinks))
				{
					
					$id_link 		 = $cn_sublinks[0];
					
					$id_activity     = $cn_sublinks[0];
					$id_project 	  = $cn_sublinks[11];
					$entry_title 	 = trim(html_entity_decode(stripslashes($cn_sublinks[3])));
					$officer_name 	= trim(html_entity_decode(stripslashes($cn_sublinks[6])));
					
					$entry_type_id 	  = $cn_sublinks[12];
					
					$my_index		= $custom_index.'.'.$sub_menu_loop;
					
					
					$subItem = array 
					(						
						'id'				 => 	''.$id_activity.'',
						'entry_type'		 => 	''.$cn_sublinks[1].'',
						'entry_index'	    => 	''.$cn_sublinks[2].'',
						'entry_title'	    => 	''.$entry_title.'',
						'planned_start'	  => 	''.$cn_sublinks[4].'',
						'planned_end'		=> 	''.$cn_sublinks[5].'',
						'officer_name'	   => 	''.$officer_name.'',
						'country'			=> 	''.$cn_sublinks[7].'',
						'report_category'	=> 	''.$cn_sublinks[8].'',
						'entry_position'	 => 	''.$cn_sublinks[9].'',
						'entry_sessions'	 => 	''.$cn_sublinks[10].'',
						'entry_type_id'	  => 	''.$cn_sublinks[12].'',
						'entry_parent_id'	=> 	''.$cn_sublinks[13].'',
						'officer_id'		 => 	''.$cn_sublinks[14].'',
						'my_index'		   =>    ''.$my_index.''		
					);
				
					$subMain[$id_activity] = $subItem;
					
				/* Workplan of Active Project */
				master::$projectWPFull[$project_id][$id_activity] = $subItem;
				
				
				/* Set Result Area Outcomes */
				if($entry_type_id == 2) { 
					$entry_type_get = 4; 
					//$this->wpOC[$id_parent][$id_activity] = $subItem; 
					master::$projectWPOutcome[$id_parent][$id_activity] = $id_activity;
				}
				
				/* Set Outcome Activities */	
				if($entry_type_id == 4) { 
					$entry_type_get = 5; 
					//$this->wpACP[$id_parent][$id_activity] = $subItem; 
					master::$projectWPActivities[$id_parent][$id_activity] = $id_activity;
				}
				
				/* Set Outcome Secondary-Activities */
				if($entry_type_id == 5) { 
					$entry_type_get = 0; 
					//$this->wpACS[$id_parent][$id_activity] = $subItem; 
					master::$projectWPActivitiesSub[$id_parent][$id_activity] = $id_activity;
				}
					
					
					$sq_chld = "SELECT `id`, `parent_entry_id` FROM `afp_projects_workplans` WHERE (`parent_entry_id` = '".$id_activity."')  and (`published` =1)  and (`entry_type_id` = '".$entry_type_get."');";
					$rs_chld = mysql_query($sq_chld, $this->dbconnect);
				
					if(mysql_num_rows($rs_chld) >=1) {						
						$subMain[$id_activity]['children'] = $this->siteMenuSub($id_activity, $entry_type_get, $my_index);
					}
				
				
					$sub_menu_loop +=1;			
				}
			}
			
			return $subMain;
		
		}
	}
	
	
	
	
	/* ========================================================
	@START -- WORKPLAN ACTIVITIES SUBMITTED REPORTS
	***********************************************************/
	
	function build_Workplan_Rpt_Nums($project_id) 
	{
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$sq_act_reports = "SELECT `project_id`, `workplan_entry_id`, COUNT(DISTINCT `report_session`)
		FROM  `afp_projects_workplans_activity_reporting`
		GROUP BY `project_id`, `workplan_entry_id` HAVING `project_id` = '".$project_id."' ;";
		
		$rs_act_reports = mysql_query($sq_act_reports, $this->dbconnect);
		
		if(mysql_num_rows($rs_act_reports)>=1)
		{ 
			while($cn_act_reports = mysql_fetch_row($rs_act_reports))
			{
				$this->numActivityReports[$cn_act_reports[1]] = $cn_act_reports[2];
			}	
		}
		
		return $this->numActivityReports;
	}
	
	
	
	
	
	/* ========================================================
	@START -- WORKPLAN LIST OUTPUT
	***********************************************************/
	
	function build_Workplan ( $entryTypeId, $entryTypeArray )	//$entryTypeId, 
	{
		$us_id		 = $_SESSION['exp_member']['u_id'];
		$us_type_id 	= $_SESSION['exp_member']['u_type_id'];
		$usacc_rights  = $_SESSION['exp_member']['u_rights'];
		
		$project_id	= $_SESSION['project_summary']['p_id'];
		
		$wp_type_id 	= $entryTypeId;
		
		$wp_array 	  = $entryTypeArray;
		
		//
		
		$entry_list = "";
		
		if(is_array($wp_array))
		{
			foreach($wp_array as $key => $val)	//$ml
			{
				$ml		    = master::$projectWPFull[$project_id][$key];
				//displayArray($ml); exit;
				$wp_id		 = $ml['id'];
				$wp_type 	   = $ml['entry_type'];
				$wp_index 	  = $ml['my_index']; //$ml['entry_index'];
				$wp_title 	  = $ml['entry_title'];
				$wp_category   = $ml['report_category'];
				$wp_incharge   = $ml['officer_name'];
				$wp_country 	= $ml['country'];
				$wp_start	  = $ml['planned_start']; 
				$wp_finish	 = $ml['planned_end']; 
				$wp_sessions   = $ml['entry_sessions']; 
				$wp_officer_id = $ml['officer_id']; 
				
				$wp_link 	   = '';
				$report_count  = '';
				$perc_done 	 = '';
				$perc_logic	= '';
				
				
				if($wp_category <> '') { $wp_category = ' - <em class="txtgreen txt10">'.$wp_category.'</em>';}
				
				if($wp_finish >  $wp_start) { $wp_due = $wp_finish; } else { $wp_due = $wp_start; }
				
				if($wp_due <> '') { $wp_due = trim(date("M d Y",strtotime($wp_due))); }
				
				
				$wp_edit = ' <a href="workplans_add.php?project_id='.$project_id.'&entry_type='.$wp_type_id.'&op=edit&acti_id='.$wp_id.'" class="txtblue btn_edit" title="Edit">&nbsp;</a>';
				
				
				
				
				// ## ACTIVITY ENTRY REPORTS **********************
		
				$report_count = ''; $perc_done    = '0%'; $perc_logic   = '';
					
				
				// ## ACTIVITY ENTRY REPORTS VIEW LINKS ***********
				
				if(array_key_exists($wp_id,$this->numActivityReports)) 
				{ 
					$num_reports = $this->numActivityReports[$wp_id];
					
					if($wp_sessions <> 0) {
						$perc_done = displayDecimal(($num_reports / $wp_sessions) * 100) . '%';
						$perc_logic = ' &nbsp; &nbsp; <span class="txtgreen float_r txt10">'.$num_reports.'/'.$wp_sessions.'</span>';
					}
					elseif($wp_sessions == 0) {
						$perc_done = '100%';
						$perc_logic = ' &nbsp; &nbsp; <span class="txtgreen float_r txt10">'.$num_reports.'/'.$num_reports.'</span>';
					}
					
					$report_count = '<a href="wpreports.php?project_id='.$project_id.'&acti_id='.$wp_id.'" class="txtblue">View</a> / <b  class="txtgreen txt10">'.$num_reports.'</b> '; 
				
				}
				
				
				// ## ENTRY TYPES COMPLETION PERCENTAGE ***********
				
				if($wp_type_id == 1 or $wp_type_id == 2 or $wp_type_id == 3) { 
					$perc_done    = ''; $perc_logic   = '';
				} else {
					if($perc_logic == '') {
						$perc_logic = ' &nbsp; &nbsp; <span class="txtgreen float_r txt10">0/'.$wp_sessions.'</span>';
					}
				}
		
		
		
				// ## ENTRY TYPES STYLING *************************
				
				if($wp_type_id == 1) { 
					$wp_due = '';
					$wp_link = '';
					$tab_space = '';
					$tab_class = ' bg-result-area';  $title_class = ''; 	// txt14x
					
					$projectRA +=1;
					}
				if($wp_type_id == 2) { 
					$wp_link = '';
					$tab_space = ' style="padding-left:15px"'; 
					$tab_class = ' bg-outcome-area'; $title_class = ''; 	// boldx
					}
				if($wp_type_id == 3) { 
					$wp_link = '';
					$tab_space = ' style="padding-left:30px"'; 
					$tab_class = ' bold'; $title_class = '';
					}
				if($wp_type_id == 4) { 
					$wp_link = '<a href="wpreports_add.php?project_id='.$project_id.'&acti_id='.$wp_id.'&tk='.time().'">Add</a> / '; 
					$tab_space = ' style="padding-left:25px"'; 
					$tab_class = ''; $title_class = '';
					}
				
				if($wp_type_id == 5) { 
					$wp_link = '<a href="wpreports_add.php?project_id='.$project_id.'&acti_id='.$wp_id.'&tk='.time().'">Add</a> / '; 
					$tab_space = ' style="padding-left:30px"'; 
					$tab_class = ''; $title_class = '';
					}
			
			
			
				// ## ACCOUNT PRIVILEGES **********************
				
				if ($usacc_rights['project_workplan_edit'] == 0) { $wp_edit = ''; }
				if ($usacc_rights['project_workplan_add'] == 0) { $wp_link = ''; }
		
		
		
				
				// ## WORKPLAN DISPLAY CONFIG **********************
		
				if($wp_type_id == 2) {  $wp_due = '<b>'.$wp_due.'</b>';  }
				if($wp_type_id == 1 or $wp_type_id == 2 or $wp_type_id == 3) {  $wp_incharge = ''; $wp_country = ''; }
				
				
				//$wp_title = $wp_title . $wp_edit;
				if($wp_index <> '') {$wp_index = $wp_index.' ';}
				$wp_title = $wp_edit . $wp_index . $wp_title;
				
				if($wp_type_id <= 3)
				{
					$entry_list .= "<tr class=\"$tab_class\">
							<td class=\"$title_class\" $tab_space>$wp_type</td>
							<td class=\"$title_class\">$wp_title $wp_category</td> 
							<td nowrap>$wp_due</td>
							<td nowrap>$wp_incharge</td>
							<td>$wp_country </td>
							<td>$wp_link $report_count</td>
							<td>$perc_done $perc_logic</td>
							</tr>";
				}
				else
				{
					
					if($us_type_id == 4 or $us_type_id == 5)  
					{
						//if($wp_officer_id == $us_id)
						//{
						if($wp_officer_id <> $us_id) { $wp_link = ''; }
						if($wp_officer_id == $us_id) { $wp_incharge = '<span class="txtred">'.$wp_incharge.'</span>'; }
						
						 
						$entry_list .= "<tr class=\"$tab_class\">
							<td class=\"$title_class\" $tab_space>$wp_type</td>
							<td class=\"$title_class\">$wp_title $wp_category</td> 
							<td nowrap>$wp_due</td>
							<td nowrap>$wp_incharge</td>
							<td>$wp_country </td>
							<td>$wp_link $report_count</td>
							<td>$perc_done $perc_logic</td>
							</tr>";
						//}
					}
					else
					{
						
						$entry_list .= "<tr class=\"$tab_class\">
							<td class=\"$title_class\" $tab_space>$wp_type</td>
							<td class=\"$title_class\">$wp_title $wp_category</td> 
							<td nowrap>$wp_due</td>
							<td nowrap>$wp_incharge</td>
							<td>$wp_country </td>
							<td>$wp_link $report_count</td>
							<td>$perc_done $perc_logic</td>
							</tr>";
					
					}
				
				}
				
				
				/* Get Outcome Activities*/
				
				if($entryTypeId == 2) 
				{
					if(array_key_exists($wp_id, master::$projectWPActivities)) { //$this->wpACP
						$entry_list .= $this->build_Workplan(4, master::$projectWPActivities[$wp_id]); //$this->wpACP[$wp_id]
					}
				}
				
				if($entryTypeId == 4) 
				{
					if (array_key_exists($wp_id, master::$projectWPActivitiesSub)) { 	//$this->wpACS
						$entry_list .= $this->build_Workplan(5, master::$projectWPActivitiesSub[$wp_id]); //$this->wpACS[$wp_id]
					}	
					
				}
				
				
			}
		}
		return $entry_list;
	}





	/* ========================================================
	@START -- PROJECT ACCOUNTS FULL LIST 
	***********************************************************/
	
	function getUsersList($project_id)
	{
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$qrycrit= "";
		
	
	$sq_mainlinksXX = "SELECT `afp_projects_team`.`project_id` , `afp_projects_team`.`role_id` , `afp_conf_person_roles`.`title` AS `role_assigned` , `afp_projects_team`.`user_id` , concat_ws(' ',`afp_conf_person_list`.`firstname`, `afp_conf_person_list`.`lastname`) as `user_name` , `afp_conf_person_list`.`firstname` , `afp_conf_person_list`.`lastname` , `afp_conf_person_list`.`email` , `afp_conf_person_list`.`phone` , `afp_conf_person_list`.`country_id` , `afp_conf_person_list`.`job_role_id` , `afp_conf_person_list`.`photo` FROM `afp_projects_team` LEFT JOIN `afp_conf_person_list` ON (`afp_projects_team`.`user_id` = `afp_conf_person_list`.`id`) LEFT JOIN `afp_conf_person_roles` ON (`afp_projects_team`.`role_id` = `afp_conf_person_roles`.`id`) WHERE (`afp_projects_team`.`project_id` = ".quote_smart($project_id)." AND `afp_conf_person_list`.`published` =1);"; 
	
	$sq_mainlinks = "SELECT
    `afp_projects_team`.`project_id`
    , `afp_projects_team`.`role_id`
    , `afp_conf_person_roles`.`title` AS `role_assigned`
    , `afp_projects_team`.`user_id`
    , `afp_vw_account_list`.`name` AS `user_name`
	, `afp_vw_account_list`.`id_partner`
    , `afp_vw_account_list`.`partner_initial`
    , `afp_vw_account_list`.`location_name`
FROM
    `afp_projects_team`
    INNER JOIN `afp_vw_account_list` 
        ON (`afp_projects_team`.`user_id` = `afp_vw_account_list`.`id`)
    INNER JOIN `afp_conf_person_roles` 
        ON (`afp_projects_team`.`role_id` = `afp_conf_person_roles`.`id`)
WHERE (`afp_projects_team`.`project_id` = ".quote_smart($project_id)."
    AND `afp_vw_account_list`.`published` =1);";
	
		//echo $sq_mainlinks;
		$rs_mainlinks=mysql_query($sq_mainlinks);	//, $this->dbconnect
		$rs_mainlinks_count=mysql_num_rows($rs_mainlinks);
		
		$p_users = array();
		
		if($rs_mainlinks_count>=1)
		{
			
			while($cn_users = mysql_fetch_array($rs_mainlinks))
			{
				$proj_id    = $cn_users['project_id'];
				$role_id    = $cn_users['role_id'];
				$role_name  = clean_output($cn_users['role_assigned']);
				$user_name  = clean_output($cn_users['user_name']);
				$location_name  = clean_output($cn_users['location_name']);
				$partner_initial  = clean_output($cn_users['partner_initial']);
				$id_partner    = $cn_users['id_partner'];
				$user_id    = $cn_users['user_id'];
				
				$u_list = array 
				(
					'mem_id'       => ''.$user_id.'',
					'mem_name' 	 => ''.$user_name.'',
					'mem_role_id'  => ''.$role_id.'',
					'mem_role'     => ''.$role_name.'',
					'mem_location' => ''.$location_name.'',
					'mem_partner'  => ''.$partner_initial.''
				);
				
				$p_users[$proj_id]['_roles'][$role_id][$user_id] = $user_id;
				$p_users[$proj_id]['_users'][$user_id] = $u_list;
				
				/*$this->user_list[$cn_mainlinks[0]] = array 
				(
					'mem_id'       => ''.trim($cn_mainlinks[0]).'',
					'mem_name' 	 => ''.trim(html_entity_decode(stripslashes($cn_mainlinks[1]))).'',
					'mem_email'    => ''.trim(html_entity_decode(stripslashes($cn_mainlinks[2]))).'',
					'mem_role'     => ''.trim(html_entity_decode(stripslashes($cn_mainlinks[3]))).'',
					'mem_mobile'   => ''.trim(html_entity_decode(stripslashes($cn_mainlinks[4]))).'',
					'mem_avatar'     => ''.trim($cn_mainlinks[5]).'',
					'mem_country_id'       => ''.trim($cn_mainlinks[6]).''
				);*/			
			}
			
		}
		//$this->user_list = $p_users;
		master::$projectTeam = $p_users;
		//print_r($this->listPositions);//exit;
		//return $this->user_list;
	}
	
	
	
	
	
	/* ========================================================
	@START -- ACCOUNT TYPE PRIVILEGES
	***********************************************************/	
	
	function build_RoleRights($role_id, $adm_call = 0) 
	{
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$privList   = array();
	
		if($role_id <> '')
		{
		
		if($adm_call == 1) 
		{
		$sq_priv = "SELECT `priv_id` , `priv_code` FROM `afp_conf_privilege_list` WHERE (`priv_type` ='fmod');"; 
		
		} else 
		{
		$sq_priv = "SELECT `afp_conf_privilege_list`.`priv_id`
		, `afp_conf_privilege_list`.`priv_code`
		, `afp_conf_privilege_to_roles`.`priv_value`
	FROM
		`afp_conf_privilege_list`
		LEFT JOIN `afp_conf_privilege_to_roles` 
			ON (`afp_conf_privilege_list`.`priv_id` = `afp_conf_privilege_to_roles`.`priv_id`)
	WHERE (`afp_conf_privilege_list`.`priv_type` ='fmod' AND `afp_conf_privilege_to_roles`.`role_id` = ".quote_smart($role_id).")
		OR (`afp_conf_privilege_list`.`priv_type` ='fmod' AND ISNULL(`afp_conf_privilege_to_roles`.`role_id`));"; 
		}
		
		//echo $sq_priv;
		$rs_priv = mysql_query($sq_priv); //, $link_cn
		if(mysql_num_rows($rs_priv))
		{
			while($cn_privileges = mysql_fetch_array($rs_priv))
			{
				$priv_id 	 = $cn_privileges['priv_id'];
				$priv_code   = $cn_privileges['priv_code'];
				
				
				if($adm_call <> 1) {
					$priv_key    = $priv_code;
					$priv_value  = $cn_privileges['priv_value'];
					if($priv_value <> 1) { $priv_value = 0; }
				}
				elseif($adm_call == 1) { 
					$priv_key    = $priv_id; 
					$priv_value  = 0;
				}
				
				$privList[$priv_key] = $priv_value;
			}
			
		}
		}
	
	
		return $privList;
		
		
		
	}
	
		
	
	function build_RoleRightsXXX($role_id, $adm_call = 0) 
	{
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		// WHERE `project_id` = '".$project_id."'
		$out = '';
		$role_priv_list_gud = array();
		
		$sq_privileges = "SELECT
    `afp_conf_privilege_list`.`priv_id`
    , `afp_conf_privilege_list`.`priv_title`
    , `afp_conf_privilege_list`.`priv_desc`
    , `afp_conf_privilege_to_roles`.`role_id`
    , `afp_conf_privilege_to_roles`.`priv_value`
FROM
    `afp_conf_privilege_list`
    LEFT JOIN `afp_conf_privilege_to_roles` 
        ON (`afp_conf_privilege_list`.`priv_id` = `afp_conf_privilege_to_roles`.`priv_id`)
ORDER BY `afp_conf_privilege_list`.`priv_title` asc, `afp_conf_privilege_list`.`priv_id` ASC;";
		
		$rs_privileges = mysql_query($sq_privileges, $this->dbconnect);
		
		if(mysql_num_rows($rs_privileges)>=1)
		{ 
			while($cn_privileges = mysql_fetch_row($rs_privileges))
			{
				$isOn = "";
				
				$priv_id 	= $cn_privileges[0];
				$priv_title = $cn_privileges[1];
				$priv_name  = $cn_privileges[2];
				$priv_role  = $cn_privileges[3];
				$priv_val   = $cn_privileges[4];
				
				//if($priv_val == 1 and $priv_role == $role_id) 
				//{  $isOn = " checked "; } else { $priv_val = 0;  }
				
				if($priv_role == $role_id) 
				{  	
					 $isOn = " checked "; 
					
					 if($priv_val <> 1) { $priv_val = 0; $isOn = ""; }
					 
					 
					 if($adm_call == 0) //called from user
					 {
						$role_user_gud[$priv_title] =  $priv_val; 
					 }
					 
					 if($adm_call == 1) //called from admin
					 {
					 	$role_priv_list_gud[$priv_role][$priv_id] =  $priv_val;
					 	$outGud[$priv_id] = '<li><label><input type="checkbox" name="role_priv['.$priv_id.']"  '.$isOn.' class="radio"/> '.$priv_name.'</label></li>';
					 }
					 
				} 
				else 
				{ 	
					$priv_val = 0; $isOn = ""; 
					
					if($adm_call == 0) //called from user
					{
						$role_user_ext[$priv_title] =  $priv_val; 
					}
					
					 if($adm_call == 1) //called from admin
					 {
					 	$role_priv_list_ext[$priv_id] =  $priv_val;
					 	$outExt[$priv_id] = '<li><label><input type="checkbox" name="role_priv['.$priv_id.']" class="radio"/> '.$priv_name.'</label></li>';
					 }
					 
				}
				
				
				//$this->role_priv_list[$role_id][$priv_id] =  $priv_val;
				
				//$out .= '<li><label><input type="checkbox" name="role_priv['.$priv_id.']" value="'.$priv_val.'" '.$isOn.' class="radio"/> '.$priv_name.'</label></li>';
			}	
			
		}
		
		
		if($adm_call == 1) 
		{
			if(count($role_priv_list_gud) > 0)  { 
				$this->role_priv_list 			=  $role_priv_list_gud;
				$out = implode("", $outGud); 
			} else { 
				$this->role_priv_list[$role_id]  =  $role_priv_list_ext; 
				$out = implode("", $outExt);
			}
			return $out;
		}
		else
		{  
			if(count($role_user_gud) > 0)  { 
				$u_roles = $role_user_gud;
			} else { 
				$u_roles = $role_user_ext;
			}
			
			return $u_roles; 
		}
		
		
	}	
	
	
	/* ========================================================
	@START -- INDICATOR / TARGET TITLES
	***********************************************************/	
	
	function getIndicatorTitles ($crit, $optResult = "txt")
	{
		$out = '';
		
		$sq_qry = "SELECT `id_record`,`indicator_title`  FROM `afp_projects_themes_indicators` WHERE `id_record` = ".quote_smart($crit).";";	
		// 
		$rs_qry  = mysql_query($sq_qry); //, $this->dbconnect
		if(mysql_num_rows($rs_qry) == 1)
		{
			$cn_qry 	    = mysql_fetch_array($rs_qry);
			$id_target     = $cn_qry['id_record'];
			$title	     = clean_output($cn_qry['indicator_title']);
			
			if($optResult == "form"){
				$out = '<option value="'.$id_target.'" selected="selected">'.$title.'</option>';
			}
			elseif($optResult == "arr"){
				$out = array('id' => ''.$id_target.'', 'title' => ''.$title.'' );
			}
			elseif($optResult == "txt"){
				$out = $title;
			}
		}
		return $out;
		
	}	
	
	
/*
	@END: class
*/	
}
	

$dispData = new data_arrays;

?>