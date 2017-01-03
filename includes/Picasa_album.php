<?php
/**
 * Picasa_album.php
 *
 * Picasa Album Class
 *
 * @category   Album
 * @author     Vivek
 * 
 * I have used stackoverflow, github & other websites to create this class.
 * 
 * */
class Picasa_album
{
	/**
	* createAlbum
	* It creates album in Picasa web with specified name.
	* @param string $album_name
	* @param string $access_token
	* 
	* @return array
	*/
	function createAlbum($album_name = "Default Album", $access_token)
	{
		$return_arr = array();
		
		$albumEntryXML = "<entry xmlns='http://www.w3.org/2005/Atom' xmlns:media='http://search.yahoo.com/mrss/' xmlns:gphoto='http://schemas.google.com/photos/2007'>
		<title type='text'>".$album_name."</title>
		<summary type='text'>This is album is moved from Facebook at ".date("Y-m-d H:i:s")."</summary>
		<gphoto:location></gphoto:location>
		<gphoto:access></gphoto:access>
		<gphoto:timestamp>".time()."</gphoto:timestamp>
		<media:group>
		<media:keywords>".$album_name."</media:keywords>
		</media:group>
		<category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/photos/2007#album'></category>
		</entry>";

		$ch     = curl_init("https://picasaweb.google.com/data/feed/api/user/default?access_token=".$access_token);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $albumEntryXML);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/atom+xml"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
		if(curl_error($ch)){
			
			$return_arr['flag'] = 0;
			$return_arr['message'] = "Something went wrong! Please try again later.";
		}
		else
		{
			$response_xml = new SimpleXMLElement($response);
			$album_id     = $response_xml->children('gphoto', true)->id;

			$return_arr['flag'] = 1;
			$return_arr['message'] = "Album created successfully.";
			$return_arr['album_id'] = $album_id;
		}

		curl_close($ch);

		return $return_arr;
	}

	/**
	* uploadPhoto
	* It uploads photos to specified album in picasa web.
	* @param string $album_id
	* @param string $photo_url
	* @param string $access_token
	* 
	* @return nothing
	*/
	function uploadPhoto($album_id, $photo_url, $access_token)
	{				
		$imgEntryXML  = '<entry xmlns="http://www.w3.org/2005/Atom">
		<title>'.time().'.jpg</title>
		<summary>This photo is copied from facebook.</summary>
		<category scheme="http://schemas.google.com/g/2005#kind"
		term="http://schemas.google.com/photos/2007#photo"/>
		</entry>';		

		$file     = fopen($photo_url, "rb");
		$imgData    = stream_get_contents($file);

		fclose($file);

		$dataLength = strlen($imgEntryXML);
		$data       = "";
		$data .= "\nMedia multipart posting\n";
		$data .= "--P4CpLdIHZpYqNn7\n";
		$data .= "Content-Type: application/atom+xml\n\n";
		$data .= $imgEntryXML . "\n";
		$data .= "--P4CpLdIHZpYqNn7\n";
		$data .= "Content-Type: image/jpeg\n\n";
		$data .= $imgData . "\n";
		$data .= "--P4CpLdIHZpYqNn7--";		

		$ch      = curl_init("https://picasaweb.google.com/data/feed/api/user/default/albumid/$album_id?access_token=".$access_token);
		$options = array(
			CURLOPT_SSL_VERIFYPEER=> false,
			CURLOPT_POST          => true,
			CURLOPT_RETURNTRANSFER=> true,
			CURLOPT_HEADER        => true,
			CURLOPT_FOLLOWLOCATION=> true,
			CURLOPT_POSTFIELDS    => $data,
			CURLOPT_HTTPHEADER    => array('GData-Version:  2','Content-Type: multipart/related; boundary=P4CpLdIHZpYqNn7;','Content-Length: ' . strlen($data),'MIME-version: 1.0')
		);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		
		curl_close($ch);
	}
}
?>