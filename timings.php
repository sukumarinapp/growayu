<?php
error_reporting(0);
session_start();
include "timeout.php";
include "config.php";
$page="timing";
$clinic_id=$_SESSION['clinic_id'];
//if($_SESSION['user_type']!="doctor")  header("location: index.php");
$doctor_id = 0;
if(isset($_SESSION['doctor_id'])) $doctor_id = $_SESSION['doctor_id'];
$today = date("Ymd");
$duration = 20;
$break = 0;
$start_date = strtotime(date("Y-m-d"));
$msg = "";
$delete_all=0;
if($doctor_id==0){
    $sql = "select * from users where user_type='doctor' and clinic_id=$clinic_id order by full_name limit 1";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $doctor_id = $row['id'];
    }
}
if (isset($_POST['doctor'])) {
    $doctor_id = $_POST['doctor'];
}

function appointment_present($date, $doctor_id)
{
    $flag = 0;
    $sql = "SELECT a.*,b.slot,b.id as slot_id FROM doctor_timings a,doctor_timing_slot b where a.id=b.timing_id and doctor_id=$doctor_id
          and date='$date' and appointment_id<>0";
    $result = mysqli_query($GLOBALS['conn'], $sql);
    while ($row = mysqli_fetch_array($result)) {
        $flag = 1;
    }
    return $flag;
}

if (isset($_POST['delete_all'])) {
    $id = $_POST['id'];
    $sql="update doctor_timing_slot set deleted =1 where appointment_id=0 and timing_id=$id";
    mysqli_query($conn, $sql);
    $delete_all = mysqli_affected_rows($conn);
}
if (isset($_POST['delete'])) {
    $months = $_POST['period'];
    $selected_weekdays = $_POST['weekday'];
    $start_date = strtotime('+0 day', $start_date);
    $end_date = strtotime("+12 month", $start_date);
    while ($start_date < $end_date) {
        $weekday = date("w", $start_date);
        if (in_array($weekday, $selected_weekdays)) {
            $db_date = date("Ymd", $start_date);

            if (appointment_present($db_date, $doctor_id) == 1) {
                $start_date = strtotime('+1 day', $start_date);
                continue;
            }

            $sql = "select * from doctor_timings where doctor_id='$doctor_id' and date='$db_date'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                $timing_id = $row['id'];
                $stmt = $conn->prepare("delete from doctor_timing_slot where timing_id=?");
                $stmt->bind_param("s", $timing_id);
                $stmt->execute();
            }
            $stmt = $conn->prepare("delete from doctor_timings where doctor_id=? and date=?");
            $stmt->bind_param("ss", $doctor_id, $db_date);
            $stmt->execute();
        }
        $start_date = strtotime('+1 day', $start_date);
    }
}

if (isset($_POST['submit'])) {
    $months = $_POST['period'];
    $selected_weekdays = $_POST['weekday'];
    $start_date = strtotime('+0 day', $start_date);
    //$start_date = strtotime($start_date);
    if ($months == 7) {
        $end_date = strtotime("+7 day", $start_date);
    } else {
        $end_date = strtotime("+$months month", $start_date);
    }

    //echo  date("d/m/Y", $start_date)."-".date("d/m/Y", $end_date);
    //echo "<br>";
    while ($start_date < $end_date) {
        $weekday = date("w", $start_date);
        if (in_array($weekday, $selected_weekdays)) {
            //echo date("l", $start_date)."-".date("d/m/Y", $start_date);
            //echo "<br>";
            $db_date = date("Ymd", $start_date);

            if (appointment_present($db_date, $doctor_id) == 1) {
                $start_date = strtotime('+1 day', $start_date);
                continue;
            }

            $timing_id=0;
            $doctor_timings_present=false;
            $sql = "select * from doctor_timings where doctor_id='$doctor_id' and date='$db_date'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                $timing_id = $row['id'];
                $doctor_timings_present=true;
            }

            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];


            if($doctor_timings_present==false) {
                $stmt = $conn->prepare("insert into doctor_timings (clinic_id,doctor_id,date,start_time,end_time,duration,slot_gap) values (?,?,?,?,?,?,?)");
                $stmt->bind_param("sssssss",$clinic_id, $doctor_id, $db_date, $start_time, $end_time, $duration, $break);
                $stmt->execute();
                $timing_id = $stmt->insert_id;
                $stmt->close();
            }

            $array_of_time = array();
            $array_of_endtime = array();
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);

            $add_mins = $duration * 60;
            $add_break_mins = $break * 60;

            while ($start_time <= $end_time) {
                $array_of_time[] = date("H:i", $start_time);
                $end_time2 = $start_time + $add_mins;
                $array_of_endtime[] = date("H:i", $end_time2);
                $start_time += $add_mins + $add_break_mins;
            }

            for ($i = 0; $i < sizeof($array_of_time) - 1; $i++) {
                $slot = $array_of_time[$i] . "-" . $array_of_endtime[$i];
                $sql = "select * from doctor_timing_slot where timing_id='$timing_id' and slot='$slot'";
                $result = mysqli_query($conn, $sql);
                $doctor_timings_slot=false;
                while ($row = mysqli_fetch_array($result)) {
                    $doctor_timings_slot=true;
                }
                if($doctor_timings_slot==false) {
                    $stmt = $conn->prepare("insert into doctor_timing_slot (clinic_id,timing_id,slot) values (?,?,?)");
                    $stmt->bind_param("sss", $clinic_id,$timing_id, $slot);
                    $stmt->execute();
                }
            }
        }
        $start_date = strtotime('+1 day', $start_date);
    }

}

function super_unique($array, $key)
{
    $temp_array = array();
    foreach ($array as &$v) {
        if (!isset($temp_array[$v[$key]]))
            $temp_array[$v[$key]] =& $v;
    }
    $array = array_values($temp_array);
    return $array;
}

$weekdays = array();
$week_end = strtotime('+7 day', strtotime(date("Y-m-d")));
$week_end = date("Ymd", $week_end);
$timings_sql = "SELECT * FROM doctor_timings where doctor_id=$doctor_id and date>=$today and date<=$week_end order by date";
$timings_result = mysqli_query($conn, $timings_sql);
$slot_duration = "";
$slot_gap = "";
$i = 0;
while ($timings_row = mysqli_fetch_array($timings_result)) {
    if ($i == 0) $slot_duration = $timings_row['duration'];
    if ($i == 0) $slot_gap = $timings_row['slot_gap'];
    $i++;
    $arr = array();
    $arr['weekday'] = date("w", strtotime(php_date($timings_row['date'])));
    $arr['start_time'] = $timings_row['start_time'];
    $arr['end_time'] = $timings_row['end_time'];
    array_push($weekdays, $arr);
}
$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
//print_r($weekdays);die;
$weekdays = super_unique($weekdays, "weekday");
//$weekdays = array_map("unserialize",array_unique(array_map("serialize", $weekdays)));
foreach ($weekdays as $key => $row) {
    $dates[$key] = $row['weekday'];
}
array_multisort($dates, SORT_ASC, $weekdays);

for ($i = 0; $i < count($weekdays); $i++) {
    $weekdays[$i]['weekday'] = $dowMap[$weekdays[$i]['weekday']];
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
    <div class="row">
        <div class="col-md-12">
            <div class="login-panel panel panel-default">
                <div class="panel-heading text-center" >
                    <b>Consultation Timings</b>
                    <?php if(!isset($_SESSION['doctor_id'])){ ?>
                    <br><br>
                    <form method="post" name="doctor_form">
                        <div class="form-inline">
                            <label class="control-label" style="text-transform: capitalize">Select Doctor</label>
                            <select onchange="change_doctor(this.value)" id="doctor_id"  name="doctor"
                                    class="form-control" required>
                                <?php
                                $sql = "select * from users where clinic_id=$clinic_id and user_type='doctor' order by full_name";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['id']; ?>"
                                        <?php if($doctor_id==$row['id']) echo " selected "; ?>
                                        ><?php echo $row['full_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <?php } ?>
                </div>
                <form method="post" action="">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 viewTxt">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 workingDays">Working Days</div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 secondTitle">
                                        <span class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <?php
                                          for ($i = 0; $i < count($weekdays); $i++) {
                                              if ($i != 0) echo ",";
                                              echo $weekdays[$i]['weekday'];
                                          }
                                          ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 workingDays">
                                        Timing on Working Days
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 secondTitle">
                                        <?php
                                        for ($i = 0; $i < count($weekdays); $i++) {
                                            echo "<span class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='text-align: justify'>";
                                            echo $weekdays[$i]['weekday'] . "<br>" . date('h:i A', strtotime($weekdays[$i]['start_time'])) . " to " . date('h:i A', strtotime($weekdays[$i]['end_time']));
                                            echo "</span>";
                                        }
                                        ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 workingDays">
                                        Consultation Duration
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 secondTitle">
                                        <span class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <?php
                                          echo $slot_duration . " Mins";
                                          ?>
                                        </span>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 secondTitle text-center margin">
                                        <!-- <input required="required" class="btn btn-success"
                                               type="submit"
                                               name="submit" value="Add/Edit"/> -->
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#myModal">Add/Edit
                                        </button>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 borderOne">
                                    <ul class="nav nav-tabs mobileNavtab">
                                        <?php
                                        $i = 0;
                                        $timings_result = mysqli_query($conn, $timings_sql);
                                        while ($timings_row = mysqli_fetch_array($timings_result)) {
                                            echo "<li ";
                                            if ($i == 0) echo "class='active'";
                                            echo "><a style='text-decoration:none' data-toggle='tab' href='#section" . $timings_row['id'] . "'>" . fromsqldmy($timings_row['date']) . "</a></li>";
                                            $i++;
                                        }
                                        ?>
                                    </ul>
                                    <div class="tab-content margin ml-9">
                                        <?php
                                        function get_status($appointment_id)
                                        {
                                            $query = "select * from appointments where appointment_id=$appointment_id";
                                            $result = mysqli_query($GLOBALS['conn'], $query);
                                            $count = mysqli_num_rows($result);
                                            if ($count >= 1) {
                                                return "paid";
                                            } else {
                                                return "";
                                            }
                                        }

                                        $i = 0;
                                        $timings_result = mysqli_query($conn, $timings_sql);
                                        while ($timings_row = mysqli_fetch_array($timings_result)) {
                                            echo "<div id='section" . $timings_row['id'] . "' class='tab-pane fade in ";
                                            if ($i == 0) echo "active";
                                            echo "'>";
                                            echo "<div class='row'>";
                                            $timing_id = $timings_row['id'];
                                            $sql2 = "select * from doctor_timing_slot where timing_id=$timing_id order by id";
                                            $result2 = mysqli_query($conn, $sql2);
                                            $early_morning=0;
                                            $morning_flag=0;
                                            $noon_flag=0;
                                            $evening_flag=0;
                                            $night_flag=0;
                                            while ($row2 = mysqli_fetch_array($result2)) {
                                                $slot = $row2['slot'];
                                                $from_time = substr($slot, 0, 5);
                                                $from_time = date('h:iA', strtotime($from_time));
                                                $to_time = substr($slot, 6, 5);
                                                $to_time = date('h:iA', strtotime($to_time));
                                                $from_time_comp = substr($slot, 0, 5);
                                                $from_time_comp = strtr ($from_time_comp, array (':' => '.'));
                                                if($from_time_comp<6 and $from_time_comp>=0 and $early_morning==0){
                                                    echo "<div class='text-left col-xs-12 col-sm-12 col-md-12 col-lg12 margin no-Rpadding'>";
                                                    echo "<span class='timeSlot label-info'>Early Morning</span>";
                                                    echo "</div>";
                                                    $early_morning=1;
                                                }elseif($from_time_comp<12 and $from_time_comp>=6 and $morning_flag==0){
                                                    echo "<div class='text-left col-xs-12 col-sm-12 col-md-12 col-lg12 margin no-Rpadding'>";
                                                    echo "<span class='timeSlot label-info'>Morning</span>";
                                                    echo "</div>";
                                                    $morning_flag=1;
                                                }elseif($from_time_comp<16 and $from_time_comp>=12 and $noon_flag==0){
                                                    echo "<div class='text-left col-xs-12 col-sm-12 col-md-12 col-lg12 margin no-Rpadding'>";
                                                    echo "<span class='timeSlot label-info'>After Noon</span>";
                                                    echo "</div>";
                                                    $noon_flag=1;
                                                }elseif($from_time_comp<20 and $from_time_comp>=16 and $evening_flag==0){
                                                    echo "<div class='text-left col-xs-12 col-sm-12 col-md-12 col-lg12 margin no-Rpadding'>";
                                                    echo "<span class='timeSlot label-info'>Evening</span>";
                                                    echo "</div>";
                                                    $evening_flag=1;
                                                }elseif($from_time_comp<=23 and $from_time_comp>=20 and $night_flag==0){
                                                    echo "<div class='text-left col-xs-12 col-sm-12 col-md-12 col-lg12 margin no-Rpadding'>";
                                                    echo "<span class='timeSlot label-info'>Night</span>";
                                                    echo "</div>";
                                                    $night_flag=1;
                                                }
                                                echo "<div class='col-xs-4 col-sm-4 col-md-2 col-lg2 margin no-Rpadding'>";
                                                if ($row2['deleted'] == "0") {
                                                    if ($row2['appointment_id'] != 0 && get_status($row2['appointment_id']) == "paid") {
                                                        echo "<span class='timeSlot label-danger'>";
                                                        echo "<input ";
                                                        echo " type='checkbox' value='" . $row2['id'] . "' checked='checked' disabled />";
                                                        echo "<span class='label-danger' id='timing_slot_" . $row2['id'] . "'>";
                                                    } else {
                                                        echo "<span class='timeSlot label-success'>";
                                                        echo "<input ";
                                                        echo " onclick=\"toggle_checkbox('timing_slot_" . $row2['id'] . "')\"";
                                                        echo " type='checkbox' value='" . $row2['id'] . "' checked='checked' />";
                                                        echo "<span class='label-success' id='timing_slot_" . $row2['id'] . "'>";
                                                    }
                                                } else {
                                                    echo "<span class='timeSlot label-success'>";
                                                    echo "<input ";
                                                    echo " onclick=\"toggle_checkbox('timing_slot_" . $row2['id'] . "')\"";
                                                    echo " type='checkbox' value='" . $row2['id'] . "'  />";
                                                    echo "<span class='label-success' id='timing_slot_" . $row2['id'] . "' style='opacity: .7'>";
                                                }
                                                echo $from_time;
                                                echo "</span></span>";
                                                echo "</div>";

                                            }
                                            ?>
                                            <div class="clearfix"></div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                                <form method="post"  action="" >
                                                    <input type="hidden" name="id"
                                                           value="<?php echo $timings_row['id']; ?>"/>
                                                    <input class="btn  btn-danger" style="text-align: center"
                                                           type="submit"  name="delete_all" value="Remove All" onclick="return delete_all_slots();"/>
                                                </form>
                                                <script>
                                                    function delete_all_slots(){
                                                        var r = confirm("Are you sure you want to delete all time slots");
                                                        if(r == true){
                                                            return true;
                                                        }else{
                                                            return false;
                                                        }
                                                    }
                                                </script>
                                            </div>
                                            <?php
                                            echo "</div>";
                                            echo "</div>";
                                            $i++;
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
    </div>
</div>
<!-- /#page-wrapper -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CONSULTATION TIMINGS</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input value="<?php echo $doctor_id; ?>" type="hidden" id="doctor_id" name="doctor">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="1">Monday
                                    </label>
                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="2">Tuesday
                                    </label>
                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="3">Wednesday
                                    </label>
                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="4">Thursday
                                    </label>
                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="5">Friday
                                    </label>
                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="6">Saturday
                                    </label>
                                    <label class="mr-20">
                                        <input checked="checked" class="form-control checkBx" name="weekday[]"
                                               type="checkbox" value="0">Sunday
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Apply For</label>
                                    <label class="mr-20">
                                        <select class="form-control" name="period">
                                            <option selected value="7">1 Week</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">2 Months</option>
                                            <option value="3">3 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="12">1 Year</option>
                                        </select>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Start Time</label>
                                    <select name="start_time" required="required" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                        for ($i = 0; $i <= 23; $i++) {
                                            for ($j = 0; $j <= 45; $j += $duration) {
                                                $start_time = str_pad($i, 2, '0', STR_PAD_LEFT) . ":" . str_pad($j, 2, '0', STR_PAD_LEFT);
                                                $start_time = date('h:i A', strtotime($start_time));
                                                echo "<option ";
                                                if ($i == 9 and $j == 0) echo "selected='selected'";
                                                echo " >" . $start_time . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">End Time</label>
                                    <select name="end_time" required="required" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                        for ($i = 0; $i <= 23; $i++) {
                                            for ($j = 0; $j <= 45; $j += $duration) {
                                                $end_time = str_pad($i, 2, '0', STR_PAD_LEFT) . ":" . str_pad($j, 2, '0', STR_PAD_LEFT);
                                                $end_time = date('h:i A', strtotime($end_time));
                                                echo "<option ";
                                                if ($i == 17 and $j == 0) echo "selected='selected'";
                                                if ($i == 0) {
                                                    echo "value='24:00'";
                                                } else {
                                                    echo "value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . ":" . str_pad($j, 2, '0', STR_PAD_LEFT) . "'";
                                                }
                                                echo " >" . $end_time . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>


                            </div>
                            <div class="col-md-offset-3 col-md-6 text-center">
                                <input required="required" class="btn btn-success"
                                       type="submit"
                                       name="submit" value="Save"/>
                                <input required="required" class="btn  btn-danger"
                                       type="submit"
                                       name="delete" value="Delete"/>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <label class="text-info">You can define multiple sessions in a day.
                                    For example first session from 09:00AM to 11:00 AM and second session from
                                    05:00PM to 08:00PM</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<?php include "footer.php"; ?>


<script>

    function change_doctor(doctor_id){
        $("#doctor_id").val(doctor_id);
        document.doctor_form.submit();
    }

    function toggle_checkbox(timing_slot_id) {
        var slot_id = timing_slot_id.split('_');
        var slot_id = slot_id[2];
        var ele = document.getElementById(timing_slot_id);
        var opacity = window.getComputedStyle(ele).getPropertyValue("opacity");
        var deleted = 0;
        if (opacity == 1) {
            ele.style.opacity = '.7';
            deleted = 1;
        } else {
            ele.style.opacity = '1';
            deleted = 0;
        }
        //console.log("id="+slot_id+"&deleted="+deleted);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            }
        }
        var queryString = "check_slot.php?id=" + slot_id + "&deleted=" + deleted;
        xmlhttp.open("GET", queryString, true);
        xmlhttp.send();
    }


    <?php
    if($delete_all<>0){
        echo "alert(\"$delete_all Time slots deleted\");";
    }
    ?>
</script>




</body>


</html>
