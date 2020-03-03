<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('America/New_York');
 
// variables used for jwt
$key = "example_key";
$iss = "https://www.dev01.wf4rit.me/";
$aud = "https://www.dev01.wf4rit.me/";