<?php
ob_start();

//هذه صفحة التسجيل
session_start();

//متغيير عنوان الصفحة
$PageTitle = 'Login';

//هذا المتغيرر لي يبين بلي هذي الصفحة مفيهاش navbar
$noNavbar = '';

//تحفضلي التسجيل باه كي ندير ريلود لصفحة متدخلينش لل صفحة التسجيل مرة وخداخر
if (isset($_SESSION['username'])) {
   Header('Location: dashboard.php'); //Redirect To Dashboard Page
}

//الملف لي فيه قاع الملفات المستدعات
include 'init.php';

//Check if User Coming From HTTP Post Request (التاكد ان الشخص دخل لصفحة بااسم المستخدم  والباسورد)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $username = $_POST['user'];
   $password = $_POST['pass'];
   $hashedPass = sha1($password);

   //Check If The User Exist In Database (التحقق ان هذه البينات موجود في الداتابيس)
   $stmt = $con->prepare("SELECT 
                      UserID,Username,Password 
                     FROM 
                        users 
                     WHERE 
                         Username= ? 
                     AND 
                        Password= ? 
                    ANd 
                       GroupID = 1
                    LIMIT 1   
                       ");
   //هذه لتحقق                       
   $stmt->execute(array($username, $hashedPass));
   // هذه لجلب البيانات
   $row = $stmt->fetch();
   //لحساب عدد الاسطر البنات لي نتحقق فيها
   $count = $stmt->rowCount();

   //If Count >0 This Mean The Database Contain Record About This Username
   if ($count > 0) {
      $_SESSION['username'] = $username; //Register Session Name
      $_SESSION['ID'] = $row['UserID']; //Register Session ID
      //ندخل لصفحة بعد با ندخل الاسم والباسورد
      header('location: dashboard.php'); //Register To Dashboard Page
      exit();
   }
}
?>


<form class='login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST'>
   <h4 class='text-center'>Admin Login</h4>
   <input class='form-control' type="text" name="user" placeholder='Username' autocomplete='off'>
   <input class='form-control' type="password" name="pass" placeholder='Password' autocomplete='new-password'>
   <input class='btn btn-primary btn-primary btn-block' type="submit" value='Login' />
</form>


<?php
include  $tpl . 'footer.php';

ob_end_flush();
?>