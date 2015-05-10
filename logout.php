<?php
include("include/config.php");
session_destroy();

header("location: index.php");
