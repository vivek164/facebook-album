<?php

/** 
* download_zip.php
* Download specified zip file.
*/

$file = "content/".$_GET['file'];
if ($file and file_exists($file)) 
{
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($file));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));
  ob_clean();
  flush();
  readfile($file);
}
else
{
	echo "File is not available to download.";
}

?>