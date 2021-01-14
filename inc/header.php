<?php 

if (!defined('FOLDER_NAME')) {
	$x = explode("/", $_SERVER['SCRIPT_NAME']);
	define('FOLDER_NAME', $x[1]);
}

?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/<?=FOLDER_NAME?>/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/<?=FOLDER_NAME?>/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="/<?=FOLDER_NAME?>/dist/css/skins/_all-skins.min.css">
<link rel="icon" href="/BackupControl/dist/img/icon.png" type="image/gif" sizes="16x16">