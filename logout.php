<?php
/**
* logout.php
* Destroyes all stored session.
*/
session_start();
session_destroy();
header("location:index.php");
?>