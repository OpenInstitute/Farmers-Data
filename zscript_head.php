<?php require("classes/cls.constants.php");
include("classes/cls.paths.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Open Institute - Nakuru North Farmer's Data 2018</title>
<link rel="shortcut icon" href="image/favicon.png" type="image/png" />
<link rel="stylesheet" type="text/css" href="assets/css/style.css" />




<link type="text/css" rel="stylesheet" href="assets/scripts/datatable/jquery.dataTables.css" />
<link type="text/css" rel="stylesheet" href="assets/scripts/datatable/jquery.dataTables.override.css" />

<!-- <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans+Condensed:300|Roboto+Condensed" rel="stylesheet"> -->
<script src="assets/scripts/jquery-3.1.1.js" type="text/javascript"></script>

<script src="assets/scripts/highcharts/highcharts-6.1.js"></script>
<script src="assets/scripts/highcharts/highcharts_funnel.js"></script>
<script src="assets/scripts/highcharts/highcharts_exporting.js"></script>
<script src="assets/scripts/highcharts/highcharts_data.js"></script>

<?php $aLoader    = '<div class="txtcenter"><img src="assets/image/loader.gif" alt="loading..." /></div>'; ?>
</head>

<body class="<?php if($this_page == 'index.php') { echo ""; } ?>">

<!-- @beg:: page-container -->
<div class="page_margins <?php if($this_page == 'index.php') { echo "home"; } else { echo "pg-inside";} ?>">
<div class="page" style="padding-bottom:70px;">
