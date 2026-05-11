<?php
/*
======================================
=== Mange Comment Page
=== You Can Add | Edit |Delete Comments From Here
=========================================
*/

ob_start();

session_start();


//اذا راه داخل لصفحة باسم المستخد والرقم السري  خليه داخل
if (isset($_SESSION['username'])) {
    $PageTitle = 'Comment';

    include 'init.php';

    //هذا الكود يعني انه لو شخص كتب ?do 
    //يستقبل قيمة ال do,ويحطها في متغيير
    // اذا محطش ?do,ابعثو طول لصفحة ال mange
    $do = isset($_GET['do']) ? $_GET['do'] : 'Mange';

    //اذا كتب do?=Mange;
    //star Manage Page
    if ($do == 'Mange') {
        //Select All Users Except Admin
        $stmt = $con->prepare("SELECT comments.c_id ,comments.comment,items.Name as item_name ,users.Username ,comments.comment_date ,comments.status FROM `comments` JOIN items ON comments.item_id=items.Item_ID JOIN users ON comments.user_id=users.UserID
ORDER BY c_id DESC

        ");
        //Execute The Statement
        $stmt->execute();
        //Assign To Variable
        $comments = $stmt->fetchAll();
        if (!empty($comments)) {

?>
<h1 class="text-center">Manage Comments </h1>
<div class="container">
    <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>ID</td>
                <td>Comment</td>
                <td>Item Name </td>
                <td>User Name </td>
                <td>Added Date </td>
                <td>Control</td>
            </tr>
            <?php
                        foreach ($comments as $comment) {
                            echo '<tr>';
                            echo '<td>' . $comment['c_id'] . '</td>';
                            echo '<td>' . $comment['comment'] . '</td>';
                            echo '<td>' . $comment['item_name'] . '</td>';
                            echo '<td>' . $comment['Username'] . '</td>';
                            echo '<td>' . $comment['comment_date'] . '</td>';
                            echo "<td>
						       <a href='comment.php?do=Edit&comId=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
						       <a href='comment.php?do=Delete&comId=" . $comment['c_id'] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete</a>";
                            if ($comment['status'] == 0) {
                                //هذي نتاع تفعيل شخص كادمين لصفحة
                                echo " <a href='comment.php?do=Approve&comId=" . $comment['c_id'] . "' class='btn btn-info'> <i class='fa fa-check'></i> Approve</a>";
                            }
                            "</td>";
                            echo '</tr>';
                        }
                        ?>

        </table>
    </div>

</div>
<?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No comment To Show</div>';
            echo '</div>';
        }
    }
    //اذا كتب do?=Edit;
    elseif ($do == 'Edit') {
        //Edit Page
        // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها


        //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
        $comId = (isset($_GET['comId']) && is_numeric($_GET['comId'])) ?  intval($_GET['comId']) : 0;

        $stmt = $con->prepare('SELECT * FROM comments WHERE c_id = ? LIMIT 1');
        //هذه لتحقق                       
        $stmt->execute(array($comId));
        //هذا لجلب بينات عى شكل مصفوفة
        $row = $stmt->fetch();
        //يحسب عدد الاسطر في البينات
        $count = $stmt->rowCount();
        //لتحقق انا هذا  id موجود
        if ($count > 0) {
        ?>
<h1 class="text-center">Edit Comment</h1>
<div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
        <input type="hidden" name="comId" value="<?php echo $comId ?>" />
        <!-- Start Comment Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Comment</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="comment" class="form-control" value="<?php echo $row['comment'] ?>"
                    autocomplete="off" required="required" />
            </div>
        </div>
        <!-- End Username Field -->

        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-primary btn-lg" />
            </div>
        </div>
        <!-- End Submit Field -->
    </form>
</div>

<?php
        } else {
            echo "<div class='container'>";

            $errorMsg = "<div class='alert alert-danger'>Thers No Such ID</div>";
            redirectHome($errorMsg);

            echo "</div'>";
        }
    }
    //اذا كنت دخلت بهذي الطريقة ?update
    elseif ($do == 'Update') {

        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";

        //لك يمكن جلب بيانات الصفحة الى اذا بعثتهم ب post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //جلب البيانات لدخلت دا خل المداخل 
            $comId = $_POST['comId'];
            $comm = $_POST['comment'];



            //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
            $formErrors = '';

            if (empty($comm)) {
                $formErrors = 'Comment Cant Be <strong>Empty</strong>';
            }

            if (empty($formErrors)) {

                //تحديث البيانات في الداتا بيس 
                $stmt = $con->prepare(
                    "UPDATE 
				                          `comments`
									   SET 
									     `comment`=?
									   WHERE  
									   `c_id`=?"
                );
                $stmt->execute(array($comm, $comId));

                //رسالة تنفيذ العملية
                $successMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($successMsg, 'back');
            }
        }
        //اذا لم تائتي عن طريق البوست 
        else {
            echo "<div class='container'>";

            $errorMsg = "<div class='alert alert-danger'>Sorry You Cont Browse This Page Directly</div>";
            redirectHome($errorMsg);
            echo "</div>";
        }

        echo "</div>";
    } elseif ($do == 'Delete') {
        //Delete Members Page
        // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";

        //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
        $comId = (isset($_GET['comId']) && is_numeric($_GET['comId'])) ?  intval($_GET['comId']) : 0;

        //هذه لتحقق                       
        $count = checkCount('c_id', 'comments', $comId);;

        //لتحقق انا هذا  id موجود
        if ($count > 0) {

            //لاحض الطريقة الثالثة لربط المتغيرات
            $stmt = $con->prepare('DELETE FROM `comments` WHERE c_id = :id');
            $stmt->bindParam(':id', $comId);
            $stmt->execute();


            $successMsg =    "<div class='alert alert-success'>" .     $count  . ' Record Deleted</div>';
            redirectHome($successMsg, 'back');
        } else {
            echo "<div class='container'>";

            $errorMsg = "<div class='alert alert-danger'>This ID Not Exist</div>";

            redirectHome($errorMsg);

            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Approve') {

        //Active Members Page
        // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

        echo "<h1 class='text-center'>Approve Member</h1>";
        echo "<div class='container'>";
        //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
        $comId = (isset($_GET['comId']) && is_numeric($_GET['comId'])) ?  intval($_GET['comId']) : 0;

        //هذه لتحقق                       
        $count = checkCount('c_id', 'comments', $comId);;
        //لتحقق انا هذا  id موجود
        if ($count > 0) {
            //لاحض الطريقة الثالثة لربط المتغيرات
            $stmt = $con->prepare('UPDATE `comments` SET `status`=1 WHERE `c_id`=?');
            $stmt->execute(array($comId));


            $successMsg =    "<div class='alert alert-success'>" .     $count  . ' Record Active</div>';
            redirectHome($successMsg, 'back');
        } else {
            echo "<div class='container'>";

            $errorMsg = "<div class='alert alert-danger'>This ID Not Exist</div>";

            redirectHome($errorMsg);

            echo "</div>";
        }
        echo "</div>";
    }



    include  $tpl . 'footer.php';
} else {
    //هذي نديروها للحماية باه لي دايدخل مباشرة لهذي الصفحة بدون مايسجل ترجعو لصفحة التسجيل
    header('location:index.php');
    exit();
}
ob_end_flush();
?>