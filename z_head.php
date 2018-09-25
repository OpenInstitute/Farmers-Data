<?php if (!isset($_COOKIE['ourguide'])){ 
$guide =  '<script src="assets/scripts/ourguide.js"></script>';
setcookie('ourguide', 'Show our guide', time() + (86400 * 365), "/")or die('unable to create cookie');
$_COOKIE['ourguide'] = $ourguide;
}else{
  $guide = '';
}

if (!isset($_COOKIE['storyguide'])){ 
$guide =  '<script src="assets/scripts/storyguide.js"></script>';
setcookie('storyguide', 'Show our guide', time() + (86400 * 365), "/")or die('unable to create cookie');
$_COOKIE['storyguide'] = $storyguide;
}else{
  $guide = '';
}
?>

<?php require("classes/cls.constants.php");
include("classes/cls.paths.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nakuru North - Farmer's Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/image/emblem.png" type="image/png" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css" />

    <!-- BX Slider -->
    <link rel="stylesheet" type="text/css" media="screen" href="assets/scripts/bxslider/jquery.bxslider.home.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/scripts/bxslider/jquery.bxslider.carousel.css" />
    <!-- BX Slider -->

  <link type="text/css" rel="stylesheet" href="assets/scripts/datatable/jquery.dataTables.css" />
  <link type="text/css" rel="stylesheet" href="assets/scripts/datatable/jquery.dataTables.override.css" />

   <!-- <script src="assets/scripts/jquery-3.1.1.js"></script> -->
  <script src="assets/scripts/jquery-1.12.3.min.js"></script>
  <script src="assets/scripts/bootstrap/js/bootstrap.min.js"></script>
  
  <!-- Enjoy hint plugin -->
  <link href="assets/scripts/enjoyhint/enjoyhint.css" rel="stylesheet">
  <script src="assets/scripts/enjoyhint/enjoyhint.min.js"></script>
  <!-- Enjoy hint plugin -->
 

    <style> @import url('https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:100,400,700,900'); </style>

    <?php $aLoader = '<div class="txtcenter"><img src="assets/image/loader.gif" alt="loading..." /></div>'; ?>

</head>
<body>
<!-- <body class="<?php //if($this_page == 'index.php') { echo ""; } ?>"> -->
   <?php include 'includes/header.php'; ?>