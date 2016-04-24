<?php
    //think of this file as one image..
	session_start();
	include("include/src/Captcha/CaptchaGenerator.php");	
	/*create class object*/
	$captchaGenerator = new CaptchaGenerator();	
	/*phptext function to genrate image with text*/
	$captchaGenerator->phpcaptcha('#162453','#fff',120,40,10,25);	
 ?>