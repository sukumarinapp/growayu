<?php
session_start();
include "timeout.php";
include "config.php";
$page = "patients";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$speciality = $_SESSION['speciality'];
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add";

$sql = "SELECT mobile,full_name,card_no from patients where clinic_id=$clinic_id and id=$patient_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$patient_name = $row['full_name'];
$card_no = $row['card_no'];
$mobile = $row['mobile'];
$visit_date = date("Y-m-d");
$chart_param_name = "chart_data";

if (isset($_POST['save_chart'])) {
    $chart_data = $_POST['chart_data'];
    $stmt = $conn->prepare("DELETE from examination where patient_id=?  and clinic_id=? and param_name=?");
    $stmt->bind_param("sss",$patient_id,$clinic_id,$chart_param_name);
    $stmt->execute();
    $stmt = $conn->prepare("INSERT INTO examination (clinic_id,patient_id,visit_date,param_name,param_value) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $clinic_id, $patient_id, $visit_date, $chart_param_name, $chart_data);
    $stmt->execute() or die($stmt->error);
}
if (isset($_POST['clear_chart'])) {
    $stmt = $conn->prepare("DELETE from examination where patient_id=?  and clinic_id=? and param_name=?");
    $stmt->bind_param("sss",$patient_id,$clinic_id,$chart_param_name);
    $stmt->execute();
}

if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("DELETE from examination where patient_id=?  and clinic_id=? and param_name<>?");
    $stmt->bind_param("sss",$patient_id,$clinic_id,$chart_param_name);
    $stmt->execute();
    foreach ($_POST as $param_name => $param_value) {
        if (substr($param_name, 0, 5) == "param") {
            $stmt = $conn->prepare("INSERT INTO examination (clinic_id,patient_id,visit_date,param_name,param_value) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $clinic_id, $patient_id, $visit_date, $param_name, $param_value);
            $stmt->execute() or die($stmt->error);
        }
    }
}

$value = array();
for($i=1;$i<100;$i++){
    $param_name = "param_".$i;
    $value[$param_name] = "";
}
$value['chart_data'] = "";

$sql = "SELECT * from examination where clinic_id=$clinic_id and patient_id=$patient_id";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $param_name = $row['param_name'];
    $param_value = $row['param_value'];
    $value[$param_name] = $param_value;
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

    <script src="js/snap.svg.js"></script>
    <script src="js/chart.js"></script>

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
                            <b>Examination</b>
                            <br>
                        </div>
                        <form method="post" action="" onsubmit="return submit_data()">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Patient Name</label>
                                            <input readonly value="<?php echo $patient_name; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Card No</label>
                                            <input readonly value="<?php echo $card_no; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Mobile</label>
                                            <input readonly value="<?php echo $mobile; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $examination_template = $_SESSION['examination_template'];
                                include "examination_template/$examination_template";
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <input id="save_button" class="btn btn-info text-center"
                                           type="submit" name="submit" value="Save"/>
                                    <a href="patients.php" class="btn btn-info">Back</a>
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
    <?php if(trim($value['chart_data'])!=""){ ?>
        var value = unescape("<?php echo $value['chart_data']; ?>");
        document.getElementById("chart").innerHTML = value;
    <?php }else{ ?>
        window.onload = function () {
            var chartPaper = Snap('#chart');
            var dentalChart = new DentalChart(chartPaper);
        }
    <?php } ?>

    function submit_data(){
        document.getElementById("chart_data").value = escape(document.getElementById("chart").innerHTML);
        return true;
    }
</script>
</body>
</html>