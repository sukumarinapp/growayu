<?php
session_start();
$page = "user";
$module = "user";
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];

$row['clinic_name'] = "";
$row['user_name'] = "";
$row['password'] = "";
$row['modules'] = "";
$msg = "";
$msg_color = "red";

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add";

if ($mode == "edit") {
    $sql = "SELECT * FROM users where id = $id and user_clinic_id=$clinic_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
}

if ($mode == "delete") {
    $stmt = $conn->prepare("DELETE from users where id=?  and user_clinic_id=?");
    $stmt->bind_param("ss", $id, $clinic_id);
    $stmt->execute();
    header("location: user.php");
}

if (isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $modules = implode(",",$_POST['modules']);
    $role = "user";
    $status = 'Active';

    if ($mode == "add") {
        $stmt = $conn->prepare("INSERT INTO users (user_clinic_id,clinic_name,user_name,password,`role`,modules,status) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $clinic_id, $full_name, $user_name, $password, $role, $modules,$status);
        $stmt->execute() or die($stmt->error);        
    } else if ($mode == "edit") {
        $stmt = $conn->prepare("UPDATE users set clinic_name=?,user_name=?,modules=? where id=?");
        $stmt->bind_param("ssss", $full_name, $user_name, $modules, $id);
        $stmt->execute();
        
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
    <style>
        input[type=checkbox] {
            cursor: pointer;
        }

        input[type=checkbox]:after {
            content: " ";
            background-color: #D7B1D7;
            display: inline-block;
            visibility: visible;
        }

        input[type=checkbox]:checked:after {
            content:
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
                        <div class="panel-heading text-center">
                            <?php if ($mode == "edit") {
                                ; ?>
                                <b>Edit User</b>
                            <?php } else { ?>
                                <b>Add User</b>
                            <?php } ?>
                            <br><span style="color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form method="post" action="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="full_name" class="control-label required">Full Name</label>
                                            <input required="required" type="text" maxlength="50"
                                                   value="<?php echo $row['clinic_name']; ?>" name="full_name"
                                                   id="full_name" class="form-control" placeholder="Full Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_name" class="control-label required">User Name</label>
                                            <input required="required" type="text" maxlength="10"
                                                   value="<?php echo $row['user_name']; ?>" name="user_name"
                                                   id="user_name" class="form-control" placeholder="User Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="password" class="control-label required">Password</label>
                                            <input required="required" type="password" maxlength="10"
                                                   value="<?php echo $row['password']; ?>" name="password" id="password"
                                                   class="form-control" placeholder="Password">
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="label label-success">Modules</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="patient" class="checkbox-inline btn btn-large">
                                            <input id="patient" name="modules[]" type="checkbox" class="btn btn-success" value="patient">Patient
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="Appointment" class="checkbox-inline btn btn-large">
                                            <input id="Appointment" name="modules[]" type="checkbox" class="btn btn-success" value="appointment">Appointment
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="Billing" class="checkbox-inline btn btn-large">
                                            <input id="Billing" name="modules[]" type="checkbox" class="btn btn-success" value="billing">Billing
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="delete_bill" class="checkbox-inline btn btn-large">
                                            <input id="delete_bill" name="modules[]" type="checkbox" class="btn btn-success" value="delete_bill">Delete Bill
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="services" class="checkbox-inline btn btn-large">
                                            <input id="services" name="modules[]" type="checkbox" class="btn btn-success" value="services">Manage Services
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="broadcast" class="checkbox-inline btn btn-large">
                                            <input id="broadcast" name="modules[]" type="checkbox" class="btn btn-success" value="broadcast">Broadcast
                                            </label>
                                        </div>
                                    </div>                                    
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input required="required" class="btn btn-success text-center"
                                               type="submit" name="submit" value="Add User"/>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTables-example">
                                <thead>
                                <tr style="background-color: #81888c;color:white">
                                    <th>S.No</th>
                                    <th>Full Name</th>
                                    <th>User Name</th>
                                    <th>Modules</th>
                                    <th style="text-align: center" width="50px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT * from users where role='user' order by clinic_name";
                                $result = mysqli_query($conn, $sql);
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row['clinic_name']; ?></td>
                                        <td><?php echo $row['user_name']; ?></td>
                                        <td><?php echo $row['modules']; ?></td>
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
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    function delete_confirm(id){
        if(confirm("This user will be deleted. Please confirm.")){
            window.location.href="user.php?mode=delete&id="+id;
        }
    }
</script>
</body>
</html>