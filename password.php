<?php
session_start();
$module="patient";

include "timeout.php";
include "config.php";
$page="password";
$clinic_id = $_SESSION['clinic_id'];
$msg="";
$color="green";

if (isset($_POST['submit'])) {
  $old_password = trim($_POST['old_password']);
  $new_password = trim($_POST['new_password']);
  $confirm_password = trim($_POST['confirm_password']);

  $flag=false;
  $sql = "SELECT * FROM users WHERE id=$clinic_id and password='$old_password'";
  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  while($row = mysqli_fetch_array($result)){
    $flag=true;
  }

  if($flag==false){
    $msg="Invalid old password";
    $color="red";
  }else{
    if($new_password!=$confirm_password) {
      $msg = "Passwords does not match";
      $color = "red";
    }else{
      $stmt = $conn->prepare("update users set password=? where id=?");
      $stmt->bind_param("ss", $new_password, $clinic_id);
      $stmt->execute();
      $stmt->close();
      $msg = "Password changed successfully";
      $color = "green";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en" class="no-js">

<meta http-equi v="content-type" content="text/html;charset=UTF-8"/>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
  <?php include "header.php"; ?>
</head>

<body>

<div id="wrapper">
    <?php include "menu.php"; ?>
    <div id="page-wrapper" class="fixed-navbar ">
        <div class="container-fluid bg-gray">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="login-panel panel panel-default">
        <div class="panel-heading text-center">
          <b>Change Password</b>
          <br><span style="color:<?php echo $color; ?>"><?php echo $msg; ?></span>
        </div>
        <form method="post" action="">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <input type="password" name="old_password" required="required" class="form-control" placeholder="Old Password">
                </div>
                <div class="form-group">
                  <input type="password" name="new_password" required="required" class="form-control" placeholder="New Password">
                </div>
                <div class="form-group">
                  <input type="password" name="confirm_password" required="required" class="form-control" placeholder="Confirm Password">
                </div>
                <div class="col-md-offset-5 col-md-2 text-center">
                  <input required="required" class="btn btn-success"
                         type="submit"
                         name="submit" value="Change"/>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<?php include "footer.php"; ?>
</body>
</html>
