 <?php
    ob_start();
    session_start();
    //الملف لي فيه قاع الملفات المستدعات
    $PageTitle = 'Show Items';

    include 'init.php';

    // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

    //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
    $Item_ID = (isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID'])) ? intval($_GET['Item_ID']) : 0;

    $stmt = $con->prepare(
        'SELECT 
                               items.* ,categories.Name As cat_name,users.Username
                            FROM 
                               `items` 
                            JOIN 
                                 `categories`   
                             ON 
                              items.Cat_ID=categories.ID
                            JOIN 
                                 `users`   
                             ON 
                              items.Member_ID=users.UserID
                            
                            WHERE 
                                 Item_ID= ?
                             AND  
                                Approve=1    
                                '
    );
    //هذه لتحقق
    $stmt->execute(array($Item_ID));


    //يحسب عدد الاسطر في البينات
    $count = $stmt->rowCount();
    if ($count > 0) {

        //هذا لجلب بينات عى شكل مصفوفة
        $Items = $stmt->fetch();
    ?>
     <h1 class="text-center"><?php echo $Items['Name']; ?></h1>
     <div class="container">
         <div class="row">
             <div class="col-md-3">
                 <img src="img.png" alt="" class="img-responsive imag-thumbnail">
             </div>

             <div class="col-md-9 item-info">
                 <h2>ّ<?php echo $Items["Name"] ?></h2>
                 <p>ّ<?php echo $Items["Description"] ?></p>
                 <ul class="list-unstyled">
                     <li>
                         <i class="fa fa-calendar fa-fw"></i>
                         <span>Added Date</span> : <?php echo $Items["Add_Date"] ?>
                     </li>
                     <li>ّ
                         <i class="fa fa-money fa-fw"></i>
                         <span>Price</span> : $<?php echo $Items["Price"] ?>
                     </li>
                     <li>
                         <i class="fa fa-building fa-fw"></i>
                         <span>Made IN </span> : <?php echo $Items["Country_Made"] ?>
                     </li>
                     <li>
                         <i class="fa fa-tags fa-fw"></i>
                         <span>Category</span> : <a href='categories.php?cat_id=<?php echo  $Items['Cat_ID']  ?>'><?php echo $Items["cat_name"] ?></a>
                     </li>
                     <li>
                         <i class=" fa fa-user fa-fw"></i>
                         <span>Add By</span> : <a href="#"><?php echo $Items["Username"] ?></a>
                     </li>
                     <li class="tags-items">
                         <i class=" fa fa-user fa-fw"></i>
                         <span>Tags</span> :
                         <?php
                            if (!empty($Items["tags"])) {
                                $allTags = explode(',', $Items["tags"]);
                                foreach ($allTags as $tag) {
                                    $tag = str_replace(' ', '', $tag);
                                    $lowerTag = strtolower($tag);
                                    echo "<a href='tags.php?name={$lowerTag}'>" . $tag . "</a> ";
                                }
                            } else {
                                echo "Don't have any tag";
                            }

                            ?>

                         </a>
                     </li>
                 </ul>
             </div>
         </div>
         <hr class="custom-hr">
         <?php
            if (isset($_SESSION['user'])) {         ?>
             <!-- star Add comment -->
             <div class="row">
                 <div class="col-md-offset-3">
                     <div class="add-comment">
                         <h3>Add Your Comment</h3>
                         <form action="<?php echo $_SERVER['PHP_SELF'] . '?Item_ID=' . $Items['Item_ID'] ?>" method="POST">
                             <textarea name="comment" required></textarea>
                             <input type="submit" class="btn btn-primary" value="Add Comment">
                         </form>

                         <?php
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                $itemid = $Items['Item_ID'];
                                $userId = $_SESSION['admin'];
                                //اضافة البيانات لداتا
                                //قادر تكتبو كيما كتبت الابديت تدير علامة استفهام
                                if (!empty($comment)) {
                                    $stmt = $con->prepare("INSERT INTO
                                                         `comments`(`comment`, `status`, `comment_date`,`item_id`, `user_id`)
                                                       VALUES
                                                        (:comment,0,now(),:item_id,:user_id)");
                                    $stmt->execute(
                                        array(
                                            'comment' => $comment,
                                            'item_id' => $itemid,
                                            'user_id' => $userId
                                        )
                                    );
                                    if ($stmt) {
                                        echo '<div class="alert alert-success">Comment Added</div> ';
                                    }
                                } else {
                                    echo '<div class="alert alert-danger">You Must Add Comment</div> ';
                                }
                            }
                            ?>

                     </div>
                 </div>
             </div>
             <!-- end Add comment -->
         <?php
            } else {
                echo '<a href="login.php">login</a>or <a href="login.php">Register</a>To Add Comment';
            }
            ?>
         <hr class="custom-hr">
         <?php

            $itemid = $Items['Item_ID'];
            $stmt = $con->prepare(
                "SELECT 
                      comments.*,users.Username AS Member 
                   FROM 
                     `comments` 
                INNER JOIN 
                      users 
                ON 
                     users.UserID=comments.user_id 
                WHERE 
                     `item_id`=? AND `status`=1;
                        ORDER BY c_id DESC"
            );
            $stmt->execute(array($itemid));

            $count = $stmt->rowCount();

            if ($count > 0) {
                //هذا لجلب بينات عى شكل مصفوفة
                $comments = $stmt->fetchAll();
                foreach ($comments as $comment) {
            ?>
                 <div class="comment-box">
                     <div class="row">
                         <div class="col-md-2 text-center">
                             <img class="img-responsive img-thumbnail img-circle  center-block" src="img.png" alt="">
                             <?php echo $comment['Member'] ?>
                         </div>
                         <div class="col-md-10">
                             <p class="lead"> <?php echo $comment['comment'] ?> </p>
                         </div>
                     </div>
                 </div>
                 <hr class="custom-hr">
         <?php
                }
            }

            ?>
     </div>
     </div>
     </div>

 <?php

    } else {
        echo "<div class='container'>";

        $errorMsg = "<div class='alert alert-danger'>There\'s no Such ID or This Is Waiting Approve</div>";
        redirectHome($errorMsg, 'back');
        echo "</div>";
    }

    include $tpl . 'footer.php';
    ob_end_flush();
