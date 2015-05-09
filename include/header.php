<?php
include("include/config.php");
include("include/database.php");
$db = new Database($GLOBAL['database']);
?>

<html>
<head>

    <meta charset="utf-8">
	<title> <?php echo $GLOBAL['pageTitle']; ?></title>
	<link rel="stylesheet" type="text/css" href="style/style.css">
	
	<div id='div_header'>
        <nav id='nav_header'>
            <a href=''>Site1</a>
            <a href=''>Site2</a>
            <a href=''>Site3</a>
            <a href=''>Site4</a>
            <a href='login.php'>login</a>
         </nav>
    <?php include("login.php");?>
    </div>

</head>
<body>



