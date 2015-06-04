<?php
include("include/config.php");
session_destroy();

//TODO : Add location to where you came from.
header("location: index.php");
