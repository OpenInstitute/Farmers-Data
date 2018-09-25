<?php

function createThumbnail($filename, $image_details, $thumbname, $target, $resizeBig = 0) {
	
	//require 'config.php';
$quality = "";	
// Set up the appropriate image handling functions based on the original image's mime type
switch ($image_details['mime'])
{
	case 'image/gif':
		// We will be converting GIFs to PNGs to avoid transparency issues when resizing GIFs
		// This is maybe not the ideal solution, but IE6 can suck it
		$creationFunction	= 'imagecreatefromgif';
		$outputFunction		= 'imagepng';
		$mime				= 'image/png'; // We need to convert GIFs to PNGs
		$doSharpen			= FALSE;
		$quality			= round(10 - ($quality / 10)); // We are converting the GIF to a PNG and PNG needs a compression level of 0 (no compression) through 9
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

	$im = $creationFunction($target . $filename);
	//$tim = $creationFunction(UPL_FLD_GALL . "thmb_".$filename);
	
	$ox = imagesx($im);
	$oy = imagesy($im);
	
		if($ox > GALLIMG_WIDTH or $oy > GALLIMG_HEIGHT) //($resizeBig == 1)
		{
			// resize the large image (based on height or based on width)
			$fxRatio		= GALLIMG_WIDTH / $ox;
			$fyRatio		= GALLIMG_HEIGHT / $oy;
			
			//echo floor($oy * (GALLIMG_WIDTH / $ox)); //exit;
			
			if ($fxRatio * $oy < GALLIMG_HEIGHT)
			{ // Resize the image based on width
				$fny	= ceil($fxRatio * $oy);
				$fnx 	= GALLIMG_WIDTH;
			}
			else // Resize the image based on height
			{
				$fnx	= ceil($fyRatio * $ox);
				$fny	= GALLIMG_HEIGHT;
			}
			
				$fnm = imagecreatetruecolor($fnx, $fny);
				imagecopyresampled($fnm, $im, 0, 0, 0, 0, $fnx,$fny,$ox,$oy);
				$outputFunction($fnm, $target . $filename);
		}
			
			// resize the thumbnail image (based on height or based on width)
			$xRatio		= GALLTHMB_WIDTH / $ox;
			$yRatio		= GALLTHMB_HEIGHT / $oy;
			
			if ($xRatio * $oy < GALLTHMB_HEIGHT)
			{ // Resize the image based on width
				$ny		= ceil($xRatio * $oy);
				$nx 	= GALLTHMB_WIDTH;
			}
			else // Resize the image based on height
			{
				$nx		= ceil($yRatio * $ox);
				$ny		= GALLTHMB_HEIGHT;
			}

			$nm = imagecreatetruecolor($nx, $ny);
			imagecopyresampled($nm, $im, 0, 0, 0, 0, $nx,$ny,$ox,$oy);
			$outputFunction($nm, $target . $thumbname);
}


function getRandomName() {
		$len = 15; $upper = 2; $number = 1; $pass='';
		//$salt = "abcdefghjklmnpqrstuvwxyz";
		//$uppercase = "ABCDEFGHJKLMNPQRSTUVWXYZ";
		$salt = "abcdefghjklm"; //
		$uppercase = "npqrstuvwxyz";
		$numbers   = "123456789";
			if ($upper) $salt .= $uppercase;
			if ($number) $salt .= $numbers;
			
			srand((double)microtime()*1000000);
			$i = 0;
				while ($i <= $len) {
				$num = rand(111,999) % strlen($salt);
				$tmp = substr($salt, $num, 1);
				$pass = $pass . $tmp;
				$i++;
				}
			return $pass;
	}
?>