<?php
session_start();
if(!in_array("services", $_SESSION['modules'])) header("location: index.php");
$page = "services";
$module = "billing";
include "timeout.php";
include "config.php";
$clinic_id = $_SESSION['clinic_id'];
$msg = "";
$msg_color = "red";

$row['description'] = "";
$row['code'] = "";
$row['nationality'] = "Indian";
$row['service_type'] = "Diagnosis";
$row['doctor_id'] = "";
$row['price'] = "";

$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

if($mode == "edit"){
    $sql = "SELECT * FROM services where id = $id and clinic_id=$clinic_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
}
if($mode == "delete"){
    $stmt = $conn->prepare("DELETE from services where id=?  and clinic_id=?");
    $stmt->bind_param("ss", $id,$clinic_id);
    $stmt->execute();
    header("location: services.php");
}


if (isset($_POST['submit'])) {
    $description = $_POST['description'];
    $nationality = $_POST['nationality'];
    $service_type = $_POST['service_type'];
    $code = $_POST['code'];
    $doctor = $_POST['doctor'];
    $price = $_POST['price'];
    if($mode == "add"){
        $sql = "INSERT INTO services (clinic_id,description,price,nationality,service_type,code,doctor_id) VALUES ($clinic_id,'$description',$price,'$nationality','$service_type','$code','$doctor')";
        mysqli_query($conn, $sql) or die(mysqli_error($conn));
    }else if($mode== "edit") {
        $stmt = $conn->prepare("UPDATE services set description=?,nationality=?,price=?,service_type=?,code=?,doctor_id=? where id=? and clinic_id=?");
        $stmt->bind_param("ssssssss", $description,$nationality, $price,$service_type,$code,$doctor, $id,$clinic_id);
        $stmt->execute() ;
    }

    $row['description'] = "";
    $row['nationality'] = "Indian";
    $row['service_type'] = "Dianosis";
    $row['price'] = "";

    if($mode== "edit") header("location: services.php");
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
                                <b>Edit Service</b>
                            <?php }else{ ?>
                                <b>Add Service</b>
                            <?php } ?>
                            <br><span style="color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form id="form" method="post" action="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="service_type required"
                                                   class="control-label required">Service Type</label>
                                            <select  name="service_type" id="service_type" class="form-control" >
                                                <option <?php if($row['service_type']=="Diagnosis") echo " selected "; ?> value="Diagnosis">Diagnosis</option>
                                                <option <?php if($row['service_type']=="Consultation") echo " selected "; ?> value="Consultation">Consultation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nationality required"
                                                   class="control-label required">Nationality</label>
                                            <select  name="nationality" id="nationality" class="form-control" >
                                                <option <?php if($row['nationality']=="Indian") echo " selected "; ?> value="Indian">Indian</option>
                                                <option <?php if($row['nationality']=="NRI") echo " selected "; ?> value="NRI">NRI</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="doctor_div">
                                        <div class="form-group">
                                            <label class="control-label required">Doctor</label>
                                            <select tabindex="1" id="doctor" name="doctor" class="form-control" >
                                                <option value="">Select</option>
                                                <?php
                                                $sql2 = "select * from doctors where clinic_id=$clinic_id order by full_name";
                                                $result2 = mysqli_query($conn, $sql2);
                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                                    ?>
                                                    <option
                                                    <?php if($row['doctor_id']==$row2['id']) echo " selected "; ?>
                                                        value="<?php echo $row2['id']; ?>"><?php echo ucwords($row2['full_name']); ?>-<?php echo $row2['experience']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="code_div">
                                        <div class="form-group">
                                            <label for="code"
                                                   class="control-label required">Code</label>
                                            <input value="<?php echo $row['code']; ?>" type="text"
                                                   maxlength="15"
                                                   name="code" id="code" class="form-control"
                                                   placeholder="Code">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="description required"
                                                   class="control-label required">Description</label>
                                            <input value="<?php echo $row['description']; ?>" type="text"
                                                   maxlength="50"
                                                   name="description" id="description" class="form-control"
                                                   placeholder="Description">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price required"
                                                   class="control-label required">Amount</label>
                                            <input value="<?php echo $row['price']; ?>" type="numeric"
                                                   pattern="\d*" maxlength="5" name="price" id="price" class="form-control Number"
                                                   placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
            <?php if($mode == "add"){; ?>
            <div class="row">
                <div class="col-md-12" id="patient_table">
                <table class="table table-bordered" id="dataTables-example">
                            <thead>
                            <tr style="background-color: #81888c;color:white">
                                <th>Service Type</th>
                                <th>Nationality</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Doctor</th>
                                <th style="width: 100px;text-align: right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            function can_delete($id)
                            {
                                $flag = true;
                                $sql = "select * from bill_items where item_id=$id";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $flag = false;
                                }
                                return $flag;
                            }
                            function get_doctor($id)
                            {
                                $name = "";
                                $sql = "select * from doctors where id='$id'";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $name = $row['full_name'];
                                }
                                return $name;
                            }

                            $sql = "select * from services where clinic_id=$clinic_id order by description";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['service_type']; ?></td>
                                    <td><?php echo $row['nationality']; ?></td>
                                    <td><?php echo $row['code']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo get_doctor($row['doctor_id']); ?></td>
                                    <td style="width: 100px;text-align: right">
                                        <a title="Edit Service" class="btn btn-info fa fa-edit"
                                                                    href="services.php?mode=edit&id=<?php echo $row['id']; ?>"></a>
                                        <?php if (can_delete($row['id'])) { ?>
                                        <a title="Delete Service" class="btn btn-danger fa fa-trash-o"
                                           onclick="delete_confirm(<?php echo $row['id']; ?>)"  href="#""></a>
                                    </td>
                                    <?php } else { ?>
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
            <?php } ?>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    var service_type = "<?php echo $row['service_type']; ?>";
    function delete_confirm(id){
        if(confirm("This service will be deleted. Please confirm.")){
            window.location.href="services.php?mode=delete&id="+id;
        }
    }

    $(document).ready(function () {
        $('#service_type').change(function() {
            if($('#service_type').val() == "Consultation"){
                $("#doctor_div").show();  
                $("#code_div").hide();  
            }else{
                $("#code_div").show();  
                $("#doctor_div").hide();  
            }
        });
        if(service_type=="Consultation")
           $("#code_div").hide();   
        else
           $("#doctor_div").hide(); 

        $("#form").submit(function (eventObj) {
            if(($('#service_type').val()) == "Consultation"){
                if($("#doctor").val().trim() == ""){
                    alert("Please select doctor");
                    $('select[name="doctor"]').focus();
                    return false;
                }                  
            }else{
                if($("#code").val().trim() == ""){
                   alert("Please enter code");
                   $("#code").focus();
                   return false; 
                }                
            }
            if($("#description").val().trim() == ""){
                alert("Please enter description");
                $('input[name="description"]').focus();
                return false;
            }                 
            if($("#price").val().trim() == ""){
                alert("Please enter price");
                $('input[name="price"]').focus();
                return false;
            }                  
            $("#form").submit();
        });       
    });

</script>
</body>
</html>
