<?php
//استدعاء ملف الاتصال بالداتا بيس
include 'connect.php';

//مسارات الملفات
$tpl = 'includes/templates/'; // Template Directory
$lan = 'includes/languages/'; // Language Directory
$func = 'includes/functions/'; // Functions Directory
$css = 'layout/css/'; // Css Directory
$js = 'layout/js/'; // Js Directory

//include the important files
include $func . 'functions.php';
include $lan . 'en.php';
include $tpl . 'header.php';

//include navbar on all pages expect the one with $noNavbar variable
if (!isset($noNavbar)) {
	include $tpl . 'navbar.php';
}
