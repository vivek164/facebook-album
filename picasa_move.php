<?php
/**
* picasa_move.php
* Handles Google Login, Move to Picasa
*/
session_start();
require_once("fb-config.php");
require_once("includes/Google_login.php");
require_once("includes/Picasa_album.php");


//Check user's facabook access token not expired
if(!$fb)
{
	header("location:index.php");
}

//Store Post album data in Session
if(isset($_POST) && !empty($_POST))
{
	$_SESSION['post_album_data'] = $_POST;
}

$google_login = new Google_login();
$picasa_album = new Picasa_album();

if(isset($_GET['code']) && !isset($_SESSION['google_access_token']))
{
	$arr_access_token = $google_login->getAccessToken($_GET['code']);
	$_SESSION['google_access_token'] = $arr_access_token['access_token'];
	
	header("location:user-albums.php#move_album");
	
}
else if(isset($_SESSION['google_access_token']))
{	
	//Check access token is valid or not
	$token_resp = $google_login->isTokenValid($_SESSION['google_access_token']);
	if(!isset($token_resp['error']))
	{
		if($_SESSION['post_album_data']['action_type'] == "selected")
		{
			//Check at least one album is selected to download
			if(isset($_SESSION['post_album_data']['album_ids']) && !empty($_SESSION['post_album_data']['album_ids']))
			{
				foreach($_SESSION['post_album_data']['album_ids'] as $album_id)
				{
					$index_album        = array_search($album_id, array_column($_SESSION['user_data']['albums']['data'], 'id'));
					$album_name         = "Facebook Album - ".$_SESSION['user_data']['albums']['data'][$index_album]['name'];
					
					//Create Album
					$created_album_data = $picasa_album->createAlbum($album_name, $_SESSION['google_access_token']);
					
					if($created_album_data['flag'] == 1)
					{
						$album_photos = $_SESSION['user_data']['albums']['data'][$index_album]['photos']['data'];
						if(!empty($album_photos))
						{
							foreach($album_photos as $photo)
							{
								//Upload Photo to Album
								$picasa_album->uploadPhoto($created_album_data['album_id'], $photo['images'][0]['source'], $_SESSION['google_access_token']);
							}
						}
					}


				}
			}
		}
		else
		if($_SESSION['post_album_data']['action_type'] == "all")
		{
			foreach($_SESSION['user_data']['albums']['data'] as $album)
			{
				$album_name         = "Facebook Album - ".$album['name'];
				//Create Album
				$created_album_data = $picasa_album->createAlbum($album_name, $_SESSION['google_access_token']);
				if($created_album_data['flag'] == 1)
				{
					$album_photos = isset($album['photos']['data'])?$album['photos']['data']:array();
					if(!empty($album_photos))
					{
						foreach($album_photos as $photo)
						{
							//Upload Photo to Album
							$picasa_album->uploadPhoto($created_album_data['album_id'], $photo['images'][0]['source'], $_SESSION['google_access_token']);
						}
					}
				}


			}
		}
		header("location:user-albums.php#moved");		
	}
	else
	{
		//Access Token has some errors
		$_SESSION['google_access_token'] = "";
	}
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
				Connect with Google
			</h2>
			<p class="margin-top-20">
				You need to connect with google in order to move album to google photos or Picasa.
			</p>
			<div class="row margin-top-20">
				<?php
				if((!isset($_SESSION['google_access_token']) || $_SESSION['google_access_token']=="") && !isset($_GET['code']))
				{
					?>
					<a href="<?php echo $google_login->getLoginUrl(); ?>">
						<img src="images/connect-google.png" class="img-responsive center-block"/>
					</a>
					<?php
				}
				
				?>

			</div>
		</div>

	</body>
</html>