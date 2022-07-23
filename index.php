<?php
session_start();
//patient,appointment,visit,prescription,billing
include "config.php";
$error = "";

$id = 0;
$logo ='<img src="logo/hctsol_logo.png" style="padding:10px; height:8rem;" />';

if (isset($_POST['submit'])) {
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users where user_name='$user_name' and password='$password' and status='Active'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if ($count >= 1) {
        $_SESSION['timestamp'] = time();
        $user_clinic_id = $row['user_clinic_id'];
        $_SESSION['clinic_id'] = $user_clinic_id;
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['modules'] = explode(",",$row['modules']);

        $sql2 = "SELECT * FROM users where id=$user_clinic_id";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_array($result2);

        $_SESSION['clinic_name'] = $row2['clinic_name'];

        $_SESSION['mobile'] = $row2['mobile'];
        $_SESSION['address_line1'] = $row2['address_line1'];
        $_SESSION['address_line2'] = $row2['address_line2'];
        $_SESSION['address_line3'] = $row2['address_line3'];
        $_SESSION['pincode'] = $row2['pincode'];
        $_SESSION['land_line'] = $row2['land_line'];
        $_SESSION['logo'] = $row2['logo'];
        $_SESSION['bill_template'] = $row2['bill_template'];
        $_SESSION['examination_template'] = $row2['examination_template'];
        $_SESSION['domain_name'] = $row2['domain_name'];
        $_SESSION['clinic_token'] = $row2['clinic_token'];
        $_SESSION['max_patient'] = $row2['max_patient'];
        $_SESSION['speciality'] = $row2['speciality'];
        if($_SESSION['clinic_id']==-1){
            header("location: customers.php");
        }else{
            header("location: patients.php");
        }
    } else {
        $error = "Your User Name or Password is invalid";
    }
}
?>


<!DOCTYPE html>
<html lang="en" class="no-js">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>

    <title>Login</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/icons/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/styles/default.css" type="text/css" rel="stylesheet" id="style_color"/>


</head>

<body>


<div style="padding-top:10%">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-body">
                    <form role="form" method="post">
                       <div align="center"> <?php echo $logo; ?></div>
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control"  maxlength="50" placeholder="User Name" name="user_name" type="text"
                                       autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" maxlength="50" placeholder="Password" name="password"
                                       type="password" >
                            </div>
                            <div class="form-group">
                                <input class="btn btn-outline btn-info btn-block" type="submit"
                                       name="submit" value="Login"/>
                            </div>
                        </fieldset>
                        <hr>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="assets/js/jquery-2.1.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>


</body>


</html>
