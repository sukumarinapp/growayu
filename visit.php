<?php
session_start();
$module = "visit";
include "timeout.php";
include "config.php";
$page = "patient";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$speciality = $_SESSION['speciality'];
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add";

//echo $mode;die;
if($mode == "delete"){
    $id = $_GET['id'];
    $sql = "DELETE from visit where visit_id=$id  and clinic_id=$clinic_id";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    $visit_date = $_POST['visit_date'];
    $doctor_id = $_POST['doctor_id'];
    $treatment = $_POST['treatment'];
    $remarks = $_POST['remarks'];
    /*$stmt = $conn->prepare("DELETE from patient_health_parameter where visit_date=? and patient_id=?  and clinic_id=?");
    $stmt->bind_param("sss",$visit_date, $patient_id,$clinic_id);
    $stmt->execute();*/

    $stmt = $conn->prepare("INSERT INTO visit (clinic_id,patient_id,doctor_id,visit_date,treatment,remarks) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $clinic_id, $patient_id, $doctor_id, $visit_date, $treatment, $remarks);
    $stmt->execute() or die($stmt->error);

}

$sql = "SELECT mobile,full_name,card_no from patients where clinic_id=$clinic_id and id=$patient_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$patient_name = $row['full_name'];
$card_no = $row['card_no'];

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
                            <?php if ($mode == "edit") {
                                ; ?>
                                <b>Edit Visit</b>
                            <?php } else { ?>
                                <b>New Visit</b>
                            <?php } ?>
                        </div>
                        <form method="post" action="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Visit Date</label>
                                            <input value="<?php echo date("Y-m-d"); ?>" type="date" name="visit_date"
                                                   class="form-control" id="visit_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Patient Name</label>
                                            <input readonly value="<?php echo $patient_name; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">OP No</label>
                                            <input readonly value="<?php echo $card_no; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label required">Doctor</label>
                                            <select tabindex="1" autofocus="" id="doctor_id" name="doctor_id"
                                                    class="form-control" required="required">
                                                <?php
                                                $sql2 = "select * from doctors where clinic_id=$clinic_id and status='Active' order by full_name";
                                                $result2 = mysqli_query($conn, $sql2);
                                                if (mysqli_num_rows($result2) > 1) echo "<option value=''>Select</option>";
                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                                    ?>
                                                    <option
                                                            value="<?php echo $row2['id']; ?>"><?php echo ucwords($row2['full_name']); ?>-<?php echo $row2['experience']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Treatment</label>
                                            <textarea maxlength="500" name="treatment" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Remarks</label>
                                            <textarea maxlength="500" name="remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input id="save_button" required="required" class="btn btn-success text-center"
                                               type="submit" name="submit" value="Save"/>
                                        <a href="patients.php" class="btn btn-success">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php


            $sql = "select a.*,b.full_name from  visit a,doctors b where a.doctor_id=b.id and 
                                                     a.clinic_id=$clinic_id order by visit_date desc";
            $result = mysqli_query($conn, $sql);

            ?>
            <div class="col-md-12" id="patient_table">
                <table class="table table-bordered">
                    <thead>
                    <tr style="background-color: #81888c;color:white">
                        <th>Visit Date</th>
                        <th>Doctor</th>
                        <th>Treatment</th>
                        <th>Remarks</th>
                        <th style="text-align: right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    function can_delete($id, $clinic_id)
                    {
                        $flag = true;
                        $sql = "select * from rx_item where visit_id=$id and clinic_id=$clinic_id limit 1";
                        $result = mysqli_query($GLOBALS['conn'], $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $flag = false;
                            break;
                        }
                        return $flag;
                    }

                    while ($row = mysqli_fetch_assoc($result)) {
                        $visit_id = $row['visit_id'];
                        echo '<tr>';
                        echo '<td>' . date("d/m/Y", strtotime($row['visit_date'])) . '</td>';
                        echo '<td>' . $row['full_name'] . '</td>';
                        echo '<td>' . $row['treatment'] . '</td>';
                        echo '<td>' . $row['remarks'] . '</td>';
                        if (in_array("prescription", $_SESSION['modules'])) {
                            echo '<td style="text-align: right"><a title="Prescription" class="btn btn-info fa fa-medkit" 
href="rx.php?visit_id=' . $visit_id . '" ></a>';
                        }
                        ?>
                        <?php if (can_delete($visit_id, $row['clinic_id'])) { ?>
                            <a title="Delete" class="btn btn-danger btn-info fa fa-trash-o"
                               onclick="delete_confirm(<?php echo $visit_id; ?>,<?php echo $row['patient_id'] ?>)"
                               href="#"></a>
                        <?php } else { ?>
                            <a disabled class="btn btn-danger btn-info fa fa-trash-o" href="#"></a>
                        <?php } ?>
                        <?php
                        echo '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    function delete_confirm(id,patient_id) {
        console.log("pa_id"+patient_id);
        if (confirm("This visit will be deleted. Please confirm.")) {
            window.location.href = "visit.php?mode=delete&id="+id+"&patient_id="+patient_id;
        }
    }
</script>
</body>
</html>