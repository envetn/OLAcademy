<?php
include("include/header.php");


$month = 5;
$year = 2015;


echo draw_calendar($db, $month,$year);


?>



<?php include("include/footer.php"); ?>
