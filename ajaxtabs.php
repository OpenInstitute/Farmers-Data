<?php
require("classes/cls.constants.php"); 

if (!empty($_GET['fcall'])) { $fcall = trim($_GET['fcall']); } else { $fcall = ''; }
if (!empty($_GET['tab'])) { $tab = trim($_GET['tab']); } else { $tab = ''; }
if (!empty($_GET['parent'])) { $parent = trim($_GET['parent']); } else { $parent = ''; }
if (!empty($_GET['id'])) { $id = trim($_GET['id']); } else { $id = ''; }
if (!empty($_GET['cat_id'])) { $cat_id = trim($_GET['cat_id']); } else { $cat_id = 24; }
if (!empty($_GET['ac_client'])) { $ac_client = trim($_GET['ac_client']); } else { $ac_client = ''; }

$dir = $tab;

$request = $_REQUEST;

//displayArray($request); exit;
/*echobr($fcall);
echobr($tab);*/
//echobr($parent);

switch($fcall) {
	case "rptcat":
	
		switch($tab) 
		{
			case "fields":			
				include("includes/inc.reportcat.fields.add.php");
				include("includes/inc.reportcat.fields.list.v2.php");
			break;
			
			
			
			case "preview":			
				include("includes/inc.reportcat.fields.preview_new.php");
			break;
			
			
			
			case "indicators":			 
				echo '<h2>Indicators</h2>
				<div class="subcolumns" style="background:#F5E1EE; margin-bottom:20px; border:1px solid #ddd">
				Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna gubergren, 
				</div>';			
			break;
			
			
			
			case "edit": 
				include("includes/inc.reportcat.form.php");
			break;
				
			
			case "data":
				echo '<h2>Data</h2>';
                if($cat_id == 23) {
				    include("includes/inc.reportcat.data.php");
                } elseif($cat_id == 21) {
				    include("includes/inc.reportcat.data-ggli_one.php");
				} elseif($cat_id == 24) {
				    include("includes/inc.reportcat.data-household.php");
                } else {
					echo '<div class="note"><h3>Coming Soon</h3></div>';
				}
			break;
				
				
			case "stats":
				echo '<h2>Data Stats</h2>';
				include("includes/inc.reportcat.stats.php");
			break;
			
			case "entryreport":
				echo '<h2>Data Entry Per Person</h2>';
				include("includes/inc.report-per-person.php");
			break;
		}
	
	break;
			
				  
	case "rptfldedit":
	
			echo '<div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h4 class="modal-title noline">Reporting Forms Set-up: Edit Field</h4> </div> <div class="modal-body modal-long nano"><div class="nano-content">';
		include("includes/inc.reportcat.fields.edit.php");
		echo '</div></div> </div> </div>';
			
	break;
	
	
	case "rptcatadd":
	
			echo '<div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h4 class="modal-title noline">Add Reporting Category</h4> </div> <div class="modal-body modal-long nano"><div class="nano-content">';
		include("includes/inc.reportcat.form.php");
		echo '</div></div> </div> </div>';
			
	break;


	
}
?>