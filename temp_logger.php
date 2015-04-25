<html>
<head>
<title>ECE 331 TEMP LOGGER</title>
</head>
<body>
<?php
   // Copying in some PHP GD code with which to experiment.
  Header( 'Content-type: image/gif');
  $image = imagecreate(200,200);
  $red = ImageColorAllocate($image,255,0,0);
  $blue = ImageColorAllocate($image,0,0,255);
  $white = ImageColorAllocate($image,255,255,255);
  $grey = ImageColorAllocate($image,200,200,200);         
															// create an initial grey rectangle for us to draw on
  ImageFilledRectangle($image,0,0,200,200,$grey);
  // Connect to the mysql server and select the database
  $connect = mysql_connect('','root','');
  mysql_select_db('graphing',$connect);
  // find out the maximum number in our recordset
  $sql = 'SELECT MAX(g_num) FROM sales';
  $maxResult = mysql_query($sql,$connect);
  $max = mysql_result($maxResult,0,0);
  // get the recordset for London
  $sql = "SELECT g_num FROM sales WHERE g_team='London' ORDER BY g_month";
  $salesResult = mysql_query($sql,$connect);
  // find out how many rows were returned, that is the number of 'columns'
  $columns = mysql_num_rows($salesResult);
  // how much to increment $x by ? 
  $xincrement = bcdiv(200,$columns-1,0);
  $x=0;
  // $i will keep track of the row number
  $i=0;
  // lop around while we have rows of data
  while($salesRow=mysql_fetch_array($salesResult)) {
      // work out the y co-ordinate as discussed above
      $y = bcmul(bcdiv($salesRow[0],$max,2),200,2);
     // add the values into the $points array
    $points[$i][0] = $x;
    $points[$i][1] = $y;
    // increment $x by $xincrement
    $x+=$xincrement;
    // increment $i
    $i++;
  }
  // now we loop around through our $points array, while $i is
  // less that $columns-1
  for($i=0;$i<$columns-1;$i++) {
    // We pass $points[$I][0] as the first x co-ord, 
// and $points[$I][1] as the first y co-ord
// $points[$I+1][0], $points[$I+1][1] will be the next
// x,y co-ord set.
ImageLine($image,$points[$i][0],$points[$i][1],$points[$i+1][0],$points[$i+1][1],$red);
  }
  // output the GIF to the browser and free up memory
  ImageGIF($image);
  ImageDestroy($image);

?> 

	$temp_vals = array();
	$dbhdle = new SQLite3('tempdata.db');
	$dsdentries = $dbhdle->query('SELECT * FROM tempdata ORDER BY rowid DESC limit 1441');
	while (($row_info = $dsdentries->fetchArray(SQLITE3_NUM)) != NULL) {
	#	echo $row_info[0] . " " . $row_info[1] . " " . $row_info[2] . "\r\n";
		$temp_vals[] = $row_info[2];
	}
	for($i = 0; $i < 1441; $i++) {
		#echo "$temp_vals[$i]<br />\n";
	}
?>
</body>
</html>

