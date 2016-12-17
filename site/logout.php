<?php
require_once( './config.php' ) ;
$_SESSION = array();
session_destroy();
header("location:{$URL_PAGE_LOGIN}");
exit();
?>
