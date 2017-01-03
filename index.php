<?php
require_once("fb-config.php");
/**
* index.php
* Login with Facebook button if user is not logged in facebook.
*/

if($fb)
{
	header("location:user-albums.php");
}
else
{
	//Generate Login URL
	//------------------
	$fb_permissions  = 'email,user_photos';
	$fb_login_url = $facebook->getLoginUrl(array('redirect_uri'=>FB_LOGIN_CALLBACK_URL,'scope'=>$fb_permissions));
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			rtCamp Assignment - Facebook-Photos Challenge
		</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.min.js">
		</script>
		<script src="js/bootstrap.min.js">
		</script>
	</head>
	<body>

		<div class="container margin-top-100">
			<h2 class="header_title">
				Facebook-Photos Challenge
			</h2>
			<p class="margin-top-20">
				Connect with facebook to get your album list. You can download all your albums from facebook.
			</p>
			<div class="row margin-top-20">
				<a href="<?php echo $fb_login_url; ?>">
					<img src="images/connect-fb.png" class="img-responsive center-block"/>
				</a>
			</div>
		</div>

	</body>
</html>