<?php
/**
* slideshow.php
* Slideshow of Albums Photos.
*/
session_start();
if(isset($_GET['album_id']) && $_GET['album_id'] != ""){
	$album_id    = $_GET['album_id'];
	$index_album = array_search($album_id, array_column($_SESSION['user_data']['albums']['data'], 'id'));
	$album_name  = $_SESSION['user_data']['albums']['data'][$index_album]['name'];
	$album_photos= $_SESSION['user_data']['albums']['data'][$index_album]['photos']['data'];
}
else
{
	header("location:user-albums.php");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>
			rtCamp Assignment - Facebook-Photos Challenge
		</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/font-awesome.css">


	</head>
	<body>

		<div id="slides">
			<div class="slides-container">
				<?php
				if(!empty($album_photos)){
					foreach($album_photos as $photo){
						?>
						<img src="<?php echo $photo['images'][0]['source']; ?>" class="slide_img" alt="Album Photo">
						<?php
					}
				}
				?>

			</div>
			<a href="user-albums.php" class="pull-left margin-left-15"><u>Go Back</u></a>
			<nav class="slides-navigation">

				<a href="#" class="prev fa fa-chevron-circle-left">

				</a>
				<a href="#" class="next fa fa-chevron-circle-right">

				</a>
			</nav>
		</div>


	</body>
</html>
<script src="js/jquery.min.js">
</script>
<script type="text/javascript">
	$(document).ready(function()
		{
			$(".slides-container > img:gt(0)").hide();
			$(".slides-navigation .next").click(function()
				{
					$('.slides-container > img:first')
					.fadeOut(500)
					.next()
					.fadeIn(500)
					.end()
					.appendTo('.slides-container');

				});
			$(".slides-navigation .prev").click(function()
				{
					$('.slides-container > img:first')
					.fadeOut(500);
					$('.slides-container > img:last')
					.fadeIn(500)
					.prependTo('.slides-container');
				});
		});
</script>
