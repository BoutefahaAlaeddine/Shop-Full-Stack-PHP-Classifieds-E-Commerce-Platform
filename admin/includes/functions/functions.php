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
**v2.0
** هذه الدالة تجلب عدد الاسطر 
**دمج الدالتين checkItem + countItems
*/
function checkCount($select, $table, $condition = null)
{
  global $con;

  $cond = ($condition == null) ? 1 : $condition;


  $stmt = $con->prepare("SELECT $select FROM $table WHERE $cond ");

  $stmt->execute();

  $count = $stmt->rowCount();

  return $count;
}


// /*
// **v2.0
// ** هذه الدالة تجلب عدد الاسطر 
// **دمج الدالتين checkItem + countItems
// */
// function checkCount($select, $table, $value = '')
// {
//   global $con;
//   $selected = ($value == '') ? 1 : $select . '= ?';
//   $arr = ($value == '') ? null : array($value);

//   $stmt = $con->prepare("SELECT $select FROM $table WHERE   $selected ");

//   $stmt->execute($arr);

//   $count = $stmt->rowCount();

//   return $count;
// }

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
//الاصدار 1
//هذه الدالة ترجعك لصفحة الرئسية اذا دخلت لصفحة اخر بلا post بعد مرور ثواني
*/
// function redirectHome($errorMsg, $seconds = 3)
// {
//   echo  "<div class='alert alert-danger'>$errorMsg</div>";
//   echo  "<div class='alert alert-info'>You Be Redirected to Homepage After $seconds Seconds</div>";
//   header(("refresh:$seconds;url=index.php"));
//   exit();
// }
/*
//الاصدار 2
//هذه الدالة ترجعك لصفحة الرئسية اذا دخلت لصفحة اخر بلا post بعد مرور ثواني
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


/*
** Check Items Function v1.0
** Function to Check Item In Database [Function Accept Parameters]
** $select = The Item To Select [ Example: user , item , category]
** $from = The Table To Select From [ Example: users ,items , categories]
** $value = The Value Of Select [Example: alilo, Box, Electronics]
** دالة التحقق ان اسم المستخدم انه موجود (استعملنها في  الاضافة)
**هذه الدالة ترجعلي 1 اذاكان موجود العنصر لي ندور عليه 
*/

// function checkItem($select, $from, $value)
// {
//   //global هذي يمكن بها استدعاء اي متغيير بها
//   global $con;

//   $stmt = $con->prepare("SELECT $select FROM $from WHERE $select =?");

//   $stmt->execute(array($value));

//   $count = $stmt->rowCount();

//   return $count;
// }


/*
**هذه الدالة تقوم بجلب عدد العناصر انطلقا من عدد الاسطر 
*/

// function countItems($item, $table)
// {
//   global $con;

//   $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

//   $stmt2->execute();

//   return $stmt2->fetchColumn();
// }



/*
**v1.0
** دالة تجيبلي اخر حيات دخلتها في الداتا بيس
*/
function getLatest($select, $table, $order, $limit = 5)
{
  global $con;
  $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
  $getStmt->execute();
  $rows = $getStmt->fetchAll();
  return $rows;
}
