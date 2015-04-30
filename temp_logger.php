<?php   

header('Content-type: image/png');

$delta_t = 1;      # spacing (in minutes) between successive data points
$hour_div = 60;
$PIX_WID = 1450;
$PIX_HGT = 590;
$MAX_TEMP = 90;
$MIN_TEMP = 40; 
$SCL_PIX = 10;
$tf = 0;
$ti = 0;
$Tf = 0;
$Ti = 0;
$line_count = 0;
$col_count = 1;
$temp_hgts = range($MIN_TEMP,$MAX_TEMP,5);
$time_vals = range(1,23,1);

$png_image = imagecreate($PIX_WID,$PIX_HGT);  # might #aneed to error check this
$graph_color = imagecolorallocate($png_image,255,255,255);
$line_color = imagecolorallocate($png_image, 255, 0, 0);
$grid_color = imagecolorallocate($png_image,0,0,0);
$box_color = imagecolorallocate($png_image,0,0,0);
$first_time = "yes";

$dbhdle = new SQLite3('datetimetemp.db');
$dsdentries = $dbhdle->query('SELECT * FROM datetimetemp ORDER BY rowid DESC limit 1441');
imagestring($png_image,7,$PIX_WID/2 - 115,20,'ECE 331 Temperature Logger', $line_color);

while(($row_info = $dsdentries->fetchArray(SQLITE3_NUM)) != NULL) {   #t == x, T == y, so to speak
	$tf=$ti+$delta_t; // Shifting in X axis
	$Tf=$row_info[2]; 
	if ($first_time=="no"){ // this is to prevent from starting $x1= and $y1=0
		$Tfpix = ($Tf - $MIN_TEMP)*$SCL_PIX;
		$Tipix = ($Ti - $MIN_TEMP)*$SCL_PIX;
		imageline ($png_image,$ti, $Tipix,$tf,$Tfpix,$line_color); // Drawing the line between two points
		if (($tf%60 == 0) ||($tf == 0)) {
			imagedashedline($png_image,$tf ,0 ,$tf ,500 , $grid_color);
			imagestring($png_image,5,$tf,$PIX_HGT - 100,"$col_count",$grid_color);
			$col_count = $col_count + 1;
		}
	}
	$ti=$tf; # make final points of first line the initial points of the next line.
	$Ti=$Tf;
	$first_time="no"; // Now flag is set to allow the drawing
}

for ($i = 500; $i > 49; $i--) {
	if ($i%50 == 0) {
		imagestring($png_image,5,5,$i-10,"$temp_hgts[$line_count]",$grid_color);
		$line_count = $line_count + 1;
		imagedashedline($png_image,0 ,$i ,1450 ,$i , $grid_color);
	}
}

date_default_timezone_set('America/New_York');
imagestring($png_image,5,5,10,"deg F",$grid_color);
imagestring($png_image,5,$PIX_WID/2 - 115, $PIX_HGT - 80,'Hours in the past', $grid_color);
imagestring($png_image,8,5,$PIX_HGT - 50,"Today's Date is: " . date("F j, Y"), $grid_color);
imagestring($png_image,8,5,$PIX_HGT - 25,"The Time is: " . date("H:i"),$grid_color);

imagepng($png_image);
imagedestroy($png_image);

?>


