<?php
ob_start();

session_start();
$PageTitle = 'TagsPage';

//الملف لي فيه قاع الملفات المستدعات
include 'init.php';
?>
<div class="container">

    <?php
    if (isset($_GET["name"])) {
        echo  "<h1 class='text-center'>" . $_GET["name"] . "</h1>";

        foreach (getAllFrom('*', 'items', "Approve = 1 AND tags LIKE '%" . $_GET["name"] . "%'", 'Item_ID') as $item) {

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
    }
    ?>
</div>

<?php include  $tpl . 'footer.php'; ?>