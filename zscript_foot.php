<!-- </div></div> -->
<!-- @end:: page-container -->

<!-- @beg:: page-footer --> 

<!-- @end:: page-footer -->


<script src="assets/scripts/bootstrap/js/popper.min.js"></script>	
<script src="assets/scripts/bootstrap/js/bootstrap.min.js"></script>	
<script type="text/javascript" src="assets/scripts/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="assets/scripts/jquery-ui-1.10.2.min.js"></script>	


<script type="text/javascript" src="assets/scripts/datatable/jquery.dataTables-1.10.19.min.js"></script>	
<script type="text/javascript" src="assets/scripts/datatable/dataTables.rowGroup.min.js"></script>	
<script type="text/javascript" src="assets/scripts/datatable/dataTables.colReorder.min.js"></script>	

<script type="text/javascript" src="assets/scripts/misc/jquery-cookie.js"></script>	
<script type="text/javascript" src="assets/scripts/misc/sayt.jquery.js"></script>	

<script type="text/javascript" src="assets/scripts/validate/jquery.validate.js"></script>
<script type="text/javascript" src="assets/scripts/easyframework/jquery.easyframework.js"></script>
<script type="text/javascript" src="assets/scripts/easyframework/jquery.easing.js"></script> 
<script type="text/javascript" src="assets/scripts/datepick/jquery.plugin.js"></script> 
<script type="text/javascript" src="assets/scripts/misc/jquery.slidetoggle.js"></script> 

<div id="dynaScript"></div>	

<script type="text/javascript" charset="utf-8">
	
jQuery(document).ready(function($) {
	var lcn = '<?php echo ucwords($loc_id); ?>';
	if(lcn !== ''){ $('#btn_county_caption').html(''+lcn+' LOCATION'); }
	
});
	
	if( !(typeof dta_edu_provider === 'undefined') ){ hc_pieChart('cht_edu_provider','',dta_edu_provider, true);  }	
	
	if( !(typeof dta_edu_child_gender === 'undefined') ){ hc_pyramid('cht_edu_child_gender','',dta_edu_child_gender, true);  }	
	
	if( !(typeof dta_health_illness === 'undefined') ){ hc_pieChart('cht_health_illness','',dta_health_illness, true);  }	
	
	if( !(typeof dta_prop_owner_edu === 'undefined') ){ hc_pieChart('prop_owner_edu','',dta_prop_owner_edu, true);  }	
	
	if( !(typeof dta_prop_business === 'undefined') ){ hc_colChartFixed('prop_business','', dta_prop_business, true); }

	
	
	
function hc_pieChart(cElement, cLabel, cData, cLegend = false){  
    jQuery(document).ready(function($) {
		var cDataLabel = (cLegend) ? false : true;
		var cSize = (cDataLabel) ? 220 : 320;
		//var cDataLabel = false;
        Highcharts.chart(cElement, {
            chart: { type: 'pie', margin: [0, 0, 0, 0] },
            title: { text: cLabel },
			colors: ['red', 'orange', 'green', 'blue', 'purple', 'brown', 'black', 'gray', 'maroon'],
            tooltip: { pointFormat: '{series.name}: {point.y}, <br/>Percentage: <b>{point.percentage:.1f}%</b> ' },
			legend: { enabled: cLegend, useHTML: true,
				labelFormatter: function() {return '<span style="color:' + this.color + '">' + this.name + ': ' + this.percentage.toFixed(2) + '%</span><br/>';},
                borderWidth: 0,itemStyle: {fontWeight: 'normal !important',fontSize: '12px' } },
            plotOptions: {
                pie: {  size: cSize, innerSize: '50%', allowPointSelect: false, cursor: 'pointer', showInLegend: cLegend, 
					  startAngle: -90, endAngle: 90, center: ['50%', '75%'],
                        dataLabels: { enabled: cDataLabel, format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: { color: (Highcharts.theme &&  Highcharts.theme.contrastTextColor) || 'black' }
                        }
                }
            },
            series: [{ name: 'Total', colorByPoint: true, data: cData }]
            , credits : { enabled : false} , exporting : { enabled : false}
        });
    });
}	
	
	
function hc_pyramid(cElement, cLabel, cData, cLegend = false){  
    jQuery(document).ready(function($) {
		var cDataLabel = (cLegend) ? false : true;
		var cSize = (cDataLabel) ? 220 : 320;
		//var cDataLabel = false;
		
		Highcharts.chart(cElement, {
			chart: {type: 'pyramid'},
			title: {text: '',x: -50},
			colors: ['red', 'orange', 'green', 'blue', 'purple', 'brown', 'black', 'gray', 'maroon'],
			plotOptions: {
				pyramid: {  size: 100 },
				series: {
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b> ({point.percentage:.1f}%)' /*{point.y:,.0f}*/,
						color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
						softConnector: true
					},
					center: ['40%', '50%'],width: '80%'
				}
			},
			legend: { enabled: false },
			series: [{name: 'Total',data: cData}]
			, credits : { enabled : false} , exporting : { enabled : false}
		});
		
      
    });
}	
	
	
function hc_colChartFixed (cElement, cLabel, cData, cLegend = false){  
    jQuery(document).ready(function($) {
		var cDataLabel = true;

			Highcharts.chart(cElement, {
				chart: {type: 'column'},
				title: {text: cLabel },
				xAxis: {categories: ['Households','','']},
				yAxis: [{min: 0,title: {text: 'Households'}}, {title: {text: ''}, opposite: true}],
				legend: {enabled: cLegend, shadow: false},
				tooltip: {shared: false},
				plotOptions: {column: {grouping: false,shadow: false,borderWidth: 0}},
				series: cData
				, credits : { enabled : false} , exporting : { enabled : false}
			});		
		
    });
}		
</script>


<script language="JavaScript" type="text/javascript">
jQuery(document).ready(function($) {	
	<?php if($dir <> '') { ?>
	var nav_curr = "<?php echo $dir; ?>";
	if( $("#nvm_"+nav_curr).length ) { $("#nvm_"+nav_curr).addClass("current"); }
	<?php } ?>
	if (top.location != location) { $(".wrap_box_user").hide(); }
	
	//$('input:checkbox').slidetoggle();
});
</script>
<script type="text/javascript" src="zscript_foot.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $(".frmNoEdit :input").prop("disabled", true).css({"border":"none", "background":"none"});
		$(".frmNoEdit").prop("action", "#");
		$(".frmNoEdit").find(":submit, .hideable, .nav_button, img[src*='delete']").css("display", "none");
    });
</script>



</body>
</html>