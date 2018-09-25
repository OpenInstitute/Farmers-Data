<?php session_start();
require_once('classes/cls.config_new.php');	
require_once('classes/cls.formats.php');

echo '<title>Data Exports</title>';

$cat_id = (isset($_GET['cat_id'])) ? $_GET['cat_id'] : 24; 
$op = (isset($_GET['op'])) ? $_GET['op'] : ''; 

$contArray = array();
$fl_rage 	= array();
$fn = '';

if($cat_id == 24)
{
	echo '<div style="padding:30px"><h1>Nakuru North GGLI 2018 - Exports</h1>';
	if($op == 'main')
	{
		
		$sq_ddata = "SELECT * FROM `vw_data_c24`  order by `post_id` ASC; "; // limit 0, 5
		$rs_ddata = $cndb->dbQuery($sq_ddata);
		$rs_print_count = $cndb->recordCount($rs_ddata);

		$cont_row = 0;
		while($cn_ddata = $cndb->fetchRow($rs_ddata, 'assoc'))
		{
			$fl_rage 		= array_keys($cn_ddata);
			
			$cont_col = 0;
			foreach($cn_ddata as $col_id => $col_v)
			{
				$contArray[$cont_row][$cont_col] = ($col_v <> '') ? $col_v : '';
				$cont_col += 1;
			}

			$cont_row += 1;
		}
		//displayArray($fl_rage); 
		//displayArray($contArray);  exit;

    	$headArray = array_map("clean_title", $fl_rage);     
    
		$_SESSION['report_header'] = $headArray; 	
		$_SESSION['report_values'] = $contArray;
		
		//$fn = "cat_".$cat_id."_data_".date("YmdHs")."_".$rs_print_count."_recs";
		$fn = "ggli2018_main_".date("YmdHs")."_".$rs_print_count."_recs";
		
	}
	elseif($op == 'subs')
	{
		
		$sq_ddata = "SELECT * FROM `vw_data_c24_subdata`  order by `post_id` ASC limit 0, 100; "; // limit 0, 5
		$rs_ddata = $cndb->dbQuery($sq_ddata);
		$rs_print_count = $cndb->recordCount($rs_ddata);

		$cont_row = 0;
		while($cn_ddata = $cndb->fetchRow($rs_ddata, 'assoc'))
		{
			$fl_rage 		= array_keys($cn_ddata);		

			$cont_col = 0;
			foreach($cn_ddata as $col_id => $col_v)
			{
				$contArray[$cont_row][$cont_col] = ($col_v <> '') ? $col_v : '';
				$cont_col += 1;
			}

			$cont_row += 1;
		}
		//displayArray($fl_rage); 
		//displayArray($contArray);  exit;

    	$headArray = array_map("clean_title", $fl_rage);     
    
		$_SESSION['report_header'] = $headArray; 	
		$_SESSION['report_values'] = $contArray;
		
		//$fn = "cat_".$cat_id."_data_".date("YmdHs")."_".$rs_print_count."_recs";
		$fn = "ggli2018_subs_".date("YmdHs")."_".$rs_print_count."_recs";
		
	}
	elseif($op == '')
	{
		echo '
		<h4><a href="excel.php?op=main">Click here to export Main Data</a></h4><i style="font-size:14px">(Location Information, Breadwinner Information
, Spouse Information etc.)</i>
		<h4><a href="excel.php?op=subs">Click here to export Sub Data</a> </h4><i style="font-size:14px">(Health Information, Disability Information, Households Information)</i>
		';
	}
	
	echo '</div>';
}
else {

$sq_rage = "SELECT `id` , `report_category_id` , `report_item_title` , `report_item_form_field` , `report_item_options_other` , `report_item_parent` , `report_item_required` , `published` , `seq` FROM `afp_conf_activity_reporting_titles` WHERE (`report_category_id` = ".q_si($cat_id)." )  and `report_item_form_field` <> 'group_header' ORDER BY `report_item_parent` ASC, `seq` ASC; ";

	$fl_rage = array();
	$tb_rage = '';
    $tb_result  = array();

	$rs_rage = $cndb->dbQuery($sq_rage);
	while($cn_rage = $cndb->fetchRow($rs_rage))
	{
		$field_seo = generate_seo_title($cn_rage['report_item_title'], '_');
		$fl_rage[$cn_rage['id']] = $field_seo;
		//$fl_rage[$field_seo] = $cn_rage['id'];
	}
	
	//displayArray($fl_rage); 

    $sq_ddata = "SELECT * FROM `afp_reports` where `cat_id`= ".q_si($cat_id)." order by `record_id` ASC; "; //
    $rs_ddata = $cndb->dbQuery($sq_ddata);
    $rs_print_count = $cndb->recordCount($rs_ddata);
    while($cn_ddata = $cndb->fetchRow($rs_ddata))
	{
        $rec_id		    = $cn_ddata['record_id'];		
		$rec_date		= $cn_ddata['date_record'];		
		$rec_data		= unserialize($cn_ddata['form_detail']);
		
		
        $building_information		   = @$rec_data['building_information'];
        $posted_by			           = @$rec_data['data_detail']['posted_by'];
        $outdoor_pavements			   = is_array(@$rec_data['outdoor_pavements']) ? $rec_data['outdoor_pavements'] : array();
        $outdoor_traffic_features	   = is_array(@$rec_data['outdoor_traffic_features']) ? $rec_data['outdoor_traffic_features'] : array();
        $outdoor_recreational_facilities  = is_array(@$rec_data['outdoor_recreational_facilities']) ? $rec_data['outdoor_recreational_facilities'] : array();
        $outdoor_street_level_features	  = is_array(@$rec_data['outdoor_street_level_features']) ? $rec_data['outdoor_street_level_features'] : array();
		$indoor_step_free_access	      = is_array(@$rec_data['indoor_step_free_access']) ? $rec_data['indoor_step_free_access'] : array();
		$indoor_entrances	              = is_array(@$rec_data['indoor_entrances']) ? $rec_data['indoor_entrances'] : array();
		$indoor_chairs_surfaces	          = is_array(@$rec_data['indoor_chairs_surfaces']) ? $rec_data['indoor_chairs_surfaces'] : array();
        
        $tb_result[$rec_id] = array_merge($building_information, 
                                          $outdoor_pavements, 
                                          $outdoor_traffic_features, 
                                          $outdoor_recreational_facilities, 
                                          $outdoor_street_level_features,
                                          $indoor_step_free_access,
                                          $indoor_entrances,
                                          $indoor_chairs_surfaces);
        $tb_result[$rec_id]['posted_by'] = $posted_by;
    }

    $headArray = array_map("clean_title", $fl_rage);     
    //$contArray = array();


    $cont_row = 0;
	foreach($tb_result as $key => $cp)
	{
		$cont_col = 0;
        foreach($fl_rage as $col_id)
        {
            if(array_key_exists($col_id, $cp)){
                $contArray[$cont_row][$cont_col] = $cp[$col_id];
            } else {
                $contArray[$cont_row][$cont_col] = '';
            }
            $cont_col += 1;
        }
		$cont_row += 1;
	}

		$_SESSION['report_header'] = $headArray; 	
		$_SESSION['report_values'] = $contArray;
		
		//$fn = "cat_".$cat_id."_data_".date("YmdHs")."_".$rs_print_count."_recs";
		$fn = "ability_data_".date("YmdHs")."_".$rs_print_count."_recs";
}

if($fn <> '')
{
?>

<script type="text/javascript">location.href="excel_print.php?fn=<?php echo $fn; ?>";</script>


<?php } ?>