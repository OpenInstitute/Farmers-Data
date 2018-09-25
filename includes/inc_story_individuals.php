<?php

/*
===== @@ INDIVIDUAL STORIES =============================
*/
	$pipo_story 	= array();
	$dt_story_pipo 	= $dispDt->dg_story_individuals();
	/*displayArray($dt_story_pipo);*/

	foreach($dt_story_pipo as $kst => $kval)
	{
		
		$ps_key 		= $kst;
		$ps_location 	= $kval['location'];
		$ps_name 		= $kval['_name_first'];
		$ps_age 		= $kval['provider_age'];
		$ps_role 		= trim($kval['provider_role']);
		$ps_farm_produce 		= $kval['farm_produce'];
		$ps_land_size 		= $kval['land_size'];
		$ps_land_ownership 		= $kval['land_ownership'];
		$ps_share_sold 		= str_replace("we sell", "", strtolower($kval['share_sold']));
		$ps_target_market 		= $kval['target_market'];
		$ps_distance_market 		= $kval['distance_market'];
		$ps_sell_option 		= $kval['sell_option'];
		$ps_reps_total 		= $kval['_total'];
		$ps_reps_total_perc 		= $kval['_total_perc'];
		
		$sell_option 	= '';
		if($ps_sell_option == 'Personally') { 
			$sell_option =  'personally takes produce to the market.'; }
		elseif($ps_sell_option == 'Through Broker') { 
			$sell_option =  'gets produce to the market via a broker.'; }
		
		if($ps_key == 'cucu'){			
			
			$ps_ref = 'She ';
			$sell_option = ($sell_option <> '') ? $ps_ref.$sell_option : '';

			$pipo_story[] = '<div>
						<div class="containerX">

							<div class="col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12">
								<div class="col-md-4 col-sm-12">
									<div class="img-holder"><img src="assets/image/shosho-nyokabi.png" alt="Farmer" /></div>
								</div>
								<div class="col-md-8 col-sm-12 farmer-type">
									<p>
										This is <strong>'. $ps_name .'</strong> (Traditional Farmer). <br>
			'. $ps_ref .' is <strong>'. $ps_age .' years</strong> old and is a crop farmer. '. $ps_ref .' <strong>'. $ps_land_ownership .'s</strong> a piece of land that is <strong>'. $ps_land_size .'</strong>, and farms <strong>'. $ps_farm_produce.'</strong>. 
			<br> '. $ps_ref .' sells <strong>'. $ps_share_sold .'</strong> of the produce to the <strong>'. $ps_target_market .'</strong>. <br>
			The distance from '. $ps_name .'\'s farm to the market is <strong>'. $ps_distance_market .'. '. $sell_option .'</strong> 
			'. $ps_name .' lives in <strong>'. $ps_location .'</strong> location and represents <strong>'. $ps_reps_total .' </strong> other such farming households in the larger community <strong>('. $ps_reps_total_perc .'%)</strong> where the provider is aged <strong>over 55 years</strong>.
									</p>


									<div class="col-md-12 col-sm-12">
										<a href="story.php?tk=0.441190806071023&bw_farming%5B%5D=crop&bw_gender%5B%5D=Female&bw_age%5B%5D=61_plus&bw_practice%5B%5D=for%20profit">
											<button type="button" class="btn btn-default btn-lg btnmore btnfancy"><i class="fas fa-database"></i> Click to see this data</button>
										</a>
									</div>
								</div>

							</div>

						</div>
					</div>
			';

			/*$pipo_story[] = '<div><div>This is '. $ps_name .'. <br>
			'. $ps_ref .' is '. $ps_age .' years old and is a crop farmer. '. $ps_ref .' <strong>'. $ps_land_ownership .'s</strong> a piece of land that is <strong>'. $ps_land_size .'</strong>, and farms '. $ps_farm_produce.'. 
			<br> '. $ps_ref .' sells <strong>'. $ps_share_sold .'</strong> of the produce to the <strong>'. $ps_target_market .'</strong>. <br>
			The distance from '. $ps_name .'\'s farm to the market is '. $ps_distance_market .'. '. $sell_option .' 
			'. $ps_name .' lives in <strong>'. $ps_location .'</strong> location and represents <strong>'. $ps_reps_total .'</strong> other such farming households in the larger community where the provider is aged <strong>over 55 years</strong>.</div>
			<div class="txtcenter"><a href="story.php?tk=0.441190806071023&bw_farming%5B%5D=crop&bw_gender%5B%5D=Female&bw_age%5B%5D=61_plus&bw_practice%5B%5D=for%20profit" class="btn btn-default"> View Data</a></div></div>
			';*/
		}
		
		
		if($ps_key == 'golden_male'){			
			
			$ps_ref = 'He ';
			$sell_option = ($sell_option <> '') ? 'He '.$sell_option : '';

			$pipo_story[] = '<div>
						<div class="containerX">

							<div class="col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12">
								<div class="col-md-4 col-sm-12">
									<div class="img-holder"><img src="assets/image/WizzieMzae.png" alt="Farmer" /></div>
								</div>
								<div class="col-md-8 col-sm-12 farmer-type">
									<p>
										This is <strong>'. $ps_name .'</strong>.(Golden Handshake Farmer) <br>
			'. $ps_ref .' is <strong>'. $ps_age .' years</strong> old and is a crop farmer. '. $ps_ref .' <strong>'. $ps_land_ownership .'s</strong> a piece of land that is <strong>'. $ps_land_size .'</strong>, and farms <strong>'. $ps_farm_produce.'</strong>. 
			<br> '. $ps_ref .' sells <strong>'. $ps_share_sold .'</strong> of the produce to the <strong>'. $ps_target_market .'</strong>. <br>
			The distance from '. $ps_name .'\'s farm to the market is <strong>'. $ps_distance_market .'</strong>. '. $sell_option .' 
			'. $ps_name .' lives in <strong>'. $ps_location .'</strong> location and represents <strong>'. $ps_reps_total .' </strong> other such farming households in the larger community <strong>('. $ps_reps_total_perc .'%)</strong> where the provider is aged <strong>BETWEEN 40 AND 55 years</strong>.
									</p>


									<div class="col-md-12 col-sm-12">
										<a href="story.php?tk=0.30415898973400357&bw_farming%5B%5D=crop&bw_gender%5B%5D=Male&bw_age%5B%5D=41_60&bw_practice%5B%5D=for%20profit">
											<button type="button" class="btn btn-default btn-lg btnmore btnfancy"><i class="fas fa-database"></i> Click to see this data</button>
										</a>
									</div>
								</div>

							</div>

						</div>
					</div>
			';
			
			/*$pipo_story[] = '<div><div>This is '. $ps_name .'. <br>
			'. $ps_ref .' is '. $ps_age .' years old and is a crop farmer. '. $ps_ref .' <strong>'. $ps_land_ownership .'s</strong> a piece of land that is <strong>'. $ps_land_size .'</strong>, and farms '. $ps_farm_produce.'. 
			<br> '. $ps_ref .' sells <strong>'. $ps_share_sold .'</strong> of the produce to the <strong>'. $ps_target_market .'</strong>. <br>
			The distance from '. $ps_name .'\'s farm to the market is '. $ps_distance_market .'. '. $sell_option .' 
			'. $ps_name .' lives in <strong>'. $ps_location .'</strong> location and represents <strong>'. $ps_reps_total .'</strong> other such farming households in the larger community where the provider is aged <strong>BETWEEN 40 AND 55 years</strong>.</div>
			<div class="txtcenter"><a href="story.php?tk=0.30415898973400357&bw_farming%5B%5D=crop&bw_gender%5B%5D=Male&bw_age%5B%5D=41_60&bw_practice%5B%5D=for%20profit" class="btn btn-default"> View Data</a></div></div>';*/
			
		}
		
		
		if($ps_key == 'youth'){			
			
			$ps_ref 	 = ($ps_role === 'female') ?  'She ' : 'He ';
			$ps_pic 	 = ($ps_role === 'female') ?  'kahiki-farmer.png' : 'male-youth.png';
//echobr($ps_role);

			if($ps_role == '') { $ps_ref = $ps_name.' ';  $ps_pic = "male-youth.png"; }
			//$ps_pic = "female-farmer.png";

			$sell_option = ($sell_option <> '') ? $ps_ref . $sell_option : '';

			
			$pipo_story[] = '<div>
						<div class="containerX">

							<div class="col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12">
								<div class="col-md-4 col-sm-12">
									<div class="img-holder"><img src="assets/image/'.$ps_pic.'" alt="Farmer" style="width:85%" /></div>
								</div>
								<div class="col-md-8 col-sm-12 farmer-type">
									<p>
										This is <strong>'. $ps_name .'</strong>.(Hobby Farmer) <br>
			'. $ps_ref .' is <strong>'. $ps_age .' years</strong> old and is a farmer. '. $ps_ref .' <strong>'. $ps_land_ownership .'s</strong> a piece of land that is <strong>'. $ps_land_size .'</strong>, and farms '. $ps_farm_produce.'. 
			<br> '. $ps_ref .' sells <strong>'. $ps_share_sold .'</strong> of the produce to the <strong>'. $ps_target_market .'</strong>. <br>
			The distance from '. $ps_name .'\'s farm to the market is '. $ps_distance_market .'. '. $sell_option .' 
			'. $ps_name .' lives in <strong>'. $ps_location .'</strong> location and represents <strong>'. $ps_reps_total .' </strong> other such youthful farming households in the larger community <strong>('. $ps_reps_total_perc .'%)</strong> where the provider is aged <strong>BETWEEN 25 AND 35 years</strong>.
									</p>


									<div class="col-md-12 col-sm-12">
										<a href="story.php?tk=0.5804404556234419&bw_farming%5B%5D=livestock&bw_age%5B%5D=18_40&bw_practice%5B%5D=for%20profit">
											<button type="button" class="btn btn-default btn-lg btnmore btnfancy"><i class="fas fa-database"></i> Click to see this data</button>
										</a>
									</div>
								</div>

							</div>

						</div>
					</div>
			';
			
			
			/*$pipo_story[] = '<div><div>This is '. $ps_name .'. <br>
			'. $ps_ref .' is '. $ps_age .' years old and is a farmer. '. $ps_ref .' <strong>'. $ps_land_ownership .'s</strong> a piece of land that is <strong>'. $ps_land_size .'</strong>, and farms '. $ps_farm_produce.'. 
			<br> '. $ps_ref .' sells <strong>'. $ps_share_sold .'</strong> of the produce to the <strong>'. $ps_target_market .'</strong>. <br>
			The distance from '. $ps_name .'\'s farm to the market is '. $ps_distance_market .'. '. $sell_option .' 
			'. $ps_name .' lives in <strong>'. $ps_location .'</strong> location and represents <strong>'. $ps_reps_total .'</strong> other such youthful farming households in the larger community where the provider is aged <strong>BETWEEN 25 AND 35 years</strong>.</div>
			<div class="txtcenter"><a href="story.php?tk=0.5804404556234419&bw_farming%5B%5D=livestock&bw_age%5B%5D=18_40&bw_practice%5B%5D=for%20profit" class="btn btn-default"> View Data</a></div></div>';*/
			
		}	


	}
	
	echo '<section id="next">
			<div class="row intro bgBrown">
			<div class="example__slider scenarioData">
			<h1 class="txtwhite txtcenter padd20_0">Meet the Community</h1>
			<div class="bxslider">';
	echo implode('', $pipo_story);
	echo '	</div>
			</div>
			</div>
		</section>';
	


/*
===== @@ END :: INDIVIDUAL STORIES =============================
*/

?>