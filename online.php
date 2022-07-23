<?php
session_start();
$page="appointment";
$module="appointment";
include "timeout.php";
include "config.php";
$clinic_id=$_SESSION['clinic_id'];

$from_date = date("Y-m-d");
$to_date = date("Y-m-d");

if (isset($_POST['submit'])) {
    $from_date =  $_POST['from_date'];
    $to_date =  $_POST['to_date'];
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
                                <b>Appointments</b>
                                <form class="form-inline" method="post" name="frm" action="" >
                                    <div class="form-group">
                                        <label class="control-label">From Date</label>
                                        <input onkeydown="return false" tabindex="1" value="<?php echo $from_date; ?>" type="date" name="from_date" />
                                        <label class="control-label">To Date</label>
                                        <input onkeydown="return false" tabindex="2" value="<?php echo $to_date; ?>" type="date" name="to_date" />
                                        <input tabindex="5" required="required" class="btn btn-success" type="submit"
                                               name="submit" value="Show"/>
                                   </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTables-example">
                                <thead>
                                <tr style="background-color: #81888c;color:white">
                                    <th>Appointment ID</th>
                                    <th>Patient Name</th>
                                    <th>Mobile</th>
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Appointment Time</th>
                                    <th>Channel</th>
									<th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT a.*,b.full_name as doc,b.experience from online_appointments a,doctors b
                                        where a.clinic_id=$clinic_id and a.doctor_id=b.id
                                        and date>='$from_date' and date <='$to_date'
                                        order by b.id,a.id";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['mobile']; ?></td>
                                        <td><?php echo $row['doc']; ?>-<?php echo $row['experience']; ?></td>
                                        <td><?php echo date("d/m/Y",strtotime($row['date'])); ?></td>
                                        <td><?php echo date('h:iA', strtotime($row['start_time']))." to ".date('h:iA', strtotime($row['end_time'])); ?></td>
                                        <?php if($row['patient_id']==0 || is_null($row['patient_id'])){ ?>
                                        <td>Website</td>
                                        <?php }else{ ?>
                                            <td>Front Desk</td>
                                        <?php } ?>

										<?php if(($row['patient_id']==0) || is_null($row['patient_id'])){?>
										<td><a title="Add/Find Patient" class="btn btn-info fa fa-user" href="patients.php?mobile=<?php echo $row['mobile']; ?>">&nbsp;Add/Find Patient</a></td>
                                        <?php }else{ ?>
                                            <td>&nbsp;</td>
                                        <?php } ?>
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
    </div>
    <?php include "footer.php"; ?>
</body>
</html>