<?php include("zscript_head.php");  ?>


<?php include("includes/wrap_line_head.php"); ?>


<?php //include("includes/dash_home_intro.php"); ?>



<div class="" style="">


	<?php include("includes/dash_nav_locations.php"); ?>
	
	<div class="subcolumns"><div class="container" style="background: #FFF;min-height:600px; border: 1px solid #DDDDDD;">	
	
	<div class="col-md-12X">
		<h2 class="txtcenter noborder"><?php echo @$sec_names[$sec_id]; ?></h2>
		</div>
<?php 
		

		
		
		
		
		
		$tb_data = '';
		$tb_farming_type = '';
		$tb_market_distance = '';
		
		
	//displayArray($request);
	//include("includes/dash_agri_home.php");
		$sq_crit = "";
		if($request['lcn'] <> ''){
			$sq_crit = " and `location` = ".q_si($request['lcn'])." ";
		}
		
	if($request['sec'] == 'crop')
	{
		include("includes/dash_agri_crop_widgets.php"); 
		
		
		
		$sq_farming_type = "SELECT
    `location`
    , `sub_location`
    , `do_you_farm_crops_in_property`
    , `do_you_practice_subsistence_or_for_profit_farming`
    , COUNT(`post_id`) AS `records`
FROM
    `".DT_TABLE_EXCEL."`
WHERE (`do_you_farm_crops_in_property` ='Yes' AND `do_you_practice_subsistence_or_for_profit_farming` <>'' $sq_crit  or `own_livestock_or_farm_animal_in_property` ='Yes'  and `do_you_practice_subsistence_or_for_profit_farming` <> '' $sq_crit)
GROUP BY `location`, `sub_location`, `do_you_practice_subsistence_or_for_profit_farming`;";	
			$rs_farming_type = $cndb->dbQueryFetch($sq_farming_type);
		/*displayArray($rs_farming_type);*/
		$tb_farming_type = autoTable($rs_farming_type);
		
		
		$sq_market_distance = "SELECT
					`location`
					, `sub_location`
					, `what_distance_nearest_market`
					, COUNT(`post_id`) AS `records`
				FROM
					`".DT_TABLE_EXCEL."`
				WHERE (`do_you_farm_crops_in_property` ='Yes' and `what_distance_nearest_market` <>'' $sq_crit)
				GROUP BY `location`, `sub_location`, `what_distance_nearest_market`;";		
			
			$rs_market_distance = $cndb->dbQueryFetch($sq_market_distance);
		//displayArray($result_a);
		
		$tb_market_distance = autoTable($rs_market_distance);
		
		/*
		$col_keys = array_keys(current($result_a));
		
		$act_rpt_row = array();
		$act_rpt_list = '';
		$i  = 1;
		foreach($result_a as $t => $rw)
		{
			$act_rpt_row = array();
			foreach($col_keys as $col_id)
			{
				if(array_key_exists($col_id, $rw)){
					$act_rpt_row[] = '<td>'. $rw[$col_id] .'</td>';
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
		
		?>
		<div class="padd20">
		<table class="forms colored jdtableX display dataTable table-responsive">
		 <thead>
			<tr>
			   <th>#</th>
				<th><?php echo $col_names; ?></th>
			</tr>
			</thead>
			<tbody>
			<?php echo $act_rpt_list; ?>
		  </tbody>
		  <tfoot>
		  <tr>
			<th colspan="<?php echo $col_number+1; ?>">&nbsp;</th>
		  </tr>
		  </tfoot>
		</table>
		</div>
		<?php
		*/
	}
	
		
		
		
		
	//include("includes/dash_property_home.php"); 
	if($request['sec'] == 'prop_own'){
		include("includes/dash_property_owners_widget.php");		
	}
		
		
		
		
		
	if($request['sec'] == 'livestock'){
				$sq_livestock = "SELECT
			`location`
			, `sub_location`
			, `own_livestock_or_farm_animal_in_property`
			, COUNT(`post_id`) AS `records`
			
		FROM
			`".DT_TABLE_EXCEL."`
		WHERE (`own_livestock_or_farm_animal_in_property` ='Yes' $sq_crit)
		GROUP BY `location`, `sub_location`, `own_livestock_or_farm_animal_in_property`;";	
		/*, GROUP_CONCAT( `yes_what_livestock_do_you_keep` SEPARATOR ',') AS `livestock_kept`*/
				$rs_livestock = $cndb->dbQueryFetch($sq_livestock);	
				$tb_livestock = autoTable($rs_livestock);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_livestock; ?></div>
		<?php
	}	
		
		
		
	if($request['sec'] == 'crop_land'){
				$sq_qry = "SELECT `location`, `sub_location`
						, `do_you_farm_crops_in_property`
						, `do_you_own_or_lease_land_are_farming`
						, COUNT(`post_id`) AS `records`
					FROM
						`".DT_TABLE_EXCEL."`
					WHERE (`do_you_farm_crops_in_property` ='Yes' and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit or `own_livestock_or_farm_animal_in_property` ='Yes'  and `do_you_own_or_lease_land_are_farming` <> '' $sq_crit )
					GROUP BY `location`, `sub_location`, `do_you_own_or_lease_land_are_farming`;";	
		
				$rs_qry = $cndb->dbQueryFetch($sq_qry);	
				$tb_qry = autoTable($rs_qry);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_qry; ?></div>
		<?php
	}	
		
		
		
	if($request['sec'] == 'edu_provider'){
				$sq_qry = "SELECT
						`location`
						, `sub_location`
						, `provider_highest_education`
						, COUNT(`post_id`) AS `records`
					FROM
						`".DT_TABLE_EXCEL."`
					WHERE (`provider_highest_education` <>''  $sq_crit)
					GROUP BY `location`, `sub_location`, `provider_highest_education`;";	
		
				$rs_qry = $cndb->dbQueryFetch($sq_qry);	
				$tb_qry = autoTable($rs_qry);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_qry; ?></div>
		<?php
	}	
		
		
	
			
	if($request['sec'] == 'edu_ctotal')
	{
				
		$sq_qry = "SELECT
    `".DT_TABLE_EXCEL."`.`location`
    , `".DT_TABLE_EXCEL."`.`sub_location`
    , COUNT(`vw_data_c24_subdata`.`id`) AS `records`
FROM
    `".DT_TABLE_EXCEL."`
    INNER JOIN `vw_data_c24_subdata` 
        ON (`".DT_TABLE_EXCEL."`.`post_id` = `vw_data_c24_subdata`.`post_id`)
WHERE (`".DT_TABLE_EXCEL."`.`location` <>'' AND `vw_data_c24_subdata`.`post_key` ='education_information'  $sq_crit)
GROUP BY `".DT_TABLE_EXCEL."`.`location`, `".DT_TABLE_EXCEL."`.`sub_location`;";
		/*echobr($sq_qry);*/
		
				$rs_livestock = $cndb->dbQueryFetch($sq_qry);	
				$tb_livestock = autoTable($rs_livestock);
		?>
		<div id="tb_livestock"><!--<h3 class="txtcenter">Data</h3>--><?php echo $tb_livestock; ?></div>
		<?php
	}
		
		
	
			
	if($request['sec'] == 'edu_cgender')
	{
				
		$sq_qry = "	SELECT
							`".DT_TABLE_EXCEL."`.`location`
							, `".DT_TABLE_EXCEL."`.`sub_location`
							
							, CASE WHEN(isnull(`vw_data_c24_subdata`.`ed_gender`) OR `vw_data_c24_subdata`.`ed_gender` = '') THEN 'Unspecified' ELSE `vw_data_c24_subdata`.`ed_gender` END AS `gender`
							, COUNT(`vw_data_c24_subdata`.`id`) AS `records`
						FROM
							`".DT_TABLE_EXCEL."`
							INNER JOIN `vw_data_c24_subdata` 
							ON (`".DT_TABLE_EXCEL."`.`post_id` = `vw_data_c24_subdata`.`post_id`)
						WHERE (`".DT_TABLE_EXCEL."`.`location` <> '' and `vw_data_c24_subdata`.`post_key` ='education_information' and `vw_data_c24_subdata`.`ed_gender` <> '' and (not isnull(`vw_data_c24_subdata`.`ed_gender`)) $sq_crit)
						GROUP BY `".DT_TABLE_EXCEL."`.`location`, `".DT_TABLE_EXCEL."`.`sub_location`, `vw_data_c24_subdata`.`ed_gender`
			;";	
		/*echobr($sq_qry);*/
		
				$rs_livestock = $cndb->dbQueryFetch($sq_qry);	
				$tb_livestock = autoTable($rs_livestock);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_livestock; ?></div>
		<?php
	}	
		
	
			
	if($request['sec'] == 'prop_struct')
	{
				
		$sq_qry = "SELECT `location`, `sub_location`
				, `type_house_structure`
				, COUNT(`post_id`) AS `records`
			FROM
				`".DT_TABLE_EXCEL."`
			WHERE (`location` <>'' and `type_house_structure` <>'' $sq_crit)
			GROUP BY `location`, `sub_location`, `type_house_structure`;";	
		/*echobr($sq_qry);*/
		
				$rs_livestock = $cndb->dbQueryFetch($sq_qry);	
				$tb_livestock = autoTable($rs_livestock);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_livestock; ?></div>
		<?php
	}	
		
	
		
			
	if($request['sec'] == 'health_ills')
	{
				
		$sq_qry = "SELECT
						`".DT_TABLE_EXCEL."`.`location`
						, `vw_data_c24_subdata`.`hel_type_illness`
						, COUNT(`vw_data_c24_subdata`.`id`) AS `records`
					FROM
						`".DT_TABLE_EXCEL."`
						INNER JOIN `vw_data_c24_subdata` 
							ON (`".DT_TABLE_EXCEL."`.`post_id` = `vw_data_c24_subdata`.`post_id`)
					WHERE (`vw_data_c24_subdata`.`post_key` ='health_information' AND `vw_data_c24_subdata`.`hel_type_illness` <> '' $sq_crit)
					GROUP BY `".DT_TABLE_EXCEL."`.`location`, `vw_data_c24_subdata`.`hel_type_illness`
					ORDER BY `".DT_TABLE_EXCEL."`.`location`, `records` DESC ;";		//limit 0, 10
		/*echobr($sq_qry);*/
		
				$rs_livestock = $cndb->dbQueryFetch($sq_qry);	
				$tb_livestock = autoTable($rs_livestock);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_livestock; ?></div>
		<?php
	}	
	
		
		
		
			
	if($request['sec'] == 'hh_nhif')
	{
				
		$sq_qry = "SELECT `location`, `sub_location`, `provider_has_nhif`
				, COUNT(`post_id`) AS `records`
			FROM
				`".DT_TABLE_EXCEL."`
			WHERE ( `provider_has_nhif` <> '' $sq_crit)
			GROUP BY `location`, `sub_location`, `provider_has_nhif`;";
		/*echobr($sq_qry);*/
		
				$rs_livestock = $cndb->dbQueryFetch($sq_qry);	
				$tb_livestock = autoTable($rs_livestock);
		?>
		<div id="tb_livestock"><h3 class="txtcenter">Data</h3><?php echo $tb_livestock; ?></div>
		<?php
	}	
		
		
		
		
?>		
	<div></div>
	<div id="tb_farm_type" style="display:none"><h3 class="txtcenter">Data</h3><?php echo $tb_farming_type; ?></div>
	<div id="tb_market_distance" style="display:none"><h3 class="txtcenter">Data</h3><?php echo $tb_market_distance; ?></div>
	<div id="tb_livestock" style="display:none"><h3 class="txtcenter">Data</h3><?php //echo $tb_livestock; ?></div>
	</div>
	
	</div>
	
			
</div>		
	
	
	<?php include("includes/wrap_line_foot.php"); ?>
	
	




<script type="text/javascript">
function tabler(tb_id){	
jQuery(document).ready(function($) {
	
	if( $('#'+tb_id+'').length ){$('#'+tb_id+'').toggle(); }
	
});
}
</script>

<?php include("zscript_foot.php"); ?>





