<?php

function columnFilter_members($arr){
	return $arr['id_member'];
}
	
function linkExternal($str) {
	$link = trim($str);	
	if(substr($link,0,3)  <> 'htt') { $link = 'http://'. $link; }
	$link = 'out.php?url='.urlencode($link);	
	return $link;
}
	
function displayDecimal($amount) { return number_format($amount,2,'.',','); }
function displayFloat($amount)   { return number_format($amount); }
function displayPercent($val, $total)   { $out = 0; if($total > 0){ $out = (intval($val) / $total ) * 100; } return number_format($out); }
function displayArray($array)    { echo "<pre>"; print_r($array); echo "</pre><hr />"; }


function yesNoPost($str)   		{ $val = ($str == "on" or $str == 1) ? 1 : 0; return $val; }
function yesNoChecked($str)     { $val = ($str == 1) ? " checked " : " "; return $val; }
function yesNoText($str) 		{ if ($str == 1) { return "Yes"; } elseif ($str == 0) { return "No"; } else { return "?"; } }


function echoBr ($text)    { print($text)."<hr />"; }
function checkArray($array)      { echo is_array($array) ? 'Array' : 'not an Array'; }
function is_connected() {
    $connected = @fsockopen("www.google.com", 80, $num, $error, 5); 
    if ($connected){ $is_conn = 1; fclose($connected); } else { $is_conn = 0; }
    return $is_conn;
} 





function fineOutput($str, $useBreak=0){
    $str = stripslashes(trim($str));
    $str = stripslashes(html_entity_decode($str));
    if($useBreak){ $str = nl2br($str); }
    $str = remove_special_chars($str);

    return $str;
}



function getInitials($str){
    $expr = '/(?<=\s|^)[a-z]/i';
    preg_match_all($expr, $str, $matches);
    $result = implode('', $matches[0]);
    $result = strtoupper($result);

    return $result;
}


function cas_query_to_array($str)
{
	$result = array();
	
	if(trim($str) <> '')
	{
		$strNew = str_replace( "` AS `", "` => `", $str );	
		$strNew = str_replace( "`", "", $strNew );
		$strNew = preg_replace("/ /", "", $strNew );
		$arrStr = explode("," , $strNew);
		
		foreach ($arrStr as $pairs)
		{
			$temp = explode("=>", $pairs);
			$temp_val = (@$temp[1]<>'') ? $temp[1] : $temp[0];
			$result[trim($temp[0])] = trim($temp_val);
		}
	}
    return $result;
}


// Bypass PHP to allow any charset!!
function html_encode($str){ return preg_replace(array('/&/', '/</', '/>/', '/"/'), array('&amp;', '&lt;', '&gt;', '&quot;'), $str);  }

function remove_special_chars($str, $useBreak=0)
{
	$str = trim($str);
	if($useBreak){ $str = nl2br($str); }
	 			 
    preg_replace(array('/&/', '/</', '/>/', '/"/'), array('&amp;', '&lt;', '&gt;', '&quot;'), $str);
	$trans = get_html_translation_table(HTML_ENTITIES);
	
	$str = trim(html_entity_decode(stripslashes(strtr($str, $trans))));
    return $str;
}


function strip_tags_clean ($str, $exceptions='') 
{
	$str = trim(html_entity_decode(stripslashes($str),ENT_QUOTES,'UTF-8'));
	$spaceString = str_replace( '<', ' <', $str );
	$doubleSpace = strip_tags( $spaceString, $exceptions );
	$singleSpace = preg_replace('/\s\s+/', ' ', $doubleSpace); 	
	
	return $singleSpace;
}


function clean_output($str, $useBreak=0)
{
	if($useBreak){ $str = nl2br($str); }
		$patterns[0] = "/`/";
		$patterns[1] = "/’/";
		
	$str = trim(html_entity_decode(stripslashes($str),ENT_QUOTES,'UTF-8'));	
	$str = iconv("ISO-8859-15", "UTF-8", iconv("UTF-8", "ISO-8859-15//IGNORE", $str));
	$str = preg_replace('/\s\s+/', ' ', trim($str));	
	
    return $str;
}

function clean_text($string)
{
	$str = trim(html_entity_decode(stripslashes($string),ENT_QUOTES,'UTF-8'));	
	$str = iconv("ISO-8859-15", "UTF-8", iconv("UTF-8", "ISO-8859-15//IGNORE", $str));
	$str = preg_replace("/\([^)]+\)/","",$str); //utf8_decode();
	
	$patts   = '/[^a-zA-Z0-9. -\/]+/';	
	$str = preg_replace($patts,' ',utf8_decode($str));	
	$str = preg_replace('/\s\s+/', ' ', trim($str));	
	
	
	return utf8_encode($str);
}

function datePickerFormat($str){
	if($str <> ''){
		$str = date('Y-m-d',strtotime($str)); //m/d/Y
	}
	return $str;
}

function dateDisplayFormat($str){
	if($str <> ''){
		$str = date('M d, Y',strtotime($str));
	}
	return $str;
}

function dateWeek($str){	
	if($str <> ''){
		$dy = date('Y', strtotime($str));
		$dw = date('W', strtotime($str));
		$str = (intval($dw) <= 52) ? $dy.$dw : $dy.'01';
	}
	return $str;
}



function display_PageTitle ($box_title, $htag = 'h2', $hcolor = '')
{
	$result = '';
	if($box_title <> ''){
		if($htag == '') { $htag = 'h2'; }
		if($hcolor <> '') { $hcolor = ' class="'.$hcolor.'" '; }
		$result = '<div class="section-title"><'.$htag.''.$hcolor.'><span>'.clean_title($box_title).'</span></'.$htag.'></div>';
	}
	return $result;
}

function display_linkMenu ($mlink, $mlinkSeo)
{
	$link = '#';
	$lbit = substr($mlink,0,3);	
	if($lbit == 'htt' or $lbit == 'www' or $lbit == 'ftp' or $lbit == 'ww2') //EXTERNAL
	{ 
		$redirect = $mlink;
		if(substr($lbit,0,2)  == 'ww') { $redirect = 'http://'. $mlink; }
		$sURL = urlencode($redirect); 
		$link = 'out.php?url='.$sURL;  
	} 
	elseif($mlink <> "#") 
	{ $link = $mlinkSeo; }
	elseif($mlink == "#") 
	{ $link = ''; }
	
	if($link <> '') { $link = 'href="'.$link.'"'; }
	
	return $link;
}

function display_linkArticle ($cid, $cref)
{
	$result = $cid.'/'.$cref.'/';
	$result = 'href="'.$result.'"';
	return $result;
}




function getNext(&$array, $curr_key, $isNews = 0)
{
    $next = 0;
    reset($array);

    do
    {
        $tmp_key = key($array);
		
		if($isNews == 1) {$tmp_key = $array[key($array)]['id'];}
		
        $res = next($array);
    } while ( ($tmp_key != $curr_key) && $res );

    if( $res )
    {
        $next = key($array); if($isNews == 1) {$next = $array[key($array)]['id'];}
    }

    return $next;
}

function getPrev(&$array, $curr_key, $isNews = 0)
{
    end($array);
    $prev = key($array);

    do
    {
        $tmp_key = key($array);
		
		if($isNews == 1) {$tmp_key = $array[key($array)]['id'];}
		
        $res = prev($array);
    } while ( ($tmp_key != $curr_key) && $res );

    if( $res )
    {
        $prev = key($array);
		
		if($isNews == 1) {$prev = $array[key($array)]['id'];}
    }

    return $prev;
}	
	
	
	
	
	

function array_multi_key_exists(array $arrNeedles, array $arrHaystack, $blnMatchAll=true){
     $blnFound = array_key_exists(array_shift($arrNeedles), $arrHaystack);
     
     if($blnFound && (count($arrNeedles) == 0 || !$blnMatchAll))
         return true;
     
     if(!$blnFound && count($arrNeedles) == 0 || $blnMatchAll)
         return false;
     
     return array_multi_key_exists($arrNeedles, $arrHaystack, $blnMatchAll);
 }




function displayTime($crit = '', $morn = 'a') {
	$conf_time_select = '';
	$selMorn = '';
	$selEve  = '';
	
	if($morn == 'a' and $crit == '') { $selMorn = 'selected'; }
	if($morn == 'p' and $crit == '') { $selEve  = 'selected'; }
	
	if($crit <> '') { 
		$conf_time_select = '<option value="'.$crit.'" selected="selected">'.$crit.'</option>'; 
	}
	
	$conf_time_select .= '<option value="00:00:00" class="midnight">Midnight</option>
<option value="01:00:00" class="evening">01:00 am</option>
<option value="02:00:00" class="evening">02:00 am</option>
<option value="03:00:00" class="evening">03:00 am</option>
<option value="04:00:00" class="evening">04:00 am</option>
<option value="05:00:00" class="evening">05:00 am</option>
<option value="06:00:00" class="morning">06:00 am</option>
<option value="07:00:00" class="morning">07:00 am</option>
<option value="07:30:00" class="morning">07:30 am</option>
<option value="08:00:00" class="morning" '.$selMorn.'>08:00 am</option>
<option value="08:30:00" class="morning">08:30 am</option>
<option value="09:00:00" class="morning">09:00 am</option>
<option value="09:30:00" class="morning">09:30 am</option>
<option value="10:00:00" class="morning">10:00 am</option>
<option value="10:30:00" class="morning">10:30 am</option>
<option value="11:00:00" class="morning">11:00 am</option>
<option value="11:30:00" class="morning">11:30 am</option>
<option value="12:00:00" class="noon">Noon</option>
<option value="12:30:00" class="afternoon">12:30 pm</option>
<option value="13:00:00" class="afternoon">01:00 pm</option>
<option value="13:30:00" class="afternoon">01:30 pm</option>
<option value="14:00:00" class="afternoon">02:00 pm</option>
<option value="14:30:00" class="afternoon">02:30 pm</option>
<option value="15:00:00" class="afternoon">03:00 pm</option>
<option value="15:30:00" class="afternoon">03:30 pm</option>
<option value="16:00:00" class="afternoon">04:00 pm</option>
<option value="16:30:00" class="afternoon">04:30 pm</option>
<option value="17:00:00" class="afternoon"  '.$selEve.'>05:00 pm</option>
<option value="17:30:00" class="afternoon">05:30 pm</option>
<option value="18:00:00" class="evening">06:00 pm</option>
<option value="18:30:00" class="evening">06:30 pm</option>
<option value="19:00:00" class="evening">07:00 pm</option>
<option value="19:30:00" class="evening">07:30 pm</option>
<option value="20:00:00" class="evening">08:00 pm</option>
<option value="20:30:00" class="evening">08:30 pm</option>
<option value="21:00:00" class="evening">09:00 pm</option>
<option value="21:30:00" class="evening">09:30 pm</option>
<option value="22:00:00" class="evening">10:00 pm</option>
<option value="22:30:00" class="evening">10:30 pm</option>
<option value="23:00:00" class="evening">11:00 pm</option>
<option value="23:30:00" class="evening">11:30 pm</option>';

return $conf_time_select;

}





function conf_SelectPeriods($pstart, $pmonths_future = 5, $pmonths_past = 5, $isSelected = 0, $isDropdown = 1) 
{
	$result = '';
	
	if($pstart == '') { return $result; exit; }
	
	$sel_val     = strtotime($pstart); 
	$sel_key     = date('Ym', $sel_val);
	$sel_label   = date('Y', $sel_val).' - '.date('F', $sel_val);
			
	if($pmonths_past > 0)
	{
		$pperiod = strtotime($pstart); 
		$pperiod = date('Y-m-d', mktime(0,0,0,date('m',$pperiod) - ($pmonths_past+1),1,date('Y',$pperiod))); //$pstart;
		
		for($i=1; $i<=$pmonths_past; $i++)
		{
			$cd 		  = strtotime($pperiod); 
			$new_date    = date('Y-m-d', mktime(0,0,0,date('m',$cd) + 1,1,date('Y',$cd))); 
			$new_key     = date('Ym', strtotime($new_date));
			$new_label   = date('Y', strtotime($new_date)).' - '.date('F', strtotime($new_date));
			
			$pperiod  	  = $new_date;
			
			if($isDropdown == 1) {
				$result  .=  '<option value="'.$new_key.'"> '.$new_label.'</option>';	
			}
		}
	}
	
	if($isSelected ==1)
	{
		$result  .=  '<option value="'.$sel_key.'" selected> '.$sel_label.'</option>';
	}
	
	if($pmonths_future > 0)
	{
		$pperiod = $pstart;
		for($i=1; $i<=$pmonths_future; $i++)
		{
			$cd 		  = strtotime($pperiod); 
			$new_date    = date('Y-m-d', mktime(0,0,0,date('m',$cd) + 1,1,date('Y',$cd))); 
			$new_key     = date('Ym', strtotime($new_date));
			$new_label   = date('Y', strtotime($new_date)).' - '.date('F', strtotime($new_date));
			
			$pperiod  	  = $new_date;
			
			if($isDropdown == 1) {
				$result  .=  '<option value="'.$new_key.'"> '.$new_label.'</option>';	
			}
		}
	}
	return $result;
}

function displayYears($crit = '',$minus=20,$plus=0) {
	
	$conf_year_select = '<option value="" selected="selected">Select Year</option>';	//&lt; None &gt;
		
	for($i=(date('Y')+$plus); $i>=(date('Y')-$minus); $i--)
	{
		if($i == $crit) { $selected=" selected "; } else { $selected=""; }
		$conf_year_select .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
	}
	
	return $conf_year_select;
}


function displayMonths($crit = '') {
	
	$conf_select = '<option value="" selected="selected">Select Month</option>';	
	
	$arr_monthsX = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December");
	
	$arr_months = array("1" => "January","February","March","April","May","June","July", "August", "September","October","November","December");
	
	foreach($arr_months as $key => $val)
	{
		if($key == $crit) { $selected=" selected "; } else { $selected=""; }
		$conf_select .= '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
	}
	
	return $conf_select;
}


function displayMonthName($crit=0, $long = 1) {
	$out = '';
	$crit_b = str_pad($crit,2,'0',STR_PAD_LEFT);
	
	$months_long = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December");
	
	$months_short = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");
	
	$arr_months = ($long == 1) ? $months_long : $months_short;
	
	if($crit_b <> '' and array_key_exists($crit_b,$arr_months))
	{ $out = $arr_months[$crit_b]; }
	return $out;
}



function cleanCalendarPeriod ($period, $long = 0) {
	
	$months_long = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December");
	
	$months_short = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");
	
	if($long == 1) { $arr_months = $months_long; } else { $arr_months = $months_short; }
	
	if($period <> '')
	{
		$p_year  = substr($period, 0, 4);
		$p_month = substr($period, 4, 2);
		$p_month = $arr_months[$p_month];
		
		$period   = $p_month.' '.$p_year;
	}
	
	return $period;
		
}


function nameSplit($fullname) {
	$result = array();
	$result['fname'] = '';
	$result['lname'] = '';
	
	if($fullname <> '')
	{
		$name_arr  = @preg_split("/ /", $fullname); 
		$result['fname'] = @ucwords($name_arr[0]);
		$result['lname'] = implode(" ",array_slice($name_arr, 1)); 
	}
	return $result;
}




/******************************************************************
@begin :: CLEAN UP VALIDATION
********************************************************************/	
function trim_input($string)
{
	$outstring = trim($string);
	return $outstring;
}

function filter_data($val)
{
	if(is_array($val)) { 
	return array_map("filter_data",$val);
	}
	else
	{
	return sanitize($val);
	}
}

function sanitize($input) 
{
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
		if (get_magic_quotes_gpc()) { $input = stripslashes($input); } 
		$input  = cleanInput($input);        
    }
    return $input;
}


function cleanTableCells($match) {
		/*//$out = str_replace('<br />', '', $match[0]);*/
		$out = preg_replace('/<p[^>]*?>/', '', $match[0]);
		$out = str_replace('</p>', '', $out);
		
		/*$out = preg_replace('/<p[^>]*?>/', '<p>', $match[0]);*/		
		$out = preg_replace('/<span[^>]*?>/', '', $out);
		$out = str_replace('</span>', '', $out);
		$out = preg_replace('/<div[^>]*?>/', '', $out);
		$out = str_replace('</div>', '', $out);
		$out = preg_replace('/<td[^>]*?>/', '<td>', $out);
        return $out;

}




function cleanInput($input, $no_domain=1) 
{
	
	if(preg_match("/<[^<]+>/",$input,$m) == 0) {			   /* Not Html */		
		$input = nl2br($input);
	} 
	
		
	$specialChars = array('@&middot;@i','@&iquest;@i','@&Agrave;@i','@&Aacute;@i','@&Acirc;@i','@&Atilde;@i','@&Auml;@i','@&Aring;@i','@&AElig;@i','@&Ccedil;@i','@&Egrave;@i','@&Eacute;@i','@&Ecirc;@i','@&Euml;@i','@&Igrave;@i','@&Iacute;@i','@&Icirc;@i','@&Iuml;@i','@&ETH;@i','@&Ntilde;@i','@&Ograve;@i','@&Oacute;@i','@&Ocirc;@i','@&Otilde;@i','@&Ouml;@i','@&times;@i','@&Oslash;@i','@&Ugrave;@i','@&Uacute;@i','@&Ucirc;@i','@&Uuml;@i','@&Yacute;@i','@&THORN;@i','@&szlig;@i','@&agrave;@i','@&aacute;@i','@&acirc;@i','@&atilde;@i','@&auml;@i','@&aring;@i','@&aelig;@i','@&ccedil;@i','@&egrave;@i','@&eacute;@i','@&ecirc;@i','@&euml;@i','@&igrave;@i','@&iacute;@i','@&icirc;@i','@&iuml;@i','@&eth;@i','@&ntilde;@i','@&ograve;@i','@&oacute;@i','@&ocirc;@i','@&otilde;@i','@&ouml;@i','@&oslash;@i','@&ugrave;@i','@&uacute;@i','@&ucirc;@i','@&uuml;@i','@&uuml;@i','@&yacute;@i','@&thorn;@i','@&yuml;@i','@&OElig;@i','@&oelig;@i','@&uml;@i','@&sect;@i');
	
	$search_items_2 = array (
	'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
	'@<meta[^>]*?>@siU',				// Strip meta tags properly
	'@</meta>@siU',						// Strip meta tags properly
	'@<style[^>]*?>.*?</style>@siU',	// Strip style tags properly
	'@<link[^>]*?/>@siU',				// Strip link tags properly
	'@<![\s\S]*?--[ \t\n\r]*>@',		// Strip multi-line comments
	'@<script[^>]*?>.*?</script>@si',	// Strip out javascript
	'@<font[^>]*?>@siU',				// Strip meta tags properly
	'@</font>@siU',						// Strip meta tags properly
	'@<span[^>]*?>@siU',
	'@</span>@siU',
	//'@\r\n@',
	//'@<p>&nbsp;</p>@',
	'@<p>[ ]</p>@siU',
	'@<o:p>@',
	'@</o:p>@',
	'@ style=[^>]*?@siU',
	'@MsoNormal@'
	//'@\n@'
	);

	$search_items_1 = array ('@(<br[^>]*?/>)[\s]+@',
				 '@([\r\n])[\s]+@',                // Strip out white space
				 '@&(quot|#34);@i',                // Replace HTML entities
				 '@&ldquo;@i',
				 '@&rdquo;@i',
				 '@&(amp|#38);@i',
				 '@&(lt|#60);@i',
				 '@&(gt|#62);@i',
				 '@&ndash;@i',
				 '@&mdash;@i',
				 '@&(nbsp|#160);@i',
				 '@([ ])[\s]+@',
				 
				 '@&(iexcl|#161);@i',
				 '@&(cent|#162);@i',
				 '@&(pound|#163);@i',
				 '@&(copy|#169);@i',
				 '@&#(\d+);@e');                    // evaluate as php
	
	$replace_items_1 = array ('\1',
				  '\1',
				  '"',
				  '"',
				  '"',
				  '&',
				  '<',
				  '>',
				  '-',
				  '-',
				  ' ',
				  '\1',
				   
				  chr(161),
				  chr(162),
				  chr(163),
				  chr(169),
				  'chr(\1)');
	
	$output = preg_replace($search_items_2, '', $input);
	//$output = preg_replace($search_items_1, $replace_items_1, $output);
    
	
	$output = str_replace(array("\r", "\n"), "", $output);						  /* Removing line breaks */
	
	/*$output = preg_replace("/(^(&nbsp;|\s)+|(&nbsp;|\s)+$)/", "", $output );*/
	$output = preg_replace("/(^(&nbsp;)*|(&nbsp;)+)/", " ", $output );			  /* remove multiple spaces - &nbsp;*/
	$output = preg_replace('/\s\s+/', ' ', $output);								/* remove multiple spaces*/
	
	$output = preg_replace('/<p[^>]*?>/', '<p>', $output);
	$output = preg_replace('/<p[^>]*>[\s|&nbsp;]*<\/p>/', '', $output);
	$output = preg_replace('/<span[^>].*?>/', '<span>', $output);
	
	
	
	
	$output = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $output);	/* remove Empty Lines*/
	$output = preg_replace('/(<br.*?>)+/', '<br>', $output);
		
	$output = preg_replace_callback("~<table\b.*?/table>~si", "cleanTableCells", $output);
	
	$output=str_replace("</p><br>", "</p>", $output);
	$output=str_replace("<p> </p>", "", $output);
	$output=str_replace("<br><p>", "<p>", $output);
	$output=str_replace("<br><ul>", "<ul>", $output);
	$output=str_replace("<br></li>", "</li>", $output);
	$output=str_replace("<li><br>", "<li>", $output);
	$output=str_replace("<li></li>", "", $output);
	$output=str_replace("<li> </li>", "", $output);
	$output=str_replace("<li>&bull;", "<li>", $output);
	$output=str_replace("<li>&middot;", "<li>", $output);
	$output=str_replace("&middot;", "<li>", $output);
	$output=str_replace("&Oslash;", "", $output);
	$output=str_replace("&oslash;", "", $output);
	$output=str_replace("&uuml;", "", $output);
	
	if($no_domain == 1){
		$output=str_replace(SITE_PATH, "", $output);
		$output=str_replace(SITE_DOMAIN_LIVE, "", $output);
	}
	
	$charEntities = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
	$output = trim(strtr($output, $charEntities));
	
	
    return $output;
}

function cleanSimplex($input) {
	$output = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $input);	 /* remove Empty Lines*/
	$output = preg_replace('/\s\s+/', ' ', $output);								/* remove multiple spaces*/
	$output = str_replace(array("\r", "\n"), "", $output);	
	
	$output = preg_replace_callback("~<table\b.*?/table>~si", "cleanTableCells", $output);
	
	$output = preg_replace('/<tr[^>]*?>/', '<tr>', $output);
	$output = preg_replace('/<td[^>]*?>/', '<td>', $output);
	$output = preg_replace('/<p[^>]*?>/', '<p>', $output);
	$output = preg_replace('/<span[^>].*?>/', '<span>', $output);
	
	//$output = str_replace('<span>', '', $output);
	//$output = str_replace('</span>', '', $output);
	
	$output = preg_replace('/<![\s\S]*?--[ \t\n\r]*>/', ' ', $output);	
	
	$output  = cleanInput($output);
	
	$output = str_replace(SITE_PATH, "", $output);
	$output = str_replace(SITE_DOMAIN_LIVE, "", $output);
	
	//$charEntities = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
	//$output = trim(strtr($output, $charEntities));	
		
	return $output;
}


function quote_smart($value, $uselike=0) {
	$cndb = new master();
	$value = $cndb->quote_si($value, $uselike);
    return $value;
}


function q_si($value, $uselike=0) {
	$cndb = new master();
	$value = $cndb->quote_si($value, $uselike);
    return $value;
}

function q_in($value, $uselike=0) {
	$out = array();
	if (is_array($value)) {
		foreach($value as $val){
			$out[] = q_si($val);
		}
	}
	return $out;
}




function cleanRedStr ($str, $qry="") {
	
	if(substr($str,strlen($str)-3,3)=="php" or substr($str,strlen($str)-1,1)=="/") {$redstr="?";} else { $redstr="&"; }
	//if($qry == "") { $redstr = ""; }
	return $str.$redstr.$qry;
}


function getThumbName ($str) {
	$pic_insert 	  = strrpos($str , '.');
	$pic_thmb		= substr_replace($str, '_t.', $pic_insert, 1);	
	return $pic_thmb;
}


function autoThumbnail($im)
{
	$thmb_width=GALLTHMB_WIDTH;
	
	if(preg_match( '/src="([^"]*)"/i', $im, $image_array ))
	{
   		$pic_thmb 	   = urldecode($image_array[1]);  
		if((strpos($pic_thmb,'file:')) ) { $pic_thmb = ''; }	//(!file_exists($pic_thmb)) or 
	}
	else
	{
		$pic_out		= '';
		$pic_insert 	 = strrpos($im , '.');
		$pic_thmb	   = substr_replace($im, '_t.', $pic_insert, 1);
		
		if(strpos($im,'image/') === 0 or strpos($im,'image/') > 0)
		{ $pic_root = SITE_PATH; $pic_rule = SITE_DOMAIN_LIVE; }
		elseif(strpos($im,'gallery/') === 0 or strpos($im,'gallery/') > 0)
		{ $pic_root = UPL_IMAGES; $pic_rule = DISP_IMAGES; }
		else
		{ $pic_root = UPL_GALLERY; $pic_rule = DISP_GALLERY; }
	
		
		
		$pic_src 	   = $pic_root; 			  //UPL_GALLERY; 				//'image/gallery/'; //
		$pic_thmb_a 	= $pic_src.$pic_thmb;
		$pic_thmb 	  = $pic_rule.$pic_thmb; //DISP_GALLERY
		
		if (!file_exists($pic_thmb_a)) 
		{	
			$image_details 	= getimagesize($pic_src.$im); //displayArray($image_details); exit;
			$quality = "";
			switch ($image_details['mime'])
			{
				case 'image/gif':
					$creationFunction	= 'imagecreatefromgif';
					$outputFunction		= 'imagepng';
					$mime				= 'image/png'; // We need to convert GIFs to PNGs
					$doSharpen			= FALSE;
					$quality			= round(10 - ($quality / 10)); 
				break;
				
				case 'image/x-png':
				case 'image/png':
					$creationFunction	= 'imagecreatefrompng';
					$outputFunction		= 'imagepng';
					$doSharpen			= FALSE;
					$quality			= round(10 - ($quality / 10)); // PNG needs a compression level of 0 (no compression) through 9
				break;
				
				default:
					$creationFunction	= 'imagecreatefromjpeg';
					$outputFunction	 	= 'imagejpeg';
					$doSharpen			= TRUE;
				break;
			}
			$orig_image = $creationFunction($pic_src.$im);
			list($width, $height, $type, $attr) = getimagesize($pic_src.$im);
			if ($width > $thmb_width) 
			{ $ratio = $thmb_width / $width; $newheight = $ratio * $height; } else 
			{ $newheight = $height; }
		
			if($sm_image = imagecreatetruecolor($thmb_width,$newheight)) // or die ("Cannot Initialize new gd image stream");
			{
				//$sm_image = imagecreatetruecolor($thmb_width,$newheight) or die ("Cannot Initialize new gd image stream");;
				imagecopyresampled($sm_image,$orig_image,0,0,0,0,$thmb_width,$newheight,imagesx($orig_image),imagesy($orig_image));
				$outputFunction($sm_image,$pic_thmb_a);
			}
			//imagedestroy($sm_image); imagedestroy($orig_image);
		}
	}
	return $pic_thmb;
}

function getContGalleryPic($cont_id, $cont_title = '', $cont_type = '_cont', $img_options = '') { 
	$imageReturn = '';
	if(is_array(@master::$listGallery['parent'][$cont_type][$cont_id]))
	{
		$pic_key        = current(master::$listGallery['parent'][$cont_type][$cont_id]); 
		$pic_arr		= master::$listGallery['full'][$pic_key];
		
		$pic_type 	   = trim($pic_arr['filetype']);
		$pic_name       = trim($pic_arr['filename']);
		$pic_small	  = '';
		
		
		if($pic_type == 'p')	
		{ $pic_small 	  = autoThumbnail($pic_name); }
		elseif($pic_type == 'v')
		{
			$vid_link		= $pic_name; 
			$vid_insert	  = strrpos($vid_link , '/')+1;
			$vid_code		= substr($vid_link, $vid_insert);
			$pic_small	  = 'http://img.youtube.com/vi/'.$vid_code.'/mqdefault.jpg';
		}	
		
		if ($cont_title == '') { $cont_title = $pic_name; }
		if ($pic_small <>'') 
		{ 
			$imageReturn		= '<img src="'.$pic_small.'" alt="'.$cont_title.'" '.$img_options.'/>'; 
		}
	}
	return $imageReturn;
}



/******************************************************************
@begin :: GeNERATE SEO TITLES
********************************************************************/	

/* takes the input, scrubs bad characters */
setlocale(LC_ALL, 'en_US.UTF8');

function generate_seo_title($str, $delimiter='-', $remove_words = true) {
	$bad_words = array('a','and','the','an','it','is','with','can','of','why','if','at','not','to','on');
	
	$delimiter = trim($delimiter);
	$clean = clean_output($str);
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
	$clean = preg_replace("/[^a-zA-Z0-9-\/_|+ ]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));	
	if($remove_words) { $clean = remove_bad_seo($clean, $delimiter, $bad_words); }	
	
	$clean = preg_replace("/[\/_|+ ]+/", $delimiter, $clean);	
	$clean = preg_replace("/(".$delimiter.")+/", "".$delimiter."", $clean );
	
	return $clean;
}

/* takes an input, scrubs unnecessary words */
function remove_bad_seo($input,$replace,$words_array = array(),$unique_words = true)
{
	$input_array = explode(' ',$input);
	$return = array();

	//loops through words, remove bad words, keep good ones
	foreach($input_array as $word)
	{
		//if it's a word we should add...
		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))
		{
			$return[] = $word;
		}
	}
	return implode($replace,$return);
}

/******************************************************************
@begin :: GeNERATE SEO TITLES
********************************************************************/	






function getFileExtension($str) {
	$i = strrpos($str,"."); if (!$i) { return ""; }
	$l = strlen($str) - $i; $ext = substr($str,$i+1,$l); return $ext;	
}


function checkIfStaff($str) {
	$staffEmail   = 0;
	$u_email_domain	 = trim(substr($str,strripos($str,"@" )+1));
	if($u_email_domain == SITE_DOMAIN_URI or $u_email_domain == SITE_DOMAIN_URI_TWO) { $staffEmail   = 1; }
	return $staffEmail;	
}




function smartTruncateNew($text, $length, $break=' ', $start=0, $suffix = '&hellip;', $isHTML = true) {
	$string = $text;
	if(strlen($string) <= $length) return $string;
	
	if($break <> '')
	  {	  // is $break present between $limit and the end of the string?
		  if(false !== ($breakpoint = @strpos($string, $break, $length))) {
			if($breakpoint < strlen($string) - 1) {
			  $string = substr($string, $start, $breakpoint);
			}
		  } //echo $breakpoint;
	  }
	  else
	  {	$string = substr($string, $start, $length);  }
	  //echo $string. '<hr>'; //exit;
	$i = 0;
	$simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
	$tags = array();
	if($isHTML){
		preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		foreach($m as $o){
			if($o[0][1] - $i >= $length)
				break;
			$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
			// test if the tag is unpaired, then we mustn't save them
			if($t[0] != '/' && (!isset($simpleTags[$t])))
				$tags[] = $t;
			elseif(end($tags) == substr($t, 1))
				array_pop($tags);
			$i += $o[1][1] - $o[0][1];
		}
	}

	// output without closing tags
	$output = substr($text, $start, $length = min(strlen($text),  $length + $i));
	// closing tags
	$output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

	// Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
	$pos = @(int)end(end(preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
	// Append closing tags to output
	$output.=$output2;

	// Get everything until last space
	$one = trim(substr($output, $start, $pos));
	// Get the rest
	$two = substr($output, $pos, (strlen($output) - $pos));
	// Extract all tags from the last bit
	preg_match_all('/<(.*?)>/s', $two, $tags);
	// Add suffix if needed
	if (strlen($text) > $length) { $one .= $suffix; }
	// Re-attach tags
	$output = $one . implode($tags[0]);

	//added to remove  unnecessary closure
	$output = str_replace('</!-->','',$output); 

	return $output;
}




// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function string_truncate($string, $limit, $break=".", $pad=" ", $useBreak="yes", $useHoverText=0, $useStart=0)
{
	//return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  if($useStart |= 0) { $string = substr($string, $useStart); }
  
  if($useBreak == "yes")
  {	  // is $break present between $limit and the end of the string?
	  if(false !== ($breakpoint = @strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
		  $string = substr($string, 0, $breakpoint);
		}
	  }
  }
  else
  {		$string = substr($string, 0, $limit);  }
  
  if($useStart |= 0) { $string = "..." . substr($string, strpos($string, " ")+1); }
	
  $string .= $pad;
	
  return $string;
}

function clean_request($str){
	if(is_array($str)) { 
		return array_map("clean_request",$str);
	}
	elseif(strlen($str) > 0){ /*!is_array($str) and */
		$str = trim(htmlentities(addslashes($str)));
	}
	return $str;
}

function clean_http($str){
	$lbit = substr($str,0,3);	//EXTERNAL
	if($lbit == 'htt' or $lbit == 'www' or $lbit == 'ftp' or $lbit == 'ww2') { 
		$redirect = $str;
		if(substr($lbit,0,2)  == 'ww') { $redirect = 'http://'. $str; }
		/*$sURL = urlencode($redirect); $link = 'out.php?url='.$sURL;  */
		$link = $redirect;
	} 
	else { $link = $str; }
	return $link;
}

function clean_input($string)
{
	$patterns[0] = "/'/";
	$patterns[1] = "/\"/";
	$patterns[2] = "/ /";
	$string = preg_replace($patterns,'',$string);
	$string = trim($string);
	$string = addslashes($string);
	return preg_replace("/[<>]/", '_', $string);
}

function clean_alphanum($string)
{
	$string = strtolower(trim(html_entity_decode(stripslashes($string))));
	
	$patterns[0] = "/[^0-9a-z ]/";
	$string = preg_replace($patterns,'',$string);
	
	$spaces[0] 	 = "/ /";
	$string = preg_replace($spaces,'-',$string);
	
	$string = trim($string);
	
	return $string;
	//return preg_replace("/[<>]/", '_', $string);
}



function clean_title($string, $ignore="")
{
	$string = trim(html_entity_decode(stripslashes($string)));
	
	$patterns[0] = "/_/";
	if($ignore <> "-"){
		$patterns[1] = "/-/";
	}
	
	$string = preg_replace($patterns,' ',$string);
	
	$string = ucwords(trim($string));
	
	return $string;
	//return preg_replace("/[<>]/", '_', $string);
}

function clean_phone($string)
{
	$string 	   = trim(html_entity_decode(stripslashes($string)));
	
	$patterns[0]  = "/ /";
	$patterns[1]  = "/-/";
	
	$string  	   = preg_replace($patterns,'',$string);
	$string 	   = trim($string);
	
	return $string;
}


function cleanFileName($string) {
	$string = trim(html_entity_decode(stripslashes($string)));	
	$patterns_remove   = '/[\'"]/';
	$patterns_replac   = '/[^a-zA-Z0-9._]+/';
	
	$string 		= preg_replace($patterns_remove,'',$string);
	$string 		= preg_replace($patterns_replac,'',$string);
	
	$string 		= trim($string);
	
	return $string;
}


function valDate($postdate, $posttime = 0, $disp_time = "")
{
	
	if( $posttime == 1) 
	{ 
		if($disp_time == "") 
		 { $disp_time = " ".date("H:i:s"); } else 
		 { $disp_time = " ".$disp_time.":00"; }
	}
	
	$del_date = $postdate;
	if (strlen($del_date)>0){
		$del_date2 = date('Y-m-d',strtotime($postdate)).$disp_time;
	} else {$del_date2= NULL;} 
	
	return $del_date2;	
}




function dateDifference($dt1,$dt2=''){
	
     $y1 = substr($dt1,0,4);
     $m1 = substr($dt1,5,2);
     $d1 = substr($dt1,8,2);
     $h1 = substr($dt1,11,2);
     $i1 = substr($dt1,14,2);
     $s1 = substr($dt1,17,2);    
 	 
	 if($dt2==''){$dt2=date('Y-m-d', time());}
     $y2 = substr($dt2,0,4);
     $m2 = substr($dt2,5,2);
     $d2 = substr($dt2,8,2);
     $h2 = substr($dt2,11,2);
     $i2 = substr($dt2,14,2);
     $s2 = substr($dt2,17,2);    

     $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
     $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
     return ($r1-$r2);

 }


function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
    foreach ($arr as $key => $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}


function formatBytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
	   
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
	   
		$bytes /= pow(1024, $pow); 
	   
		return round($bytes, $precision) . ' ' . $units[$pow]; 
	} 


function add_date($orgDate,$mth){ 
	$cd = strtotime($orgDate); 
	$retDAY = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$mth,date('d',$cd),date('Y',$cd))); 
	return $retDAY; 
} 
 
 
function stringExists($input){
	$result = 0;
	$pattern[] = 'amount';
	$pattern[] = 'allowance';
	$pattern[] = 'tax';
	$pattern[] = '%';
	$pattern[] = 'balance';
	$pattern[] = 'total';
	
	foreach($pattern as $string)
	{
	  if(strpos($input, $string) !== false) 
	  {
		$result = 1;
		break;
	  }
	}
	return $result;
} 
?>