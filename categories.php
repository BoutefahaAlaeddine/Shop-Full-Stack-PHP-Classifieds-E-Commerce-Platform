<?php
ob_start();

session_start();
$PageTitle = 'Categories';

//الملف لي فيه قاع الملفات المستدعات
include 'init.php';
?>
<div class="container">

    <h1 class="text-center">Category page</h1>
    <?php
    $catId = (isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) ? intval($_GET['cat_id']) : 0;

    $items = getAllFrom('*', 'items', 'Cat_ID =' .  $catId . ' AND Approve = 1', 'Item_ID');
    if (!empty($items)) {
        foreach ($items as $item) {
            echo  "<div class='col-sm-6 col-md-3'>";
            echo  "<div class='thumbnail item-box'>";
            echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
            echo  "<img class='img-responsive' src='img.png' alt=''>";
            echo  "<div class='caption'>";

            echo "<h3><a href='item.php?Item_ID=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h3>";
            echo  "<p>" . $item['Description'] . "</p>";
            echo "<div class='date'>" . $item['Add_Date'] . "</div>";

            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='container'>";

        $errorMsg = "<div class='alert alert-danger'>There\'s no Such ID </div>";
        redirectHome($errorMsg, 'back');
        echo "</div>";
    }

    ?>
</div>

<?php include  $tpl . 'footer.php'; ?>