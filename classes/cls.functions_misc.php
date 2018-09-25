<?php
@ini_set('upload_max_filesize', '5M');

class mailPost
{
	var $sendto; 
	var $subject; 
	var $message; 
	var $sendfrom;
	
	function form_alerts($sendto, $subject, $message) 
	{
		$content = '<html><head><title>'.$subject.'</title></head>'
			.'<body style="font-family: Tahoma, Verdana, Arial; font-size:12px;">'.$message.'</body></html>';
		
		$headers  = 'MIME-Version: 1.0' . "\r\n"; 
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: '.$sendto. "\r\n";	
		$headers .= 'From: '.SITE_TITLE_SHORT.' <'.SITE_MAIL_FROM_BASIC.'>' . "\r\n";
     	
		return 	@mail($sendto, $subject, $content, $headers);
	}
}
	$mailPost = new mailPost; // INITIALIZE MAILING FUNCTION
	


/******************************************************************
@begin :: MISC VARIABLES
********************************************************************/	



$curr_array = array(1=>"USD",2=>"GBP",3=>"EUR",4=>"CAD",5=>"AUD",6=>"KES");

$msge_array  = array(
		199  => "Session has expired. Login to proceed.",
		7  => "Update successfull.",
		20 => "Error. Account with specified Email exists!",
		21 => "Error. Username (email) does NOT exist!",
		22 => "Success. Check your email for your new password.",
		23 => "Logged out (Log in below to access projects / proceed).",
		24 => "Message sent.",
		25 => "Your submission upload was successfull.",
		26 => "Error. File NOT uploaded. Try again or contact the Administrator.",
		27 => "Access not Authorized. Contact the Administrator.",
		28 => "Invalid Request.",
		10 => "Welcome.",
		12 => "Your details were posted successfully.",
		14 => "Please confirm your login details.",
		101 => "No Project Defined. Select Project to view.",
		);






/******************************************************************
@begin :: misc functions
********************************************************************/	





function XXdisplayDecimal($amount,$thao=',') { return number_format($amount,2, '.', $thao); }
function XXdisplayFloat($amount)   { return number_format($amount); }


function XXyesNoPost($str)   		{ $val = ($str == "on" or $str == 1) ? 1 : 0; return $val; }
function XXyesNoChecked($str)     { $val = ($str == 1) ? " checked " : ""; return $val; }
function XXyesNoText($str) 		{ if ($str == 1) { return "Yes"; } elseif ($str == 0) { return "No"; } else { return "?"; } }


function echoQry ($text)    { $output=str_replace("',", "',<br>", $text); print($output)."<hr />"; }


function array_sort_by_columnXX(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
	$doSort = 0;
    foreach ($arr as $key=> $row) {
		if(array_key_exists($col, $row)) {
       		$sort_col[$key] = $row[$col];
			$doSort = 1;
		}
    }
	if($doSort == 1) {
    @array_multisort($sort_col, $dir, $arr);
	}
}


function remove_empty ($array) { 
	if(is_array($array)) {   
		$arrayResult = array();
		foreach ($array as $key => $value) {
			if(is_array($value))
			{ remove_empty($value); }
			else
			{
			$value = trim($value);
			if (!empty($value)) $arrayResult[$key] = $value;
			}
		}
	} else { $arrayResult = $array; }	
	
	return $arrayResult;
}

function remove_special_charsXX($str){
    $str = trim($str);			 
    preg_replace(array('/&/', '/</', '/>/', '/"/'), array('&amp;', '&lt;', '&gt;', '&quot;'), $str);
	$trans = get_html_translation_table(HTML_ENTITIES);
	$str = html_entity_decode(strtr($str, $trans));
    return $str;
}

function getFileExtensionXX($str) {
	$i = strrpos($str,"."); if (!$i) { return ""; }
	$l = strlen($str) - $i; $ext = substr($str,$i+1,$l); return $ext;	
}

function getEmailName($str) {
	$email = strtolower(substr($str,0,strpos($str,"@")));
	return $email;	
}



/**************************************************************************************
@begin: Deadline Days
*************************************************************************************/	
function getDays($begin,$end,$short=1) 
{ 
	//for two timestamp format dates, returns the plain english difference between them. 
	$dif = $end - $begin; 
	
		$days=intval($dif/(60*60*24)); 
		$dif=$dif-($days*(60*60*24)); 
		
		$hours=intval($dif/(60*60)); 
		$dif=$dif-($hours*(60*60)); 
		
		$minutes=intval($dif/(60)); 
		//$seconds=$dif-($minutes*60); 
		
		$days_pad		= str_pad($days, 2, "0", STR_PAD_LEFT);
		$hours_pad		= str_pad($hours, 2, "0", STR_PAD_LEFT);
		$minutes_pad	= str_pad($minutes, 2, "0", STR_PAD_LEFT);
	
		if($short == 0) { 		
			$d_txt = " days "; 	$h_txt = " hours "; 	$m_txt = " min ";
		}
		else { 		
			$d_txt = "d "; 		$h_txt = "h "; 			$m_txt = "m"; 
		}
		
			$s = $days_pad; 
	
	return $s; 

} 


	
function getFormField_Targets ($project_id, $activityTargetsArray, $elRequired = 0) 
{
	$classRequired = "";
	if($elRequired == 1) { $classRequired = "required "; }
	$field_result_body = '';
	
	$themeData	= new themedata_arrays;	
		
	
	if(is_array($activityTargetsArray))
	{
		$i = 0;
		foreach($activityTargetsArray as $tKey => $tVal)
		{
			$target_id 	= key($tVal);
			$targetDrops  = $themeData->getProjectTargetDrops($project_id, $target_id, 1);
			
			$field_result_body .= '<tr class="tr_target_'.$i.'">
			<td><select name="target_group['.$i.']" id="target_group_'.$i.'"  class="'.$classRequired.'width_full" >'.$targetDrops.'</select></td>
			<td><input type="text" name="target_number['.$i.']" id="target_number_'.$i.'" class="width_50 digitvalue" placeholder="Num." maxlength="7" value="0"  /></td>
			<td><input type="text" name="target_date['.$i.']" id="target_date_'.$i.'" class="width_80 date-pick" /></td>
			<td><a onclick="javascript: delRowTarget('.$i.');"><img src="image/delete.png" /></a></td>
			</tr>';
			
			$i += 1;
		}
	}
	
	$field_result = '<table class="nocolor nopad noboda full" id="tbl_target">
			<thead><tr>
			<th nowrap>Indicator/Target</th>
			<th nowrap>No. Achieved</th>
			<th nowrap>Date Achieved</th>
			<th><!--action--></th>
			</tr></thead>
			<tbody>'.$field_result_body.'</tbody>
			<tfoot><tr>
			<td colspan="3"><a id="btn_add_target" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';
	return $field_result;
}







function getFormField ($lbl_element, $elRow, $elRequired = 0, $titleName = '') 
{
	$classRequired = "";
	if($elRequired == 1) { $classRequired = "required "; }
	
	$field_detail = ($titleName <> '') ? ''.$titleName.'' : "field_detail";
	
	switch($lbl_element)
	{ 
		case "short_text":
		return '<input type="text" name="'.$field_detail.'" id="field_detail_'.$elRow.'"  class="'.$classRequired.' width_full form-control" >';
		break;
		
		case "long_text":
		return '<textarea name="'.$field_detail.'" id="field_detail_'.$elRow.'" class="'.$classRequired.'width_fullX form-control" > </textarea>';
		break;
		
		case "date_select":
		return '<input type="text" name="'.$field_detail.'" id="field_detail_'.$elRow.'" class="'.$classRequired.'width_150 date-pick" >';
		break;
		
		case "number_text":
		return '<input type="text" name="'.$field_detail.'" id="field_detail_'.$elRow.'" class="'.$classRequired.'width_150 digitvalue" maxlength="7" >';
		break;
		
		
		case "multi_text":
		return '<table class="nocolor nopad noboda full" id="tbl_multi">
			<tbody><tr class="tr_multi_0">
			<td><input type="text" name="field_multi[0]" id="field_multi_0"  class="'.$classRequired.'width_full" placeholder="Enter Text" ></td>
			<td class="width_15"></td>
			</tr></tbody>
			<tfoot><tr>
			<td><a id="btn_add_multi" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';  
		break;
		
		
		
		case "group_select":
		return '';
		break;	
		
		
		
		case "material_select":
		$dd_drop_downs	= new drop_downs;
		$materialDrops = $dd_drop_downs->dropper_select("afp_conf_project_materials", "id", "title");
		
		return '<table class="nocolor nopad noboda full" id="tbl_material">
			<tbody><tr class="tr_material_0">
			<td><select name="material_item[0]" id="material_item_0"  class="'.$classRequired.'width_full" >'.$materialDrops.'</select></td>
			<td><input type="text" name="material_number[0]" id="material_number_0" class="'.$classRequired.'width_50 digitvalue" placeholder="Units" maxlength="7"  /></td>
			<td class="width_15"></td>
			</tr></tbody>
			<tfoot><tr>
			<td colspan="2"><a id="btn_add_material" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';
		break;	
		
		
		
		case "file_photo":
		return '<div><input type="hidden" name="field_photo_title_0" value="'.$elRow.'" /><input type="file" name="field_photo[]" id="field_photo_0" class="'.$classRequired.'width_300" multiple accept="image/*" /><span class="hint">To upload multiple images: Press \'CTRL\' key then click images </span></div>';
		break;
		
		
		
		case "file_docs":
		return '<div><input type="hidden" name="field_doc_title_0" value="'.$elRow.'" /><input type="file" name="field_doc[]" id="field_doc_0" class="width_300" multiple /><span class="hint">To upload multiple files: Press \'CTRL\' key then click files</span></div>';
		break;
		
		
		
		case "file_video":
		return '<div><input type="hidden" name="field_video_title_0" value="'.$elRow.'" /><input type="file" name="field_video[]" id="field_video_0" class="'.$classRequired.'width_300" multiple accept="video/*,audio/*" /><span class="hint">To upload multiple videos/audios: Press \'CTRL\' key then click files </span></div>';
		break;
		
		
		
		case "file_video_old":
		return '<table class="nocolor nopad noboda width_auto" id="tbl_video">
			<tbody><tr class="tr_video_0">
			<td><input type="text" name="field_video[0]" id="field_video_0"  class="'.$classRequired.'width_300" placeholder="Enter Video Link" ></td>
			<td></td>
			</tr></tbody>
			<tfoot><tr>
			<td><a id="btn_add_video" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';
		break;
		
		
		
	}
}





function getFormFieldXXX ($lbl_element, $elRow, $elRequired = 0, $titleName = '') 
{
	$classRequired = "";
	if($elRequired == 1) { $classRequired = "required "; }
	
	$field_detail = ($titleName <> '') ? ''.$titleName.'' : "field_detail";
	
	switch($lbl_element)
	{ 
		case "short_text":
		return '<input type="text" name="'.$field_detail.'['.$elRow.']" id="field_detail_'.$elRow.'"  class="'.$classRequired.'width_full" >';
		break;
		
		case "long_text":
		return '<textarea name="'.$field_detail.'['.$elRow.']" id="field_detail_'.$elRow.'" class="'.$classRequired.'width_full" > </textarea>';
		break;
		
		case "date_select":
		return '<input type="text" name="'.$field_detail.'['.$elRow.']" id="field_detail_'.$elRow.'" class="'.$classRequired.'width_150 date-pick" >';
		break;
		
		case "number_text":
		return '<input type="text" name="'.$field_detail.'['.$elRow.']" id="field_detail_'.$elRow.'" class="'.$classRequired.'width_150 digitvalue" maxlength="7" >';
		break;
		
		
		case "multi_text":
		return '<table class="nocolor nopad noboda full" id="tbl_multi">
			<tbody><tr class="tr_multi_0">
			<td><input type="text" name="field_multi[0]" id="field_multi_0"  class="'.$classRequired.'width_full" placeholder="Enter Text" ></td>
			<td class="width_15"></td>
			</tr></tbody>
			<tfoot><tr>
			<td><a id="btn_add_multi" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';  
		break;
		
		
		
		case "group_select":
		return '';
		break;	
		
		
		
		case "material_select":
		$dd_drop_downs	= new drop_downs;
		$materialDrops = $dd_drop_downs->dropper_select("afp_conf_project_materials", "id", "title");
		
		return '<table class="nocolor nopad noboda full" id="tbl_material">
			<tbody><tr class="tr_material_0">
			<td><select name="material_item[0]" id="material_item_0"  class="'.$classRequired.'width_full" >'.$materialDrops.'</select></td>
			<td><input type="text" name="material_number[0]" id="material_number_0" class="'.$classRequired.'width_50 digitvalue" placeholder="Units" maxlength="7"  /></td>
			<td class="width_15"></td>
			</tr></tbody>
			<tfoot><tr>
			<td colspan="2"><a id="btn_add_material" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';
		break;	
		
		
		
		case "file_photo":
		return '<div><input type="hidden" name="field_photo_title_0" value="'.$elRow.'" /><input type="file" name="field_photo[]" id="field_photo_0" class="'.$classRequired.'width_300" multiple accept="image/*" /><span class="hint">To upload multiple images: Press \'CTRL\' key then click images </span></div>';
		break;
		
		
		
		case "file_docs":
		return '<div><input type="hidden" name="field_doc_title_0" value="'.$elRow.'" /><input type="file" name="field_doc[]" id="field_doc_0" class="width_300" multiple /><span class="hint">To upload multiple files: Press \'CTRL\' key then click files</span></div>';
		break;
		
		
		
		case "file_video":
		return '<div><input type="hidden" name="field_video_title_0" value="'.$elRow.'" /><input type="file" name="field_video[]" id="field_video_0" class="'.$classRequired.'width_300" multiple accept="video/*,audio/*" /><span class="hint">To upload multiple videos/audios: Press \'CTRL\' key then click files </span></div>';
		break;
		
		
		
		case "file_video_old":
		return '<table class="nocolor nopad noboda width_auto" id="tbl_video">
			<tbody><tr class="tr_video_0">
			<td><input type="text" name="field_video[0]" id="field_video_0"  class="'.$classRequired.'width_300" placeholder="Enter Video Link" ></td>
			<td></td>
			</tr></tbody>
			<tfoot><tr>
			<td><a id="btn_add_video" class="nav_button width_50">[+] Add</a></td><td></td>
			</tr></tfoot>
			</table>';
		break;
		
		
		
	}
}




function passGenerator()
{
	$len = 8; $upper = 4; $number = 4; $pass='';
	$salt = "abcdefghjklmnpqrstuvwxyz@";
	$uppercase = "ABCDEFGHJKLMNPQRSTUVWXYZ@";
	$numbers   = "123456789";
	
		if ($upper) $salt .= $uppercase;
		if ($number) $salt .= $numbers;
		
		srand((double)microtime()*1000000);
		$i = 1;
			while ($i <= $len) {
			$num = rand() % strlen($salt);
			$tmp = substr($salt, $num, 1);
			$pass = $pass . $tmp;
			$i++;
			}
	return strtoupper($pass);	
}



function getChecksum($input) 
{
	$checkval = crc32($input);
	if($checkval < 0) {
		$checkval = $checkval *-1;
	}
	return $checkval;
}



/******************************************************************
@begin :: IMAGE UPLOAD FUNCTION
********************************************************************/	

function imageUploadArrXX ($pic, $uploadname, $uploadtarget, $getthumbnail, $loopNum)
{
	//$img_mimetypes = array("image/jpeg", "image/jpe", "image/jpg", "image/pjpeg", "image/gif", "image/png", "image/x-png");
	$image_details 	= getimagesize($pic['tmp_name'][$loopNum]);
	
	$mimetype 		= $image_details['mime'];
	$image_size 	= $pic['size'][$loopNum];
	$max_size 		= "500000";
	$img_ext 		= ".".getFileExtension(strtolower($pic['name'][$loopNum]));
	
	$img_new_name 	=  $uploadname.$img_ext;  
	$img_thmb_name 	=  $uploadname."_t".$img_ext;
	
				
	if(substr($mimetype,0,6) == "image/")
	{
		$filename 		= $img_new_name; 					
		$filename_thmb 	= $img_thmb_name;
		
		$source = $pic['tmp_name'][$loopNum];	
		$target = $uploadtarget . $filename;
		
		$isUploaded = move_uploaded_file($pic['tmp_name'][$loopNum], $target);
		
		if($isUploaded)
		{
			if($getthumbnail==1) {
			createThumbnail($filename, $image_details, $filename_thmb, $uploadtarget, 1);	
			}	
		
			echo "<script>alert(\"Image was successfully uploaded.\"); </script>";
			$the_image 	= $filename; 
			
		}
		else
		{
			echo "<script>
				alert(\"Image was NOT uploaded.\nPlease ensure destination folder exists and you are allowed access.\");
				history.back(-1);
			  </script>";  
				exit;  							
		}
		 

	}	
	else
		{
			echo "<script>
				alert(\"File selected for upload is not an Image.\");
				history.back(-1);
			  </script>";  
			exit;  
		}
	return $the_image;
}

	
function imageUploadXX ($pic, $uploadname, $uploadtarget, $getthumbnail = 0)
{
	$the_image = array();
		
	/* ===================================================
	$img_mimetypes = array("image/jpeg", "image/jpe", "image/jpg", "image/pjpeg", "image/gif", "image/png", "image/x-png");
	====================================================== */
	$image_details 	= getimagesize($pic['tmp_name']); //displayArray($image_details);
	$mimetype 		 = $image_details['mime'];
	$image_size 	   = $pic['size'];
	$max_size 		 = "1000000";
	$img_ext 		  = ".".getFileExtension(strtolower($pic['name']));
	
	$img_new_name 	 =  $uploadname.$img_ext;  
	$img_thmb_name 	=  $uploadname."_t".$img_ext;
				
	if(substr($mimetype,0,6) == "image/")
	{
		$filename 		 = $img_new_name; 					
		$filename_thmb 	= $img_thmb_name;
		
		$source = $pic['tmp_name'];	
		$target = $uploadtarget . $filename;
		
		if (intval($image_size) > intval($max_size)) 
		{  
			$the_image 	= array('name' => ''.$filename.'', 'thumb' => ''.$filename_thmb.'', 'result' => 0);	
		}
		else
		{
					
			$isUploaded = @move_uploaded_file($pic['tmp_name'], $target);
			
			if($isUploaded)
			{
				$img_result   = createThumbnail($filename, $image_details, $filename_thmb, $uploadtarget, $getthumbnail);	
				$img_size_new = filesize($uploadtarget . $filename);
				//echo $img_size_new; exit;
				
				$the_image 	= array('name' => ''.$filename.'', 'thumb' => ''.$filename_thmb.'', 'size' => ''.$img_size_new.'', 'result' => 1);
			}
			else
			{
				$the_image 	= array('name' => ''.$filename.'', 'thumb' => ''.$filename_thmb.'', 'result' => 0);					
			}
		 }

	}	
	else
	{
		$the_image 	= array('name' => ''.$img_new_name.'', 'thumb' => ''.$img_thmb_name.'', 'result' => 0);	
	}
	return $the_image;
}


function itemUploadXX ($file, $uploadname, $uploadtarget, $mimeoptions = 1, $getthumbnail = 0)
{
	$max_size 		= "5000000";
	
	$mimetypes = array(
		"application/pdf",
		"application/msword", 
		"application/vnd.openxmlformats-officedocument.wordprocessingml.document", 		
		"application/vnd.ms-powerpoint", 
		"application/vnd.openxmlformats-officedocument.presentationml.presentation",
		"application/vnd.ms-excel",
		"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		"text/plain", "text/csv", "text/comma-separated-values",
		"image/jpeg", "image/jpe", "image/jpg", "image/pjpeg", "image/gif", "image/png", "image/x-png"
		);
		
	if($mimeoptions == 2) {
		$image_details 	= getimagesize($file['tmp_name']);
		$item_type 		= $image_details['mime'];		
		
		$mimetypes = array(
		"image/jpeg", "image/jpe", "image/jpg", "image/pjpeg", "image/gif", "image/png", "image/x-png"
		);
	} 
	elseif($mimeoptions == 1) 
	{
		$item_type 		= $file['type'];
	}
	
	$item_arr 	    = array();
	$item_source 	 = $file['tmp_name'];
	$item_size 	   = $file['size'];
	
	$item_ext_a	  = getFileExtension(strtolower($file['name']));
	
	$item_ext 		= "." . $item_ext_a;
	$item_new 		=  $uploadname . $item_ext;  
	$item_target 	 =  $uploadtarget . $item_new;
	
	
	if(in_array($item_type, $mimetypes))
	{
		if (intval($item_size) > intval($max_size)) 
		{  
			$item_arr 	= array('name' => ''.$item_new.'', 'result' => 0, 'error' => 'File exceeds size limit.');	
		}
		else
		{
					
			$isUploaded = @move_uploaded_file($item_source , $item_target);
			
			if($isUploaded)
			{
				$item_arr 	= array('name' => ''.$item_new.'', 'result' => 1);
			}
			else
			{
				$item_arr 	= array('name' => ''.$item_new.'', 'result' => 0, 'error' => 'File not uploaded. Contact Admin.');					
			}
		}		
	}
	else
	{
		$item_arr 	= array('name' => ''.$item_new.'', 'result' => 0, 'error' => 'Invalid file type.');	
	}
		
			
	return $item_arr;
}


/******************************************************************
@end :: IMAGE UPLOAD FUNCTION
********************************************************************/	


/*******************************************************************
@BEGIN :: UPLOADS
*******************************************************************/

function itemUploadArr ($file, $uploadname, $uploadtarget, $loopNum) //, $fileoption = ""
{
	$do_upload		= NULL;
	$max_size 		 = "50000000";
	
	$item_arr 		= array();
	$item_source 	= $file['tmp_name'][$loopNum];
	
	$item_type 		= $file['type'][$loopNum];
	$item_size 		= $file['size'][$loopNum];	
	
	$mimetypes = array("application/pdf","application/msword", "image/jpeg", "image/jpe", "image/jpg", "image/pjpeg", "image/gif", "image/png", "image/x-png", "text/plain");
	
		$item_ext_a		= getFileExtension(strtolower($file['name'][$loopNum]));
		
		$item_origi	  = $file['name'][$loopNum];
		$item_ext 		= "." . $item_ext_a;
		$item_new 		=  $uploadname . $item_ext;  
		$item_target 	 =  $uploadtarget . $item_new;
		
		$isUploaded = move_uploaded_file($item_source, $item_target);
		
		if($isUploaded)
		{
			echo ' <hr> File <strong>' . $item_origi .'</strong> has been uploaded! <br>';
			$item_arr 	= array('name' => ''.$item_new.'', 'size' => ''.intval($item_size).'', 'type' => ''.$item_type.''); //
		}
		else
		{
			?> <script>alert("File NOT uploaded.\n\nEnsure destination folder exists and you are allowed access.");</script>  
			<?php 
			exit;  		
		}
	
	return $item_arr;
}


function itemUpload ($file, $uploadname, $uploadtarget, $fileoption = "")
{
	$do_upload		= NULL;
	$max_size 		= "3000000";
	
	$item_arr 		= array();
	$item_source 	= $file['tmp_name'];
	
	if($fileoption == "pic") {
		$image_details 	= getimagesize($file['tmp_name']);
		$item_type 		= $image_details['mime'];
		$item_size 		= $file['size'];
	} 
	else 
	{
		$item_type 		= $file['type'];
		$item_size 		= $file['size'];	
	}
	
	
	$item_ext_a		= getFileExtension(strtolower($file['name']));
	
	$item_ext 		= "." . $item_ext_a;
	$item_new 		=  $uploadname . $item_ext;  
	
	$item_target 	=  $uploadtarget . $item_new;
		
		$isUploaded = move_uploaded_file($item_source, $item_target);
		
		if($isUploaded)
		{
			$item_arr 	= array('name' => ''.$item_new.'', 'size' => ''.intval($item_size).'');
		}
		else
		{	$item_arr 	= array('error' => 26);
					
		}
		
	return $item_arr;
}

/*******************************************************************
@END :: UPLOADS
*******************************************************************/





	

function copy_file($source, $destination) 
{
	$sp = fopen($source, 'r');
	$op = fopen($destination, 'w');

	while (!feof($sp)) {
		$buffer = fread($sp, 512);  // use a buffer of 512 bytes
		fwrite($op, $buffer);
	}
	// close handles
	fclose($op);
	fclose($sp);
}

function recurse_copy($src,$dst) 
{ 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 




		
		
?>