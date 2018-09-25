<?php include 'z_head.php'; ?>
  
  <div class="row bgHeaderBrown welcome-intro">
    <!-- <h1 class="heavy" style="text-align:center; color: #ffffff;">Nakuru North Farmer's Data  </h1>          -->
  </div>

  <div class="row intro">    
      <div class="row">
         <iframe class="nnmap" width="425" height="370" style="width: 100%; height: 600px; margin: auto !important;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="_leafmap.php" style="border: 1px solid black"></iframe><br/>
      </div>

    <!-- Buttons for the location -->
      <div class="buttons">
            <div class="container">
                <div class="col-md-4">
                    <a href="">
                        <button type="button" class="btn btn-default btnmore btn-lg btnfancy sublocation"><i class="fas fa-compass"></i> Population by Sub-Location</button>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="">
                    <button type="button" class="btn btn-default btnmore btn-lg btnfancy agegroup"><i class="fas fa-hourglass-half"></i> Population by Age Group</button>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="">
                    <button type="button" class="btn btn-default btnmore btn-lg btnfancy gender"><i class="fas fa-female"></i><i class="fas fa-male"></i> Population by Gender</button>
                    </a>
                </div>
                <!-- <div class="col-md-3">
                    <a href="">
                    <button type="button" class="btn btn-default btnmore btn-lg btnfancy"><i class="fas fa-female"></i><i class="fas fa-male"></i> Breadwinner by Gender</button>
                    </a>
                </div> -->
            </div>
      </div>
    <!-- Buttons for the location -->

     
     
     
      <div class="col-md-12 intro bgGreen padd10 col-sm-12">
          <div class="col-md-offset-2 col-md-8 col-md-offset-2">
				<?php include("includes/inc_story_locations.php"); ?>
          </div>
      </div>



 </div>



<?php include("includes/inc_story_individuals.php"); ?>

<?php //include 'datastories.php'; ?>


<div class="arrow">
    <a href="#next">
        <i class="fas fa-angle-double-down animate  fa-2x"></i>
    </a>
</div>
<section class="our-partners">
<h1 class="txtblack txtcenter padd20_0">Our Partners</h1>
    <?php include 'includes/partners.php'; ?>
</section>


<!-- Enjoy Hint plugin script-->
<?php 
echo $guide;
?>
<!-- Enjoy Hint plugin script-->
<?php 
include 'includes/footer.php';
include 'z_foot.php';
?>

