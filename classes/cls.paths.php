<?php
$my_header 	= "";
if($this_page == 'index.php') { $my_header = "Welcome"; }
if($this_page == 'project_new.php') { $my_header = "Project Definition"; }
if($this_page == 'home_project.php') { $my_header = "Project Dashboard"; }

if($this_page == 'home_theme.php') { $my_header = "Dashboard"; }

if($this_page == 'workplans.php') { $my_header = "Project Workplan"; }
if($this_page == 'workplans_add.php') { $my_header = "Workplan Activity Detail";  $dir = 'activities'; }
if($this_page == 'workplans_loc.php') { $my_header = "Activities by Location"; }
if($this_page == 'workplans_month.php') { $my_header = "Activities by Month"; }
if($this_page == 'workplans_staff.php') { $my_header = "Activities by Staff"; }
if($this_page == 'workplans_cat.php') { $my_header = "Activities by Category"; }
if($this_page == 'workplans_rsa.php') { $my_header = "Activities by Result Area"; }

if($this_page == 'wpreports.php') { $my_header = "Activity Reports"; }
if($this_page == 'wpreports_cats.php') { $my_header = "Report Categories"; }
if($this_page == 'wpreports_add.php') { $my_header = "Activity Reporting"; }

if($this_page == 'conf_themes.php') { $my_header = "Institutional Logframe"; }
if($this_page == 'conf_themes_targets.php') { $my_header = "Logframe Targets"; }
if($this_page == 'dashone.php') { $my_header = clean_title($dir);/*"System Statistics";*/ }

if($my_header <> "") { $my_header = $my_header. " | "; }


//$materialDrops = $ddSelect->dropper_select("afp_conf_project_materials", "id", "title");
//$GLOBALS['FORM_DISTRIBUTABLES'] = $materialDrops;

?>