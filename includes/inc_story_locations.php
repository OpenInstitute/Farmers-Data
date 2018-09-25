<?php


/*
==== @@ LOCATIONS STORIES ==================
*/
	$loc_story 		= array();
	$loc_fm_type	= array('crop', 'livestock');
	$loc_fm_random	= array_rand($loc_fm_type,1);
	$loc_fm_sel		= $loc_fm_type[$loc_fm_random];

	$dt_story_loc 	= $dispDt->dg_story_locations();
	//displayArray($dt_story_loc);
	
	foreach($dt_story_loc as $kst => $kval)
	{
		$lc_name 		= $kst;
		$lc_total 		= $kval['hh_location_total'];
		$lc_farmers 	= $kval['hh_farm_crop_total'];
		$lc_female 		= $kval['bw_sex_female'];
		$lc_female_perc = displayPercent($lc_female, $lc_total);
		$lc_male 		= $kval['bw_sex_male'];
		$lc_male_perc 	= displayPercent($lc_male, $lc_total);
		$lc_subsistence = $kval['farm_subsistence'];
		$lc_subsistence_perc = displayPercent($lc_subsistence, $lc_total);
		$lc_profit 		= $kval['farm_profit'];
		$lc_profit_perc = displayPercent($lc_profit, $lc_total);	

		$lc_ages 		= (array) $kval['bw_age'];
		arsort($lc_ages); 
		
		$lc_ages_high	= key($lc_ages);
		$lc_ages_curr	= current($lc_ages);
		$lc_ages_curr_perc	= displayPercent($lc_ages_curr, $lc_total);


		$loc_story[] = '<div>
				<div class="col-md-12" >
						<div class="col-md-8 col-sm-12 locationData">
							<p class="introtxt population"><strong>'.$lc_name.'</strong> location recorded a total of <b>'.displayFloat($lc_total).'</b> respondents. Out of these, there were <strong>'.displayFloat($lc_farmers).'</strong> <em>crop farming</em> households, of which those led by <strong><em>women</em></strong> was <strong>'. $lc_female_perc .'%</strong>, while those led by <strong><em>men</em></strong> was <strong>'. $lc_male_perc .'%</strong>.</p>

							<p class="introtxt population">The highest number of farming households by <em>age of provider</em> were aged <strong>'. $sec_ages_two[$lc_ages_high] .'</strong> with <strong>'. $lc_ages_curr .'</strong> records ('. $lc_ages_curr_perc .'%)</p>

							<p class="introtxt population">Those who practice <em>subsistence only</em> farming was recorded at <strong>'. $lc_subsistence_perc .'%</strong>. Comparatively, those who practice <em>BOTH subsistence and profit farming</em> was <strong>'. $lc_profit_perc .'% </strong></p>
						</div>
						<div class="col-md-4 col-sm-12 population-stat">
							<div class="pop">
								<p><i class="fas fa-male fa-3x"></i> '. $lc_male .'</p>
								<p><i class="fas fa-female fa-3x"></i> '. $lc_female .'</p>
							</div>
							
							<div class="txtcenter"><a href="story.php?tk=0.621761168551733&bw_farming%5B%5D=crop&bw_location%5B%5D='.strtolower($lc_name).'&bw_age%5B%5D=41_60&bw_practice%5B%5D=subsistence" class="btn btn-default btn-outline col-md-8 pull-center"> View Data</a></div>
							
						</div>
						<div class="col-md-12" >
							
						</div>
				</div></div>';


	}

	echo '<h1 class="txtwhite txtcenter padd20_0">Location Highlights</h1>';
	echo '<div class="bxslider_location">';
	echo implode('', $loc_story);
	echo '</div>';
/*
============================================================
*/

?>