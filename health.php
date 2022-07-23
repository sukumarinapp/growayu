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
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

$sql = "SELECT mobile,full_name,card_no from patients where clinic_id=$clinic_id and id=$patient_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$patient_name = $row['full_name'];
$card_no = $row['card_no'];
$mobile = $row['mobile'];

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
                            <b>Health Parameters</b>
                            <a href="visit.php?patient_id=<?php echo $patient_id; ?>" class="btn btn-info fa fa-plus pull-right">&nbsp;New Visit</a>
                            <br>
                        </div>
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
                                        <label class="control-label">OP No</label>
                                        <input readonly value="<?php echo $card_no; ?>" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Mobile</label>
                                        <input readonly value="<?php echo $mobile; ?>" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <?php
                            function get_value($clinic_id,$patient_id,$visit_date,$param_id)
                            {
                                $value = "";
                                $sql = "select param_value from patient_health_parameter where clinic_id=$clinic_id and patient_id=$patient_id and visit_date='$visit_date' and param_id = $param_id";
                                $result = mysqli_query($GLOBALS['conn'], $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $value = $row['param_value'];
                                }
                                return $value;
                            }

                            $param_array = array();
                            $visit_array = array();
                            $sql = "select id,param_name from  health_parameter where clinic_id=$clinic_id and speciality in (0,$speciality) order by 
                                                                                                             display_order ";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                array_push($param_array, $row);
                            }
                            $sql = "select distinct(visit_date) from  patient_health_parameter where patient_id=$patient_id
                            and clinic_id=$clinic_id order by visit_date";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                array_push($visit_array, $row);
                            }
                            echo "<table class='table table-bordered'>";
                            echo "<thead>";
                            echo "<tr style='background-color: #81888c;color:white'>";
                            echo "<th>Date</th>";
                            for($j=0;$j<count($visit_array);$j++){
                                echo "<th>".date("d/m/Y",strtotime($visit_array[$j]['visit_date']))."</th>";
                            }
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            for($i=0;$i<count($param_array);$i++){
                                echo "<tr>";
                                echo "<td>".$param_array[$i]['param_name']."</td>";
                                for($j=0;$j<count($visit_array);$j++){
                                    $visit_date = $visit_array[$j]['visit_date'];
                                    $param_id = $param_array[$i]['id'];
                                    $value = get_value($clinic_id,$patient_id,$visit_date,$param_id);
                                    echo "<td>".$value."</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "<table>";
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="patients.php" class="btn btn-success">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>