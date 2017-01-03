<?php

/**
 * Google_login.php
 *
 * Google Login Class
 *
 * @category   Social Login
 * @author     Vivek
 * 
 * I have used stackoverflow, github & other websites to create this class.
 * 
 * */

define("GOOGLE_AUTH_URL", "https://accounts.google.com/o/oauth2/auth");
define("GOOGLE_TOKEN_URL", 'https://accounts.google.com/o/oauth2/token');
define("GOOGLE_TOKENINFO_URL", 'https://www.googleapis.com/oauth2/v1/tokeninfo');
define("GOOGLE_CLIENT_ID", "Your Google Client ID");
define("GOOGLE_CLIENT_SECRET", "Your Google Client Secret");
define("GOOGLE_LOGIN_CALLBACK_URL", "http://your_project_path/picasa_move.php");

class Google_login
{
	/**
	* getLoginUrl
	* This function will return the google login auth url.
	*
	* @return string Google Login Auth URL.
	*/

	function getLoginUrl()
	{
		$params = array(
			"response_type"=> "code",
			"client_id"    => GOOGLE_CLIENT_ID,
			"redirect_uri" => GOOGLE_LOGIN_CALLBACK_URL,
			"scope"        => "http://picasaweb.google.com/data",
			"access_type"=>"offline"			
		);

		return GOOGLE_AUTH_URL . '?' . http_build_query($params);
	}

	/**
	* getAccessToken
	* Returns Access token from Authorization code
	* @param string $code
	*
	* @return string Google Access Token
	*/

	function getAccessToken($code)
	{		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_URL, GOOGLE_TOKEN_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&client_id=".GOOGLE_CLIENT_ID."&client_secret=".GOOGLE_CLIENT_SECRET."&code=$code&redirect_uri=".GOOGLE_LOGIN_CALLBACK_URL);
		$response      = curl_exec($ch);
		$responseArray = json_decode($response, TRUE);
		
		return $responseArray;
	}
	
	/**
	* isTokenValid
	* Returns array indicating Token is valid or not.
	* @param string $access_token
	* 
	* @return array 
	*/
	
	function isTokenValid($access_token)
	{		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, GOOGLE_TOKENINFO_URL."?access_token=$access_token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
		
		$response      = curl_exec($ch);
		$responseArray = json_decode($response, TRUE);
		
		return $responseArray;
	}
}
?>