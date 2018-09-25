<?php include("zscript_head.php");  ?>


<?php include("includes/wrap_line_head.php"); ?>


<?php //include("includes/dash_home_intro.php"); 

/*
https://datatables.net/examples/advanced_init/row_grouping.html
https://datatables.net/extensions/rowgroup/
https://datatables.net/download/release#DataTables
https://datatables.net/examples/index
*/
?>



<div class="">


	<?php //include("includes/dash_nav_locations.php"); ?>

<style>
.row{/*margin-top:40px;padding: 0 10px;*/}
.clickable{cursor: pointer; }
.panel-heading span {margin-top: -25px;font-size: 15px;}
</style>	
	
<div class="containerX row">
	<div class="col-md-2">
		<div class="row search-panels">
		
		<form id="frm_search" name="frm_search">
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Locations</h3>
					<span class="pull-right clickable"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label> <input type="checkbox" class="gg_checks" name="bw_location[]" value="bahati"> Bahati </label>
						<label> <input type="checkbox" class="gg_checks" name="bw_location[]" value="dundori"> Dundori </label>
						<label> <input type="checkbox" class="gg_checks" name="bw_location[]" value="kirima"> Kirima </label>
						<label> <input type="checkbox" class="gg_checks" name="bw_location[]" value="lanet umoja"> Lanet Umoja </label>
						<!--<label> &nbsp; </label>-->
						<label> <input type="checkbox" class="gg_checks" name="bw_location_sub[]" checked value="allow"> <i>Show Sub-Locations</i> </label>
					</div>				
				</div>
			</div>
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Provider Gender</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label> <input type="radio" class="gg_checks" name="bw_gender[]" value="Female"> Female </label>
						<label> <input type="radio" class="gg_checks" name="bw_gender[]" value="Male"> Male </label>
						<label> <input type="radio" class="gg_checks" name="bw_gender[]" value="_clear"> Clear </label>
					</div>				
				</div>
			</div>
			
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Provider Education Level</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_edu_level[]" value="None"> None </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_edu_level[]" value="Primary"> Primary </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_edu_level[]" value="Secondary"> Secondary </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_edu_level[]" value="Technical"> Technical </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_edu_level[]" value="University"> University </label>
					</div>				
				</div>
			</div>
			
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Provider Status</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_status[]" value="Cohabit"> Cohabit </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_status[]" value="Married"> Married </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_status[]" value="Single"> Single </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_status[]" value="Widow"> Widow </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_status[]" value="Widower"> Widower </label>
					</div>				
				</div>
			</div>
			
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Provider Age</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label class="col-md-6"> <input type="radio" class="gg_checks" name="bw_age[]" value="18_30"> 18 - 30 </label>
						<label class="col-md-6"> <input type="radio" class="gg_checks" name="bw_age[]" value="31_40"> 31 - 40 </label>
						<label class="col-md-6"> <input type="radio" class="gg_checks" name="bw_age[]" value="41_60"> 41 - 60 </label>
						<label class="col-md-6"> <input type="radio" class="gg_checks" name="bw_age[]" value="61_plus"> 61 plus </label>
						<label> <input type="radio" class="gg_checks" name="bw_age[]" value="_clear"> Clear </label>
					</div>				
				</div>
			</div>
			
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Provider Income</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_income[]" value="Ksh 0 - 5000"> 0 - 5k </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_income[]" value="Ksh 5001 - 20000"> 5k - 20k </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_income[]" value="Ksh 20001 - 35000"> 20k - 35k </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_income[]" value="Ksh 35001 - 50000"> 35k - 50k </label>
						<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_income[]" value="Ksh 50001 and above"> 50k plus </label>
						<!--<label class="col-md-6"> <input type="checkbox" class="gg_checks" name="bw_income[]" value="_clear"> Clear </label>-->
					</div>				
				</div>
			</div>
			
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Farming Options</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label> <input type="checkbox" class="gg_checks" name="bw_farming[]" value="Crop"> Crops </label>
						<label> <input type="checkbox" class="gg_checks" name="bw_farming[]" value="Livestock"> Livestock </label>
					</div>				
				</div>
			</div>
			
			
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Housing Structure</h3>
					<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div class="panel-body search-ops">				
					<div>
						<label> <input type="checkbox" class="gg_checks" name="bw_housing[]" value="Permanent"> Permanent </label>
						<label> <input type="checkbox" class="gg_checks" name="bw_housing[]" value="Temporary"> Temporary </label>
					</div>				
				</div>
			</div>
			
			
		</form>
		
		
		</div>
	</div>
	
	<div class="col-md-10 bg-white">
		<div>
			<h3>Results</h3>
			<div id="gg_data_result">
			...
			
			</div>
		</div>
	</div>
</div>	
	
	
	
	
	
	

</div>		
	
	
	<?php //include("includes/wrap_line_foot.php"); ?>
	
	




<script language="JavaScript" type="text/javascript">
	
jQuery(document).ready(function($) {	
	
	$('.panel-heading span.clickable').each(function() {
		var $this = $(this);
		
		if($this.hasClass('panel-collapsed')) {
			$this.parents('.panel').find('.panel-body').slideUp();
			$this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
		}
	});
	
	gg_data_search();
	
	
	jQuery(document).on('click', '.panel-heading span.clickable', function(e){
		var $this = $(this);
		if(!$this.hasClass('panel-collapsed')) {
			$this.parents('.panel').find('.panel-body').slideUp();
			$this.addClass('panel-collapsed');
			$this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
		} else {
			$this.parents('.panel').find('.panel-body').slideDown();
			$this.removeClass('panel-collapsed');
			$this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
		}
	})
	
	$(document).on('change', '.gg_checks', function (e) {		
		gg_data_search();
	});
	
});
	
function gg_data_search(){
	jQuery(document).ready(function($) {	
		
		$(".gg_checks").each(function() {
			var label = $(this).parent();
			if ($(this).prop('checked')) {label.css('color', 'red');} else {label.css('color', '#777777');}
		});
		
		$.ajax({
			url: 'ajdata.php?tk='+Math.random(),
			type: 'post',
			data: $('#frm_search').serialize(),
			beforeSend: function() { $('#gg_data_result').html('loading <img src="image/icons/a-loader.gif" alt="..."  />'); },
			success: function(response) { $('#gg_data_result').html(response); }            
		});
	});
}	
</script>

<?php include("zscript_foot.php"); ?>





