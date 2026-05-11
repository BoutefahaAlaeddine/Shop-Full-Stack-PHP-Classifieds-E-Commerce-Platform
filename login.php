<?php
ob_start();

//هذه صفحة التسجيل
session_start();

//متغيير عنوان الصفحة
$PageTitle = 'Login';



//تحفضلي التسجيل باه كي ندير ريلود لصفحة متدخلينش لل صفحة التسجيل مرة وخداخر
//!ملاحضة مهمة تسمية السيشون لازملك متسميهاش نفس تسميت السيشن نتاع الادمين 
if (isset($_SESSION['user'])) {
  Header('Location: index.php'); //Redirect To index Page 
}

//الملف لي فيه قاع الملفات المستدعات
include 'init.php';

//*Check if User Coming From HTTP Post Request (التاكد ان الشخص دخل لصفحة بااسم المستخدم  والباسورد)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //! هذي راه كيما نتاع صفحة لي تححق من البيانات المرسلة

  if (isset($_POST['login'])) {

    $user = $_POST['username'];
    $pass = $_POST['password'];
    $hashedPass = sha1($pass);

    //Check If The User Exist In Database (التحقق ان هذه البينات موجود في الداتابيس)
    $stmt = $con->prepare("SELECT 
                     UserID ,Username,Password 
                     FROM 
                        users 
                     WHERE 
                         Username= ? 
                     AND 
                        Password= ? 
                 
                       ");
    //هذه لتحقق                       
    $stmt->execute(array($user, $hashedPass));
    // هذه لجلب البيانات
    $row = $stmt->fetch();
    //لحساب عدد الاسطر البنات لي نتحقق فيها
    $count = $stmt->rowCount();

    //If Count >0 This Mean The Database Contain Record About This Username
    if ($count > 0) {
      $_SESSION['user'] = $user; //Register Session Name
      $_SESSION['uid'] = $row['UserID']; //Register Session Name
      //ندخل لصفحة بعد با ندخل الاسم والباسورد
      header('location: index.php'); //Register To Dashboard Page
      exit();
    }
  } else {
    //! هذي راه كيما نتاع صفحة insert

    $formErrors = array();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];

    //* التحقق من سلامة اسم المستخدم

    if (isset($username)) {
      /**  
       * ? هذه نسخدمها في للحماية هذي يقوم بفلترت الاسم من اكواد الاشتمل 
       */
      $filterUser = filter_var($username, FILTER_SANITIZE_STRING);
      if (strlen(($filterUser) < 4)) {
        $formErrors[] = 'Username Must Be Larger Than 4 Charcters';
      }
    }

    //* التحقق من سلامة الباسورد

    if (isset($password) && isset($password2)) {

      //* التحقق ان الباسورد ليس فارغ
      if (empty($password)) {
        $formErrors[] = 'Sorry Password cant be Empty';
      }


      if (sha1($password) !== sha1($password2)) {
        $formErrors[] = 'Sorry Password Is Not Match';
      }
    }
    //*التحقق من ان الايميل سليم الكحلوش  
    if (isset($email)) {
      $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
      if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
        $formErrors[] = 'This Email Is Not Valid';
      }
    }

    if (empty($formErrors)) {

      // دالة التحقق ان اسم المستخدم انه موجود بهدف عدم تكرار اسم المستخدم
      $count = checkCount('username', 'users', $username);

      if ($count > 0) {
        $formErrors[] = "Sorry  username already exists";
        //الدال التي ترجعك الى الصفحة الرئسية بعد 6 ثواني
      } else {

        //اضافة البيانات لداتا
        //قادر تكتبو كيما كتبت الابديت  تدير علامة استفهام
        $stmt = $con->prepare("INSERT INTO 
				                        `users`(`Username`, `Password`, `Email`,`RegStatus`, `Date`)
									 VALUES 
									     (:user,:pass,:email,0,now())");
        $stmt->execute(
          array(
            'user' => $username,
            'pass' => sha1($password),
            'email' => $email,
          )
        );

        //رسالة تنفيذ العملية


        $successMsg = "Congrats You Are Now Registered User";
      }
    }
  }
}
?>


<div class="container login-page">
  <h1 class="text-center">
    <span class="selected" data-class="login">Login</span> |
    <span data-class="signup">Signup</span>
  </h1>
  <!-- Start Login Form -->
  <!-- 
       // ! هذي راه كيما نتاع صفحة Add او edit 
    -->

  <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="input-container">
      <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required />
    </div>
    <div class="input-container">
      <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type your password" required />
    </div>
    <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
  </form>
  <!-- End Login Form -->
  <!-- Start Signup Form 
    <!-- 
    //! هذي راه كيما نتاع صفحة Add او edit
    -->
  <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="input-container">
      <input pattern=".{4,}" title="Username Must Be Between 4 Chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required />
    </div>
    <div class="input-container">
      <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a Complex password" required />
    </div>
    <div class="input-container">
      <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a password again" required />
    </div>
    <div class="input-container">
      <input class="form-control" type="email" name="email" placeholder="Type a Valid email" />
    </div>
    <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
  </form>
  <!-- End Signup Form -->
  <div class="the-message text-center">
    <?php
    if (!empty($formErrors)) {
      foreach ($formErrors as $error) {
        echo  '<div class="msg error">' . $error . '</div>';
      }
    } elseif (isset($successMsg)) {
      echo  '<div class="msg success">' . $successMsg . '</div>';
    }


    ?>
  </div>';

</div>


<?php include  $tpl . 'footer.php';
ob_end_flush();
?>