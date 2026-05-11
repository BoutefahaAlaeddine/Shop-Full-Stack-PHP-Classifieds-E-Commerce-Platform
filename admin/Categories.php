<?php

/*
======================================
=== Mange Categories Page
=== You Can Add | Edit |Delete Members From Here
=========================================
*/


//هذا التصميم الذي تمشي بيه كل الصفحات
// Manage Categories Page
ob_start();

session_start();
if (isset($_SESSION['username'])) {
    $PageTitle =  'Categories';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
    if ($do == "Manage") {
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');

        $sort = (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) ? $_GET['sort'] : 'ASC';

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent=0 ORDER BY ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll();
        if (!empty($cats)) {



?>

            <h1 class="text-center">Mange Categories</h1>
            <div class="container categories">
                <div class="panel Panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i> Manage Categories

                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordering:[
                            <a class="<?php echo ($sort == 'ASC') ? 'active' : ''; ?>" href="?sort=ASC">Asc</a>|
                            <a class="<?php echo ($sort == 'DESC') ? 'active' : ''; ?>" href="?sort=DESC">Desc</a>
                            ]
                            <i class="fa fa-eye"></i> View:[
                            <span class='active' data-view='full'>Full</span>
                            <span data-view='classic'>Classic</span>
                            ]
                        </div>


                    </div>
                    <div class="panel-body">
                        <?php
                        foreach ($cats as $cat) {
                            echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>
                        <a href='Categories.php?do=Edit&catId=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>
                        <a href='Categories.php?do=Delete&catId=" . $cat['ID'] . "' class='btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>
                        </div>";
                            echo "<h3>" . $cat['Name'] . "</h3>";
                            echo "<div class='full-view'>";
                            echo '<p>';
                            echo ($cat['Description'] == '') ? 'This Category has no description ' : $cat['Description'];
                            echo '</p>';
                            echo ($cat['Visibility'] == 1) ? '<span class="visibility cat-span "><i class="fa fa-eye"></i> Hidden</span>' : '';
                            echo ($cat['Allow_Comment'] == 1) ? '<span class="commenting cat-span "><i class="fa fa-close"></i> Comment Disabled</span>' : '';
                            echo ($cat['Allow_Ads'] == 1) ? '<span class=" advertises cat-span "><i class="fa fa-close"></i> Ads Disabled</span>' : '';
                            echo "</div>";

                            //Get Child Categories

                            $childCats = getAllFrom("*", "categories", "parent=" . $cat['ID'], 'ID', 'ASC');
                            if (!empty($childCats)) {
                                echo "<h4 class='child-head'>Child Categories</h4>";
                                echo "<ul class='list-unstyled child-cats'>";
                                foreach ($childCats as $c) {
                                    echo "<li class='child-link'>";
                                    echo "<a href='Categories.php?do=Edit&catId=" . $c['ID'] . "'>" . $c['Name'] . "</a>";
                                    echo "<a href='Categories.php?do=Delete&catId=" . $c['ID'] . "' class='show-delete confirm'>Delete</a>";
                                    echo " </li>";
                                }
                                echo "</ul>";
                            }
                            echo "</div>";
                            echo "<hr>";
                        }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus">Add New Category</i></a>
            </div>
        <?php

        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Categories To Show</div>';
            echo " <a href='categories.php?do=Add' class='add-category btn btn-primary'><i class='fa fa-plus'>Add New Category</i></a>";
            echo '</div>';
        }
    } elseif ($do == "Add") {
        //Add Page
        ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category" />
                    </div>
                </div>
                <!-- End Name Field -->
                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="Description The Category" />
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Ordering Field Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" />
                    </div>
                </div>
                <!-- End Ordering Field -->
                <!-- Start Category Type-->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Parent?</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php
                            $allCats = getAllFrom('*', 'categories', 'parent=0', 'ID', 'ASC');
                            foreach ($allCats as $cat) {
                                echo "<option value=" . $cat['ID'] . ">" . $cat['Name'] . "</option>";
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type-->

                <!-- Start Visibility Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <input id="vis-yes" type="radio" name="visible" value="0" checked />
                        <label for="vis-yes">Yes</label>
                    </div>
                    <div class="col-sm-10 col-md-6">
                        <input id="vis-no" type="radio" name="visible" value="1" />
                        <label for="vis-no">No</label>
                    </div>
                </div>
                <!-- End Visibility Field -->
                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <input id="com-yes" type="radio" name="commenting" value="0" checked />
                        <label for="com-yes">Yes</label>
                    </div>
                    <div class="col-sm-10 col-md-6">
                        <input id="com-no" type="radio" name="commenting" value="1" />
                        <label for="com-no">No</label>
                    </div>
                </div>
                <!-- End Commenting Field -->
                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <input id="ads-yes" type="radio" name="ads" value="0" checked />
                        <label for="ads-yes">Yes</label>
                    </div>
                    <div class="col-sm-10 col-md-6">
                        <input id="ads-no" type="radio" name="ads" value="1" />
                        <label for="ads-no">No</label>
                    </div>
                </div>
                <!-- End Ads Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End Submit Field -->

            </form>
        </div>
        <?php
    } elseif ($do == "Insert") {
        //Insert Page

        //لك يمكن جلب بيانات الصفحة الى اذا بعثتهم ب post

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Categories</h1>";
            echo "<div class='container'>";

            //جلب البيانات لدخلت دا خل المداخل 
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $ord = $_POST['ordering'];
            $parent = $_POST['parent'];
            $vis = $_POST['visible'];
            $com =  $_POST['commenting'];
            $ads =  $_POST['ads'];


            //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend

            if (strlen($name) == 0) {
                $formErrors =
                    'Username Cant Be <strong>Empty</strong>';
            }

            if (empty($formError)) {

                // دالة التحقق ان اسم المستخدم انه موجود بهدف عدم تكرار اسم المستخدم
                $count = checkCount('name', 'categories', $name);

                if ($count > 0) {
                    $errorMsg = "<div class='alert alert-danger'>This name already exists,Please enter another name</div>";
                    //الدال التي ترجعك الى الصفحة الرئسية بعد 6 ثواني
                    redirectHome($errorMsg, 'back', 6);
                } else {

                    //اضافة البيانات لداتا
                    //قادر تكتبو كيما كتبت الابديت  تدير علامة استفهام
                    $stmt = $con->prepare("INSERT INTO 
				                        `categories`(`Name`, `Description`, `Ordering`,`parent`,`Visibility`,`Allow_Comment`, `Allow_Ads`)
									 VALUES 
									     (:name,:desc,:ord,:parent,:vis,:com,:ads)");
                    $stmt->execute(
                        array(
                            'name' => $name,
                            'desc' => $desc,
                            'ord' => $ord,
                            'parent' => $parent,
                            'vis' => $vis,
                            'com' => $com,
                            'ads' => $ads
                        )
                    );

                    //رسالة تنفيذ العملية


                    $successMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Add</div>';
                    redirectHome($successMsg, 'index.php');
                }
            }
        }
        //اذا لم تائتي عن طريق البوست 
        else {
            echo "<div class='container'>";

            $errorMsg = "<div class='alert alert-danger'>Sorry You Cont Browse This Page Directly</div>";
            redirectHome($errorMsg, '', 6);

            echo "</div>";
        }

        echo "</div>";
    } elseif ($do == "Edit") {
        //Edit Page
        // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها


        //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
        $catId = (isset($_GET['catId']) && is_numeric($_GET['catId'])) ?  intval($_GET['catId']) : 0;

        $stmt = $con->prepare('SELECT * FROM categories WHERE ID = ? LIMIT 1');
        //هذه لتحقق                       
        $stmt->execute(array($catId));
        //هذا لجلب بينات عى شكل مصفوفة
        $row = $stmt->fetch();
        //يحسب عدد الاسطر في البينات
        $count = $stmt->rowCount();
        //لتحقق انا هذا  id موجود
        if ($count > 0) {
        ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catId" value="<?php echo $catId  ?>" />
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" value="<?php echo $row['Name'] ?>" />
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" value="<?php echo $row['Description'] ?>" />
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Ordering Field Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" class="form-control" value="<?php echo $row['Ordering'] ?>" />
                        </div>
                    </div>
                    <!-- End Ordering Field -->
                    <!-- Start Category Type-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                $allCats = getAllFrom('*', 'categories', 'parent=0 AND ID !=' . $_GET['catId'], 'ID', 'ASC');
                                foreach ($allCats as $c) {
                                    echo "<option value='" . $c['ID'] . "'";

                                    echo ($c['ID'] == $row['parent']) ? 'selected' : '';

                                    echo  ">" . $c['Name'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category Type-->
                    <!-- Start Visibility Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-6">
                            <input id="vis-yes" type="radio" name="visible" value="0" <?php echo ($row['Visibility'] == '0') ? 'checked' : '' ?> />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div class="col-sm-10 col-md-6">
                            <input id="vis-no" type="radio" name="visible" value="1" <?php echo ($row['Visibility'] == '1') ? 'checked' : '' ?> />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                    <!-- End Visibility Field -->
                    <!-- Start Commenting Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <input id="com-yes" type="radio" name="commenting" value="0" <?php echo ($row['Allow_Comment'] == '0') ? 'checked' : '' ?> />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div class="col-sm-10 col-md-6">
                            <input id="com-no" type="radio" name="commenting" value="1" <?php echo ($row['Allow_Comment'] == '1') ? 'checked' : '' ?> />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                    <!-- End Commenting Field -->
                    <!-- Start Ads Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <input id="ads-yes" type="radio" name="ads" value="0" <?php echo ($row['Allow_Ads'] == '0') ? 'checked' : '' ?> />
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div class="col-sm-10 col-md-6">
                            <input id="ads-no" type="radio" name="ads" value="1" <?php echo ($row['Allow_Ads'] == '1') ? 'checked' : '' ?> />
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                    <!-- End Ads Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
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
    } elseif ($do == "Update") {
        //Update Page

        //لك يمكن جلب بيانات الصفحة الى اذا بعثتهم ب post

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Categories</h1>";
            echo "<div class='container'>";

            //جلب البيانات لدخلت دا خل المداخل 
            $id = $_POST['catId'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $ord = $_POST['ordering'];
            $parent = $_POST['parent'];
            $vis = $_POST['visible'];
            $com =  $_POST['commenting'];
            $ads =  $_POST['ads'];

            //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
            //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend

            if (strlen($name) == 0) {
                $formErrors = 'name Cant Be <strong>Empty</strong>';
            }

            if (empty($formError)) {
                //تحديث البيانات في الداتا بيس 
                $stmt = $con->prepare(
                    "UPDATE 
				                          `categories`
									   SET 
									     `Name`=?,`Description`=?,`ordering`=?,`parent`=?,`Visibility`=?,`Allow_Comment`=? ,`Allow_Ads`=? 
									   WHERE  
									   `ID`=?"
                );
                $stmt->execute(array($name, $desc, $ord, $parent, $vis, $com, $ads, $id));

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
    } elseif ($do == "Delete") {
        //Delete  Page
        // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

        echo "<h1 class='text-center'>Delete Categories</h1>";
        echo "<div class='container'>";

        //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
        $catId = (isset($_GET['catId']) && is_numeric($_GET['catId'])) ?  intval($_GET['catId']) : 0;

        //يحسب عدد الاسطر في البينات
        $count = checkCount('ID', 'categories', $catId);;
        //لتحقق انا هذا  id موجود
        if ($count > 0) {

            //لاحض الطريقة الثالثة لربط المتغيرات
            $stmt = $con->prepare('DELETE FROM `Categories` WHERE ID = :id');
            $stmt->bindParam(':id', $catId);
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
    }
    include $tpl . 'footer.php';
} else {
    header('Location:index.php'); // redirect to dashboard page
    exit();
}
ob_end_flush();
