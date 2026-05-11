<?php

//الكود لي يفعلك الاخطاء المخفية
ini_set('display_errors', 'On');
error_reporting(E_ALL);

//استدعاء ملف الاتصال بالداتا بيس
include 'admin/connect.php';
$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}
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
