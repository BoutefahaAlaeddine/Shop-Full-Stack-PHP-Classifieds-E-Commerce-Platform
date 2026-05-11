<?php

/*
** الاصدار 3
** دالة جلب الاصناف 
*/
function getAllFrom($filed, $table, $condition, $orderField, $ordering = "DESC", $limit = 100000)
{
  $cond = ($condition == null) ? 1 : $condition;

  global $con;
  $stmt2 = $con->prepare("SELECT $filed FROM $table WHERE   $cond ORDER BY $orderField $ordering LIMIT  $limit");
  $stmt2->execute();
  return $stmt2->fetchAll();
}
/*

/*
**v2.0
** هذه الدالة تجلب عدد الاسطر 
**دمج الدالتين checkItem + countItems
*/
function checkCount($select, $table, $condition)
{
  global $con;
  $cond = ($condition == null) ? 1 : $condition;


  $stmt = $con->prepare("SELECT $select FROM $table WHERE    $cond  ");

  $stmt->execute();

  $count = $stmt->rowCount();

  return $count;
}





/*
**الاصدار 1.1
**دالة تحقق ان هذا الشخص ادمين ولا والو
** تتحقق عن طريق الحالة نتاعو
*/



function checkUserStatus($user)
{
  global $con;
  $stmtx = $con->prepare("SELECT 
                      Username,RegStatus 
                     FROM 
                        users 
                     WHERE 
                         Username= ? 
                     AND 
                        RegStatus= 0
                 
                       ");
  //هذه لتحقق                       
  $stmtx->execute(array($user));

  //لحساب عدد الاسطر البنات لي نتحقق فيها
  $status = $stmtx->rowCount();
  return $status;
}






/*
** Title Function That Echo The Page Title in Case The Page
** Has The Variable $PageTitle And Echo Defult Title For Other Pages
*/

/*
//الاصدار1.0 
//دالة تغيير عنوان صحفات تلقائيا
*/
function getTitle()
{
  global $PageTitle;

  if (isset($PageTitle)) {

    echo $PageTitle;
  } else {

    //في لا يوجد عنوان للمتغير عنوان حطو 
    echo 'Default';
  }
}

/*
الاصدار 2
هذه الدالة ترجعك لصفحة الرئسية اذا دخلت لصفحة اخر بلا post بعد مرور ثواني
** هذه دالة الرسالة اما خطا او نجاح التنفيذ او استعلام
*/
function redirectHome($theMsg, $url = null, $seconds = 3)
{
  //اذاكان مخليها فارغة رجعو لصفحة الرئسية
  if ($url === null) {

    $url = 'index.php';

    $link = 'Homepage';
  } else {    //اذا كانت معمرة لكن قالط في الكتيبة ترجعني لصفحة لي قبلها
    // اذاكان كاين الصفحة لي ترجعلهارجعو
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

      //متغيرات الصفحة لي قبلها
      $url = $_SERVER['HTTP_REFERER'];

      $link = 'Previous Page';
    } else {

      //اذا كان  مكانش الصفحة
      $url = 'index.php';

      $link = 'Homepage';
    }
  }
  //هذا متغيير الرسالة
  echo  $theMsg;

  echo  "<div class='alert alert-info'>You Be Redirected to $link After $seconds Seconds</div>";

  header(("refresh:$seconds;url=$url"));

  exit();
}
