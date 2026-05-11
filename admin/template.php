<?php
//هذا التصميم الذي تمشي بيه كل الصفحات
// Manage Categories Page
ob_start();

session_start();
if (isset($_SESSION['username'])) {
    $$PageTitle = 'Categories';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
    if ($do == "Manage") {
    } elseif ($do == "Add") {
    } elseif ($do == "Insert") {
    } elseif ($do == "Edit") {
    } elseif ($do == "Update") {
    } elseif ($do == "Delete") {
    } elseif ($do == "Activate") {
    }
    include $tpl . 'footer.php';
} else {
    header('Location:index.php'); // redirect to dashboard page
    exit();
}
ob_end_flush();
