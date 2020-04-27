<?php
#-------------------------------
#	ravishajoodha@gmail.com
#	2020-04-21
#-------------------------------
session_start();
// NO CACHE
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('max_execution_time', 0); // for infinite time of execution 

//*******************************************
// Turn off all error reporting
#error_reporting(0);
// Report simple running errors
#error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Reporting E_NOTICE can be good too (to report uninitialized variables or catch variable name misspellings ...)
#error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
//*******************************************

//get timezone to South Africa
date_default_timezone_set('Africa/Johannesburg');

// -----------------------
// Custom Functions
// -----------------------
include(dirname(__FILE__)."/functions.php");

?>
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		
		<title>Dominoes</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="css/css.css">
		
		<!-- Font Awesome JS -->
		<script defer src="vendor/fontawesome-5.9.0/js/solid.js"></script>
		<script defer src="vendor/fontawesome-5.9.0/js/fontawesome.js"></script>
		
		<!-- jQuery -->
		<script src="vendor/jquery-3.4.1.min.js"></script>
	</head>