<?php include("z_head.php");  ?>

<!--Stories Introduction-->
<section class="story-intro bgGreen intFilter">
	<div class="row">
		<!-- <div class="col-md-6 col-md-offset-3" style="padding-bottom: 5%; padding-top: 5%;">
			<h3 class="heavy">Our Data Stories</h3>
			<p style="text-align: justify; font-weight: 100; font-size:25px;">
				This is the story of the farmers in Nakuru North. When you click on the filters, the stories change dynamically to reflect what you might want to see.
			</p>
		</div> -->
		<div class="col-md-12">
			<?php include 'includes/form-filter.php'; ?>
		</div>

	</div>
</section>
<!--Stories Introduction-->


<!--Filters and their data-->
<section class="story-filters-and-data">
	<div class="row" style="background: rgba(20, 89, 89, .6);padding-bottom: 5%; padding-top: 5%;">

		<div class="col-md-12">
			<!-- Table results -->
			<div id="gg_data_result">...</div>
			<!-- Table results -->
		</div>
	</div>
</section>
<!--Filters and their data-->

<!--Table with more data-->
<section class="story-table">
	<div class="row">
		<div class="col-md-12"><!-- Table --></div>
	</div>
</section>
<!--Table with more data-->

<?php echo $guide; ?>
<!-- <script src="assets/scripts/storyguide.js"></script> -->

<?php
$reqs = array();			
foreach($request as $rk => $rv){ if($rk <> 'tk' ){ if(current($rv) <> '_clear'){ $reqs[] = current($rv); }}}
?>
<script> 
var reqs = <?php echo json_encode($reqs); ?>; 
jQuery(document).ready(function($) {
	if(reqs.length > 0){
	$(".gg_checks").each(function() { var chk_val = $(this).attr('value'); if( jQuery.inArray( chk_val, reqs ) >= 0){ $(this).prop('checked', 'checked'); } });
	gg_data_search();
	}
});
	
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
			url: 'ajdatastory.php?tk='+Math.random(),
			type: 'get',
			data: $('#frm_search').serialize(),
			beforeSend: function() { $('#gg_data_result').html('<img style="width: 20px; height:20px; margin: auto;" src="assets/image/loader.gif" alt="..."  />'); },
			success: function(response) { $('#gg_data_result').html(response); }            
		});
	});
}	
</script>

<?php
include("includes/footer.php"); 
include("z_foot.php"); 
?>





