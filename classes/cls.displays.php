<?php

class displays extends master
{
	var $errors = array();		// array of errors
	var $error_string;			// error string
	var $num_rows;               // number of rows retrieved
	public $num_fields;
	var $field_names = array();
	var $dbconn;
	var $result;
	
	var $disp_query;
	var $disp_query2; 
	var $redirect; 
	var $id; 
	var $adsCurrentCat; 
	var $page;
	var $page_back;
	var $addir;
	var $com; var $com2; var $com3; var $fc_code; var $pg_code; var $stat; var $item;
	
	function getCount($disp_query) {
		$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$this->result=mysql_query($disp_query, $this->dbconnect) or die (mysql_error());
		$this->num_fields = mysql_num_fields($this->result);
		$this->num_rows = mysql_num_rows($this->result);
	}
	
	
	
	
	
	
	
	
	// Original PHP code by Chirp Internet: www.chirp.com.au
	function myTruncate($string, $limit, $break=".", $pad=" ", $useBreak="yes", $useHoverText=0)
	{
		if($useHoverText==1){
	  		$fieldHoverText = $string;
		} else { $fieldHoverText = ""; }
	  
	  // return with no change if string is shorter than $limit
	  if(strlen($string) <= $limit) return $string;
	
	  if($useBreak == "yes")
	  {
		  // is $break present between $limit and the end of the string?
		  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
			if($breakpoint < strlen($string) - 1) {
			  $string = substr($string, 0, $breakpoint) . $pad;
			}
		  }
	  }
	  else
	  {
	  		$string = "<span title='".$fieldHoverText."'>". substr($string, 0, $limit) . $pad ."</span>";
	  }
	  
	  return $string;
	}
	
	// Original PHP code by Chirp Internet: www.chirp.com.au
	function restoreTags($input)
	{
		$opened = array();
		
		// loop through opened and closed tags in order
		if(preg_match_all("/<(\/?[a-z]+)>?/i", $input, $matches)) {
		  foreach($matches[1] as $tag) {
			if(preg_match("/^[a-z]+$/i", $tag, $regs)) {
			  // a tag has been opened
			  if(strtolower($regs[0]) != 'br') $opened[] = $regs[0];
			} elseif(preg_match("/^\/([a-z]+)$/i", $tag, $regs)) {
			  // a tag has been closed
			  unset($opened[array_pop(array_keys($opened, $regs[1]))]);
			}
		  }
		}
		
		// close tags that are still open
		if($opened) {
		  $tagstoclose = array_reverse($opened);
		  foreach($tagstoclose as $tag) $input .= "</$tag>";
		}
		
		return $input;
	}
	
	

/*
@END :: FUNCTION: Truncate
***********************************************************/





/**************************************************************************************
@begin: admin lists - FRONT
*************************************************************************************/
function getDataList($disp_query, $redirect, $redir_b = "" ) 
{
	$this->connect() or trigger_error('SQL', E_USER_ERROR);
	$this->getCount($disp_query);
	
	
	$us_staff = $_SESSION['sess_siteprfx_member']['u_staff'];
	
	
	
	echo "<form method=\"post\" action=\"adm_posts.php\">
	<table border=0 cellpadding='0' cellspacing='0' width='100%' class='display dataTableX table jdtable table-hover' id='example'><thead><tr>"; 
		//echo "<th class='skip-filter len-short'>#</th>";
		for ($i = 0; $i<($this->num_fields); $i++)						// Field Names
		{
			$field_names[$i] = mysql_fetch_field($this->result, $i);
			if ($field_names[$i]->name!="show" and 
				$field_names[$i]->name!="active" and 
				$field_names[$i]->name!="cnfmd"  and 
				$field_names[$i]->name!="approved"  and 
				$field_names[$i]->name!="pos." and
				$field_names[$i]->name!="seq" and 
				$field_names[$i]->name!="year" and 
				$field_names[$i]->name!="items"  and 
				$field_names[$i]->name!="orders"  and 
				$field_names[$i]->name!="pics"  and 
				$field_names[$i]->name!="front"  and 
				$field_names[$i]->name!="top"  and 
				$field_names[$i]->name!="home"  and 
				$field_names[$i]->name!="side") {
			
				if($field_names[$i]->type!="int"){} 
					echo "<th>".$field_names[$i]->name."</th> ";
				
			} else {
				echo "<th>".$field_names[$i]->name."</th>";
			}
		}
	echo "<th class='len-short'>...</th>";
	echo "</tr></thead><tbody>";
	$rn=1;
		while ($field_data = mysql_fetch_array($this->result)) 			// Row Data
		{ 
			if (($rn/2)==intval($rn/2)){$bg=" class='even'";} else { $bg=" class='odd'"; }
			echo "<tr $bg>";
			//echo "<td>$rn</td>";	
			for ($f=0 ; $f<=($this->num_fields-1); $f++) 
			{
				$field = "";
				$field = $field_data[$f]; 
					
					
					if ($field_names[$f]->name=="title" or $field_names[$f]->name=="name") 
					{
						
						if(trim($field)=="...") {$field = $field." { ". substr($field_data["parent"],0,20) . ".. } ";}
						//echo $this->addir;
						if($this->addir=="menus") {
						if(strlen($field_data["parent"])>0) { $field = "&not; &nbsp;".$field ; }
						}
						
						/*if($this->addir=="articles") {
						if(strlen($field_data["yn_static_parent"])>0) { $field = $field . "/ ".$field_data["yn_static_parent"] ; }
						}*/
						
						$field = strip_tags(html_entity_decode(stripslashes($field)));
						$field = $this->myTruncate($field, 40, "", "...", "no");
						
						$field = "<a href='".$redirect."op=edit&id=$field_data[0]'>$field</a>"; 
						echo "<td >".html_entity_decode(stripslashes($field))."</td>"; //style='width:20%'
					} 	
					
					elseif ($field_names[$f]->name!="show"  and
							$field_names[$f]->name!="active"  and 
							$field_names[$f]->name!="cnfmd"  and 
							$field_names[$f]->name!="approved"  and 
							$field_names[$f]->name!="pos." and 
							$field_names[$f]->name!="seq" and 
							$field_names[$f]->name!="image" and 
							$field_names[$f]->name!="items" and
							$field_names[$f]->name!="pics" and
							$field_names[$f]->name!="orders" and
							$field_names[$f]->name!="front" and 
							$field_names[$f]->name!="top" and 
							$field_names[$f]->name!="home" and 
							$field_names[$f]->name!="side"
							) 
					{	
							
							if(	$field_names[$f]->name=="presenters" or 
								$field_names[$f]->name=="child_of")
							{
								$field_arr 	= unserialize($field);
								$field		= @implode(", ", $field_arr);
							}	
							
							if(	$field_names[$f]->type=="timestamp" or
								$field_names[$f]->type=="datetime") 
							{
								$field		= date("M d Y",strtotime($field));
							}	
							else
							{
							$field = trim(strip_tags(html_entity_decode(stripslashes($field))));
							$field = $this->myTruncate($field, 40, " ", "...", "yes");
							}	
								echo "<td>".$field."</td>";
								$field='';
						
					} 
					
					elseif ($field_names[$f]->name=='image') {
						
						$pos=" align=left "; 
						$field = strip_tags(html_entity_decode(stripslashes($field)));
						$field = $this->myTruncate($field, 20, "", "...", "no");
						
						echo "<td $pos>".$field."</td>";							
					}
					
					elseif ($field_names[$f]->name=="show" or 
							$field_names[$f]->name=="active" or 
							$field_names[$f]->name=="cnfmd" or 
							$field_names[$f]->name=="approved" or 
							$field_names[$f]->name=="side" or
							$field_names[$f]->name=="top" or
							$field_names[$f]->name=="home" or
							$field_names[$f]->name=="front") 
					{
						$pos=" style=\"text-align:center\" "; 
						//if($field==0) {$field="<img src='image/off.png'>";} else {$field="<img src='image/on.png'>";}
						if($field==0) {$field="<span class='label-no'>no</span>";} 
						else {$field="<span class='label-yes'>yes</span>";}
						echo "<td class='txtcenter'>".$field."</td>";
					}
					
					elseif ($field_names[$f]->name=="pos." or 
							$field_names[$f]->name=="seq"  ) {
						$pos=" style=\"text-align:center\" "; 
						echo "<td $pos>".$field."&nbsp;</td>";
						//echo "<td $pos><input type=\"text\" name=\"pos[".$field_data[0]."]\" value=\"".$field."\" style=\"width:25px;\"></td>";
					}
					
					elseif ($field_names[$f]->name=="items" or
							$field_names[$f]->name=="orders") {
						$pos=" align=left "; 
						echo "<td $pos>".$field;
						if($field<>0){
						echo "&nbsp;&nbsp;<a href=\"#&id=$field_data[0]\">view</a>";
						} else { echo "" ;}
						echo "</td>";
					}
					
					elseif ($field_names[$f]->name=="pics") {
						$pos=" align=left "; 
						//$inlink = "#";
						//if($field<>0){
							$inlink = " href=\"adm_projects_pics.php?d=project galleries&op=edit&id=$field_data[0]\"";
						//} 
						$field =  str_pad($field, 2, "0", STR_PAD_LEFT); 
						echo "<td $pos><a".$inlink.">".$field."</a></td>";
						//echo "<td $pos>".$field."</td>";
					}
					
					
			}
			echo "<td><a href='".$redirect."op=edit&id=$field_data[0]' title='View details &raquo;'>&raquo;&raquo;</a></td>";
			echo "</tr>";
			$rn += 1;
		} 
	echo "</tbody></table>
	<input type=\"hidden\" name=\"formname\" value=\"posUpdates\" />
			<input type=\"hidden\" name=\"redirect\" value=\"".$redirect."\" />
	</form>";

}		
			




/**************************************************************************************
@begin: admin lists
*************************************************************************************/
function getData($disp_query, $redirect, $redir_b = "" ) 
{
	$this->connect() or trigger_error('SQL', E_USER_ERROR);
	$this->getCount($disp_query);
	
	echo "<form method=\"post\" action=\"adm_posts.php\">
	<table border=0 cellpadding='0' cellspacing='0' width='100%' class='display dataTableX table jdtable table-hover' id='example'><thead><tr>"; 
		echo "<th class='skip-filter len-short'>#</th>";
		for ($i = 0; $i<($this->num_fields); $i++)						// Field Names
		{
			$field_names[$i] = mysql_fetch_field($this->result, $i);
			if ($field_names[$i]->name!="show" and 
				$field_names[$i]->name!="active" and 
				$field_names[$i]->name!="cnfmd"  and 
				$field_names[$i]->name!="approved"  and 
				$field_names[$i]->name!="pos." and
				$field_names[$i]->name!="seq" and 
				$field_names[$i]->name!="year" and 
				$field_names[$i]->name!="items"  and 
				$field_names[$i]->name!="orders"  and 
				$field_names[$i]->name!="pics"  and 
				$field_names[$i]->name!="front"  and 
				$field_names[$i]->name!="top"  and 
				$field_names[$i]->name!="home"  and 
				$field_names[$i]->name!="side") {
			
				if($field_names[$i]->type!="int"){
					
					$hideDtFilter = "";
					//
					
					if ($field_names[$i]->name!="parent" and
						$field_names[$i]->name!="section" and 
						$field_names[$i]->name!="menu type" and 
						$field_names[$i]->name!="sector" and 
						$field_names[$i]->name!="status" /*and 
						$field_names[$i]->name!="date"*/ )
					{
						$hideDtFilter = "skip-filter";
					}
					
					echo "<th class='".$hideDtFilter."'>".$field_names[$i]->name."</th> ";
				} 
			} else {
				echo "<th class='txtcenter ' style='width:70px;' nowrap>".$field_names[$i]->name."</th>";
			}
		}
	echo "<th class='len-short'>...</th>";
	echo "</tr></thead><tbody>";
	$rn=1;
		while ($field_data = mysql_fetch_array($this->result)) 			// Row Data
		{ 
			if (($rn/2)==intval($rn/2)){$bg=" class='even'";} else { $bg=" class='odd'"; }
			echo "<tr $bg>";
			echo "<td>$rn</td>";	
			for ($f=0 ; $f<=($this->num_fields-1); $f++) {
				$field = "";
				$field = $field_data[$f]; 
					//echo $field_names[$f]->name;
					if ($field_names[$f]->name=="title" or $field_names[$f]->name=="name") {
						
						if(trim($field)=="...") {$field = $field." { ". substr($field_data["parent"],0,20) . ".. } ";}
						//echo $this->addir;
						if($this->addir=="menus") {
						if(strlen($field_data["parent"])>0) { $field = "&not; &nbsp;".$field ; }
						}
						
						/*if($this->addir=="articles") {
						if(strlen($field_data["yn_static_parent"])>0) { $field = $field . "/ ".$field_data["yn_static_parent"] ; }
						}*/
						
						$field = strip_tags(html_entity_decode(stripslashes($field)));
						$field = $this->myTruncate($field, 50, "", "...", "no");
						
						$field = "<a href='".$redirect."op=edit&id=$field_data[0]'>$field</a>"; 
						echo "<td >".html_entity_decode(stripslashes($field))."</td>"; //style='width:20%'
					} 	
					
					elseif ($field_names[$f]->name!="show"  and
							$field_names[$f]->name!="active"  and 
							$field_names[$f]->name!="cnfmd"  and 
							$field_names[$f]->name!="approved"  and 
							$field_names[$f]->name!="pos." and 
							$field_names[$f]->name!="seq" and 
							$field_names[$f]->name!="image" and 
							$field_names[$f]->name!="items" and
							$field_names[$f]->name!="pics" and
							$field_names[$f]->name!="orders" and
							$field_names[$f]->name!="front" and 
							$field_names[$f]->name!="top" and 
							$field_names[$f]->name!="home" and 
							$field_names[$f]->name!="side"
							) {
						if($field_names[$f]->type!="int"){ 
							if($field_names[$f]->type=="real"){ 
								$pos=" align=left "; 
								//$field = number_format($field,2); 
							} else { 
								$pos="";
								$field = $field_data[$f]; 
								
									if(	$field_names[$f]->name=="presenters" or 
										$field_names[$f]->name=="child_of")
									{
										$field_arr 	= unserialize($field);
										$field		= @implode(", ", $field_arr);
									}	
									
									if(	$field_names[$f]->type=="timestamp" or
										$field_names[$f]->type=="datetime") 
									{
										$field		= date("M d Y",strtotime($field));
									}	
									else
									{
									$field = trim(strip_tags(html_entity_decode(stripslashes($field))));
									$field = $this->myTruncate($field, 90, " ", "...", "yes");
									}
							}
								//strip_tags(html_entity_decode(stripslashes($field)))
								//$field_names[$f]->type.
								echo "<td $pos>".$field."</td>";
								$field='';
						}
					} 
					
					elseif ($field_names[$f]->name=='image') {
						
						$pos=" align=left "; 
						$field = strip_tags(html_entity_decode(stripslashes($field)));
						$field = $this->myTruncate($field, 20, "", "...", "no");
						
						echo "<td $pos>".$field."</td>";							
					}
					
					elseif ($field_names[$f]->name=="show" or 
							$field_names[$f]->name=="active" or 
							$field_names[$f]->name=="cnfmd" or 
							$field_names[$f]->name=="approved" or 
							$field_names[$f]->name=="side" or
							$field_names[$f]->name=="top" or
							$field_names[$f]->name=="home" or
							$field_names[$f]->name=="front") 
					{
						$pos=" style=\"text-align:center\" "; 
						//if($field==0) {$field="<img src='image/off.png'>";} else {$field="<img src='image/on.png'>";}
						if($field==0) {$field="<span class='label-no'>no</span>";} 
						else {$field="<span class='label-yes'>yes</span>";}
						echo "<td class='txtcenter'>".$field."</td>";
					}
					
					elseif ($field_names[$f]->name=="pos." or 
							$field_names[$f]->name=="seq"  ) {
						$pos=" style=\"text-align:center\" "; 
						echo "<td $pos>".$field."&nbsp;</td>";
						//echo "<td $pos><input type=\"text\" name=\"pos[".$field_data[0]."]\" value=\"".$field."\" style=\"width:25px;\"></td>";
					}
					
					elseif ($field_names[$f]->name=="items" or
							$field_names[$f]->name=="orders") {
						$pos=" align=left "; 
						echo "<td $pos>".$field;
						if($field<>0){
						echo "&nbsp;&nbsp;<a href=\"#&id=$field_data[0]\">view</a>";
						} else { echo "" ;}
						echo "</td>";
					}
					
					elseif ($field_names[$f]->name=="pics") {
						$pos=" align=left "; 
						//$inlink = "#";
						//if($field<>0){
							$inlink = " href=\"adm_projects_pics.php?d=project galleries&op=edit&id=$field_data[0]\"";
						//} 
						$field =  str_pad($field, 2, "0", STR_PAD_LEFT); 
						echo "<td $pos><a".$inlink.">".$field."</a></td>";
						//echo "<td $pos>".$field."</td>";
					}
					
					
			}
			echo "<td><a href='".$redirect."op=edit&id=$field_data[0]' title='View details &raquo;'>&raquo;&raquo;</a></td>";
			echo "</tr>";
			$rn += 1;
		} 
	echo "</tbody></table>
	<input type=\"hidden\" name=\"formname\" value=\"posUpdates\" />
			<input type=\"hidden\" name=\"redirect\" value=\"".$redirect."\" />
	</form>";

}		
			
	
}

$ddDisplay 	 = new displays;
?>