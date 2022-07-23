<?php
session_start();
$page="customers";
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id=$_SESSION['clinic_id'];
if ($clinic_id!=-1) header("location: index.php");
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
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 5px" >
                </div>
                <div class="col-md-12"">
                    <table class="table table-bordered" id="dataTables-example">
                            <thead>
                            <tr style="background-color: #81888c;color:white">
                                <th>Clinic ID</th>
                                <th>Clinic Name</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th style="text-align: right">Total Amount</th>
                                <th style="text-align: right">Amount Paid</th>
                                <th style="text-align: right">Max SMS</th>
                                <th style="text-align: right">SMS Sent</th>
                                <th style="text-align: right">Max Patients</th>
                                <th style="text-align: right">Patients</th>
                                <th style="text-align: right">Doctors</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            function doctors($clinic_id)
                            {
                                $doctor_count = 0;
                                $sql = "select count(*) as doctor_count from doctors where clinic_id=$clinic_id";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $doctor_count = $row['doctor_count'];
                                }
                                return $doctor_count;
                            }

                            function sms_sent($clinic_id)
                            {
                                $sms_sent = 0;
                                $sql = "select count(*) as sms_sent from sms where clinic_id=$clinic_id";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sms_sent = $row['sms_sent'];
                                }
                                return $sms_sent;
                            }

                            function patients_created($clinic_id)
                            {
                                $no_of_patients = 0;
                                $sql = "select count(*) as no_of_patients from patients where clinic_id=$clinic_id";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $no_of_patients = $row['no_of_patients'];
                                }
                                return $no_of_patients;
                            }

                            $sql = "select * from users where id>0 order by id ";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['clinic_name']; ?></td>
                                    <td><?php echo $row['user_name']; ?></td>
                                    <td><?php echo $row['password']; ?></td>
                                    <td style="text-align: right"><?php echo $row['premium_amount']; ?></td>
                                    <td style="text-align: right"><?php echo $row['amount_paid']; ?></td>
                                    <td style="text-align: right"><?php echo $row['sms_balance']; ?></td>
                                    <td style="text-align: right"><?php echo sms_sent($row['id']); ?></td>
                                    <td style="text-align: right"><?php echo $row['max_patient']; ?></td>
                                    <td style="text-align: right"><?php echo patients_created($row['id']); ?></td>
                                    <td style="text-align: right"><?php echo doctors($row['id']); ?></td>
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
</body>
</html>