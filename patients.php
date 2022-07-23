<?php
session_start();

$page="patient";
$module="patient";
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id=$_SESSION['clinic_id'];
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
    <?php 
	include "header.php"; 
	if(isset($_GET['mobile'])) $search = $_GET['mobile'];
	?>
</head>
<body>
<div id="wrapper">
    <?php include "menu.php"; ?>
    <div id="page-wrapper" class="fixed-navbar ">
        <div class="container-fluid bg-gray">
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 5px" >
                    <a href="patient.php" class="btn btn-info fa fa-plus pull-right">&nbsp;Add Patient</a>
                    <br>
                </div>
                <div class="col-md-12" id="patient_table">
                    <table class="table table-bordered" id="dataTables-example">
                            <thead>
                            <tr style="background-color: #81888c;color:white">
                                <th>Name</th>
                                <th>OP No</th>
                                <th>Mobile</th>
                                <th>Nationality</th>
                                <th style="width: 220px;text-align: right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            function can_delete($id,$clinic_id)
                            {
                                $flag = true;
                                $sql = "select * from billing where patient_id=$id and clinic_id=$clinic_id limit 1";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $flag = false;
                                    break;
                                }
                                if($flag==true){
                                    $sql = "select * from patient_health_parameter where patient_id=$id and clinic_id=$clinic_id limit 1";
                                    $result = mysqli_query($GLOBALS['conn'], $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $flag = false;
                                        break;
                                    }
                                }
                                return $flag;
                            }

                            $sql = "select * from patients where clinic_id=$clinic_id ";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['card_no']; ?></td>
                                    <td><?php echo $row['mobile']; ?></td>
                                    <td><?php echo nl2br($row['nationality']); ?></td>
                                    <td style="width: 300px;text-align: right">
                                        <?php /*if(trim($_SESSION['examination_template'])!=""){ */?><!--
                                        <a title="Examination" class="btn btn-info btn-danger fa fa-heartbeat" href="examination.php?patient_id=<?php /*echo $row['id']; */?>"></a>
                                        <?php /*}else{ */?>
                                        <a title="ExaminaNtion" class="btn btn-info btn-danger fa fa-heartbeat"
                                        href="health.php?patient_id=<?php /*echo $row['id']; */?>"></a>
                                        --><?php /*} */?>

                                        <?php if(in_array("visit",$_SESSION['modules'])) {?>
                                        <a title="Book Appointment" class="btn btn-info fa fa-calendar-plus-o" href="app.php?patient_id=<?php echo $row['id']; ?>">&nbsp;Appointment</a>
                                        <?php } ?>

                                        <?php if(in_array("billing",$_SESSION['modules'])) {?>
                                        <a title="Billing" class="btn btn-info fa fa-rupee" href="billing.php?patient_id=<?php echo $row['id']; ?>">&nbsp;Billing</a>
                                        <?php } ?>

                                        <a title="Edit Patient" class="btn btn-info fa fa-edit" href="patient.php?mode=edit&id=<?php echo $row['id']; ?>"></a>
                                        <?php if (can_delete($row['id'],$clinic_id)){ ?>
                                            <a title="Delete" class="btn btn-danger btn-info fa fa-trash-o"
                                               onclick="delete_confirm(<?php echo $row['id']; ?>)"  href="#"></a>
                                        <?php }else{ ?>
                                        <a disabled class="btn btn-danger btn-info fa fa-trash-o" href="#"></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    function delete_confirm(id){
        if(confirm("This patient will be deleted. Please confirm.")){
            window.location.href="patient.php?mode=delete&id="+id;
        }
    }
</script>
</body>
</html>