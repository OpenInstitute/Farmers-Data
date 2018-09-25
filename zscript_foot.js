jQuery.noConflict();

var flagDatePick = false;
var flagDataTable = false;

/* //DYNAMIC LOADER - css */
	function loadcssfile(filename){  
	  var fileref=document.createElement("link");
	  fileref.setAttribute("rel", "stylesheet");
	  fileref.setAttribute("type", "text/css");
	  fileref.setAttribute("href", filename) ;
	  if (typeof fileref!="undefined") { document.getElementById("dynaScript").appendChild(fileref); }
	}


/* //DYNAMIC LOADER - js */
	function loadjsfile(filename){
	  var fileref=document.createElement('script');
	  fileref.setAttribute("type","text/javascript");
	  fileref.setAttribute("src", filename); 
	 if (typeof fileref!="undefined") { document.getElementById("dynaScript").appendChild(fileref); }
	}


jQuery(document).ready(function ($) {

/* //NAVIGATION */
	$("ul.sf-menu li:has(ul)").children("a").addClass("sf-with-ul");
	$('ul.sf-menu li:has(a.current)').children(':first').addClass("current");
	
	$("ul.sf-menu li").hover(function(){ $(this).addClass("sfHover"); }, function(){ $(this).removeClass("sfHover"); } );		
	
	
	//var textarea = document.querySelector('textarea');
	//textarea.addEventListener('keypress', autosize);
				 
	function autosize(){ alert('rage');
	  var el = this;
	  setTimeout(function(){
		el.style.cssText = 'height:auto; padding:0';
		el.style.cssText = 'height:' + el.scrollHeight + 'px';
	  },0);
	}
	
	$.easy.navigation();
	$('ul#nav_top li:has(li.current)').addClass("current");
	$.easy.navigation({ 'selector' : '#nav_user li', 'className' : 'over' }); 
	
/* //VALIDATOR - BASE */
	
	jQuery.validator.addMethod("notDefault", function(value, element) { return value != element.defaultValue;	}, "Required");
	$('label.required').append('<span class="rq"> *</span>');	
	
	/* //validate - CUMULATIVE */
	if( $('.frm-be-bas').length ) 	{ //, ignore: ''
		$(".frm-be-bas").validate({errorPlacement: function(error, element) { }}); }
		
	/* //validate - WYSIWYG */
	if( $('#frm_forum_reply').length ) { $("#frm_forum_reply").validate({ ignore: '' });  } 
	
	/* //validate - ERROR PLACEMENT */
	if( $('#frm_passgen').length ) { $("#frm_passgen").validate({errorPlacement: function(error, element) {
				error.appendTo( element.parent("div").next("div") );
			}});  }
	
	
	if( $(".rwdvalid").length ) { $(".rwdvalid").validate({errorPlacement: function(error, element) {} }); }	
	
	
	
			
	
/* //DYNAMIC LOADER - CACHE */
	jQuery.cachedScript = function( url, options ) { 
	  options = $.extend( options || {}, { dataType: "script", cache: true, url: url }); 
	  return jQuery.ajax( options );
	};	

/* //DYNAMIC LOADER - Actual */	
	
	/*//DATA TABLE*/
	//zul_DataTable();
	
	
	/*//DATE PICKER*/
	zul_DatePick();
	 
	/*//WYSIWYG*/
	if( $('.jwysiwyg').length ) { 
		loadcssfile("scripts/jwysiwyg/jquery.wysiwyg.css");
		$.cachedScript( "scripts/jwysiwyg/jquery.wysiwyg.js" ).done(function( script, textStatus ) {
			$('.jwysiwyg').wysiwyg({
					autoGrow: true,
					maxHeight: 600,
					controls: {
						h1: { visible: false }, h2: { visible: false }, h3: { visible: false },
						indent  : { visible : false }, outdent : { visible : false },
						cut   : { visible : true }, copy  : { visible : true }, paste : { visible : true }				
						},
					css: {
							fontSize: '12px',
							fontFamily: 'Tahoma, Geneva, sans-serif',
							color: '#313435',
							lineHeight: '1.6'
						},
					initialContent: "Enter Description..."
				}); 
		});
	}  
	
	
	/*//MASKED INPUTS*/
	if( $('input[class*="mask_"]').length ) { 	 
	  $.cachedScript( "scripts/validate/jquery.inputmask.js" ).done(function( script, textStatus ) { 
		if( $('.mask_date').length ) { $('.mask_date').inputmask( 'mm/dd/yyyy' ); }
		if( $('.mask_time').length ) { $('.mask_time').inputmask( 'h:s t' ); }
		if( $('.mask_phone').length ) { $('.mask_phone').inputmask('+999 999 999999'); }
	  });
	}
	
	
	
	/*//MULTI SELECT*/
	if( $('select.multiple').length ) { 
	  loadcssfile("scripts/multiselect/jquery.multiselect.css");
	  $.cachedScript("scripts/multiselect/jquery.multiselect.filter.js" ).done(function( script, textStatus ) {});
	  $.cachedScript("scripts/multiselect/jquery.multiselect.js" ).done(function( script, textStatus ) { 
	  
	  	//$("select.multiple").multiselect(); 
		$("select.multiple").multiselect({
			 close: function(event, ui)
			 {
				 var this_sel = $(this).attr('id');
				 /*if(this_sel === 'id_associates'){getAssociates();}*/
			   }
			});
	  });
	}
	
	
	
	
	/*//Fancybox */
	if( $('.shadowpop').length ) { 
	  loadcssfile("scripts/fancybox/jquery.fancybox-1.3.4.css");
	  $.cachedScript("scripts/fancybox/jquery.fancybox-1.3.4.pack.js" ).done(function( script, textStatus ) { 
	  	$('a.shadowpop').fancybox({'width' : '75%','height' : '75%','autoScale' : false,'type' : 'iframe'});
	  });
	}
	
	
	/*//data_page */
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




/* //CUSTOM SCRIPTS */

function zul_DataTable() {
	jQuery(document).ready(function($) {		
		
		/*//DATA TABLE*/
		if( $('table.display').length ) { 
			//loadcssfile("scripts/datatable/jquery.dataTables-1.10.16.css");
			loadcssfile("scripts/datatable/jquery.dataTables.css");
			loadcssfile("scripts/datatable/jquery.dataTables.override.css");
			/**/
			if (window.flagDataTable === false) {
			$.cachedScript( "scripts/datatable/jquery.dataTables-1.10.19.min.js" ).done(function( script, textStatus ) {
				$.cachedScript("scripts/datatable/dataTables.rowGroup.min.js" ).done(function( script, textStatus ) {});
				
				
					$('table.display').dataTable({
						"bProcessing": true
						//, "bServerSide": true
						, "ordering": false
						, "bJQueryUI": true
						, "sPaginationType": "full_numbers"
						, "bStateSave": true 
						,"iDisplayLength": 25 
						, "aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]]
						, "aaSorting": []
						, rowGroup: { dataSrc: 1 }
						/*, "scrollX": true*/
						/*, "autoWidth": true*/
						//, "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] } ]  
					});
					
			});
				window.flagDataTable = true;
				}
		}
		
	});	
}


function zul_DatePick() {	
	jQuery(document).ready(function($) {		
		if( $('.date-pick').length ) { 	
			jQuery.ajax({
				  url: "scripts/datepick/jquery.datepick.js",
				  dataType: "script",
				  cache: true
			}).done(function() {
				window.flagDatePick = true;
				$('.date-pick').datepick();
			});
		}			
	});	
}



