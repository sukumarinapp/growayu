<?php
session_start();
$page = "users";
$module="patient";
//ALTER TABLE `doctors` ADD `mobile` VARCHAR(20) NULL AFTER `status`;
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$msg = "";
$msg_color = "";
$row['full_name'] = "";
$row['status'] = "Active";
$row['gender'] = "Male";
$row['qualification'] = "";
$row['experience'] = "";
$row['mobile'] = "";
$file_name = "";

$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

if($mode == "edit"){
    $sql = "SELECT * FROM doctors where id = $id and clinic_id=$clinic_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
}
if($mode == "delete"){
    $sql = "SELECT * FROM doctors where id = $id and clinic_id=$clinic_id";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        $photo = trim($row['photo']);
        if($photo != "m.jpg" and $photo != "f.jpg") unlink("photo/$photo");
    }

    $stmt = $conn->prepare("DELETE from doctors where id=?  and clinic_id=?");
    $stmt->bind_param("ss", $id,$clinic_id);
    $stmt->execute();
    header("location: doctors.php");
}
if (isset($_POST['submit'])) {
    $full_name = trim($_POST['full_name']);
	$mobile = trim($_POST['mobile']);
    $gender = trim($_POST['gender']);
    $qualification = trim($_POST['qualification']);
    $experience = trim($_POST['experience']);
    $status = trim($_POST['status']);
    $doctor_token = md5(uniqid());

    if($mode == "add"){
        $stmt = $conn->prepare("INSERT INTO doctors (mobile,clinic_id,doctor_token,full_name,gender,qualification,experience,status)
                   VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss", $mobile,$clinic_id,$doctor_token, $full_name, $gender, $qualification, $experience, $status);
        $stmt->execute() or die($stmt->error);
        $id = $stmt->insert_id;
        $msg_color = "green";
        $msg = "Doctor added successfully";
    }else if($mode== "edit") {
        $stmt = $conn->prepare("UPDATE doctors set mobile=?,full_name=?,gender=?,qualification=?,experience=?,status=? where id=? and clinic_id=?");
        $stmt->bind_param("ssssssss", $mobile,$full_name, $gender, $qualification, $experience, $status, $id,$clinic_id);
        $stmt->execute() or $stmt->error;
    }

    if($mode == "add" or $mode == "edit") {
        $file_name = $_FILES['photo']['name'];
        $no_image="";
        if($mode == "add" and trim($file_name == "")) {
            if ($gender == "Male") {
                $no_image = "m.jpg";
            }
            if ($gender == "Female") {
                $no_image = "f.jpg";
            }
        }
        if (trim($no_image) != "") {
            $query = "update doctors set photo = '" . $no_image . "' where id=$id";
            mysqli_query($conn, $query);

        }else{
            if (trim($file_name) != "") {
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_name = $id . "." . $ext;
                $target_path = "photo/";
                $target_path = $target_path . $file_name;
                move_uploaded_file($_FILES['photo']['tmp_name'], $target_path);
                $query = "update doctors set photo = '" . $file_name . "' where id=$id";
                mysqli_query($conn, $query);
            }
        }
    }

    $row['full_name'] = "";
    $row['specialization'] = 1;
    $row['status'] = "Active";
    $row['gender'] = "Male";
    $row['qualification'] = "";
    $row['experience'] = "";
    $row['consultation_fee'] = "";
	$row['mobile'] = "";

    if($mode== "edit") header("location: doctors.php");
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
                                <b>Edit Doctor</b>
                            <?php }else{ ?>
                                <b>Doctors</b>
                            <?php } ?>
                            <br><span style="color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <form onsubmit="return CheckFileName()" method="post" action="" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6" style="border-right: 1px solid #efefef;">

                                        <div class="form-group">
                                            <label for="full_name required"
                                                   class="control-label required">Name</label>
                                            <input value="<?php echo $row['full_name']; ?>" required="required" type="text"
                                                   maxlength="50"
                                                   name="full_name" id="full_name" class="form-control"
                                                   placeholder="Full Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="gender" class="control-label required">Gender<?php echo $row['gender']; ?></label>

                                            <div class="form-control">
                                                <input <?php if ($row['gender'] == "Male") echo " checked='checked' "; ?>
                                                    class="form-group" value="Male" type="radio" name="gender"
                                                    id="gender">Male
                                                <input <?php if ($row['gender'] == "Female") echo " checked='checked' "; ?>
                                                    class="form-group" value="Female" type="radio" name="gender"
                                                    id="gender">Female
                                            </div>
                                        </div>
										
										<div class="form-group">
                                            <label for="mobile"
                                                   class="control-label required">Mobile</label>
                                            <input required="required" value="<?php echo $row['mobile']; ?>"  type="text"
                                                   maxlength="50"
                                                   name="mobile" id="mobile" class="form-control"
                                                   placeholder="Mobile">
                                        </div>
                                        <div class="form-group">
                                            <?php if($mode == "edit"){ ?>
                                            <div class="col-md-2">
                                                    <img width="50" height="50" src="photo/<?php echo $row['photo']; ?>?<?php echo rand(); ?>"/>
                                            </div>
                                            <div class="col-md-10">
                                            <?php } ?>
                                                <label for="photo" class="control-label">Photo (jpg or png)</label>
                                                <input onclick="validate_upload()" id="file_upload" name="photo" class="form-control" type="file">
                                            <?php if($mode == "edit") echo "</div>"; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="qualification"
                                                   class="control-label required">Qualification</label>
                                            <textarea rows="1" required="required" placeholder="Qualification"
                                                      maxlength="50"
                                                      name="qualification"
                                                      class="form-control"><?php echo $row['qualification']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="experience" class="control-label">Experience</label>
                                            <textarea rows="1" placeholder="Experience" maxlength="500"
                                                      name="experience"
                                                      class="form-control"><?php echo $row['experience']; ?></textarea>
                                        </div>
										<div class="form-group">
                                            <label for="status" class="control-label required">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option <?php if ($row['status'] == "Active") echo " selected='selected'"; ?>
                                                    value="Active">Active
                                                </option>
                                                <option <?php if ($row['status'] == "Inactive") echo " selected='selected'"; ?>
                                                    value="Inactive">Inactive
                                                </option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <input required="required" class="btn btn-success"
                                               type="submit"
                                               name="submit" value="Save"/>
                                        <?php if($mode == "edit"){ ?>
                                        <a href="doctors.php" class="btn btn-success">Back</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if($mode == "add"){ ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="dataTables-example">
                        <thead>
                        <tr style="background-color: #81888c;color:white">
                            <th>Name</th>
                            <th>Gender</th>
                            <th style="width:150px">Qualification</th>
                            <th>Experience</th>
							<th>Mobile</th>
                            <th>Status</th>
                            <th width="100px" style="text-align: right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        function can_delete($id)
                        {
                            $flag = true;
                            $sql = "select * from rosters where doctor_id=$id";
                            $result = mysqli_query($GLOBALS['conn'], $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $flag = false;
                            }
                            return $flag;
                        }
                        $sql = "select * from doctors where  clinic_id=$clinic_id order by full_name";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td>
                                    <img width="50" height="50" src="photo/<?php echo $row['photo']; ?>?<?php echo rand(); ?>"/>
                                    <?php echo $row['full_name']; ?>
                                </td>
                                <td><?php echo $row['gender']; ?></td>
                                <td style="width:150px"><?php echo $row['qualification']; ?></td>
                                <td><?php echo $row['experience']; ?></td>
								<td><?php echo $row['mobile']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td width="100px" style="text-align: right"><a title="Edit" class="btn btn-info btn-info fa fa-edit"
                                                                 href="doctors.php?mode=edit&id=<?php echo $row['id']; ?>"></a>
                                    <?php if (can_delete($row['id'])){ ?>
                                        <a title="Delete" class="btn btn-danger btn-info fa fa-trash-o"
                                           onclick="delete_confirm(<?php echo $row['id']; ?>)"  href="#"></a>
                                    <?php }else{ ?>
                                    <a disabled class="btn btn-danger btn-info fa fa-trash-o" href="#"></a></td>
                                <?php } ?>
                            </tr>
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
    function delete_confirm(id){
        if(confirm("This doctor will be deleted. Please confirm.")){
            window.location.href="doctors.php?mode=delete&id="+id;
        }
    }
    function CheckFileName() {

        var fileName = document.getElementById("file_upload").value.trim();

        if (fileName.split(".")[1].toUpperCase() != "JPEG" && fileName.split(".")[1].toUpperCase() != "PNG" &&
            fileName.split(".")[1].toUpperCase() != "JPG" )
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