<?php
/*
======================================
=== Mange Members Page
=== You Can Add | Edit |Delete Members From Here
=========================================
*/

ob_start();

session_start();


//اذا راه داخل لصفحة باسم المستخد والرقم السري  خليه داخل
if (isset($_SESSION['username'])) {
	$PageTitle = 'Members';

	include 'init.php';

	//هذا الكود يعني انه لو شخص كتب ?do 
	//يستقبل قيمة ال do,ويحطها في متغيير
	// اذا محطش ?do,ابعثو طول لصفحة ال mange
	$do = isset($_GET['do']) ? $_GET['do'] : 'Mange';

	//اذا كتب do?=Mange;
	//star Manage Page
	if ($do == 'Mange') {
		$query = '';
		if (isset($_GET['Page']) and $_GET['Page'] == 'Pending') {
			$query = 'AND RegStatus = 0';
		}
		//Select All Users Except Admin
		$stmt = $con->prepare("SELECT * FROM users WHERE GroupID !=1 $query ORDER BY UserID DESC ");
		//Execute The Statement
		$stmt->execute();
		//Assign To Variable
		$members = $stmt->fetchAll();
		if (!empty($members)) {
?>
			<h1 class="text-center">Add New Member</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Avatar</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registered Date</td>
							<td>Control</td>
						</tr>
						<?php
						foreach ($members as $member) {
							echo '<tr>';
							echo '<td>' . $member['UserID'] . '</td>';
							echo '<td>';
							if (empty($member['avatar'])) {
								echo 'No Image';
							} else {
								echo "<img src='uploads/avatars/" . $member['avatar'] . "' alt='' />";
							}

							echo '</td>';
							echo '<td>' . $member['Username'] . '</td>';
							echo '<td>' . $member['Email'] . '</td>';
							echo '<td>' . $member['FullName'] . '</td>';
							echo '<td>' . $member['Date'] . '</td>';
							echo "<td>
						       <a href='members.php?do=Edit&userid=" . $member['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
						       <a href='members.php?do=Delete&userid=" . $member['UserID'] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete</a>";
							if ($member['RegStatus'] == 0) {
								//هذي نتاع تفعيل شخص كادمين لصفحة
								echo " <a href='members.php?do=Active&userid=" . $member['UserID'] . "' class='btn btn-info'> <i class='fa fa-check'></i> Active</a>";
							}
							"</td>";
							echo '</tr>';
						}
						?>

					</table>
				</div>
				<a href="members.php?do=Add" class='btn btn-primary'><i class='fa fa-plus'></i> Add New Member</a>

			</div>
		<?php
		} else {
			echo '<div class="container">';
			echo '<div class="nice-message">There\'s No Members To Show</div>';
			echo "<a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> Add New Member</a>";
			echo '</div>';
		}
	} elseif ($do == 'Add') {
		//Add Page
		?> <h1 class="text-center">Add New Member</h1>
		<div class="container">
			<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
				<!-- Start Username Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop" />
					</div>
				</div>
				<!-- End Username Field -->
				<!-- Start Password Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Password</label>
					<div class="col-sm-10 col-md-6">
						<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
						<i class="show-pass fa fa-eye fa-2x"></i>
					</div>
				</div>
				<!-- End Password Field -->
				<!-- Start Email Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10 col-md-6">
						<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
					</div>
				</div>
				<!-- End Email Field -->
				<!-- Start Full Name Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Full Name</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
					</div>
				</div>
				<!-- End Full Name Field -->
				<!-- Start Avatar -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">َUser Avatar</label>
					<div class="col-sm-10 col-md-6">
						<input type="file" name="avatar" class="form-control" required="required" />
					</div>
				</div>
				<!-- End Avatar Field -->
				<!-- Start Submit Field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
					</div>
				</div>
				<!-- End Submit Field -->

			</form>
		</div>
		<?php
	} elseif ($do == 'Insert') {
		//Insert Page

		//لك يمكن جلب بيانات الصفحة الى اذا بعثتهم ب post

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo "<h1 class='text-center'>Insert Member</h1>";
			echo "<div class='container'>";

			//upload variables
			$avatar = $_FILES['avatar'];
			$avatarName = $avatar['name'];
			$avatarSize = $avatar['size'];
			$avatarTmp = $avatar['tmp_name'];
			//List of Allowed File Typed To Upload
			$avatarAllowedExtension = array('jpeg', 'jpg', 'png', 'gif');

			$avatarExtension = explode('.', $avatarName);
			$length = count($avatarExtension);
			//جلب البيانات لدخلت دا خل المداخل 
			$user = $_POST['username'];
			$email = $_POST['email'];
			$name = $_POST['full'];
			$pass =  $_POST['password'];
			$hashPass =  sha1($_POST['password']);

			//التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
			$formErrors = array();
			if (strlen($user) < 4) {
				$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
			}
			if (strlen($user) > 20) {
				$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
			}
			if (empty($user)) {
				$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
			}
			if (empty($name)) {
				$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}
			if (empty($email)) {
				$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
			}
			if (empty($pass)) {
				$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
			}
			if (!empty($avatarName) && !in_array($avatarExtension[$length - 1], $avatarAllowedExtension)) {
				$formErrors[] = 'This Extension Is Not <strong>َAllowed</strong>';
			}
			if (empty($avatarName)) {
				$formErrors[] = 'Avatar Is<strong>Required</strong>';
			}

			if ($avatarSize > 4194304) {
				$formErrors[] = 'Avatar Cant Be Lager Than<strong>4MB</strong>';
			}

			foreach ($formErrors as $error) {
				$errorMsg = '<div class="alert alert-danger">' . $error . '</div>';
				redirectHome($errorMsg, 'back');
			}

			if (empty($formErrors)) {

				$avatar = rand(0, 10000000) . '_' . $avatarName;
				move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

				// دالة التحقق ان اسم المستخدم انه موجود بهدف عدم تكرار اسم المستخدم

				$stmt = $con->prepare("SELECT Username FROM users WHERE Username= ?");

				$stmt->execute(array($user));

				$count = $stmt->rowCount();

				if ($count > 0) {
					$errorMsg = "<div class='alert alert-danger'>This username already exists,Please enter another username</div>";
					//الدال التي ترجعك الى الصفحة الرئسية بعد 6 ثواني
					redirectHome($errorMsg, 'back', 6);
				} else {

					//اضافة البيانات لداتا
					//قادر تكتبو كيما كتبت الابديت  تدير علامة استفهام
					$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, RegStatus, Date, avatar)
												VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
					$stmt->execute(array(

						'zuser' 	=> $user,
						'zpass' 	=> $hashPass,
						'zmail' 	=> $email,
						'zname' 	=> $name,
						'zavatar'	=> $avatar

					));
					//رسالة تنفيذ العملية


					$successMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Add</div>';
					redirectHome($successMsg, 'back');
				}
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
	}

	//اذا كتب do?=Edit;
	elseif ($do == 'Edit') {
		//Edit Page
		// الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها


		//هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
		$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ?  intval($_GET['userid']) : 0;

		$stmt = $con->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1');
		//هذه لتحقق                       
		$stmt->execute(array($userid));
		//هذا لجلب بينات عى شكل مصفوفة
		$row = $stmt->fetch();
		//يحسب عدد الاسطر في البينات
		$count = $stmt->rowCount();
		//لتحقق انا هذا  id موجود
		if ($count > 0) {
		?>
			<h1 class="text-center">Edit Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="userid" value="<?php echo $userid ?>" />
					<!-- Start Username Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
						</div>
					</div>
					<!-- End Username Field -->
					<!-- Start Password Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
							<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
						</div>
					</div>
					<!-- End Password Field -->
					<!-- Start Email Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
						</div>
					</div>
					<!-- End Email Field -->
					<!-- Start Full Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
						</div>
					</div>
					<!-- End Full Name Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Save" class="btn btn-primary btn-lg" />
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
	}
	//اذا كنت دخلت بهذي الطريقة ?update
	elseif ($do == 'Update') {

		echo "<h1 class='text-center'>Update Member</h1>";
		echo "<div class='container'>";

		//لك يمكن جلب بيانات الصفحة الى اذا بعثتهم ب post
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			//جلب البيانات لدخلت دا خل المداخل 
			$id = $_POST['userid'];
			$user = $_POST['username'];
			$email = $_POST['email'];
			$name = $_POST['full'];

			//بيانات الرقم السري
			$pass = (empty($_POST['newpassword'])) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

			//التحقق من انك مدخل البيانات مكش مخليها فارغة هذا التحقق في backend في حالة لوصرا مشكل في التحقق نتاع forntend
			$formErrors = array();
			if (strlen($user) < 4) {
				$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
			}
			if (strlen($user) > 20) {
				$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
			}
			if (empty($user)) {
				$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
			}
			if (empty($name)) {
				$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}
			if (empty($email)) {
				$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
			}

			foreach ($formErrors as $error) {
				echo '<div class="alert alert-danger">' . $error . '</div>';
			}
			if (empty($formErrors)) {

				$stmt2 = $con->prepare(
					"SELECT * FROM users WHERE Username=? AND UserID !=?"
				);
				$stmt2->execute(array($user, $id));
				$count = $stmt2->rowCount();
				if ($count == 1) {

					$errorMsg = "<div class='alert alert-danger'>Sorry This username already exists,Please enter another username</div>";
					redirectHome($errorMsg);
				} else {

					//تحديث البيانات في الداتا بيس 
					$stmt = $con->prepare(
						"UPDATE 
				                          `users`
									   SET 
									     `Username`=?,`Email`=?,`FullName`=?,`Password`=? 
									   WHERE  
									   `UserID`=?"
					);
					$stmt->execute(array($user, $email, $name, $pass, $id));

					//رسالة تنفيذ العملية
					$successMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($successMsg, 'back');
				}
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
	} elseif ($do == 'Delete') {
		//Delete Members Page
		// الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

		echo "<h1 class='text-center'>Delete Member</h1>";
		echo "<div class='container'>";

		//هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
		$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ?  intval($_GET['userid']) : 0;



		//يحسب عدد الاسطر في البينات
		$count = checkCount('userid', 'users', 'userid=' . $userid);;
		//لتحقق انا هذا  id موجود
		if ($count > 0) {

			//لاحض الطريقة الثالثة لربط المتغيرات
			$stmt = $con->prepare('DELETE FROM `users` WHERE UserID = :id');
			$stmt->bindParam(':id', $userid);
			$stmt->execute();


			$successMsg =	"<div class='alert alert-success'>" . 	$count  . ' Record Deleted</div>';
			redirectHome($successMsg, 'back');
		} else {
			echo "<div class='container'>";

			$errorMsg = "<div class='alert alert-danger'>This ID Not Exist</div>";

			redirectHome($errorMsg);

			echo "</div>";
		}
		echo "</div>";
	} elseif ($do == 'Active') {

		//Active Members Page
		// الهدف من اليوزر ايدي هو لجب كل البيناتي من الداتا بيس والتعديل عليها

		echo "<h1 class='text-center'>Active Member</h1>";
		echo "<div class='container'>";

		//هذه التحقق من ان التي ادخلتها في الرابط رقم وليس حرف
		$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ?  intval($_GET['userid']) : 0;



		//يحسب عدد الاسطر في البينات
		$count = checkCount('userid', 'users', 'userid=' . $userid);;
		//لتحقق انا هذا  id موجود
		if ($count > 0) {
			//لاحض الطريقة الثالثة لربط المتغيرات
			$stmt = $con->prepare('UPDATE `users` SET `RegStatus`=1 WHERE `UserID`=?');
			$stmt->execute(array($userid));


			$successMsg =	"<div class='alert alert-success'>" . 	$count  . ' Record Active</div>';
			redirectHome($successMsg, 'back');
		} else {
			echo "<div class='container'>";

			$errorMsg = "<div class='alert alert-danger'>This ID Not Exist</div>";

			redirectHome($errorMsg);

			echo "</div>";
		}
		echo "</div>";
	}



	include  $tpl . 'footer.php';
} else {
	//هذي نديروها للحماية باه لي دايدخل مباشرة لهذي الصفحة بدون مايسجل ترجعو لصفحة التسجيل
	header('location:index.php');
	exit();
}
ob_end_flush();
?>