<?php
session_start();
/** 
* fb-config.php
* This file contains different URL used for login with facebook.
*/
require_once 'lib/facebook/facebook.php';

define("FACEBOOK_APP_ID", "Your Facebook APP ID");
define("FACEBOOK_APP_SECRET", "Your Facebook APP Secret");
define("FB_LOGIN_CALLBACK_URL", "http://your_project_path/user-albums.php");

$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_APP_SECRET
));

$fb = $facebook->getUser();
//print_r($fb);
?>
