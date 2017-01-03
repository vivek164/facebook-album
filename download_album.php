<?php
session_start();

/** 
* download_album.php
* Generates zip file to download albums from facebook.
*/

$response = array();
/**
* remove_dir_recursively
* It will remove specified directory recursively.
* @param string $dir
* 
* @return nothing
*/
function remove_dir_recursively($dir)
{
	if(is_dir($dir))
	{
		$objects = scandir($dir);
		foreach(array_diff($objects, array('..', '.')) as $object)
		{
			if(is_dir($dir."/".$object))
			{
				remove_dir_recursively($dir."/".$object);
			}
			else
			{
				unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

if(isset($_POST['action_type']))
{
	$download_type = $_POST['action_type'];
	if($download_type == "selected")
	{
		//Check at least one album is selected to download
		if(isset($_POST['album_ids']) && !empty($_POST['album_ids']))
		{
			//Create User's album main directory
			$user_album_main_dir = "content/".$_SESSION['user_data']['id']."_albums";
			if(file_exists($user_album_main_dir))
			{
				remove_dir_recursively($user_album_main_dir);
			}

			$is_dir_created = mkdir($user_album_main_dir);
			if($is_dir_created)
			{
				//Create user's album direcotry
				foreach($_POST['album_ids'] as $album_id)
				{
					$index_album       = array_search($album_id, array_column($_SESSION['user_data']['albums']['data'], 'id'));
					$album_name        = $_SESSION['user_data']['albums']['data'][$index_album]['name'];

					//Create folder with album name
					$user_album_subdir = $user_album_main_dir."/".$album_name;
					$is_subdir_created = mkdir($user_album_subdir);
					if($is_subdir_created)
					{
						$album_photos = $_SESSION['user_data']['albums']['data'][$index_album]['photos']['data'];
						if(!empty($album_photos))
						{
							foreach($album_photos as $photo)
							{
								copy($photo['images'][0]['source'], $user_album_subdir."/".$photo['id'].".jpg");
							}
						}
					}
				}
			}

			// Get real path for our folder
			$rootPath = realpath($user_album_main_dir);

			// Initialize archive object
			$zip      = new ZipArchive();
			$zip->open($user_album_main_dir.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

			// Create recursive directory iterator
			/** @var SplFileInfo[] $files */
			$files    = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($rootPath),
				RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach($files as $name => $file)
			{
				// Skip directories (they would be added automatically)
				if(!$file->isDir())
				{
					// Get real and relative path for current file
					$filePath     = $file->getRealPath();
					$relativePath = substr($filePath, strlen($rootPath) + 1);

					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
				}
			}

			// Zip archive will be created only after closing object
			$zip->close();

			remove_dir_recursively($user_album_main_dir);

			$zipname = $_SESSION['user_data']['id']."_albums.zip";
			$response['flag'] = 1;
			$response['message'] = "Your zip file '$zipname' has been generated successfully. Click Download button below to download your album in zip archive.";
			$response['download_link'] = "download_zip.php?file=".$zipname;

		}
		else
		{
			$response['flag'] = 0;
			$response['message'] = "Please select atleast one album to download.";
		}
	}
	else
	if($download_type == "all")
	{
		//Create User's album main directory
		$user_album_main_dir = "content/".$_SESSION['user_data']['id']."_albums";
		if(file_exists($user_album_main_dir))
		{
			remove_dir_recursively($user_album_main_dir);
		}

		$is_dir_created = mkdir($user_album_main_dir);
		if($is_dir_created)
		{
			//Create user's album direcotry
			foreach($_SESSION['user_data']['albums']['data'] as $album)
			{
				$album_name        = $album['name'];

				//Create folder with album name
				$user_album_subdir = $user_album_main_dir."/".$album_name;
				$is_subdir_created = mkdir($user_album_subdir);
				if($is_subdir_created)
				{
					$album_photos = isset($album['photos']['data'])?$album['photos']['data']:array();
					if(!empty($album_photos))
					{
						foreach($album_photos as $photo)
						{
							copy($photo['images'][0]['source'], $user_album_subdir."/".$photo['id'].".jpg");
						}
					}
				}
			}
		}

		// Get real path for our folder
		$rootPath = realpath($user_album_main_dir);

		// Initialize archive object
		$zip      = new ZipArchive();
		$zip->open($user_album_main_dir.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files    = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach($files as $name => $file)
		{
			// Skip directories (they would be added automatically)
			if(!$file->isDir())
			{
				// Get real and relative path for current file
				$filePath     = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);

				// Add current file to archive
				$zip->addFile($filePath, $relativePath);
			}
		}

		// Zip archive will be created only after closing object
		$zip->close();

		remove_dir_recursively($user_album_main_dir);

		$zipname = $_SESSION['user_data']['id']."_albums.zip";
		$response['flag'] = 1;
		$response['message'] = "Your zip file '$zipname' has been generated successfully. Click Download button below to download your album in zip archive.";
		$response['download_link'] = "download_zip.php?file=".$zipname;


	}
	else
	{
		$response['flag'] = 0;
		$response['message'] = "Something went wrong! Please try again later.";
	}
}
else
{
	$album_id    = $_POST['album_id'];
	$index_album = array_search($album_id, array_column($_SESSION['user_data']['albums']['data'], 'id'));
	$album_name  = $_SESSION['user_data']['albums']['data'][$index_album]['name'];
	$album_photos= $_SESSION['user_data']['albums']['data'][$index_album]['photos']['data'];
	if(!empty($album_photos))
	{
		$zipname = $_SESSION['user_data']['id']."_".strtolower(str_replace(" ","_", $album_name)).'.zip';
		$zip     = new ZipArchive;
		$zip->open("content/".$zipname, ZipArchive::CREATE);


		foreach($album_photos as $photo)
		{
			// download file
			$download_file = file_get_contents($photo['images'][0]['source']);

			// add it to the zip
			$zip->addFromString($photo['id'].".jpg",$download_file);
		}

		$zip->close();
		$response['flag'] = 1;
		$response['message'] = "Your zip file '$zipname' has been generated successfully. Click Download button below to download your album in zip archive.";
		$response['download_link'] = "download_zip.php?file=".$zipname;
	}
	else
	{
		$response['flag'] = 0;
		$response['message'] = "There is no photos found in your album";
	}

}

echo json_encode($response);
?>