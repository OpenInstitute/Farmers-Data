<div id="dynaScript"></div>	



<script src="assets/scripts/bootstrap/js/popper.min.js"></script>	
<script src="assets/scripts/bootstrap/js/bootstrap.min.js"></script>	
<script src="assets/scripts/jquery-migrate-1.2.1.min.js"></script>
<script src="assets/scripts/jquery-ui-1.10.2.min.js"></script>	


<script src="assets/scripts/datatable/jquery.dataTables-1.10.19.min.js"></script>	
<script src="assets/scripts/datatable/dataTables.rowGroup.min.js"></script>	
<script src="assets/scripts/datatable/dataTables.colReorder.min.js"></script>	

 <!-- DataTables Export -->
 <script src="assets/scripts/datatable/dataTables.buttons.min.js"></script>
 <script src="assets/scripts/datatable/jszip.min.js"></script>
 <script src="assets/scripts/datatable/pdfmake.min.js"></script>
 <script src="assets/scripts/datatable/vfs_fonts.js"></script>
 <script src="assets/scripts/datatable/buttons.html5.min.js"></script>
 <script src="assets/scripts/datatable/buttons.print.min.js"></script>
 <!-- DataTables Export -->


<script src="assets/scripts/misc/jquery-cookie.js"></script>	
<script src="assets/scripts/misc/sayt.jquery.js"></script>	

<script src="assets/scripts/validate/jquery.validate.js"></script>
<script src="assets/scripts/easyframework/jquery.easyframework.js"></script>
<script src="assets/scripts/easyframework/jquery.easing.js"></script> 
<script src="assets/scripts/datepick/jquery.plugin.js"></script> 
<script src="assets/scripts/misc/jquery.slidetoggle.js"></script> 


<script>
	/* @@Murage edit: 20180809*/
  jQuery(document).ready(function($){
    jQuery('.bxslider').bxSlider({mode: 'fade',captions: true,minSlides: 2,infiniteLoop: true,controls: false,auto:true,autoStart: true,pause: 30000});
    jQuery('.bxslider_location').bxSlider({mode: 'fade',captions: true,minSlides: 2,infiniteLoop: true,controls: false,auto:true,autoStart: true,pause: 30000});
    jQuery('.yt-bxslider').bxSlider({mode: 'fade',captions: true, autoControls: true, minSlides: 2,infiniteLoop: true,auto:true,autoStart: true,pause: 4000});
  


/*//data_page */
// Script for export 
$('table.display').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
// End script for export
if( $('#gg_data_result').length ) {
        $("#gg_data_result").ajaxComplete(function() {
					/*if (window.flagDataTable === false) { zul_DataTable(); } window.flagDataTable = false;  */    
			
			var groupColumn = 1;
			var table = $('#gg_data_tb').DataTable({
				"columnDefs": [ { "visible": true, "targets": groupColumn } ],
				"order": [[ groupColumn, 'asc' ]],
				"displayLength": 25,
				colReorder: true,
				"drawCallback": function ( settings ) {
					var api  = this.api();
					var rows = api.rows( {page:'current'} ).nodes(); 
					var last = null;
					var grp_row = 0;
					
					var $loc = 0;
					var grp_total = 0;
					
					
					api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
						/*console.log(i); console.log(group); console.log(last);*/
						
						if ( last !== group && i > 0) { grp_row = 0;}
						
						/*console.log('rw '+grp_row);*/
						var row_cells = $(rows).eq( i ).find('td').length;						
						
						$loc = $('td[data-loc="'+group+'"]'); 
						/*console.log($loc[grp_row]);
						console.log('$loc length - '+$loc.length);*/
						
						if($loc[grp_row] !== undefined){
							grp_total += parseInt($loc[grp_row].getAttribute("data-loc-recs"));
						}
						
						if(grp_row == ($loc.length-1) )
						{
							$(rows).eq(i).after(
									'<tr class="group"><td colspan="'+ (row_cells-1) +'">'+last+' Total</td><td>'+grp_total+'</td></tr>'
								); 
							grp_total = 0;
						} 
						
						grp_row += 1;
						
						if ( last !== group) {
							last = group;
						}
						
						
					} );
				}
				, initComplete: function () {
					var num_cols = this.api().columns().nodes().length; /*console.log(num_cols);*/
							this.api().columns().every( function (tb_col) {
								//console.log(this);
								//if(tb_col < (num_cols - 1) ) {
									var column = this; 
									var select = $('<select><option value=""></option></select>')
										.appendTo( $(column.footer())/*.empty()*/ )
										.on( 'change', function () {
											var val = $.fn.dataTable.util.escapeRegex(
												$(this).val()
											);

											column
												.search( val ? '^'+val+'$' : '', true, false )
												.draw();
										} );

									column.data().unique().sort().each( function ( d, j ) {
										select.append( '<option value="'+d+'">'+d+'</option>' )
									} );
								//}
							} );
						}
			} );

			
			/*$('#example tbody').on( 'click', 'tr.group', function () {var currentOrder = table.order()[0];if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {table.order( [ groupColumn, 'desc' ] ).draw();}else {table.order( [ groupColumn, 'asc' ] ).draw();}} );*/
        });
    }








});
</script>

<!-- Our Scripts -->
<script src="assets/scripts/bxslider/jquery.bxslider.min.js"></script>
<!-- <script src="zscript_foot.js"></script> -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-34157316-19"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-34157316-19');
</script>

</body>
</html>

