<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title><?php getTitle() ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css ?>front.css" />
</head>

<body>
    <div class="upper-bar">
        <div class="container">
            <?php
            if (isset($_SESSION['user'])) { ?>
                <img src="img.png" alt="" class="my-image img -thumbnail img-circle">
                <div class="btn-group my-info">
                    <span class="btn-default dropdown-toggle" data-toggle="dropdown">
                        <?php echo $sessionUser ?>
                        <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="newAds.php">New Item</a></li>
                        <li><a href="profile.php#my-ads">My Items</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>

            <?php
                // $userStatus = checkUserStatus($_SESSION['user']);
                // if ($userStatus == 1) {
                //     //هنا اذاكان الشخص الذي سجل ليس من الادمين
                // }
            } else {
                echo " <a href='login.php'>
                <span class='pull-right'>Login/Signup</span>
                  </a>
            ";
            }
            ?>

        </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo lang('HOME_ADMIN') ?></a>
            </div>
            <div class="collapse navbar-collapse " id="app-nav">
                <ul class="nav navbar-nav  navbar-right">
                    <?php
                    foreach (getAllFrom('*', 'categories', 'parent=0', 'ID') as $cat) {
                        echo "<li><a href='categories.php?cat_id=" . $cat['ID'] . "'>" . $cat['Name'] . "</a> </li>";
                    } ?>


                </ul>

            </div>
        </div>
    </nav>