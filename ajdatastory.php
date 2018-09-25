<?php 
require("classes/cls.constants.php"); include("classes/cls.paths.php"); 

/* ============================================================================== 
/*	SPAM BLOCK! 
/* ------------------------------------------------------------------------------ */
//if($_SERVER['REQUEST_METHOD'] !== 'POST') {  echo 'invalid request'; exit; }

/* ============================================================================== */

$cat_id 	= (array_key_exists('cat_id', $request)) ? $request['cat_id'] : 24; 
$cum_total	= '';
//$sel_ops 	= array_map("clean_request", $_POST);
$sel_ops 	= $request;
$dt_story_farm = array();

$lbl_farmtype = '';
//displayArray($sel_ops);



if (array_key_exists('bw_farming', $sel_ops)) 
{
	$lbl_farmtype = ucwords($sel_ops['bw_farming'][0]);
	
	if ($sel_ops['bw_farming'][0] == 'crop') 
	{	
		$dt_story_farm = $dispDt->dg_story_farm_crop_plain($cat_id, $cum_total, $sel_ops);		

	} elseif( $sel_ops['bw_farming'][0] == 'livestock' ) 
	{
		$dt_story_farm = $dispDt->dg_story_farm_livestock_plain($cat_id, $cum_total, $sel_ops);
	}

	$dt_region = $dt_story_farm['_all_households_data'];


?>
<style type="text/css">
	.storyWrap  section {display: inline-block; /*width: 80%;*/}
	.storyWrap  span { font-weight: bold; font-size: 110%; color: #880000; display: inline; /*float: right;*/ }
	.storyDigits { font-weight: bold; font-size: 110%; color: #880000;  }
	.storyPerc { font-weight: bold; font-size: 110%; color: #0d9b10;  }
</style>

<div class="subcolumns">
	<!-- Where stories are -->
	<div class="col-md-6">
		<h2>Basic overview of <?php echo $lbl_farmtype; ?> Farmers</h2>
		<!-- Story Wrap 1 -->
		<div class="col-md-12 storyWrap">
				<!-- <h3>Total Households Stats...</h3> -->
				<div class="col-md-12 storyWrap-inner">
					The total number of households surveyed in this project is <span><?php echo number_format($dt_region['_hh_all_total']); ?></span>. Out of these households, the number of <?php echo $lbl_farmtype; ?> farming households is <span><?php echo number_format($dt_region['_hh_all_farmers']); ?></span>.
				</div>
				
				<!-- <div class="col-md-12">
					<section><?php //echo $lbl_farmtype; ?> Farming Households:</section> &nbsp; <span><?php echo $dt_region['_hh_all_farmers']; ?></span>
				</div> -->
				
				<?php if (array_key_exists('bw_gender', $sel_ops) and $sel_ops['bw_gender'][0] <> '_clear') { ?>
					<div class="col-md-12"><section>There are <span><?php echo $dt_region['_hh_all_farmers_gender']['value']; ?></span> households recorded where the provider's gender is <b class="txtupper"><?php echo $sel_ops['bw_gender'][0]; ?></b></section> 
					&nbsp; </div>
				<?php } ?>
				
				<?php if (array_key_exists('bw_age', $sel_ops) and $sel_ops['bw_age'][0] <> '_clear') { ?>
					<div class="col-md-12"><span><?php echo $dt_region['_hh_all_farmers_age_group']['value']; ?></span> are aged <b class="txtupper"><?php echo $sec_ages[$sel_ops['bw_age'][0]]; ?></b>.</div>
				<?php } ?>
				
				<?php if (array_key_exists('bw_practice', $sel_ops) and $sel_ops['bw_practice'][0] <> '_clear') { ?>
					<div class="col-md-12"><section>Out of this figure, <span><?php echo $dt_region['_hh_all_farmers_practice']['value']; ?></span> practice <b class="txtupper"><?php echo $sel_ops['bw_practice'][0]; ?></b> Farming.</section> 
					&nbsp; </div><br/>
				<?php } ?>
				
				<?php if (array_key_exists('bw_housing', $sel_ops) and $sel_ops['bw_housing'][0] <> '_clear') { ?>
				<p>&nbsp;</p>
					<div class="col-md-12"><section><span><?php echo $dt_region['_hh_all_farmers_housing']['value']; ?></span> of them live in a <b class="txtupper"><?php echo $sel_ops['bw_housing'][0]; ?></b> <u>Housing Structure</u></section></div>
				<?php } ?>
				
		</div>
		<!-- End Story Wrap 1 -->
		<!-- Select locations code -->
		<?php if (array_key_exists('bw_location', $sel_ops) and $sel_ops['bw_location'][0] <> '_clear') { 
				$bw_location = ucwords($sel_ops['bw_location'][0]);
				$dt_location = 	$dt_story_farm['_location_data'];
		?>
		<!-- End select locations code -->

		<!-- Narrow down code -->
		<div class="col-md-12 storyWrap">
				<h3>Let us narrow down to <?php echo $bw_location; ?> location:</h3>
				<div class="col-md-10">
					<section>Total Households in <?php echo $bw_location; ?>:</section> &nbsp; <span><?php echo $dt_location['_hh_location_total']; ?></span>
				</div>
				<div class="col-md-2"><span class="storyPerc"><?php echo displayPercent($dt_location['_hh_location_total'], $dt_region['_hh_all_total']); ?>%</span></div>
				
				<div class="col-md-10">
					<section>Total Farming Households:</section> &nbsp; <span><?php echo $dt_location['_hh_location_farmers']; ?></span>
				</div>
				<div class="col-md-2"><span class="storyPerc"><?php echo displayPercent($dt_location['_hh_location_farmers'], $dt_region['_hh_all_farmers']); ?>%</span></div>
				
				<?php if (array_key_exists('bw_gender', $sel_ops) and $sel_ops['bw_gender'][0] <> '_clear') { ?>
					<div class="col-md-10"><section>Where <u>Provider Gender</u> is <b class="txtupper"><?php echo $sel_ops['bw_gender'][0]; ?></b>:</section> 
					&nbsp; <span><?php echo $dt_location['_hh_location_farmers_gender']['value']; ?></span></div>
					<div class="col-md-2"><span class="storyPerc"><?php echo displayPercent($dt_location['_hh_location_farmers_gender']['value'], $dt_region['_hh_all_farmers_gender']['value']); ?>%</span></div>
				<?php } ?>
				
				<?php if (array_key_exists('bw_age', $sel_ops) and $sel_ops['bw_age'][0] <> '_clear') { ?>
					<div class="col-md-10"><section>Where <u>Provider Age</u> is <b class="txtupper"><?php echo $sec_ages[$sel_ops['bw_age'][0]]; ?></b>: </section>
					&nbsp; <span><?php echo $dt_location['_hh_location_farmers_age_group']['value']; ?></span></div>
					<div class="col-md-2"><span class="storyPerc"><?php echo displayPercent($dt_location['_hh_location_farmers_age_group']['value'], $dt_region['_hh_all_farmers_age_group']['value']); ?>%</span></div>
				<?php } ?>
				
				<?php if (array_key_exists('bw_practice', $sel_ops) and $sel_ops['bw_practice'][0] <> '_clear') { ?>
					<div class="col-md-10"><section>Which practice <b class="txtupper"><?php echo $sel_ops['bw_practice'][0]; ?></b> Farming:</section> 
					&nbsp; <span><?php echo $dt_location['_hh_location_farmers_practice']['value']; ?></span></div>
					<div class="col-md-2"><span class="storyPerc"><?php echo displayPercent($dt_location['_hh_location_farmers_practice']['value'], $dt_region['_hh_all_farmers_practice']['value']); ?>%</span></div>
				<?php } ?>
				
				<?php if (array_key_exists('bw_housing', $sel_ops) and $sel_ops['bw_housing'][0] <> '_clear') { ?>
					<div class="col-md-10"><section>Where type of <u>Housing Structure</u> is <b class="txtupper"><?php echo $sel_ops['bw_housing'][0]; ?></b>:</section> 
					&nbsp; <span><?php echo $dt_location['_hh_location_farmers_housing']['value']; ?></span></div>
					<div class="col-md-2"><span class="storyPerc"><?php echo displayPercent($dt_location['_hh_location_farmers_housing']['value'], $dt_region['_hh_all_farmers_housing']['value']); ?>%</span></div>
				<?php } ?>
					
		</div>
		<?php } ?>
		<!-- End narrow down code -->

	</div>
	<!-- Where stories are -->

	<!-- Metadata and youtube videos -->
	<div class="col-md-6 txtright">
		Total <?php echo $lbl_farmtype; ?> Farming Households Recorded: <span class="storyDigits"><?php echo number_format($dt_region['_hh_all_farmers']); ?></span>

		<div class="col-md-12 padd10_t">
			<?php include 'includes/youtube.php'; ?>
		</div>
	</div>

	<!-- End metadata and youtube videos -->

	<div class="clearfix">
		<div style="height: 10vh"></div>
	</div> 

	<!-- Displays more info -->
	<div class="col-md-12">
			
			
			<?php 
				
			function arrayStats( $arr ){
				$out 		= array();
				$arrSum		= array_sum($arr);
				arsort($arr);
				
				$arrLabels = array_keys($arr);
				foreach($arr as $lbl => $v){
					if($lbl == '') { $lbl = 'Unspecified'; }
					$vPerc = displayPercent($v, $arrSum);
					$out[] = '<li>'. clean_title($lbl, "-") .' : <strong>'. $v .'</strong> &nbsp; <code class="txt10 txtyellow">('. $vPerc .'%)</code></li>';
				}
				return implode('', $out);
			}
			
			
			
			
			
			if (array_key_exists('bw_practice', $sel_ops) and $sel_ops['bw_practice'][0] <> '_clear') 
			{ 
				$dcrops = array_chunk($dt_story_farm['_location_subdata']['_produce_prevalence'], 6, true);
				$dcropsNames = array_keys($dcrops[0]);
				?>
					<div class="col-md-12"><h3>Additional Data</h3></div>
					<div class="col-md-12">
						<div class="col-md-2">Most Common <?php echo $lbl_farmtype; ?>:</div><div class="col-md-7"><code class="txtyellow"><?php echo implode(', ', $dcropsNames); ?></code></div>
					</div>
					<div class="col-md-12"><hr /></div>
				<?php 
			}
			
			if (array_key_exists('bw_practice', $sel_ops) and $sel_ops['bw_practice'][0] == 'for profit') 
			{ 
				$share_sold 	= $dt_story_farm['_location_subdata']['_produce_share_sold'];
				arsort($share_sold); //displayArray($share_sold);
				?>
					
					<div class="col-md-6">
						<div class="col-md-4">Share of Produce Sold:</div><div class="col-md-7"><?php echo arrayStats($share_sold); ?></div>
					</div>
					<!--<div class="col-md-12"><hr /></div>-->
				<?php 
				
				
				$_produce_target_market 	= $dt_story_farm['_location_subdata']['_produce_target_market'];
				arsort($_produce_target_market); 
				?>		
					<div class="col-md-6">
						<div class="col-md-4">Produce Target Market:</div><div class="col-md-7"><?php echo arrayStats($_produce_target_market); ?></div>
					</div>
					<div class="col-md-12"><hr /></div>
				<?php 
				
				
				$_produce_distance_to_market	= $dt_story_farm['_location_subdata']['_produce_distance_to_market'];
				arsort($_produce_distance_to_market); 
				?>		
					<div class="col-md-6">
						<div class="col-md-4">Distance to Nearest Market Place:</div><div class="col-md-7"><?php echo arrayStats($_produce_distance_to_market); ?></div>
					</div>
					<!--<div class="col-md-12"><hr /></div>-->
				<?php 
				
				
				$_produce_how_taken_to_market	= $dt_story_farm['_location_subdata']['_produce_how_taken_to_market'];
				arsort($_produce_how_taken_to_market); 
				?>		
					<div class="col-md-6">
						<div class="col-md-4">How Produce is taken to the Market:</div><div class="col-md-7"><?php echo arrayStats($_produce_how_taken_to_market); ?></div>
					</div>
					<div class="col-md-12"><hr /></div>
				<?php 
				
				if ($sel_ops['bw_farming'][0] == 'crop') 
				{
					$_produce_fertilizer_access	= $dt_story_farm['_location_subdata']['_produce_fertilizer_access'];
					arsort($_produce_fertilizer_access); 
					?>		
						<div class="col-md-6">
							<div class="col-md-4">How farmers access Fertilizer:</div><div class="col-md-7"><?php echo arrayStats($_produce_fertilizer_access); ?></div>
						</div>
						<div class="col-md-12"><hr /></div>
					<?php 
				}
				
				
			} 
			?>
		</div>
	</div>
		<!-- End displays more info -->

		<!-- Displays table -->
		<div class="col-md-12">
			<?php
			if(array_key_exists('_hh_table_data', $dt_story_farm)){
				$_hh_table_data = $dt_story_farm['_hh_table_data'];
				
				if( count($_hh_table_data) ){
					echo '<h3>&nbsp;</h3>';
					echo '<h3> Finally, this is the data we generated from your filters</h3>';
					echo '<p><b>NOTE:</b> The download feature will be added soon.</p>';
					$tb_hh_data = autoTable($_hh_table_data, '');	
					
					echo $tb_hh_data;
				}
				
			}
			
			?>
		</div>
		<!-- End displays table -->

<?php
	
}
//displayArray($dt_story_farm);


//displayArray($dt_search_combo);
//$tb_search_combo = autoTable($dt_search_combo);
//echo $tb_search_combo;
?>

