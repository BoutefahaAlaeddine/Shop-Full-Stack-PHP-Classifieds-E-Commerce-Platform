<?php
//مثلا انا حاب ننشى صفحات فرعية من صفحة مش لازم نشئ لكل صفحى ملف
//Categories =>[Manage|Edit|Update|Add|Insert|Delete|Stats]

$do = '';
//هذا الكود يعني انه لو شخص كتب ?do 
if (isset($_GET['do'])) {
    //يستقبل قيمة ال do,ويحطها في متغيير
    $do = $_GET['do'];
} else { // اذا محطش ?do,ابعثو طول لصفحة ال mange
    $do = 'Manage';
}

//اذا كتب do?=Mange;
if ($do == 'Manage') {
    echo 'welcome You Are In Manage Category Page';
    echo '<a href="?do=Add">Add New Category Page +</a>';
}
//اذا كتب do?=Add;
elseif ($do == 'Add') {
    echo 'Welcome You Are In Add Category Page';
}
//اذا كتب do?=blabla;
else {
    echo 'Error There\'s No Page With This Name';
}
