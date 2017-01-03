## rtcamp-facebook-album
Facebook Photos Challange

Demo URL : [Facebook Album Assignment](http://www.vivekpipaliya.com/rtcamp_facebook_album/)

Note : For the demo, you should request to add your facebook account as tester in my facebook application.

## Functions

- Login with facebook
- Get facebook album list
- Download seperate album in zip
- Download selected or all albums in zip
- Move seperate album to Picasa
- Move selected or all albums to Picasa
- Slideshow of all images of particular album.


## How to use

- Create Application in Facebook : https://developers.facebook.com/docs/apps/register#create-app
- Replace facebook APP ID & APP Secret in fb-config.php
- Replace "your_project_path" in fb-config.php

- Create Google client id : https://console.developers.google.com/
- Replace Google Client ID & Client Secret in includes/Google_login.php
- Replace "your_project_path" in Google_login.php

## Reference Libraries & URL

-- For simple login with google 
	https://lornajane.net/posts/2012/using-oauth2-for-google-apis-with-php
	
-- For simple login with facebook
	http://www.codexworld.com/login-with-facebook-using-php/
	
-- Picasa Web Albums 
	https://developers.google.com/picasa-web/docs/2.0/developers_guide_protocol
	http://forselfref.blogspot.in/2013/08/create-picasa-album-and-upload-images.html
	http://stackoverflow.com/questions/7133140/create-a-picasa-album-and-upload-images-to-it-with-php-and-curl
	https://holtstrom.com/michael/blog/post/522/Google-OAuth2-with-PicasaWeb.html
