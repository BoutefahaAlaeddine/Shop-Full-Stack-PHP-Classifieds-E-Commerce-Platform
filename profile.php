<?php
session_start();

//الملف لي فيه قاع الملفات المستدعات
$PageTitle = 'Profile';

include 'init.php';
if (isset($_SESSION['user'])) {

    $getUser = $con->prepare(
        'SELECT * FROM users WHERE Username = ?'
    );
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();


?>
    <h1 class="text-center">My Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Information </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Login Name</span> : <?php echo $info['Username'] ?>
                        </li>
                        <li>
                            <i class="fa fa-envelope-o fa-fw"></i>
                            <span>Email</span> : <?php echo $info['Email'] ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>FullName</span> : <?php echo $info['FullName'] ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span> Date</span> : <?php echo $info['Date'] ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span> Fav Category</span> :
                        </li>
                    </ul>
                    <a href="#" class="btn btn-default">Edit Information</a>
                </div>
            </div>
        </div>
    </div>
    <div id='my-ads' class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Items</div>
                <div class="panel-body">
                    <?php

                    if (!empty(getAllFrom('*', 'items', 'Member_ID=' . $info['UserID'], 'Item_ID'))) {
                        echo '<div class="row">';
                        foreach (getAllFrom('*', 'items', 'Member_ID=' . $info['UserID'], 'Item_ID') as $item) {
                            echo "<div class='col-sm-6 col-md-3'>";
                            echo "<div class='thumbnail item-box'>";
                            echo ($item['Approve'] == 0) ? '<span class="approve-status">Waiting Approve</span> ' : '';
                            echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
                            echo "<img class='img-responsive' src='img.png' alt=''>";
                            echo "<div class='caption'>";

                            echo "<h3><a href='item.php?Item_ID=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h3>";
                            echo "<p>" . $item['Description'] . "</p>";
                            echo "<div class='date'>" . $item['Add_Date'] . "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo '</div>';
                    } else {
                        echo "There's No Ads To Show <a href='newAds.php'>New Ads</a>";
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Latest Comments</div>
                <div class="panel-body">
                    <?php
                    $comments = getAllFrom('*', 'comments', 'user_id =' . $info['UserID'], 'c_id');

                    if (!empty($comments)) {
                        foreach ($comments as $comment) {
                            echo $comment['comment'];
                        }
                    } else {
                        echo "There's No comment To Show";
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

<?php }
include  $tpl . 'footer.php';
?>