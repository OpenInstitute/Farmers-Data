<?php

class data_ggli extends master
{

	

/* ============================================================================== 
/*	@ Household Numbers
/* ------------------------------------------------------------------------------ */
		
	function dg_households_total($cat_id, $loc='', $subloc='')
	{
		echobr(DT_TABLE_EXCEL); exit;
		$result = array();
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		if($cat_id)
		{
			$sq_qry = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `post_id` <>''  ".$sq_crit.";";		
			$result = current($this->dbQueryFetch($sq_qry));
		
		}
		return $result;
	}
	
	
	function dg_households_members($cat_id, $loc='', $subloc='')
	{
		$result = array();
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `vw_data_c24`.`location` = ".q_si($loc)." ";
		}
		if($cat_id)
		{
			$sq_qry = "SELECT COUNT(`vw_data_c24_subdata`.`id`) AS `records` FROM `vw_data_c24_subdata` INNER JOIN `vw_data_c24`  ON (`vw_data_c24_subdata`.`post_id` = `vw_data_c24`.`post_id`) WHERE (`vw_data_c24_subdata`.`post_key` ='household_information' ".$sq_crit.");";		
			$result = current($this->dbQueryFetch($sq_qry));
		
		}
		return $result;
	}
	
	
	function dg_households_per_location($cat_id, $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		if($cat_id)
		{
			$sq_qry = "SELECT
    CASE WHEN(ISNULL(`location`)) THEN 'Unspecified' ELSE `location` END AS `location`
    , CASE WHEN(ISNULL(`sub_location`)) THEN 'Unspecified' ELSE `sub_location` END AS `sub_location`
    , COUNT(`post_id`) AS `records`
FROM `vw_data_c24`
 WHERE `location` <> '' ".$sq_crit."
GROUP BY `location`, `sub_location` ;";	//echobr($sq_qry);	
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr){
				$location = $arr['location'];
				$sub_location = $arr['sub_location'];
				$records = $arr['records'];
				
				$result[$location][$sub_location] = intval($records);
			}
			
			$_cumulative_total = 0;			
			foreach(array_keys($result) as $k){
				$_total 	= intval(array_sum($result[$k]));
				$result[$k]['_total'] = $_total;
				$_cumulative_total 	  += $_total;
			}
		
			$result['_cumulative']['_total'] = $_cumulative_total;
		
		}
		$out['households_per_location'] = $result;
		
		return $out;
	}
	
	
	
	
	
/* ============================================================================== 
/*	@ AGRICULTURE
/* ------------------------------------------------------------------------------ */	
	
	
	function dg_story_farm_crop_plain ($cat_id, $cum_only='', $ops_arr='')
	{
		$out 		= array();
		$result 	= array();
		$resultx 	= array();
		$result_a 	= array();
		$result_all 	= array();
		$result_loc 	= array();
		$result_notes 	= array();
		$ops_cln 	= array();
		$loc_id		= '';
		
		
		
		$ops_arr 	= array_map("autoLower", $ops_arr);
		/*displayArray($ops_arr);*/
		
		$sq_groups 	= array();
		$sq_fields 	= array();
		$sq_cols 	= "";
		$sq_crit 	= "";
		$sq_gender_crit 	= "";
		$sq_gender_fields 	= "";
		
		$sq_loc_crit 	= "";
		$sq_age_crit 	= "";
		$sq_practice_crit 	= "";
		
		$sq_crit_totl 	= "";
		
		
		$sq_groups[] 	= " `vw_data_c24`.`do_you_farm_crops_in_property` ";
		$sq_fields[] 	= " `vw_data_c24`.`do_you_farm_crops_in_property` ";
		$sq_crit 	   .= " and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ";	
		$sq_crit_totl 	.= " and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ";	
		
		if (is_array($ops_arr)) {	
			
			if (count($ops_arr)) {
				//$sq_crit_totl 	.= " and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ";	
			}
			
			if (array_key_exists('bw_farming', $ops_arr)) {
				if(in_array('crop', $ops_arr['bw_farming'])){
					
					
				}	
				
				/*if(in_array('livestock', $ops_arr['bw_farming'])){
					$sq_groups[] = " `vw_data_c24`.`own_livestock_or_farm_animal_in_property` ";
					$sq_fields[] = " `vw_data_c24`.`own_livestock_or_farm_animal_in_property` ";
					$sq_crit .= " and `vw_data_c24`.`own_livestock_or_farm_animal_in_property` = 'yes' ";	
				}	*/						
			}
			
			
			if (array_key_exists('bw_location', $ops_arr)) {	
				$sq_groups[] = " `vw_data_c24`.`location` ";
				$sq_fields[] = " `vw_data_c24`.`location` ";
				
				
				$ops_cln 	 = q_in($ops_arr['bw_location']); 
				$sq_loc_crit 	= " and `vw_data_c24`.`location` IN (". implode(',', $ops_cln) .") ";			
				$sq_crit 	.= $sq_loc_crit; //" and `vw_data_c24`.`location` IN (". implode(',', $ops_cln) .") ";			
				$sq_crit_totl 	.= $sq_loc_crit; //" and `vw_data_c24`.`location` IN (". implode(',', $ops_cln) .") ";			
			}	
			
			
			if (array_key_exists('bw_location_sub', $ops_arr)) {		
				$sq_fields[] = " `vw_data_c24`.`sub_location` ";
				//$sq_groups 	= array(" `vw_data_c24`.`location` ", " `vw_data_c24`.`sub_location` ");		
				$sq_groups 	= array(" `vw_data_c24`.`sub_location` ");		
			} 
			
			
			if (array_key_exists('bw_edu_level', $ops_arr)) {
				$col_bw_edu_level = true;
				
				$sq_groups[] = " `vw_data_c24`.`provider_highest_education` ";
				$sq_fields[] = " `vw_data_c24`.`provider_highest_education` ";
				$sq_crit .= " and `vw_data_c24`.`provider_highest_education` IN (". implode(',', q_in($ops_arr['bw_edu_level'])) .") ";			
			}	
			
			
			if (array_key_exists('bw_practice', $ops_arr)) {
				
				if(!in_array('_clear', $ops_arr['bw_practice'])){		
					$sq_groups[] = " `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` ";
					$sq_fields[] = " `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` ";
					if(in_array('subsistence', $ops_arr['bw_practice'])){	
						$sq_practice_crit = " and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` IN ('subsistence') ";			
					}
					if(in_array('for profit', $ops_arr['bw_practice'])){	
						$sq_practice_crit = " and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` IN ('both', 'for profit') ";			
					}
					$sq_crit .= $sq_practice_crit;
				}
			}			
			
			
			if (array_key_exists('bw_gender', $ops_arr)){	
				
				$arr_gender_f = array("mother", "sister", "grandmother", "daughter", "wife", "aunt");
				$arr_gender_m = array("father", "brother", "grandfather", "son", "husband", "uncle");
				
				if(!in_array('_clear', $ops_arr['bw_gender'])){		
					//$sq_groups[] = " `vw_data_c24`.`provider_role` ";
					//$sq_fields[] = " `vw_data_c24`.`provider_role` ";
					
					//$sq_crit_totl 	.= " and `vw_data_c24`.`provider_role` <> '' ";	
					$result_notes[] = 'Gender: where is NOT null';

					if(in_array('female', $ops_arr['bw_gender'])){	
						$sq_gender_fields = " case when(`vw_data_c24`.`provider_role` = 'mother' or `vw_data_c24`.`provider_role` = 'grandmother' or `vw_data_c24`.`provider_role` = 'sister' or `vw_data_c24`.`provider_role` = 'daughter') then 'Female' end as  `provider_role` ";
						$sq_fields[] = $sq_gender_fields;
						
						$sq_crit 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_f, 1)) .") ";	
						//$sq_crit_totl 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_f, 1)) .") ";	
						$sq_gender_crit 	= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_f, 1)) .") ";	
						 
					}	

					if(in_array('male', $ops_arr['bw_gender'])){
						$sq_gender_fields = " case when(`vw_data_c24`.`provider_role` = 'father' or `vw_data_c24`.`provider_role` = 'grandfather' or `vw_data_c24`.`provider_role` = 'brother' or `vw_data_c24`.`provider_role` = 'son') then 'Male' end as  `provider_role` ";
						$sq_fields[] = $sq_gender_fields;
						
						$sq_crit 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_m)) .") ";	
						//$sq_crit_totl 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_m)) .") ";	
						$sq_gender_crit 	= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_m)) .") ";	
					}	
				}
			}	
			
			
			if (array_key_exists('bw_status', $ops_arr)) {
				
				$sq_groups[] = " `vw_data_c24`.`provider_marital_status` ";
				$sq_fields[] = " `vw_data_c24`.`provider_marital_status` ";
				$sq_crit .= " and `vw_data_c24`.`provider_marital_status` IN (". implode(',', q_in($ops_arr['bw_status'])) .") ";			
			}
			
			
			if (array_key_exists('bw_income', $ops_arr)) {
				
				$sq_groups[] = " `vw_data_c24`.`providers_monthly_income` ";
				$sq_fields[] = " `vw_data_c24`.`providers_monthly_income` ";
				$sq_crit .= " and `vw_data_c24`.`providers_monthly_income` IN (". implode(',', q_in($ops_arr['bw_income'])) .") ";			
			}
					
						
			if (array_key_exists('bw_age', $ops_arr)) {	
				
				if(!in_array('_clear', $ops_arr['bw_age'])){		
					
					//$sq_groups[] = " `vw_data_c24`.`provider_age` ";
					//$sq_fields[] = " `vw_data_c24`.`provider_age` ";

					if(in_array('18_40', $ops_arr['bw_age'])){
						$sq_age_crit	= " and `vw_data_c24`.`provider_age` <= '40' ";	
					}	

					if(in_array('31_40', $ops_arr['bw_age'])){
						$sq_age_crit	= " and `vw_data_c24`.`provider_age` > '30' and `vw_data_c24`.`provider_age` <= '40' ";	
					}		

					if(in_array('41_60', $ops_arr['bw_age'])){
						$sq_age_crit	= " and `vw_data_c24`.`provider_age` > '40' and `vw_data_c24`.`provider_age` <= '60' ";	
					}		

					if(in_array('61_plus', $ops_arr['bw_age'])){
						$sq_age_crit	= " and `vw_data_c24`.`provider_age` > '60' ";	
					}	
					$sq_crit 	.= $sq_age_crit;
				}
			}
			
			
			
			
			
			if (array_key_exists('bw_housing', $ops_arr)) 
			{
				$sq_groups[] = " `vw_data_c24`.`type_house_structure` ";
				$sq_fields[] = " `vw_data_c24`.`type_house_structure` ";
				$sq_crit 	.= " and `vw_data_c24`.`type_house_structure`  IN (". implode(',', q_in($ops_arr['bw_housing'])) .")  ";
										
			}
			
		}
		
		
		$sq_fields[] = " COUNT(`post_id`) AS `records` ";
		
		if(count($sq_fields)) {
			//$sq_cols = ", ". implode(',', $sq_fields);
			$sq_cols = " ". implode(',', $sq_fields) . " ";
		}
		
		
		if($cat_id and count($sq_fields))
		{
			
			$hh_all  	= intval($this->dg_households_total($cat_id, '')['records']); 
		/*displayArray($hh_all);*/
			$result_all['_hh_all_total'] = $hh_all; 
			
			
			if (array_key_exists('bw_location', $ops_arr)){
				$loc_id 	= current($ops_arr['bw_location']);
				$result_loc['_hh_location_name'] = $loc_id;
					
				$hh_loc  	= intval($this->dg_households_total($cat_id, $loc_id)['records']); 
			/*displayArray($hh_all);*/
				$result_loc['_hh_location_total'] = $hh_loc; 
				
				
				$sq_farm_loc = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_loc_crit ." ;";		
				$rs_farm_loc = current($this->dbQueryFetch($sq_farm_loc));
				$result_loc['_hh_location_farmers'] = $rs_farm_loc['records'];
			}
			
			
			$sq_farm_total = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ;";		
			$rs_farm_total = current($this->dbQueryFetch($sq_farm_total));
			//echobr($sq_totl);
//			displayArray($rs_totl);
			$result_all['_hh_all_farmers'] = $rs_farm_total['records'];
			
			
			
			
			
			$sq_totl = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> ''  ".$sq_crit_totl." ;";		
			$rs_totl = current($this->dbQueryFetch($sq_totl));
			//echobr($sq_totl);
//			displayArray($rs_totl);
			//$result_a['_hh_farm_loc'] = $rs_totl['records'];
			
			
			if (array_key_exists('bw_gender', $ops_arr) and current($ops_arr['bw_gender']) <> '_clear' ) 
			{
				
				$sq_gender = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ;";		
				$rs_gender = current($this->dbQueryFetch($sq_gender));
				//echobr($sq_gender);
				//displayArray($rs_gender);
				//$result_a['_hh_farm'] = $rs_gender['records'];
				
				$result_all['_hh_all_farmers_gender']['title'] = current($ops_arr['bw_gender']);
				$result_all['_hh_all_farmers_gender']['value'] = $rs_gender['records'];
				
				
				$sq_gender_loc = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ". $sq_loc_crit ." ;";		
				$rs_gender_loc = current($this->dbQueryFetch($sq_gender_loc));
				//echobr($sq_gender_loc);
				//displayArray($rs_gender);
				//$result_a['_hh_farm'] = $rs_gender['records'];
				
				$result_loc['_hh_location_farmers_gender']['title'] = current($ops_arr['bw_gender']);
				$result_loc['_hh_location_farmers_gender']['value'] = $rs_gender_loc['records'];
				
			}
			
			
			if (array_key_exists('bw_age', $ops_arr) and current($ops_arr['bw_age']) <> '_clear' ) 
			{
				
				$sq_age = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ". $sq_age_crit ." ;";		
				$rs_age = current($this->dbQueryFetch($sq_age));
				
				$result_all['_hh_all_farmers_age_group']['title'] 	= current($ops_arr['bw_age']);
				$result_all['_hh_all_farmers_age_group']['value'] 	= $rs_age['records'];
				
				
				$sq_age_loc = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ". $sq_loc_crit ." ". $sq_age_crit ."  ;";		
				$rs_age_loc = current($this->dbQueryFetch($sq_age_loc));
				
				$result_loc['_hh_location_farmers_age_group']['title'] = current($ops_arr['bw_age']);
				$result_loc['_hh_location_farmers_age_group']['value'] = $rs_age_loc['records'];
				
			}
			
			
			if (array_key_exists('bw_practice', $ops_arr) and current($ops_arr['bw_practice']) <> '_clear' ) 
			{
				//$sq_practice_crit
				$sq_pract = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ". $sq_age_crit ." ". $sq_practice_crit ." ;";		
				$rs_pract = current($this->dbQueryFetch($sq_pract));
				
				//$result_all['_hh_all_practice'] = $rs_pract;
				$result_all['_hh_all_farmers_practice']['title'] 	= current($ops_arr['bw_practice']);
				$result_all['_hh_all_farmers_practice']['value'] 	= $rs_pract['records'];
				
				
				$sq_age_loc = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ". $sq_loc_crit ." ". $sq_age_crit ." ". $sq_practice_crit ." ;";		
				$rs_age_loc = current($this->dbQueryFetch($sq_age_loc));
				
				$result_loc['_hh_location_farmers_practice']['title'] = current($ops_arr['bw_practice']);
				$result_loc['_hh_location_farmers_practice']['value'] = $rs_age_loc['records'];
				
			}
			
			
				
			
			
			
			
			
			$result_a['_all_households_data'] = $result_all;
			$result_a['_location_data'] = $result_loc;
			
			
			$sq_qry = "SELECT ". $sq_cols ."     			
				FROM `vw_data_c24`
				WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit )
				GROUP BY ". implode(',', $sq_groups) ." ;";	
			/* or  `vw_data_c24`.`post_id` <> '' $sq_crit and `vw_data_c24`.`own_livestock_or_farm_animal_in_property` = 'yes')*/
			//echobr($sq_qry);
			$result = current($this->dbQueryFetch($sq_qry)); 
			//displayArray($result);
			
			
			if (array_key_exists('bw_location', $ops_arr)){
				//$result_a['_summary'][$result['location']] = $result['records'];
				//$result_a['_summary']['title'] = $result['location'];
				//$result_a['_summary']['value'] = $result['records'];
			}
			
			
			
			
			
			//$result_a['_summary'] = $result; //$this->dbQueryFetch($sq_qry);
			
			
//			if(count($result_notes)){
//				$result_a['_notes'] = $result_notes;
//			}
			
			
			$result_subs = array();
			
			if (array_key_exists('bw_practice', $ops_arr) ) 
			{
				/* @@ FARM PRODUCTS BY PREVALENCE: */
				//$sq_crops = "SELECT COUNT(*) AS `records` FROM `vw_data_c24` WHERE `vw_data_c24`.`post_id` <> '' and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ". $sq_gender_crit ." ". $sq_age_crit ." ". $sq_practice_crit ." ;";		
				//$rs_pract = current($this->dbQueryFetch($sq_pract));
				//echobr($sq_crops);
				$sq_crops = "SELECT `location`, `do_you_farm_crops_in_property`, COUNT(`post_id`) AS `records` , GROUP_CONCAT( `yes_what_crops_do_you_farm` SEPARATOR ',') AS `crops_type` FROM `vw_data_c24` WHERE (`do_you_farm_crops_in_property` ='Yes'   $sq_crit) ;";	
				/* GROUP BY ". implode(',', $sq_groups) ." */
				//echobr($sq_crops);	
				$rs_crops = $this->dbQueryFetch($sq_crops);
			
					
				$crop_patterns[0] = "/[^0-9a-z ,]/";
	
			
 				$crops_type = strtolower( current($rs_crops)['crops_type'] );	
				$crops_type = preg_replace($crop_patterns,',',$crops_type); 
				$crops_type = str_replace(" and ", ",", $crops_type); 
				$crops_type = str_replace(" ", ",", $crops_type); 
				$crops_type = str_replace(",,", ",", $crops_type); 
				$crops_kept = explode(',', $crops_type); 
				
				$crops_kept_num = array_count_values($crops_kept);
				unset($crops_kept_num['']);
				arsort($crops_kept_num);
				//displayArray($crops_kept_num);	
				$result_subs['_produce_prevalence'] = $crops_kept_num;
			}
			
			if (array_key_exists('bw_practice', $ops_arr) and current($ops_arr['bw_practice']) == 'for profit' ) 
			{
				
				
				/* @@ FARM PRODUCE CATEGORY: Subsistence vs For-profit */
				$sq_subsistence = "SELECT `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` , COUNT(`post_id`) AS `records`    			
					FROM `vw_data_c24`
					WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` <> '' )
					GROUP BY `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` ;";	
				//". implode(',', $sq_groups) .", 

				/*$rs_subsistence = $this->dbQueryFetch($sq_subsistence, 'do_you_practice_subsistence_or_for_profit_farming');
				$_farm_cat 		= array();
				foreach($rs_subsistence as $k => $varr){ $_farm_cat[$k] = $varr['records']; }
				$result_subs['_farm_cat'] = $_farm_cat;*/
				/*echobr($sq_subsistence);
				displayArray($result_subs);*/



				/* @@ FARM PRODUCE SALES: */
				$sq_sales = "SELECT `vw_data_c24`.`what_share_your_production_do_you_family_sell` , COUNT(`post_id`) AS `records`    			
					FROM `vw_data_c24`
					WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit   )
					GROUP BY `vw_data_c24`.`what_share_your_production_do_you_family_sell` ;";	
				// and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` IN ('for profit', 'both')
				// and `vw_data_c24`.`what_share_your_production_do_you_family_sell` <> '' 


				$rs_sales 		= $this->dbQueryFetch($sq_sales, 'what_share_your_production_do_you_family_sell');
				$_prod_sales 	= array();
				foreach($rs_sales as $k => $varr){ $_prod_sales[$k] = $varr['records']; }
				$result_subs['_produce_share_sold'] = $_prod_sales;
				//echobr($sq_sales);


				/* @@ FARM PRODUCE TARGET MARKET: */
				$sq_market = "SELECT `vw_data_c24`.`what_your_target_market` , COUNT(`post_id`) AS `records`    			
					FROM `vw_data_c24`
					WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit ". $sq_practice_crit ."  )
					GROUP BY `vw_data_c24`.`what_your_target_market` ;";	
				// and `vw_data_c24`.`what_your_target_market` <> ''

				$rs_market 		= $this->dbQueryFetch($sq_market, 'what_your_target_market');
				$_prod_market 	= array();
				foreach($rs_market as $k => $varr){ $_prod_market[$k] = $varr['records']; }
				$result_subs['_produce_target_market'] = $_prod_market;



				/* @@ FARM PRODUCE DISTANCE MARKET: */
				$sq_distance = "SELECT `vw_data_c24`.`what_distance_nearest_market` , COUNT(`post_id`) AS `records`    			
					FROM `vw_data_c24`
					WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` IN ('for profit', 'both')  )
					GROUP BY `vw_data_c24`.`what_distance_nearest_market` ;";	
				// and `vw_data_c24`.`what_distance_nearest_market` <> ''

				$rs_distance 		= $this->dbQueryFetch($sq_distance, 'what_distance_nearest_market');
				$_prod_distance 	= array();
				foreach($rs_distance as $k => $varr){ $_prod_distance[$k] = $varr['records']; }
				$result_subs['_produce_distance_to_market'] = $_prod_distance;



				/* @@ FARM PRODUCE TAKE TO MARKET: */
				$sq_tomarket = "SELECT `vw_data_c24`.`how_do_you_take_your_farm_produce_market` , COUNT(`post_id`) AS `records`    			
					FROM `vw_data_c24`
					WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` IN ('for profit', 'both')  )
					GROUP BY `vw_data_c24`.`how_do_you_take_your_farm_produce_market` ;";	
				// and `vw_data_c24`.`how_do_you_take_your_farm_produce_market` <> ''

				$rs_tomarket 		= $this->dbQueryFetch($sq_tomarket, 'how_do_you_take_your_farm_produce_market');
				$_prod_tomarket 	= array();
				foreach($rs_tomarket as $k => $varr){ $_prod_tomarket[$k] = $varr['records']; }
				$result_subs['_produce_how_taken_to_market'] = $_prod_tomarket;



				/* @@ FARM ACCESS TO FERTILIZER: */
				$sq_fertilizer = "SELECT `vw_data_c24`.`how_do_you_access_fertilizer` , COUNT(`post_id`) AS `records`    			
					FROM `vw_data_c24`
					WHERE ( `vw_data_c24`.`post_id` <> '' $sq_crit and `vw_data_c24`.`do_you_practice_subsistence_or_for_profit_farming` IN ('for profit', 'both')  )
					GROUP BY `vw_data_c24`.`how_do_you_access_fertilizer` ;";	
				// and `vw_data_c24`.`how_do_you_access_fertilizer` <> ''

				$rs_fertilizer 		= $this->dbQueryFetch($sq_fertilizer, 'how_do_you_access_fertilizer');
				$_prod_fertilizer 	= array();
				foreach($rs_fertilizer as $k => $varr){ $_prod_fertilizer[$k] = $varr['records']; }
				$result_subs['_produce_fertilizer_access'] = $_prod_fertilizer;

				
				
				
				

			}
			
			
			if(count($result_subs)){
				$result_a['_location_subdata'] = $result_subs;
			}
			
			//displayArray($result_subs);
			return $result_a;
			
		}
		
	}
	
	
	
	function dg_story_farm_crop_subs ($col, $cum_only='', $ops_arr='') {
		
		
	}
	
	
	function dg_story_farm_crop_subs_XXX ($cat_id, $cum_only='', $ops_arr='')
	{
		$out 		= array();
		$result 	= array();
		$resultx 	= array();
		$result_a 	= array();
		$ops_cln 	= array();
		
		
		$ops_arr 	= array_map("autoLower", $ops_arr);
		displayArray($ops_arr);
		
		$sq_groups 	= array();
		$sq_fields 	= array();
		$sq_cols 	= "";
		$sq_crit 	= "";
		
		if (is_array($ops_arr)) {	
			
			
			if (array_key_exists('bw_location', $ops_arr)) {	
				$sq_groups[] = " `vw_data_c24`.`location` ";
				$sq_fields[] = " `vw_data_c24`.`location` ";
				
				$ops_cln = q_in($ops_arr['bw_location']); 
				$sq_crit .= " and `vw_data_c24`.`location` IN (". implode(',', $ops_cln) .") ";			
			}	
			
			if (array_key_exists('bw_location_sub', $ops_arr)) {		
				$sq_fields[] = " `vw_data_c24`.`sub_location` ";
				//$sq_groups 	= array(" `vw_data_c24`.`location` ", " `vw_data_c24`.`sub_location` ");		
				$sq_groups 	= array(" `vw_data_c24`.`sub_location` ");		
			} 
			
			if (array_key_exists('bw_edu_level', $ops_arr)) {
				$col_bw_edu_level = true;
				
				$sq_groups[] = " `vw_data_c24`.`provider_highest_education` ";
				$sq_fields[] = " `vw_data_c24`.`provider_highest_education` ";
				$sq_crit .= " and `vw_data_c24`.`provider_highest_education` IN (". implode(',', q_in($ops_arr['bw_edu_level'])) .") ";			
			}			
			
			
			if (array_key_exists('bw_gender', $ops_arr)) 
			{	
				$arr_gender_f = array("mother", "sister", "grandmother", "daughter", "wife", "aunt");
				$arr_gender_m = array("father", "brother", "grandfather", "son", "husband", "uncle");
				
				if(!in_array('_clear', $ops_arr['bw_gender'])){		
					//$sq_groups[] = " `vw_data_c24`.`provider_role` ";
					//$sq_fields[] = " `vw_data_c24`.`provider_role` ";

					if(in_array('female', $ops_arr['bw_gender'])){	
						$sq_fields[] = " case when(`vw_data_c24`.`provider_role` = 'mother' or `vw_data_c24`.`provider_role` = 'grandmother' or `vw_data_c24`.`provider_role` = 'sister' or `vw_data_c24`.`provider_role` = 'daughter') then 'Female' end as  `provider_role` ";
						$sq_crit 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_f, 1)) .") ";	
					}	

					if(in_array('male', $ops_arr['bw_gender'])){
						$sq_fields[] = " case when(`vw_data_c24`.`provider_role` = 'father' or `vw_data_c24`.`provider_role` = 'grandfather' or `vw_data_c24`.`provider_role` = 'brother' or `vw_data_c24`.`provider_role` = 'son') then 'Male' end as  `provider_role` ";
						$sq_crit 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_m)) .") ";	
					}	
				}
			}	
			
			
			if (array_key_exists('bw_status', $ops_arr)) {
				
				$sq_groups[] = " `vw_data_c24`.`provider_marital_status` ";
				$sq_fields[] = " `vw_data_c24`.`provider_marital_status` ";
				$sq_crit .= " and `vw_data_c24`.`provider_marital_status` IN (". implode(',', q_in($ops_arr['bw_status'])) .") ";			
			}
			
			
			if (array_key_exists('bw_income', $ops_arr)) {
				
				$sq_groups[] = " `vw_data_c24`.`providers_monthly_income` ";
				$sq_fields[] = " `vw_data_c24`.`providers_monthly_income` ";
				$sq_crit .= " and `vw_data_c24`.`providers_monthly_income` IN (". implode(',', q_in($ops_arr['bw_income'])) .") ";			
			}
					
			
			
			if (array_key_exists('bw_age', $ops_arr)) 
			{		
				if(!in_array('_clear', $ops_arr['bw_age'])){		
					
					//$sq_groups[] = " `vw_data_c24`.`provider_age` ";
					//$sq_fields[] = " `vw_data_c24`.`provider_age` ";

					if(in_array('18_30', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` <= '30' ";	
					}	

					if(in_array('31_40', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` > '30' and `vw_data_c24`.`provider_age` <= '40' ";	
					}		

					if(in_array('41_60', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` > '40' and `vw_data_c24`.`provider_age` <= '60' ";	
					}		

					if(in_array('61_plus', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` > '60' ";	
					}	
				}
			}
			
			
			if (array_key_exists('bw_farming', $ops_arr)) {
				if(in_array('crop', $ops_arr['bw_farming'])){
					$sq_groups[] = " `vw_data_c24`.`do_you_farm_crops_in_property` ";
					$sq_fields[] = " `vw_data_c24`.`do_you_farm_crops_in_property` ";
					$sq_crit 	.= " and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ";	
				}	
				
				if(in_array('livestock', $ops_arr['bw_farming'])){
					$sq_groups[] = " `vw_data_c24`.`own_livestock_or_farm_animal_in_property` ";
					$sq_fields[] = " `vw_data_c24`.`own_livestock_or_farm_animal_in_property` ";
					$sq_crit .= " and `vw_data_c24`.`own_livestock_or_farm_animal_in_property` = 'yes' ";	
				}							
			}
			
			
			if (array_key_exists('bw_housing', $ops_arr)) 
			{
				$sq_groups[] = " `vw_data_c24`.`type_house_structure` ";
				$sq_fields[] = " `vw_data_c24`.`type_house_structure` ";
				$sq_crit 	.= " and `vw_data_c24`.`type_house_structure`  IN (". implode(',', q_in($ops_arr['bw_housing'])) .")  ";
										
			}
			
		}
		
		$sq_fields[] = " COUNT(`post_id`) AS `records` ";
		
		if(count($sq_fields)) {
			//$sq_cols = ", ". implode(',', $sq_fields);
			$sq_cols = " ". implode(',', $sq_fields) . " ";
		}
		
		
		if($cat_id and count($sq_fields))
		{
			//, GROUP_CONCAT( `yes_what_livestock_do_you_keep` SEPARATOR ',') AS `livestock_kept`
			//, `vw_data_c24`.`provider_highest_education`
			//`vw_data_c24`.`location` 
			$sq_qry = "SELECT ". $sq_cols ." 
    			
				FROM `vw_data_c24`
				WHERE (`vw_data_c24`.`post_id` <> '' $sq_crit)
				GROUP BY ". implode(',', $sq_groups) ." ;";	
			//echobr($sq_qry);
			$result_a = $this->dbQueryFetch($sq_qry);
			
			return $result_a;
			//displayArray($result_a);
			
			/*
			foreach($result_a as $arr){				
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');	
				$bw_ed 			= $arr['provider_highest_education'];
				$records 		= $arr['records'];		
								
				$result[$location][$sub_location] = intval($records);
				
				if($col_bw_edu_level == true)
				{ $resultx[$location][$sub_location][$bw_ed] = intval($records); }
			}
			
			
			if( count($resultx)){
				$result = $resultx;
			}
			
			$_cumulative_total  = 0;
			$_highest_val 		= 0;
			$_highest_key 		= '';
			//displayArray($result); 
			
			
			foreach(array_keys($result) as $k)
			{ 
				$_total			= intval(array_sum($result[$k]));
				
				if(is_array(current($result[$k])))
				{ $_total = 0;
					foreach(array_keys($result[$k]) as $sl){ 
						$_total += intval(array_sum($result[$k][$sl]));
					}
				}
				
				$result[$k]['_total'] 	= $_total;
				$_cumulative_total 	   += $_total;
				
				if($_total > $_highest_val){$_highest_val = $_total;$_highest_key = $k;}
			}
			
			$result['_cumulative']['_total'] = $_cumulative_total;
			$result['_cumulative']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
			*/
		}
		
		/*$out['search_combo'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative'];
		} else {
			return $out;
		}*/
		
	}
	
	
	
	
	
	function dg_search_combo ($cat_id, $cum_only='', $ops_arr='')
	{
		$out 		= array();
		$result 	= array();
		$resultx 	= array();
		$result_a 	= array();
		$ops_cln 	= array();
		
		$col_bw_edu_level = false;		$col_bw_edu_level_key = "provider_highest_education";
		
		$ops_arr 	= array_map("autoLower", $ops_arr);
		/*displayArray($ops_arr);*/
		
		$sq_groups 	= array();
		$sq_fields 	= array();
		$sq_cols 	= "";
		$sq_crit 	= "";
		
		if (is_array($ops_arr)) {	
			
			
			$sq_fields[] = " `vw_data_c24`.`location` ";
			
			if (array_key_exists('bw_location', $ops_arr)) {	
				$sq_groups[] = " `vw_data_c24`.`location` ";
				
				
				$ops_cln = q_in($ops_arr['bw_location']); 
				$sq_crit .= " and `vw_data_c24`.`location` IN (". implode(',', $ops_cln) .") ";			
			}	
			
			if (array_key_exists('bw_location_sub', $ops_arr)) {		
				$sq_fields[] = " `vw_data_c24`.`sub_location` ";
				//$sq_groups 	= array(" `vw_data_c24`.`location` ", " `vw_data_c24`.`sub_location` ");		
				$sq_groups 	= array(" `vw_data_c24`.`sub_location` ");		
			} 
			
			if (array_key_exists('bw_edu_level', $ops_arr)) {
				$col_bw_edu_level = true;
				
				$sq_groups[] = " `vw_data_c24`.`provider_highest_education` ";
				$sq_fields[] = " `vw_data_c24`.`".$col_bw_edu_level_key."` ";
				$sq_crit .= " and `vw_data_c24`.`".$col_bw_edu_level_key."` IN (". implode(',', q_in($ops_arr['bw_edu_level'])) .") ";			
			}			
			
			
			if (array_key_exists('bw_gender', $ops_arr)) 
			{	
				$arr_gender_f = array("mother", "sister", "grandmother", "daughter", "wife", "aunt");
				$arr_gender_m = array("father", "brother", "grandfather", "son", "husband", "uncle");
				
				if(!in_array('_clear', $ops_arr['bw_gender'])){		
					//$sq_groups[] = " `vw_data_c24`.`provider_role` ";
					//$sq_fields[] = " `vw_data_c24`.`provider_role` ";

					if(in_array('female', $ops_arr['bw_gender'])){	
						$sq_fields[] = " case when(`vw_data_c24`.`provider_role` = 'mother' or `vw_data_c24`.`provider_role` = 'grandmother' or `vw_data_c24`.`provider_role` = 'sister' or `vw_data_c24`.`provider_role` = 'daughter') then 'Female' end as  `provider_role` ";
						$sq_crit 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_f, 1)) .") ";	
					}	

					if(in_array('male', $ops_arr['bw_gender'])){
						$sq_fields[] = " case when(`vw_data_c24`.`provider_role` = 'father' or `vw_data_c24`.`provider_role` = 'grandfather' or `vw_data_c24`.`provider_role` = 'brother' or `vw_data_c24`.`provider_role` = 'son') then 'Male' end as  `provider_role` ";
						$sq_crit 	.= " and `vw_data_c24`.`provider_role` IN (". implode(',', q_in($arr_gender_m)) .") ";	
					}	
				}
			}	
			
			
			if (array_key_exists('bw_status', $ops_arr)) {
				
				$sq_groups[] = " `vw_data_c24`.`provider_marital_status` ";
				$sq_fields[] = " `vw_data_c24`.`provider_marital_status` ";
				$sq_crit .= " and `vw_data_c24`.`provider_marital_status` IN (". implode(',', q_in($ops_arr['bw_status'])) .") ";			
			}
			
			
			if (array_key_exists('bw_income', $ops_arr)) {
				
				$sq_groups[] = " `vw_data_c24`.`providers_monthly_income` ";
				$sq_fields[] = " `vw_data_c24`.`providers_monthly_income` ";
				$sq_crit .= " and `vw_data_c24`.`providers_monthly_income` IN (". implode(',', q_in($ops_arr['bw_income'])) .") ";			
			}
					
			
			
			if (array_key_exists('bw_age', $ops_arr)) 
			{		
				if(!in_array('_clear', $ops_arr['bw_age'])){		
					
					//$sq_groups[] = " `vw_data_c24`.`provider_age` ";
					//$sq_fields[] = " `vw_data_c24`.`provider_age` ";

					if(in_array('18_30', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` <= '30' ";	
					}	

					if(in_array('31_40', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` > '30' and `vw_data_c24`.`provider_age` <= '40' ";	
					}		

					if(in_array('41_60', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` > '40' and `vw_data_c24`.`provider_age` <= '60' ";	
					}		

					if(in_array('61_plus', $ops_arr['bw_age'])){
						$sq_crit 	.= " and `vw_data_c24`.`provider_age` > '60' ";	
					}	
				}
			}
			
			
			if (array_key_exists('bw_farming', $ops_arr)) {
				if(in_array('crop', $ops_arr['bw_farming'])){
					$sq_fields[] = " `vw_data_c24`.`do_you_farm_crops_in_property` ";
					$sq_crit 	.= " and `vw_data_c24`.`do_you_farm_crops_in_property` = 'yes' ";	
				}	
				
				if(in_array('livestock', $ops_arr['bw_farming'])){
					$sq_fields[] = " `vw_data_c24`.`own_livestock_or_farm_animal_in_property` ";
					$sq_crit .= " and `vw_data_c24`.`own_livestock_or_farm_animal_in_property` = 'yes' ";	
				}							
			}
			
			
			if (array_key_exists('bw_housing', $ops_arr)) 
			{
				$sq_groups[] = " `vw_data_c24`.`type_house_structure` ";
				$sq_fields[] = " `vw_data_c24`.`type_house_structure` ";
				$sq_crit 	.= " and `vw_data_c24`.`type_house_structure`  IN (". implode(',', q_in($ops_arr['bw_housing'])) .")  ";
										
			}
			
		}
		
		$sq_fields[] = " COUNT(`post_id`) AS `records` ";
		
		if(count($sq_fields)) {
			//$sq_cols = ", ". implode(',', $sq_fields);
			$sq_cols = " ". implode(',', $sq_fields) . " ";
		}
		
		
		if($cat_id)
		{
			//, GROUP_CONCAT( `yes_what_livestock_do_you_keep` SEPARATOR ',') AS `livestock_kept`
			//, `vw_data_c24`.`provider_highest_education`
			//`vw_data_c24`.`location` 
			$sq_qry = "SELECT ". $sq_cols ." 
    			
				FROM `vw_data_c24`
				WHERE (`vw_data_c24`.`post_id` <> '' $sq_crit)
				GROUP BY ". implode(',', $sq_groups) ." ;";	
			/*echobr($sq_qry);*/
			$result_a = $this->dbQueryFetch($sq_qry);
			
			return $result_a;
			//displayArray($result_a);
			
			/*
			foreach($result_a as $arr){				
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');	
				$bw_ed 			= $arr['provider_highest_education'];
				$records 		= $arr['records'];		
								
				$result[$location][$sub_location] = intval($records);
				
				if($col_bw_edu_level == true)
				{ $resultx[$location][$sub_location][$bw_ed] = intval($records); }
			}
			
			
			if( count($resultx)){
				$result = $resultx;
			}
			
			$_cumulative_total  = 0;
			$_highest_val 		= 0;
			$_highest_key 		= '';
			//displayArray($result); 
			
			
			foreach(array_keys($result) as $k)
			{ 
				$_total			= intval(array_sum($result[$k]));
				
				if(is_array(current($result[$k])))
				{ $_total = 0;
					foreach(array_keys($result[$k]) as $sl){ 
						$_total += intval(array_sum($result[$k][$sl]));
					}
				}
				
				$result[$k]['_total'] 	= $_total;
				$_cumulative_total 	   += $_total;
				
				if($_total > $_highest_val){$_highest_val = $_total;$_highest_key = $k;}
			}
			
			$result['_cumulative']['_total'] = $_cumulative_total;
			$result['_cumulative']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
			*/
		}
		
		/*$out['search_combo'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative'];
		} else {
			return $out;
		}*/
		
	}
	
	
	
	
	
	
/* ============================================================================== 
/*	@ AGRICULTURE
/* ------------------------------------------------------------------------------ */	
	
	
	function dg_agri_livestock ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		if($cat_id)
		{
			$sq_qry = "SELECT
    `location`
    , `sub_location`
    , `own_livestock_or_farm_animal_in_property`
    , COUNT(`post_id`) AS `records`
    , GROUP_CONCAT( `yes_what_livestock_do_you_keep` SEPARATOR ',') AS `livestock_kept`
FROM
    `vw_data_c24`
WHERE (`own_livestock_or_farm_animal_in_property` ='Yes' $sq_crit)
GROUP BY `location`, `sub_location`, `own_livestock_or_farm_animal_in_property`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr){
				//$location = $arr['location'];
				//$sub_location = $arr['sub_location'];
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				
				$records = $arr['records'];
				$livestock_kept = strtolower($arr['livestock_kept']);
				$livestock_kept = str_replace(" and ", ",", $livestock_kept);
				$livestock_kept = str_replace("/", ",", $livestock_kept);
				$livestock_kept = str_replace("&", ",", $livestock_kept);
				$livestock_kept = str_replace(" ", ",", $livestock_kept);
				
				$livestock = array();
				$ngombe    = explode(',', $livestock_kept); //displayArray($ngombe);
				
				$livestock = array_count_values($ngombe);
				
				/*foreach($ngombe as $item){if(strlen(trim($item)) > 2){$name = clean_title($item);$livestock[] = $name;}}*///displayArray($livestock);
				
				$result[$location][$sub_location] = intval($records);
				//$result[$location][$sub_location]['_num_livestock'] = intval($records);
				//$result[$location][$sub_location]['_type_livestock'] = $livestock;
			}
			
			$_cumulative_total = 0;
			$_highest_val = 0;
			$_highest_key = '';
			foreach(array_keys($result) as $k){
				$_total 	= intval(array_sum($result[$k]));
				$result[$k]['_total'] = $_total;
				$_cumulative_total 	  += $_total;
				
				if($_total > $_highest_val){$_highest_val = $_total;$_highest_key = $k;}
			}
			
			$result['_cumulative']['_total'] = $_cumulative_total;
			$result['_cumulative']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['number_keep_livestock'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative'];
		} else {
			return $out;
		}
		//return $result;
	}
	
	
	
	
	function dg_agri_crops ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
    `location`
    , `sub_location`
    , `do_you_farm_crops_in_property`
    , COUNT(`post_id`) AS `records`
    , GROUP_CONCAT( `yes_what_crops_do_you_farm` SEPARATOR ',') AS `crops_type`
FROM
    `vw_data_c24`
WHERE (`do_you_farm_crops_in_property` ='Yes' $sq_crit)
GROUP BY `location`, `sub_location`, `do_you_farm_crops_in_property`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				
				/*$crops_type = strtolower($arr['crops_type']);$crops_type = str_replace(" and ", ",", $crops_type);$crops_type = str_replace("/", ",", $crops_type);$crops_type = str_replace("&", ",", $crops_type);$crops_type = str_replace(" ", ",", $crops_type);$ngombe = explode(',', $crops_type); $crops = array_count_values($ngombe);*/
				
				
				$result[$location][$sub_location] = intval($records);
				//$result[$location][$sub_location]['_crops_num'] = intval($records);
				//$result[$location][$sub_location]['_crops_type_'] = $crops;
			}
						
			$_cumulative_total = 0; $_highest_val = 0; $_highest_key = '';
			
			foreach(array_keys($result) as $k){
				$_total 	= intval(array_sum($result[$k]));
				$result[$k]['_total'] = $_total;
				$_cumulative_total 	  += $_total;
				
				if($_total > $_highest_val){$_highest_val = $_total;$_highest_key = $k;}
			}
			
			$result['_cumulative']['_total'] = $_cumulative_total;
			$result['_cumulative']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['number_farm_crops'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative'];
		} else {
			return $out;
		}
	}
	
	
	
	
	
	function dg_agri_market_distance ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
					`location`
					, `sub_location`
					, `what_distance_nearest_market`
					, COUNT(`post_id`) AS `records`
				FROM
					`vw_data_c24`
				WHERE (`do_you_farm_crops_in_property` ='Yes' and `what_distance_nearest_market` <>'' $sq_crit)
				GROUP BY `location`, `sub_location`, `what_distance_nearest_market`;";		
			/*echobr($sq_qry);*/
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr) {
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['what_distance_nearest_market'], '_');
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
											 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix;}
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
				
		
		}
		$out['crops_distance_to_market'] = $result;
		//return $out;
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	
	
	function dg_agri_market_access ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
    `location`
    , `sub_location`
    , `how_do_you_take_your_farm_produce_market`
    , COUNT(`post_id`) AS `records`
FROM
    `vw_data_c24`
WHERE (`do_you_farm_crops_in_property` ='Yes' and `how_do_you_take_your_farm_produce_market` <>'' $sq_crit )
GROUP BY `location`, `sub_location`, `how_do_you_take_your_farm_produce_market`;";		/*and */
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['how_do_you_take_your_farm_produce_market'], '_', false);
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix;}
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		}
		
		$out['crops_access_to_market'] = $result;
		
		
		//return $out;
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	
	
	function dg_agri_produce_sold ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
    `location`
    , `sub_location`
    , `what_share_your_production_do_you_family_sell`
    , COUNT(`post_id`) AS `records`
FROM
    `vw_data_c24`
WHERE (`do_you_farm_crops_in_property` ='Yes' and `what_share_your_production_do_you_family_sell` <>'' $sq_crit)
GROUP BY `location`, `sub_location`, `what_share_your_production_do_you_family_sell`;";		
			$result_a = $this->dbQueryFetch($sq_qry);
			
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= $arr['what_share_your_production_do_you_family_sell'];
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
			
		
		}
		$out['crops_share_sold'] = $result;
		return $out;
	}
	
	
	
	
	
	
	function dg_agri_farming_type ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			
			$sq_qry = "SELECT
    `location`
    , `sub_location`
    , `do_you_farm_crops_in_property`
    , `do_you_practice_subsistence_or_for_profit_farming`
    , COUNT(`post_id`) AS `records`
FROM
    `vw_data_c24`
WHERE (`do_you_farm_crops_in_property` ='Yes' AND `do_you_practice_subsistence_or_for_profit_farming` <>'' $sq_crit  or `own_livestock_or_farm_animal_in_property` ='Yes'  and `do_you_practice_subsistence_or_for_profit_farming` <> '' $sq_crit)
GROUP BY `location`, `sub_location`, `do_you_practice_subsistence_or_for_profit_farming`;";	
			/*`do_you_farm_crops_in_property` ='Yes' and */
			$result_a = $this->dbQueryFetch($sq_qry);
			
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['do_you_practice_subsistence_or_for_profit_farming']);
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			//displayArray(array_keys($result));//exit;
			//displayArray($result_loc);//exit;
			//displayArray($result); exit;
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				//echobr($loc);
				//displayArray($loc_arr); 
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					//$indix == 'Own' and 
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix;}
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		
		$out['crops_farming_type'] = $result;
		//return $out;
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	function dg_agri_land_ownership($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT `location`, `sub_location`
						, `do_you_farm_crops_in_property`
						, `do_you_own_or_lease_land_are_farming`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`do_you_farm_crops_in_property` ='Yes' and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit or `own_livestock_or_farm_animal_in_property` ='Yes'  and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit )
					GROUP BY `location`, `sub_location`, `do_you_own_or_lease_land_are_farming`;";	
			/*`do_you_farm_crops_in_property` ='Yes' and */
			$result_a = $this->dbQueryFetch($sq_qry);
			
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= $arr['do_you_own_or_lease_land_are_farming'];
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			//displayArray(array_keys($result));//exit;
			//displayArray($result_loc);//exit;
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				//echobr($loc);
				//displayArray($loc_arr); 
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					
					if($indix == 'Own' and $_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc;}
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['land_ownership'] = $result;
		//return $out;
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	
	function dg_agri_land_vs_education($cat_id, $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `do_you_own_or_lease_land_are_farming`
						, `provider_highest_education`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`do_you_farm_crops_in_property` ='Yes' and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit or `own_livestock_or_farm_animal_in_property` ='Yes'  and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit )
					GROUP BY `location`, `do_you_own_or_lease_land_are_farming`, `provider_highest_education`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			$indicator_cat = array();
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				//$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$sub_location 	= $arr['do_you_own_or_lease_land_are_farming'];
				$records 		= $arr['records'];
				$indicator 		= $arr['provider_highest_education'];
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
				
				$indicator_cat[$sub_location][$indicator][] = intval($records);
			}
			
			//displayArray($indicator_group);//exit;
			//displayArray($result_loc);//exit;
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $indicator_cat as $cat_indx => $cat_arr){ 
				$_cum_tot_val = 0;
				
				foreach( $cat_arr as $_indx => $_indx_val){ 
					$tot_indx_val = array_sum($_indx_val);
					$result['_cumulative_tot'][$cat_indx][$_indx] = $tot_indx_val;
					$_cum_tot_val += $tot_indx_val;
				}
					$result['_cumulative_tot'][$cat_indx]['_total'] = $_cum_tot_val;				
			}
		
			
		}
		$out['land_vs_education'] = $result;
		return $out;
	}
	
	
	
	
	
	function dg_agri_land_vs_income($cat_id, $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `do_you_own_or_lease_land_are_farming`
						, `providers_monthly_income`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`do_you_farm_crops_in_property` ='Yes' and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit or `own_livestock_or_farm_animal_in_property` ='Yes'  and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit )
					GROUP BY `location`, `do_you_own_or_lease_land_are_farming`, `providers_monthly_income`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			$indicator_cat = array();
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				//$sub_location = $arr['sub_location'];
				$sub_location 	= $arr['do_you_own_or_lease_land_are_farming'];
				$records 		= $arr['records'];
				$indicator 		= $arr['providers_monthly_income'];
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
				
				$indicator_cat[$sub_location][$indicator][] = intval($records);
			}
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $indicator_cat as $cat_indx => $cat_arr){ 
				$_cum_tot_val = 0;
				
				foreach( $cat_arr as $_indx => $_indx_val){ 
					$tot_indx_val = array_sum($_indx_val);
					$result['_cumulative_tot'][$cat_indx][$_indx] = $tot_indx_val;
					$_cum_tot_val += $tot_indx_val;
				}
					$result['_cumulative_tot'][$cat_indx]['_total'] = $_cum_tot_val;				
			}
		
		}
		$out['land_vs_income'] = $result;
		return $out;
	}
	
	
	

	
/* ============================================================================== 
/*	@ EDUCATION
/* ------------------------------------------------------------------------------ */	
	
	
	
	function dg_edu_provider ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{	
			
			$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `provider_highest_education`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`provider_highest_education` <>''  $sq_crit)
					GROUP BY `location`, `sub_location`, `provider_highest_education`;";		
			/*echobr($sq_qry);*/
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr) {
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['provider_highest_education'], '_');
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
											 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix;}
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
				
		
		}
		$out['edu_provider'] = $result;
		//return $out;
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	
	
	function dg_edu_provider_house_structure ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `do_you_rent_or_own_house_live_in`
						, `provider_highest_education`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`do_you_rent_or_own_house_live_in` <>'' $sq_crit )
					GROUP BY `location`, `do_you_rent_or_own_house_live_in`, `provider_highest_education`;";
			
			$sq_qry = "SELECT
							`location`
							, `sub_location`
							, `provider_highest_education`
							, `type_house_structure`
							, COUNT(`post_id`) AS `records`
						FROM
							`vw_data_c24`
						WHERE (`location` <>'' $sq_crit )
						GROUP BY `location`, `provider_highest_education`, `type_house_structure`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			$indicator_cat = array();
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				//$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$sub_location 	= generate_seo_title($arr['type_house_structure']);
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['provider_highest_education'], '_');
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
				
				$indicator_cat[$sub_location][$indicator][] = intval($records);
			}
			
			//displayArray($indicator_group);//exit;
			//displayArray($result_loc);//exit;
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $indicator_cat as $cat_indx => $cat_arr){ 
				$_cum_tot_val = 0;
				
				foreach( $cat_arr as $_indx => $_indx_val){ 
					$tot_indx_val = array_sum($_indx_val);
					$result['_cumulative_tot'][$cat_indx][$_indx] = $tot_indx_val;
					$_cum_tot_val += $tot_indx_val;
				}
					$result['_cumulative_tot'][$cat_indx]['_total'] = $_cum_tot_val;				
			}
		
			
		}
		$out['edu_provider_house_structure'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
		
	}
	
	
	
	
	function dg_edu_child_total ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$_highest_val = 0; $_highest_key = '';
		
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `vw_data_c24`.`location` = ".q_si($loc)." ";
		}
		
		if($cat_id)
		{			
			
			$sq_qry = "SELECT
    `vw_data_c24`.`location`
    , `vw_data_c24`.`sub_location`
    , `vw_data_c24_subdata`.`post_key`
    , COUNT(`vw_data_c24_subdata`.`id`) AS `records`
FROM
    `vw_data_c24`
    INNER JOIN `vw_data_c24_subdata` 
        ON (`vw_data_c24`.`post_id` = `vw_data_c24_subdata`.`post_id`)
WHERE (`vw_data_c24`.`location` <>'' AND `vw_data_c24_subdata`.`post_key` ='education_information'  $sq_crit)
GROUP BY `vw_data_c24`.`location`, `vw_data_c24`.`sub_location`;";	/*echobr($sq_qry);	*/
			// AND `vw_data_c24_subdata`.`ed_name_school` <>''
			
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr){
				$location = $arr['location'];
				$sub_location = $arr['sub_location'];
				$records = $arr['records'];
				//$indicator 		= generate_seo_title($arr['do_you_rent_or_own_house_live_in']);
				
				$result[$location][$sub_location] = intval($records);
				
				/*$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);*/
			}
			
			$_cumulative_total = 0;			
			foreach(array_keys($result) as $k){
				$_total 	= intval(array_sum($result[$k]));
				$result[$k]['_total'] = $_total;
				$_cumulative_total 	  += $_total;
				
				if($_total > $_highest_val){$_highest_val = $_total; $_highest_key = $k; }
			}
		
			$result['_cumulative']['_total'] = $_cumulative_total;
			$result['_cumulative']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['edu_child_total'] = $result;
		
		//return $out;
		
		if($cum_only == 'cum'){
			return $result['_cumulative'];
		} else {
			return $out;
		}
	}
	
	
	
	function dg_edu_child_gender ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `vw_data_c24`.`location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{	
			
			$sq_qry = "	SELECT
							`vw_data_c24`.`location`
							, `vw_data_c24`.`sub_location`
							, `vw_data_c24_subdata`.`post_key`
							, COUNT(`vw_data_c24_subdata`.`id`) AS `records`
							, CASE WHEN(isnull(`vw_data_c24_subdata`.`ed_gender`) OR `vw_data_c24_subdata`.`ed_gender` = '') THEN 'Unspecified' ELSE `vw_data_c24_subdata`.`ed_gender` END AS `ed_gender`
							, `vw_data_c24_subdata`.`ed_name_school`
						FROM
							`vw_data_c24`
							INNER JOIN `vw_data_c24_subdata` 
							ON (`vw_data_c24`.`post_id` = `vw_data_c24_subdata`.`post_id`)
						WHERE (`vw_data_c24`.`location` <> '' and `vw_data_c24_subdata`.`post_key` ='education_information' and `vw_data_c24_subdata`.`ed_gender` <> '' and (not isnull(`vw_data_c24_subdata`.`ed_gender`)) $sq_crit)
						GROUP BY `vw_data_c24`.`location`, `vw_data_c24`.`sub_location`, `vw_data_c24_subdata`.`ed_gender`
			;";		
			// AND `vw_data_c24_subdata`.`ed_name_school` <>''
			/*echobr($sq_qry);*/
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr) {
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['ed_gender'], '_');
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
											 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix;}
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot']['_index'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
				
		
		}
		$out['edu_child_gender'] = $result;
		//return $out;
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	

	
/* ============================================================================== 
/*	@ PROPERTY
/* ------------------------------------------------------------------------------ */	
	
	
	
	
	function dg_property_structure ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT `location`, `sub_location`
				, `type_house_structure`
				, COUNT(`post_id`) AS `records`
			FROM
				`vw_data_c24`
			WHERE (`type_house_structure` <>'' $sq_crit)
			GROUP BY `location`, `sub_location`, `type_house_structure`;";	
			/*`do_you_farm_crops_in_property` ='Yes' and */
			$result_a = $this->dbQueryFetch($sq_qry);
			
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['type_house_structure']);
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					
					//$indix == 'own' and 
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix; }
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot']['_index'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['property_structure'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	function dg_property_ownership ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT `location`, `sub_location`
				, `do_you_rent_or_own_house_live_in`
				, COUNT(`post_id`) AS `records`
			FROM
				`vw_data_c24`
			WHERE (`do_you_rent_or_own_house_live_in` <>'' $sq_crit)
			GROUP BY `location`, `sub_location`, `do_you_rent_or_own_house_live_in`;";	
			/*`do_you_farm_crops_in_property` ='Yes' and */
			$result_a = $this->dbQueryFetch($sq_qry);
			
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['do_you_rent_or_own_house_live_in']);
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					
					//
					if($indix == 'own' and $_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix; }
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['property_ownership'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	function dg_property_vs_education ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `do_you_rent_or_own_house_live_in`
						, `provider_highest_education`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`do_you_rent_or_own_house_live_in` <>'' $sq_crit )
					GROUP BY `location`, `do_you_rent_or_own_house_live_in`, `provider_highest_education`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			$indicator_cat = array();
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				//$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$sub_location 	= generate_seo_title($arr['do_you_rent_or_own_house_live_in']);
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['provider_highest_education'], '_');
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
				
				$indicator_cat[$sub_location][$indicator][] = intval($records);
			}
			
			//displayArray($indicator_group);//exit;
			//displayArray($result_loc);//exit;
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $indicator_cat as $cat_indx => $cat_arr){ 
				$_cum_tot_val = 0;
				
				foreach( $cat_arr as $_indx => $_indx_val){ 
					$tot_indx_val = array_sum($_indx_val);
					$result['_cumulative_tot'][$cat_indx][$_indx] = $tot_indx_val;
					$_cum_tot_val += $tot_indx_val;
				}
					$result['_cumulative_tot'][$cat_indx]['_total'] = $_cum_tot_val;				
			}
		
			
		}
		$out['property_vs_education'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
		
	}
	
	
	
	
	function dg_property_vs_income ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `do_you_rent_or_own_house_live_in`
						, `providers_monthly_income`
						, COUNT(`post_id`) AS `records`
					FROM
						`vw_data_c24`
					WHERE (`do_you_rent_or_own_house_live_in` <>'' $sq_crit)
					GROUP BY `location`, `do_you_rent_or_own_house_live_in`, `providers_monthly_income`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			$indicator_cat = array();
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				//$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$sub_location 	= generate_seo_title($arr['do_you_rent_or_own_house_live_in']);
				$records 		= $arr['records'];
				$indicator 		= $arr['providers_monthly_income']; //generate_seo_title($arr['providers_monthly_income'], '_');
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
				
				$indicator_cat[$sub_location][$indicator][] = intval($records);
			}
			
			//displayArray($indicator_group);//exit;
			//displayArray($result_loc);//exit;
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $indicator_cat as $cat_indx => $cat_arr){ 
				$_cum_tot_val = 0;
				
				foreach( $cat_arr as $_indx => $_indx_val){ 
					$tot_indx_val = array_sum($_indx_val);
					$result['_cumulative_tot'][$cat_indx][$_indx] = $tot_indx_val;
					$_cum_tot_val += $tot_indx_val;
				}
					$result['_cumulative_tot'][$cat_indx]['_total'] = $_cum_tot_val;				
			}
		
			
		}
		$out['property_vs_income'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
		
	}
	
	
	
	
	
	function dg_property_business ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry = "SELECT
					`location`
					, `sub_location`
					, `do_you_rent_or_own_house_live_in`
					, COUNT(`post_id`) AS `records`
				FROM
					`vw_data_c24`
				WHERE (`do_you_conduct_any_business_in_premises` <> '' AND `do_you_conduct_any_business_in_premises` <> 'No' $sq_crit )
				GROUP BY `location`, `sub_location`, `do_you_rent_or_own_house_live_in`;";	
			
			$result_a = $this->dbQueryFetch($sq_qry);
			
			
			foreach($result_a as $arr){
				$location 		= generate_seo_title($arr['location'], '_');
				$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= generate_seo_title($arr['do_you_rent_or_own_house_live_in']);
				
				$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);
			}
			
			
			foreach( $result as $loc => $loc_arr)
			{ 	
				$_cumulative_loc = 0;
			 
				foreach( $loc_arr as $sloc => $sloc_arr){ 
					$result[$loc][$sloc]['_total'] = array_sum(array_values($sloc_arr));
				}
												 
				foreach($result_loc[$loc] as $indix => $cumulative_arr){
					$_cumulative = intval(array_sum($cumulative_arr));
					$result[$loc]['_cumulative'][$indix] = $_cumulative;
					$_cumulative_loc += $_cumulative;
					
					$_cum_tot_arr[$indix][] = $_cumulative;
					
					//$indix == 'own' and 
					if($_cumulative > $_highest_val){$_highest_val = $_cumulative; $_highest_key = $loc.': '.$indix; }
				}		
				$result[$loc]['_cumulative']['_total'] = $_cumulative_loc;
			}
			
			
			foreach( $_cum_tot_arr as $tot_indx => $tot_arr){ 
				$tot_arr_sum = array_sum(array_values($tot_arr));
				$result['_cumulative_tot']['_index'][$tot_indx] = $tot_arr_sum;
				$_cum_tot_val += $tot_arr_sum;
			}
				$result['_cumulative_tot']['_total'] = $_cum_tot_val;
				$result['_cumulative_tot']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['property_business'] = $result;
		
		if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}
	}
	
	
	
	
	
	
/* ============================================================================== 
/*	@ PROPERTY
/* ------------------------------------------------------------------------------ */	
	
	function dg_health_illness ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$result_loc = array();
		$_cum_tot_arr = array();
		$_cum_tot_val = 0;
		
		$_highest_val = 0; $_highest_key = '';
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `vw_data_c24`.`location` = ".q_si($loc)." ";
		}
		
		
		if($cat_id)
		{
			$sq_qry_total = "SELECT COUNT(`vw_data_c24_subdata`.`id`) AS `records` FROM `vw_data_c24_subdata` 
    INNER JOIN `vw_data_c24` ON (`vw_data_c24_subdata`.`post_id` = `vw_data_c24`.`post_id`)
WHERE (`vw_data_c24_subdata`.`post_key` ='health_information' AND `vw_data_c24_subdata`.`hel_type_illness` <>'' $sq_crit)
GROUP BY `vw_data_c24_subdata`.`post_key`;";	
			$rs_total = current($this->dbQueryFetch($sq_qry_total));
			//displayArray($rs_total);
			
			$sq_qry = "SELECT
						`vw_data_c24_subdata`.`post_key`
						, `vw_data_c24_subdata`.`hel_type_illness`
						, COUNT(`vw_data_c24_subdata`.`id`) AS `records`
					FROM
						`vw_data_c24`
						INNER JOIN `vw_data_c24_subdata` 
							ON (`vw_data_c24`.`post_id` = `vw_data_c24_subdata`.`post_id`)
					WHERE (`vw_data_c24_subdata`.`post_key` ='health_information' AND `vw_data_c24_subdata`.`hel_type_illness` <> '' $sq_crit)
					GROUP BY `vw_data_c24_subdata`.`hel_type_illness`
					ORDER BY `records` DESC limit 0, 10;";		
			/*echobr($sq_qry);*/
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr) {
				//$location 		= generate_seo_title($arr['location'], '_');
				//$sub_location 	= generate_seo_title($arr['sub_location'], '_');
				$records 		= $arr['records'];
				$indicator 		= $arr['hel_type_illness']; //generate_seo_title($arr['hel_type_illness'], '_');
				
				//$result[$location][$sub_location][$indicator] = intval($records);
				//$result_loc[$location][$indicator][] = intval($records);
				
				$result[] = array('name' => ucwords($indicator), 'y' => intval($records));
			}
			
			$out['_res'] = $result;
			$out['_total'] = $rs_total;
			//displayArray($result);
		
		}
		return $out;
		/*if($cum_only == 'cum'){
			return $result['_cumulative_tot'];
		} else {
			return $out;
		}*/
	}
	
	
	
	function dg_health_nhif_access ($cat_id, $cum_only='', $loc='', $subloc='', $loc_only='0', $subloc_only='0')
	{
		$out = array();
		$result = array();
		$result_a = array();
		$_highest_val = 0; $_highest_key = '';
		
		
		$sq_crit = "";
		if($loc <> ''){
			$sq_crit = " and `location` = ".q_si($loc)." ";
		}
		
		if($cat_id)
		{
			$sq_qry = "SELECT `location`, `sub_location`, `provider_has_nhif`
				, COUNT(`post_id`) AS `records`
			FROM
				`vw_data_c24`
			WHERE (`provider_has_nhif` ='yes' AND `provider_has_nhif` <> '' $sq_crit)
			GROUP BY `location`, `sub_location`, `provider_has_nhif`;";	/*echobr($sq_qry);	*/
			$result_a = $this->dbQueryFetch($sq_qry);
			
			foreach($result_a as $arr){
				$location = $arr['location'];
				$sub_location = $arr['sub_location'];
				$records = $arr['records'];
				//$indicator 		= generate_seo_title($arr['do_you_rent_or_own_house_live_in']);
				
				$result[$location][$sub_location] = intval($records);
				
				/*$result[$location][$sub_location][$indicator] = intval($records);
				$result_loc[$location][$indicator][] = intval($records);*/
			}
			
			$_cumulative_total = 0;			
			foreach(array_keys($result) as $k){
				$_total 	= intval(array_sum($result[$k]));
				$result[$k]['_total'] = $_total;
				$_cumulative_total 	  += $_total;
				
				if($_total > $_highest_val){$_highest_val = $_total; $_highest_key = $k; }
			}
		
			$result['_cumulative']['_total'] = $_cumulative_total;
			$result['_cumulative']['_highest'] = array('label' => $_highest_key, 'value' => $_highest_val);
		
		}
		$out['health_nhif_access'] = $result;
		
		//return $out;
		
		if($cum_only == 'cum'){
			return $result['_cumulative'];
		} else {
			return $out;
		}
	}

/*
* END CLASS
*/	
}

//displayArray($request);
$dispDt = new data_ggli;

$loc_id = ( isset($request['lcn'])) ? $request['lcn'] : '';
$sec_id = ( isset($request['sec'])) ? $request['sec'] : '';

$sec_names = array(
	'crop' 			=> 'Agriculture Data: <span class="txtgreen"> Crop Farming</span>',
	'crop_land' 	=> 'Agriculture Data: <span class="txtgreen"> Farming Land Ownership</span>',
	'livestock' 	=> 'Agriculture Data: <span class="txtgreen"> Livestock Farming</span>',
	'prop_own' 		=> 'Property Data: <span class="txtgreen"> Ownership</span>',
	'prop_struct' 	=> 'Property Data: <span class="txtgreen"> Housing Structures</span>',
	'health_ills' 	=> 'Health Data: <span class="txtgreen"> Common Illnesses</span>',
	'hh_nhif' 		=> 'Household Data: <span class="txtgreen"> Households with NHIF</span>',
	'edu_provider' 		=> 'Education Data: <span class="txtgreen"> Providers Education Levels</span>',
	'edu_ctotal' 		=> 'Education Data: <span class="txtgreen"> School Going Children</span>',	
	'edu_cgender' 		=> 'Education Data: <span class="txtgreen"> School Going Children By Gender</span>',	
);


$hh_all  = intval($dispDt->dg_households_total($cat_id, $loc_id)['records']); 
$hh_members  = $hh_all + intval($dispDt->dg_households_members($cat_id, $loc_id)['records']); //echobr($hh_all); echobr($hh_members);
$GLOBALS['HHOLD_ALL']  = $hh_all; 


?>
