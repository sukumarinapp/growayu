<?php
session_start();
$module="patient";

include "timeout.php";
include "config.php";
$page = "settings";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$msg = "";
$msg_color = "";

if (isset($_POST['submit'])) {
    $clinic_name = trim($_POST['clinic_name']);
    $mobile = trim($_POST['mobile']);
    $address_line1 = trim($_POST['address_line1']);
    $address_line2 = trim($_POST['address_line2']);
    $address_line3 = trim($_POST['address_line3']);
    $pincode = trim($_POST['pincode']);
    $domain_name = trim($_POST['website']);

    $stmt = $conn->prepare("UPDATE users set domain_name=?,clinic_name=?,mobile=?,address_line1=?,address_line2=?,address_line3=?,pincode=? where id=?");
    $stmt->bind_param("ssssssss", $domain_name,$clinic_name,$mobile,$address_line1,$address_line2,$address_line3,$pincode,$clinic_id);
    $stmt->execute();

    $logo = $_FILES['logo']['name'];
    if (trim($logo) != "") {
        $ext = pathinfo($logo, PATHINFO_EXTENSION);
        $logo = $clinic_id . "." . $ext;
        $target_path = "logo/";
        $target_path = $target_path . $logo;
        move_uploaded_file($_FILES['logo']['tmp_name'], $target_path);
        $query = "update users set logo = '" . $logo . "' where id=$clinic_id";
        mysqli_query($conn, $query);
    }

    session_unset();
    session_destroy();
    header("location: index.php");
}
$sql = "SELECT * FROM users where id = $clinic_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

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
</head>

<body>

<div id="wrapper">
    <?php include "menu.php"; ?>
    <div id="page-wrapper" class="fixed-navbar ">
        <div class="container-fluid bg-gray">
            <div class="row" style="margin:0;">
                <div class="col-md-12">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading text-center">
                                <b>Settings</b>
                            <br><span style="color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form onsubmit="return CheckFileName()"  method="post" action="" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="clinic_name"
                                                   class="control-label required">Name</label>
                                            <input value="<?php echo $row['clinic_name']; ?>" required="required" type="text"
                                                   maxlength="50"
                                                   name="clinic_name" id="clinic_name" class="form-control"
                                                   placeholder="Clinic Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="website" class="control-label"><a target="_blank" href="web.php">Website</a>
                                            </label>
                                            <input value="<?php echo $row['domain_name']; ?>" type="text"
                                                   maxlength="200"
                                                   name="website" id="website" class="form-control"
                                                   placeholder="Website">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="mobile"
                                                   class="control-label required">Mobile</label>
                                            <input value="<?php echo $row['mobile']; ?>" required="required" type="text"
                                                   maxlength="50"
                                                   name="mobile" id="mobile" class="form-control"
                                                   placeholder="Mobile">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address_line1"
                                                   class="control-label">Address Line 1</label>
                                            <input value="<?php echo $row['address_line1']; ?>" type="text"
                                                   maxlength="50"
                                                   name="address_line1" id="address_line1" class="form-control"
                                                   placeholder="Address Line 1">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address_line2"
                                                   class="control-label">Address Line 2</label>
                                            <input value="<?php echo $row['address_line2']; ?>" type="text"
                                                   maxlength="50"
                                                   name="address_line2" id="address_line2" class="form-control"
                                                   placeholder="Address Line 2">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address_line1"
                                                   class="control-label">Address Line 3</label>
                                            <input value="<?php echo $row['address_line3']; ?>"  type="text"
                                                   maxlength="50"
                                                   name="address_line3" id="address_line3" class="form-control"
                                                   placeholder="Address Line 3">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="pincode"
                                                   class="control-label">Pincode</label>
                                            <input value="<?php echo $row['pincode']; ?>" type="text"
                                                   maxlength="50"
                                                   name="pincode" id="pincode" class="form-control"
                                                   placeholder="Pincode">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="control-label">Logo (jpg or png)</label>
                                            <input id="file_upload"  name="logo" class="form-control" type="file">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <img src="logo/<?php echo $row['logo']; ?>?<?php echo rand(); ?>"
                                                 width="200" height="150" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <input required="required" class="btn btn-success"
                                               type="submit"
                                               name="submit" value="Save"/>
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
<script>
    function CheckFileName() {

        var fileName = document.getElementById("file_upload").value.trim();

        if (fileName.split(".")[1].toUpperCase() != "PNG" && fileName.split(".")[1].toUpperCase() != "JPG" )
        {
            alert("Please select a jpg or png file as photo");
            return false;
        }
        else
        {
            return true;
        }
    }
</script>
</body>
</html>