<?php
session_start();
$module="patient";

include "timeout.php";
include "config.php";
$clinic_id=$_SESSION['clinic_id'];
$doctor_id = 0;
$description = "";
$from_date = "";
$to_date = "";

$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

$page = "leave";
$msg = "";
$msg_color = "red";

if($mode == "delete"){
    $stmt = $conn->prepare("DELETE from `leave` where id=? and clinic_id=?");
    $stmt->bind_param("ss", $id,$clinic_id);
    $stmt->execute();
    header("location: leave.php");
}

if (isset($_POST['submit'])) {
    $doctor_id = $_POST['doctor_id'];
    $description = trim($_POST['description']);
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $sql = "INSERT INTO `leave` (clinic_id,doctor_id,from_date,to_date,description) VALUES ($clinic_id,$doctor_id,'$from_date','$to_date','$description')";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $msg = "Leave added successfully";
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
                            <b style="color: steelblue;text-transform: capitalize">Leave</b>
                            <br><span style="text-transform: capitalize;color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form method="post" action="" onsubmit="return check_dates();">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="description required"
                                                   class="control-label required">Doctor</label>
                                            <select tabindex="1" autofocus=""
                                                    id="doctor_id" name="doctor_id" class="form-control"
                                                    required="required">
                                                <option value="">Select</option>
                                                <?php
                                                $sql2 = "select * from doctors where clinic_id=$clinic_id order by full_name";
                                                $result2 = mysqli_query($conn, $sql2);
                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                                    ?>
                                                    <option <?php if ($doctor_id == $row2['id']) echo " selected "; ?> value="<?php echo $row2['id']; ?>"><?php echo $row2['full_name']; ?>-<?php echo $row2['experience']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="description required"
                                                   class="control-label required">From Date</label>
                                            <input onkeydown="return false" value="<?php echo $from_date; ?>" required="required" type="date"
                                                   maxlength="10"
                                                   name="from_date" id="from_date" class="form-control"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="description required"
                                                   class="control-label required">To Date</label>
                                            <input onkeydown="return false" value="<?php echo $to_date; ?>" required="required" type="date"
                                                   maxlength="10"
                                                   name="to_date" id="to_date" class="form-control"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label required">Reason</label>
                                            <input value="<?php echo $description; ?>" required="required" type="text"
                                                   maxlength="50"
                                                   name="description" id="description" class="form-control"
                                                   placeholder="Reason">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <input required="required" class="btn btn-success"
                                                   type="submit"
                                                   name="submit" value="Save"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="patient_table">
                    <div class="table-responsive">
                        <table class="table table-bordered tabletable-striped" id="dataTables-example">
                            <thead>
                            <tr style="background-color: #81888c;color:white">
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Doctor</th>
                                <th>Reason</th>
                                <th style="text-align: center" width="50px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $today=date("Y-m-d");
                            $sql = "select a.*,b.full_name,b.experience from `leave` a,doctors b where a.clinic_id=$clinic_id and a.doctor_id=b.id";
                            if($doctor_id!=0) {
                                $sql=$sql." and a.to_date >= '$today'";
                            }
                            $sql=$sql." order by a.from_date,a.doctor_id asc";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo date("d/m/Y",strtotime($row['from_date'])); ?></td>
                                    <td><?php echo date("d/m/Y",strtotime($row['to_date'])); ?></td>
                                    <td><?php echo $row['full_name']; ?>-<?php echo $row['experience']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td align="center" width="50px">
                                    <a class="btn btn-danger fa fa-trash-o" onclick="delete_confirm(<?php echo $row['id']; ?>)"  href="#"></a>
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
</div>

<?php include "footer.php"; ?>
<script>
    function delete_confirm(id){
        if(confirm("This leave will be deleted. Please confirm.")){
            window.location.href="leave.php?mode=delete&id="+id;
        }
    }
</script>
</body>


</html>