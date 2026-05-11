<?php

ob_start();
session_start();

//اذا راه داخل لصفحة باسم المستخد والرقم السري  خليه داخل
if (isset($_SESSION['username'])) {

    //متغيير عنوان الصفحة
    $PageTitle = 'Dashboard';


    include 'init.php';
    /*Start Dashboard Page */


    /*Str latest variables*/
    $latest = 5; //اخر 5 اشخاص مسجلين
    $theLatestUser = getLatest("*", "users", "UserID", $latest);
    /*End latest variables*/


    $theLatestItems = getLatest("*", "items", "Item_ID", $latest);
    /*End latest variables*/

?>
    <div class="home-stats">
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-user"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo checkCount('UserID', 'users') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?Page=Pending"><?php echo checkCount("RegStatus", "users", "RegStatus=0") ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo checkCount('Item_ID', 'items') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comment.php"><?php echo checkCount('c_id', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>
                            Latest <?php echo $latest ?> Registered Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fal-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($theLatestUser)) {
                                    foreach ($theLatestUser as $user) {
                                        echo '<li>';
                                        echo $user['Username'];
                                        echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                        echo '<span class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit"></i> Edit';
                                        if ($user['RegStatus'] == 0) {
                                            echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' 
												class='btn btn-info pull-right activate'>
												<i class='fa fa-check'></i> Activate</a>";
                                        }
                                        echo '</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo 'There\'s No Members To Show';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>
                            Latest <?php echo $latest ?> Registered Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fal-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($theLatestItems)) {
                                    foreach ($theLatestItems as $Item) {
                                        echo '<li>';
                                        echo $Item['Name'];
                                        echo '<a href="items.php?do=Edit&Item_ID=' . $Item['Item_ID'] . '">';
                                        echo '<span class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit"></i> Edit';
                                        if (
                                            $Item['Approve'] == 0
                                        ) {
                                            //هذي نتاع تفعيل شخص كادمين لصفحة
                                            echo "<a href='items.php?do=Approve&Item_ID=" . $Item['Item_ID'] . "' 
												class='btn btn-info pull-right activate'>
												<i class='fa fa-check'></i> Activate</a>";
                                        }
                                        echo '</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo 'There\'s No Item To Show';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i>
                            Latest <?php echo $latest ?> Comment
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fal-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $con->prepare("SELECT 
                                     comments.comment,users.Username 
                               FROM  
                                    `comments` 
                               JOIN 
                                    users 
                               ON 
                                   comments.user_id=users.UserID
                              ORDER BY 
                            comments. c_id
                              DESC LIMIT $latest
    ;
");
                            //Execute The Statement
                            $stmt->execute();
                            //Assign To Variable
                            $comments = $stmt->fetchAll();
                            if (!empty($comments)) {
                                foreach ($comments as $comment) {
                                    echo '<div class="comment-box">';
                                    echo '<span class="member-n">' . $comment['Username'] . '</span>';
                                    echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                    echo ' </div>';
                                }
                            } else {
                                echo "There's No comment To Show";
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    <?php
    include  $tpl . 'footer.php';
    /*End Dashboard Page */
} else {
    //هذي نديروها للحماية باه لي دايدخل مباشرة لهذي الصفحة بدون مايسجل ترجعو لصفحة التسجيل

    header('location:index.php');

    exit();
}

ob_end_flush();
    ?>