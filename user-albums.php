<?php
/**
* user-albums.php
* Main Page contains Listing of Albums fetched from facebook, facebook user information.
*/
require_once("fb-config.php");

$response = array();

if(!$fb)
{
	header("location:index.php");
}
else
{
	$fb_response = $facebook->api('/me?fields=id,name,email,gender,picture.type(large),albums.fields(id,name,cover_photo.fields(source),photos.fields(name,picture,images,created_time))');
	
	if(!empty($fb_response))
	{
		$_SESSION['user_data'] = $user_data = $fb_response;
	}
	else
	{
		header("location:index.php");
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
		<link rel="stylesheet" href="css/font-awesome.css">
		<link rel="stylesheet" href="css/checkbox.css">

		<script src="js/jquery.min.js">
		</script>
		<script src="js/bootstrap.min.js">
		</script>
		<script src="js/script.js">
		</script>
	</head>
	<body>

		<!-- Modal -->
		<div class="modal fade" id="downloading_model" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							&times;
						</button>
						<h4 class="modal-title">
							Preparing for download..
						</h4>
					</div>
					<div class="modal-body">
						<p class="response_message">
							Please wait while preparing your file to download...
						</p>
					</div>
					<div class="zip_download_link modal-footer">
						<a href="#">
							<button type="button" class="btn btn-primary">
								Download
							</button>
						</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Moving Dialog -->
		<div class="modal fade" id="moving_model" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close close_moving_modal" data-dismiss="modal">
							&times;
						</button>
						<h4 class="modal-title">
							Move Albums to Picasa
						</h4>
					</div>
					<div class="modal-body">
						<p class="response_message">
							Please wait while moving your album photos to picasa...
						</p>
					</div>
					<div class="modal-footer close_moving_modal" style="display: none;">
						<a href="#">
							<button type="button" onclick="window.location.href='#'" class="btn btn-primary" data-dismiss="modal">
								Close
							</button>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<h2 class="header_title">
				Facebook-Photos Challenge
			</h2>
			<div class="col-sm-2">
				<nav class="nav-sidebar">
					<ul class="nav tabs">
						<li class="">
							<a href="#profile" data-toggle="tab">
								Profile
							</a>
						</li>
						<li class="active">
							<a href="#albums" data-toggle="tab">
								My Albums
							</a>
						</li>
						<li class="">
							<a href="logout.php">
								Logout
							</a>
						</li>
					</ul>
				</nav>

			</div>
			<!-- tab content -->
			<div class="tab-content col-sm-10">
				<div class="tab-pane text-style" id="profile">
					<h3 class="pad-bottom-10 header_title">
						Profile
					</h3>
					<div class="col-sm-3">

						<img src="<?php echo $user_data['picture']['data']['url']; ?>" class="img-responsive img-circle"/>

					</div>
					<div class="col-sm-9">
						<h3>
							<?php echo $user_data['name']; ?>
						</h3>
						<p>
							<?php echo $user_data['email']; ?>
						</p>
						<p>
							<a href="logout.php">
								<button type="button" class="btn btn-danger">
									Logout
								</button>
							</a>
						</p>

					</div>

				</div>
				<div class="tab-pane active text-style" id="albums">
					<form id="frm_albums" name="frm_albums" method="POST">
						<input type="hidden" name="action_type" id="action_type" value=""/>

						<h3 class="pad-bottom-10 header_title">
							My Albums
							<div class="pull-right">
								<input type="submit" class="btn btn-sm btn-primary btn_move_multiple" data-action-type="selected" value="Move Selected">
								<input type="submit" class="btn btn-sm btn-primary btn_move_multiple" data-action-type="all" value="Move All">

								<input type="submit" class="btn btn-sm btn-primary btn_download_multiple" data-action-type="selected" value="Download Selected">
								<input type="submit" class="btn btn-sm btn-primary btn_download_multiple" data-action-type="all" value="Download All">


							</div>
							<div class="clearfix">
							</div>
						</h3>

						<div class = "row">
							<?php
							foreach($user_data['albums']['data'] as $album){
								$album_cover_image = isset($album['cover_photo']['source'])?$album['cover_photo']['source']:"images/empty-album.png";
								$photos = isset($album['photos']['data'])?$album['photos']['data']:array();
								if(!empty($photos)){
									$view_slideshow_link = "slideshow.php?album_id=$album[id]";
								}
								else
								{
									$view_slideshow_link = "javascript:;";
								}
								?>
								<div class = "col-sm-6 col-md-3">
									<div class = "thumbnail img_container">
										<a href="<?php echo $view_slideshow_link; ?>">
											<img src = "<?php echo $album_cover_image; ?>" alt = "<?php echo $album['name']; ?>" class="album_thumbnail img-responsive">
										</a>
										<div class="checkbox checkbox-info cbox">
											<input id="chk_<?php echo $album['id']; ?>" name="album_ids[]" value="<?php echo $album['id']; ?>" type="checkbox" <?php echo (sizeof($photos)?'':'disabled'); ?>>
											<label for="chk_<?php echo $album['id']; ?>">
											</label>
										</div>
									</div>

									<div class = "caption">
										<h5>
											<a href="<?php echo $view_slideshow_link; ?>">
												<?php echo $album['name']; ?>
											</a>
										</h5>
										<p>
											<a href = "<?php echo $view_slideshow_link; ?>" class = "btn btn-sm btn-primary <?php echo (sizeof($photos)?'':'disabled'); ?>" role = "button">
												Show
											</a>

											<a href = "javascript:;" class = "btn btn-sm btn-default <?php echo (sizeof($photos)?'':'disabled'); ?> btn_download" data-album-id="<?php echo $album['id']; ?>" role = "button">
												Download
											</a>

											<a href = "javascript:;" class = "btn btn-sm btn-default <?php echo (sizeof($photos)?'':'disabled'); ?> btn_move" data-album-id="<?php echo $album['id']; ?>" role = "button">
												Move
											</a>
										</p>

									</div>
								</div>
								<?php
							}
							?>

						</div>
					</form>
				</div>

			</div>
		</div>
	</body>
</html>