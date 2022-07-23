<?php
session_start();
$module="patient";

include "timeout.php";
include "config.php";
$clinic_id=$_SESSION['clinic_id'];

$page = "holiday";
$msg = "";
$msg_color = "red";

$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

if($mode == "delete"){
    $stmt = $conn->prepare("DELETE from holiday where id=? and clinic_id=?");
    $stmt->bind_param("ss", $id,$clinic_id);
    $stmt->execute();
    header("location: holiday.php");
}

if (isset($_POST['submit'])) {
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $sql = "INSERT INTO holiday (clinic_id,date,description) VALUES ($clinic_id,'$date','$description')";
    mysqli_query($conn, $sql);
    $msg = "Holiday added successfully";
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
                            <b style="color: steelblue;text-transform: capitalize">New Holiday</b>
                            <br><span style="text-transform: capitalize;color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="description"
                                                   class="control-label required">Date</label>
                                            <input onkeydown="return false" required="required" type="date"
                                                   maxlength="50"
                                                   name="date" class="form-control"  >
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label required">Description</label>
                                            <input required="required" type="text"
                                                   maxlength="50"
                                                   name="description" id="description" class="form-control"
                                                   placeholder="Description">
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
                                <th>Date</th>
                                <th>Description</th>
                                <th style="text-align: center" width="50px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $sql = "select * from holiday where clinic_id=$clinic_id order by date";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo date("d/m/Y",strtotime($row['date'])); ?></td>
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
        if(confirm("This holiday will be deleted. Please confirm.")){
            window.location.href="holiday.php?mode=delete&id="+id;
        }
    }
</script>
</body>
</html>