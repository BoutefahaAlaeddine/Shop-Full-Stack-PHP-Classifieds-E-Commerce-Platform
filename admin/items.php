<?php
//هذا التصميم الذي تمشي بيه كل الصفحات
// Manage Categories Page
ob_start();

session_start();
if (isset($_SESSION['username'])) {
    $PageTitle = 'Items';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
    if ($do == "Manage") {


        //Select All Users Except Admin
        $stmt = $con->prepare("SELECT items.Item_ID,users.Username , categories.Name AS cat_Name, items.Name as item_Name, items.Description,items.Price ,items.Add_Date,items.Approve FROM `items` JOIN users ON items.Member_ID = users.UserID JOIN categories ON items.Cat_ID=categories.ID;
                           ORDER BY Item_ID DESC 
                         ");
        //Execute The Statement
        $stmt->execute();
        //Assign To Variable
        $items = $stmt->fetchAll();
        if (!empty($items)) {

?>
            <h1 class="text-center">Add New Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Item Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($items as $item) {
                            echo '<tr>';
                            echo '<td>' . $item['Item_ID'] . '</td>';
                            echo '<td>' . $item['item_Name'] . '</td>';
                            echo '<td>' . $item['Description'] . '</td>';
                            echo '<td>' . $item['Price'] . '</td>';
                            echo '<td>' . $item['Add_Date'] . '</td>';
                            echo '<td>' . $item['cat_Name'] . '</td>';
                            echo '<td>' . $item['Username'] . '</td>';
                            echo "<td>
						       <a href='items.php?do=Edit&Item_ID=" .  $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
						       <a href='items.php?do=Delete&Item_ID=" .  $item['Item_ID'] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete</a>";
                            if (
                                $item['Approve'] == 0
                            ) {
                                //هذي نتاع تفعيل شخص كادمين لصفحة
                                echo " <a href='items.php?do=Approve&Item_ID=" .  $item['Item_ID'] . "' class='btn btn-info'> <i class='fa fa-check'></i> Approve</a>";
                            }
                            "</td>";
                            echo '</tr>';
                        }
                        ?>

                    </table>
                </div>
                <a href="items.php?do=Add" class='btn btn-primary'><i class='fa fa-plus'></i> Add New Item</a>

            </div>
        <?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Item To Show</div>';
            echo "<a href='items.php?do=Add'class='btn btn-primary'><i class='fa fa-plus'></i> Add New Item</a>";
            echo '</div>';
        }
    } elseif ($do == "Add") {
        //Add Page
        ?>
        <h1 class="text-center">Add New Item</h1>
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
                        <input type="text" name="description" class="form-control" placeholder="Description The Item" required="required" />
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Price Field Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" placeholder="Price Of The Item" required="required" />
                    </div>
                </div>
                <!-- End Price Field -->
                <!-- Start Country Field Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class="form-control" placeholder="Country Of The Made" required="required" />
                    </div>
                </div>
                <!-- End Country Field -->
                <!-- Start Status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status Field -->
                <!-- Start members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">members</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="members">
                            <option value="0">...</option>
                            <?php
                            $users = getAllFrom('*', 'users', '', 'UserID');
                            foreach ($users as $user) {
                                echo "<option value=' " . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End members Field -->
                <!-- Start Categories Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Categories</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="categories">
                            <option value="0">...</option>
                            <?php
                            $cats = getAllFrom('*', 'categories', 'parent=0', 'ID');
                            foreach ($cats as $cat) {
                                echo "<option value=' " . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $ChildCats = getAllFrom('*', 'categories', 'parent=' . $cat['ID'], 'ID');
                                foreach ($ChildCats as $ChildCat) {
                                    echo "<option value=' " . $ChildCat['ID'] . "'>--- " . $ChildCat['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Categories Field -->
                <!-- Start Tags Field Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
                    </div>
                </div>
                <!-- End Tags Field -->

        </div>
        </div>
        <!-- End members Field -->

        <!-- Start Submit Field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
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
            echo "<h1 class='text-center'>Insert Items</h1>";
            echo "<div class='container'>";

            //جلب البيانات لدخلت دا خل المداخل 
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status =  $_POST['status'];
            $user =  $_POST['members'];
            $cat =  $_POST['categories'];
            $tags = $_POST['tags'];

            //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
            //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'name Cant Be <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'description Cant Be <strong>Empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'price Cant Be <strong>Empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'country Cant Be <strong>Empty</strong>';
            }
            if ($status == 0) {
                $formErrors[] = 'You  Must Choose The <strong>Status</strong>';
            }
            if ($user == 0) {
                $formErrors[] = 'You  Must Choose The <strong>Members</strong>';
            }
            if ($cat == 0) {
                $formErrors[] = 'You  Must Choose The <strong>Categories</strong>';
            }

            if (empty($formErrors)) {


                //اضافة البيانات لداتا
                //قادر تكتبو كيما كتبت الابديت  تدير علامة استفهام
                $stmt = $con->prepare("INSERT INTO 
				                        `items`(`Name`, `Description`, `Price`,`Add_Date`, `Country_Made`,`Status`,`Member_ID`,`Cat_ID`,`tags`)
									 VALUES 
									     (:name,:desc,:price,now(),:country,:status,:member,:categories,:tags)");
                $stmt->execute(
                    array(
                        'name' => $name,
                        'desc' => $desc,
                        'price' => $price,
                        'country' => $country,
                        'status' => $status,
                        'member' => $user,
                        'categories' => $cat,
                        'tags' => $tags
                    )
                );

                //رسالة تنفيذ العملية


                $successMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Add</div>';
                redirectHome($successMsg, 'back');
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
        $Item_ID = (isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID'])) ?  intval($_GET['Item_ID']) : 0;
        $Items = getAllFrom('*', 'items', 'Item_ID=' . $Item_ID, 'Item_ID');

        $count =  checkCount('*', 'items', 'Item_ID=' . $Item_ID);
        echo $count;
        //لتحقق انا هذا  id موجود
        if ($count > 0) {
        ?>
            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="Item_ID" value="<?php echo $Item_ID ?>" />
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category" value=" <?php echo $Items['0']['Name'] ?>" />
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Description The Item" required="required" value=" <?php echo $Items['0']['Description'] ?>" />
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" placeholder="Price Of The Item" required="required" value="<?php echo $Items['0']['Price'] ?>" />
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country Field Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" placeholder="Country Of The Made" required="required" value=" <?php echo $Items['0']['Country_Made'] ?>" />
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status">
                                <option value="0" <?php echo ($Items['0']['Status'] == '0') ? 'checked' : '' ?>>...</option>
                                <option value="1" <?php echo ($Items['0']['Status'] == '1') ? 'selected' : ''  ?>>New</option>
                                <option value="2" <?php echo ($Items['0']['Status'] == '2') ? 'selected' : ''  ?>>Like New</option>
                                <option value="3" <?php echo ($Items['0']['Status'] == '3') ? 'selected' : ''  ?>>Used</option>
                                <option value="4" <?php echo ($Items['0']['Status'] == '4') ? 'selected' : ''  ?>>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">members</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="members">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM `users`");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value=' " . $user['UserID'] . "'";
                                    echo ($Items['0']['Member_ID'] == $user['UserID']) ? 'selected' : '';
                                    echo  " >" . $user['Username'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End members Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categories</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="categories">
                                <option value="0">...</option>
                                <?php
                                $cats = getAllFrom('*', 'categories', 'parent=0', 'ID');
                                foreach ($cats as $cat) {
                                    echo "<option value=' " . $cat['ID'] . "'";
                                    echo ($Items['0']['Cat_ID'] == $cat['ID']) ? 'selected' : '';
                                    echo ">" . $cat['Name'] . "</option>";
                                    $ChildCats = getAllFrom('*', 'categories', 'parent=' . $cat['ID'], 'ID');
                                    foreach ($ChildCats as $ChildCat) {
                                        echo "<option value=' " . $ChildCat['ID'] . "'";
                                        echo ($Items['0']['Cat_ID'] == $ChildCat['ID']) ? 'selected' : '';
                                        echo    ">--- " . $ChildCat['Name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->

                    <!-- End Item Field -->
                    <!-- Start Tags Field Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" value=" <?php echo $Items['0']['tags'] ?>" />
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- End members Field -->

                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->

                </form>
                <?php
                //Select All Users Except Admin
                $stmt = $con->prepare("SELECT comments.c_id ,comments.comment,items.Name as item_name ,users.Username ,comments.comment_date ,comments.status FROM `comments` JOIN items ON comments.item_id=items.Item_ID JOIN users ON comments.user_id=users.UserID
        WHERE items.item_id=? 
        
        ;
");
                //Execute The Statement
                $stmt->execute(array($Item_ID));
                //Assign To Variable
                $rows = $stmt->fetchAll();
                //لتحقق انا هذا  id موجود
                if (!empty($rows)) {
                ?>
                    <h1 class="text-center">Manage [<?php echo $Items['Name'] ?> ] Comments </h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>comment</td>
                                <td>User Name </td>
                                <td>Added Date </td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo '<tr>';
                                echo '<td>' . $row['comment'] . '</td>';
                                echo '<td>' . $row['Username'] . '</td>';
                                echo '<td>' . $row['comment_date'] . '</td>';
                                echo "<td>
						       <a href='comment.php?do=Edit&comId=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
						       <a href='comment.php?do=Delete&comId=" . $row['c_id'] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete</a>";
                                if ($row['status'] == 0) {
                                    //هذي نتاع تفعيل شخص كادمين لصفحة
                                    echo " <a href='comment.php?do=Approve&comId=" . $row['c_id'] . "' class='btn btn-info'> <i class='fa fa-check'></i> Approve</a>";
                                }
                                "</td>";
                                echo '</tr>';
                            }
                            ?>

                        </table>
                    </div>

            </div>


            </div>
<?php
                }
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
                echo "<h1 class='text-center'>Update Item</h1>";
                echo "<div class='container'>";

                //جلب البيانات لدخلت دا خل المداخل 
                $Item_ID = $_POST['Item_ID'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status =  $_POST['status'];
                $user =  $_POST['members'];
                $cat = $_POST['categories'];
                $tags = $_POST['tags'];

                //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
                //التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend

                $formErrors = array();
                if (empty($name)) {
                    $formErrors[] = 'name Cant Be <strong>Empty</strong>';
                }
                if (empty($desc)) {
                    $formErrors[] = 'description Cant Be <strong>Empty</strong>';
                }
                if (empty($price)) {
                    $formErrors[] = 'price Cant Be <strong>Empty</strong>';
                }
                if (empty($country)) {
                    $formErrors[] = 'country Cant Be <strong>Empty</strong>';
                }
                if ($status == 0) {
                    $formErrors[] = 'You  Must Choose The <strong>Status</strong>';
                }
                if ($user == 0) {
                    $formErrors[] = 'You  Must Choose The <strong>Members</strong>';
                }
                if ($cat == 0) {
                    $formErrors[] = 'You  Must Choose The <strong>Categories</strong>';
                }

                if (empty($formErrors)) {
                    //تحديث البيانات في الداتا بيس 
                    $stmt = $con->prepare(
                        "UPDATE `items` SET `Name`=?,`Description`=?,`Price`=?,`Country_Made`=?,`Status`=?,`Cat_ID`=?,`Member_ID`=? ,`tags`=? WHERE `Item_ID`=?"
                    );
                    $stmt->execute(array($name, $desc, $price, $country, $status,  $cat, $user, $tags, $Item_ID));

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

            //Delete Members Page
            // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

            echo "<h1 class='text-center'>Delete Items</h1>";
            echo "<div class='container'>";

            //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
            $Item_ID = (isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID'])) ?  intval($_GET['Item_ID']) : 0;



            //يحسب عدد الاسطر في البينات
            $count = checkCount('Item_ID', 'items', 'Item_ID=' . $Item_ID);;
            //لتحقق انا هذا  id موجود
            if ($count > 0) {

                //لاحض الطريقة الثالثة لربط المتغيرات
                $stmt = $con->prepare('DELETE FROM `items` WHERE Item_ID = :id');
                $stmt->bindParam(':id', $Item_ID);
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
        } elseif ($do == "Approve") {
            //Approve Item Page
            //هدف من هذه الصفحة منع او سماح للاعلان عن المنتج
            // الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

            //هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
            $Item_ID = (isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID'])) ?  intval($_GET['Item_ID']) : 0;



            //يحسب عدد الاسطر في البينات
            $count = checkCount('Item_ID', 'items', 'Item_ID=' . $Item_ID);;
            //لتحقق انا هذا  id موجود
            if ($count > 0) {

                // لاحض الطريقة الثالثة لربط المتغيرات
                $stmt = $con->prepare('UPDATE `items` SET `Approve`=1 WHERE `Item_ID`=?');
                $stmt->execute(array($Item_ID));


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
        include $tpl . 'footer.php';
    } else {
        header('Location:index.php'); // redirect to dashboard page
        exit();
    }
    ob_end_flush();
