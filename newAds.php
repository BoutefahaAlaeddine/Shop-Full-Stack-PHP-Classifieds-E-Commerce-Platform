<?php
session_start();

//الملف لي فيه قاع الملفات المستدعات
$PageTitle = 'Create New Ads';
include 'init.php';


if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $formErrors = array();
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['categories'], FILTER_SANITIZE_NUMBER_INT);
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_NUMBER_INT);

        if (strlen($name) < 4) {
            $formErrors[] = 'Item Title Must Be At least 4 Characters';
        }
        if (strlen($desc) < 10) {
            $formErrors[] = 'Item Description Must Be At least 10 Characters';
        }
        if (strlen($country) < 2) {
            $formErrors[] = 'Item country Must Be At least 2 Characters';
        }
        if (empty($price)) {
            $formErrors[] = 'Item Price Must Be Not Empty';
        }



        if (empty($formErrors)) {


            //اضافة البيانات لداتا
            //قادر تكتبو كيما كتبت الابديت  تدير علامة استفهام
            $stmt = $con->prepare("INSERT INTO 
				                        `items`(`Name`, `Description`, `Price`,`Add_Date`, `Country_Made`,`Status`,`Member_ID`,`Cat_ID`,`tags`)
									 VALUES 
									     (:name,:desc,:price,now(),:country,:status,:member,:categories,tags)");
            $stmt->execute(
                array(
                    'name' => $name,
                    'desc' => $desc,
                    'price' => $price,
                    'country' => $country,
                    'status' => $status,
                    'member' => $_SESSION['uid'],
                    'categories' => $category,
                    'tags' => $tags

                )
            );

            //رسالة تنفيذ العملية

            if ($stmt) {
                $successMsg = 'Item  Has Been Added';
            }
        }
    }



?>
    <h1 class="text-center"><?php $PageTitle ?></h1>



    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php $PageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input pattern=".{4,}" title="This Field Require At Least 4 Characters" type="text" name="name" class="form-control live" autocomplete="off" required="required" placeholder="Name Of The Category" data-class=".live-name" />
                                    </div>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Description Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input pattern=".{10,}" title="This Field Require At Least 10 Characters" type="text" name="description" class="form-control live" placeholder="Description The Item" required="required" data-class=".live-desc" />
                                    </div>
                                </div>
                                <!-- End Description Field -->
                                <!-- Start Price Field Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="price" class="form-control live" placeholder="Price Of The Item" required="required" data-class=".live-price" />
                                    </div>
                                </div>
                                <!-- End Price Field -->
                                <!-- Start Country Field Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="country" class="form-control " placeholder="Country Of The Made" required="required" />
                                    </div>
                                </div>
                                <!-- End Country Field -->
                                <!-- Start Status Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status" required>
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Very Old</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Status Field -->

                                <!-- Start Categories Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Categories</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="categories" required>
                                            <option value="">...</option>
                                            <?php

                                            $cats = getAllFrom('*', 'categories', 'parent=0', 'ID');
                                            foreach ($cats as $cat) {
                                                echo "<option value=' " . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Categories Field -->
                                <!-- Start Tags Field Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
                                    </div>
                                </div>
                                <!-- End Tags Field -->
                                <!-- Start Submit Field -->
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                                    </div>
                                </div>
                                <!-- End Submit Field -->

                            </form>

                        </div>
                        <div class='col-md-4'>
                            <div class='thumbnail item-box  live-preview'>
                                <span class='price-tag'>
                                    $ <span class="live-price">0</span>

                                </span>
                                <img class='img-responsive' src='img.png' alt=''>
                                <div class='caption'>
                                    <h3 class="live-name">title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    //* Star Looping errors
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    } elseif (isset($successMsg)) {
                        echo  '<div class="alert alert-success">' . $successMsg . '</div>';
                    }

                    //* End  Looping errors
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php }
include  $tpl . 'footer.php';
?>