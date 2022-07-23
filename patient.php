<?php
/*
ALTER TABLE `patients` ADD `nationality` VARCHAR(50) NULL AFTER `blood_group`;
ALTER TABLE `services` ADD `nationality` VARCHAR(50) NULL AFTER `description`;
update services set nationality='Indian';
update patients set nationality='Indian';

ALTER TABLE `services` ADD `service_type` VARCHAR(50) NULL AFTER `description`;
update services set service_type='Diagnosis';
ALTER TABLE `services` ADD `code` VARCHAR(50) NULL AFTER `description`;
ALTER TABLE `services` ADD `doctor_id` int(11) NULL AFTER `description`;

update users set modules='patient,appointment,visit,prescription,billing,user,delete_bill,services' where id=1

*/

session_start();
$page="patient";
$module="patient";
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id=$_SESSION['clinic_id'];
$max_patient=$_SESSION['max_patient'];

$sql = "select * from users where id=$clinic_id ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$max_patient=$row['max_patient'];

$patient_count = 0;
$sql = "select * from patients where clinic_id=$clinic_id ";
$result = mysqli_query($conn, $sql);
$patient_count = mysqli_num_rows($result);

$sql5 = "SELECT max(id) as max_id from patients where clinic_id=$clinic_id";
$result5 = mysqli_query($conn, $sql5);
$max_id = 1;
while($row5 = mysqli_fetch_assoc($result5)){
    $max_id = $row5['max_id'];
}
$max_id = str_pad($max_id, 5, "0",STR_PAD_LEFT);

$row['full_name'] = "";
$row['age'] = "";
$row['sex'] = "Male";
$row['address'] = "";
$row['mobile'] = "";
$row['card_no'] = "";
$row['height'] = "";
$row['weight'] = "";
$row['bmi'] = "";
$row['blood_group'] = "";
$row['nationality'] = "Indian";
$msg = "";
$msg_color = "red";

$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

if($mode == "edit"){
    $sql = "SELECT * FROM patients where id = $id and clinic_id=$clinic_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $max_id = $row['card_no'];
}

if($mode == "delete"){
    $stmt = $conn->prepare("DELETE from patients where id=?  and clinic_id=?");
    $stmt->bind_param("ss", $id,$clinic_id);
    $stmt->execute();
    header("location: patients.php");
}

if (isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $card_no = $_POST['card_no'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $bmi = $_POST['bmi'];
    $blood_group = $_POST['blood_group'];
    $nationality = $_POST['nationality'];   


    if($mode == "add"){
        $stmt = $conn->prepare("INSERT INTO patients (clinic_id,full_name,age,sex,address,mobile,card_no,height,weight,bmi,blood_group,nationality) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssssss",$clinic_id,  $full_name, $age, $sex, $address, $mobile, $card_no,$height,$weight,$bmi,$blood_group,$nationality);
        $stmt->execute();
        header("location: patients.php");
    }else if($mode== "edit") {
        $stmt = $conn->prepare("UPDATE patients set full_name=?,age=?,sex=?,address=?,mobile=?,card_no=?,height=?,weight=?,bmi=?,blood_group=?,nationality=? where id=? and clinic_id=?");
        $stmt->bind_param("sssssssssssss", $full_name, $age, $sex, $address, $mobile, $card_no,$height,$weight,$bmi,$blood_group,$nationality, $id,$clinic_id);
        $stmt->execute();
        header("location: patients.php");
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
                            <?php if($mode == "edit"){; ?>
                                <b>Edit Patient</b>
                            <?php }else{ ?>
                                <b>New Patient</b>
                            <?php } ?>
                            <br><span style="color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="full_name"
                                                   class="control-label required">Name</label>
                                            <input required="required" type="text"
                                                   maxlength="50"
                                                   value="<?php echo $row['full_name']; ?>" name="full_name" id="full_name"
                                                   class="form-control"
                                                   placeholder="Full Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="age" class="control-label required Number">Age</label>
                                            <input value="<?php echo $row['age']; ?>" required="required" type="numeric"
                                                   pattern="\d*" maxlength="3"
                                                   name="age" class="form-control Number" placeholder="Age">
                                        </div>
                                        <div class="form-group">
                                            <label for="card_no" class="control-label required Number">OP No</label>
                                            <input readonly value="<?php echo $max_id; ?>" required="required"
                                                   maxlength="10" name="card_no" class="form-control" placeholder="OP No">
                                        </div>
                                        <div class="form-group">
                                            <label for="height" class="control-label Number">Height in cm</label>
                                            <input value="<?php echo $row['height']; ?>" type="numeric" pattern="\d*" maxlength="3" name="height" id="height" class="form-control Number" placeholder="Height in cm" />
                                        </div>
                                        <div class="form-group">
                                            <label for="bmi" class="control-label">BMI</label>
                                            <input readonly value="<?php echo $row['bmi']; ?>" name="bmi" id="bmi" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="nationality" class="control-label">Nationality</label>
                                            <select  name="nationality" id="nationality" class="form-control" >
                                                <option <?php if($row['nationality']=="Indian") echo " selected "; ?> value="Indian">Indian</option>
                                                <option <?php if($row['nationality']=="NRI") echo " selected "; ?> value="NRI">NRI</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile" class="control-label required">Mobile</label>
                                            <input required="required" type="text" maxlength="20"
                                                   value="<?php echo $row['mobile']; ?>" name="mobile" id="mobile"
                                                   class="form-control"
                                                   placeholder="Mobile">
                                        </div>
                                        <div class="form-group">
                                            <label for="sex" class="control-label">Gender</label>

                                            <div class="form-control">
                                                <input <?php if ($row['sex'] == "Male") echo " checked='checked' "; ?>
                                                    class="form-group" value="Male" type="radio" name="sex"
                                                    id="sex">Male
                                                <input <?php if ($row['sex'] == "Female") echo " checked='checked' "; ?>
                                                    class="form-group" value="Female" type="radio" name="sex"
                                                    id="sex">Female
                                                <input <?php if ($row['sex'] == "Trans Gender") echo " checked='checked' "; ?>
                                                    class="form-group" value="Trans Gender" type="radio" name="sex"
                                                    id="sex">Trans Gender
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="control-label">Address</label>
                                            <textarea maxlength="500" rows="1" name="address" id="address" class="form-control" placeholder="Address"><?php echo $row['address']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="weight" class="control-label">Weight in kg</label>
                                            <input value="<?php echo $row['weight']; ?>"
                                                   pattern="\d*" maxlength="3" name="weight" id="weight" class="form-control" placeholder="Weight in kg">
                                        </div>
                                        <div class="form-group">
                                            <label for="blood_group" class="control-label">Blood Group</label>
                                            <select name="blood_group" class="form-control" >
                                                <option value="" >Select</option>
                                                <option <?php if ($row['blood_group'] == "A+") echo " selected='selected' "; ?> value="A+" >A+</option>
                                                <option <?php if ($row['blood_group'] == "A-") echo " selected='selected' "; ?> value="A-" >A-</option>
                                                <option <?php if ($row['blood_group'] == "AB+") echo " selected='selected' "; ?> value="AB+" >AB+</option>
                                                <option <?php if ($row['blood_group'] == "AB+") echo " selected='selected' "; ?> value="AB-" >AB-</option>
                                                <option <?php if ($row['blood_group'] == "B+") echo " selected='selected' "; ?> value="B+" >B+</option>
                                                <option <?php if ($row['blood_group'] == "B-") echo " selected='selected' "; ?> value="B-" >B-</option>
                                                <option <?php if ($row['blood_group'] == "O+") echo " selected='selected' "; ?> value="O+" >O+</option>
                                                <option <?php if ($row['blood_group'] == "O-") echo " selected='selected' "; ?> value="O-" >O-</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-12 text-center">
                                        <?php if($patient_count<$max_patient or $mode == "edit"){ ?>
                                        <input required="required" class="btn btn-success"
                                               type="submit"
                                               name="submit" value="Save"/>
                                        <?php } ?>
                                        <a href="patients.php" class="btn btn-success">Back</a>
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
    $(document).ready(function () {
        /*
       $("#Weight").keypress(function (e) {
            var $txtBox = $("#Weight");
            var charCode = (e.which) ? e.which : e.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
                return false;
            else {
                var len = $txtBox.val().length;
                var index = $txtBox.val().indexOf('.');

                if (index > 0 && charCode == 46) {
                    return false;
                }
                if (index > 0) {
                    var charAfterdot = (len + 1) - index;
                    if (charAfterdot > 3) {
                        return false;
                    }
                }
            }
            return $txtBox;
        });
        */

        $("#weight").keypress(function (e) {
            return /\d+/.test( String.fromCharCode(e.keyCode) );
        });

        $("#height").keypress(function (e) {
            return /\d+/.test( String.fromCharCode(e.keyCode) );
        });

        /*
        $('#BMI').prop('readonly', true);
        $('#Weight').prop('maxlength', 6);
        $('#Height').prop('maxlength', 3);
        */

        $("#weight").keyup(function () {
            var h = $('#height').val();
            var w = $('#weight').val();
            var b;
            if (h != "" && w != "") {
                b = w / (Math.pow(h/100, 2));
                b = b.toFixed(2);
            }
            $('#bmi').val(b);
        });

        $("#height").keyup(function () {
            var h = $('#height').val();
            var w = $('#weight').val();
            var b;
            if (h != "" && w != "") {
                b = w / (Math.pow(h/100, 2));
                b = b.toFixed(2);
            }
            $('#bmi').val(b);
        });

    });
</script>
</body>
</html>