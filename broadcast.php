<?php
/*
update users set modules = concat(modules,",broadcast,holiday") where id=1
*/
session_start();
$module="broadcast";

include "timeout.php";
include "config.php";
$clinic_id=$_SESSION['clinic_id'];

$page = "broadcast";
$msg = "";
$msg_color = "red";

if (isset($_POST['submit'])) {
    $message = trim($_POST['message']);
    foreach ($_POST['patient'] as $key => $mobile) {
       if(trim($mobile)!=""){
           send_sms($message,$mobile);  
       }
    }
    $msg = "Message sent successfully";
    $msg_color = "green";
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
    <?php include "header.php"; ?>
    <style>
        .table-responsive {
            overflow-x: inherit;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include "menu.php"; ?>
    <div id="page-wrapper" class="fixed-navbar ">
        <div class="container-fluid bg-gray">
            <div class="row" style="margin:0;">
                <div class="col-md-12">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <b style="color: steelblue;text-transform: capitalize">Broadcast</b>
                            <br><span style="text-transform: capitalize;color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="message" class="control-label required">Message</label>
                                            <textarea required="required" maxlength="200" 
                                                   name="message" id="message" class="form-control"
                                                   placeholder="Message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                    <ul>
                                    <?php
                                    $sql="select * from patients";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <li>
                                        <label for="pat_<?php echo $row['id']; ?>" class="radio-inline"><input type="checkbox" name="patient[]" id="pat_<?php echo $row['id']; ?>" value="<?php echo $row['mobile']; ?>" class="sq-form-field" /><?php echo $row['full_name']; ?></label>
                                    </li>
                                    <?php
                                    }
                                    ?>  
                                    </ul>  
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <input required="required" class="btn btn-success"
                                                   type="submit"
                                                   name="submit" value="SEND"/>
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