<?php
session_start();
$page = "roster";
$module="patient";

include "timeout.php";
include "config.php";
$clinic_id = $_SESSION['clinic_id'];

$msg = "";
$msg_color = "red";
$doctor_id = 0;

$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;
if($mode == "delete"){
    $id=$_GET['id'];
    $query ="delete from rosters where id=$id and clinic_id=$clinic_id";
    mysqli_query($conn, $query);
    header("location: roster.php");
}

if (isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];
    $applicable_from = $_POST['applicable_from'];
    $day_no = implode(",", $_POST['day_no']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    for ($i = 0; $i < count($start_time); $i++) {
        $start = date('H:i', strtotime($start_time[$i]));
        $end = date('H:i', strtotime($end_time[$i]));
        $stmt = $conn->prepare("INSERT INTO rosters (clinic_id,doctor_id,applicable_from,
        day_no,start_time,end_time) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$clinic_id,$doctor_id, $applicable_from,$day_no, $start, $end);
        $stmt->execute() or die($stmt->error);
        $msg = "Doctor Timings added successfully";
        $msg_color = "green";
    }
    $doctor_id=0;
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
        .well {
            padding: 0px;
            background-color: white;
            border: none;
            min-height: 0px;
            margin-bottom: 0px;
        }

        .fa-clock-o {
            margin: -2px 0 0 -22.5px;
            pointer-events: none;
            position: relative;
        }

        .timepicker {
            width: 75px;
        }

        th, td {
            text-align: center;
        }

        .add-button {
            margin: 5px;
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
                            <b style="color: steelblue;text-transform: capitalize">Doctor Timings</b>
                            <br><span
                                style="text-transform: capitalize;color:<?php echo $msg_color; ?>"><?php echo $msg; ?></span>
                        </div>
                        <div class="panel-body">
                            <form id="roster_form" name="roster_form" onsubmit="return validate_schedule(event)" method="post" action=""  >
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="description required"
                                                   class="control-label required">Doctor</label>
                                            <select tabindex="1" autofocus="" id="doctor_id" name="doctor_id" class="form-control" required="required">
                                                <?php
                                                $sql2 = "select * from doctors where clinic_id=$clinic_id and status='Active' order by full_name";
                                                $result2 = mysqli_query($conn, $sql2);
                                                if(mysqli_num_rows($result2)>1) echo "<option value=''>Select</option>";
                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                                    ?>
                                                    <option
                                                        <?php if ($doctor_id == $row2['id']) echo " selected "; ?>
                                                        value="<?php echo $row2['id']; ?>"><?php echo ucwords($row2['full_name']); ?>-<?php echo $row2['experience']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="description required"
                                                   class="control-label required">Applicable From</label>
                                            <input onkeydown="return false" min="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>" required="required" type="date"
                                                   name="applicable_from" id="applicable_from" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="weekday_div">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="1">Mon
                                                    </label>
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="2">Tue
                                                    </label>
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="3">Wed
                                                    </label>
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="4">Thu
                                                    </label>
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="5"> Fri
                                                    </label>
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="6">Sat
                                                    </label>
                                                    <label class="mr-20">
                                                        <input class="form-control checkBx" name="day_no[]"
                                                               type="checkbox" value="0">Sun
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="time_div">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="time-table table table-bordered" border="1" width="400px">
                                                    <tr style="background-color: #81888c;color:white">
                                                        <th>Session</th>
                                                        <th>Start Time</th>
                                                        <th>End Time</th>
                                                        <th width='50px'>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class='well' id="session_top">1</div>
                                                        </td>
                                                        <td>
                                                            <div class='well'><input id="start_time_top"
                                                                                     name='start_time_top'
                                                                                     class='timepicker' type='text'><i
                                                                    class='fa fa-clock-o fa-fw'></i></div>
                                                        </td>
                                                        <td>
                                                            <div class='well'><input id="end_time_top"
                                                                                     name='end_time_top'
                                                                                     class='timepicker' type='text'><i
                                                                    class='fa fa-clock-o fa-fw'></i></div>
                                                        </td>
                                                        <td width='50px'><a onclick="add_row()" title='Add Session'
                                                                            class='btn btn-info btn-info fa fa-plus'
                                                                            href='#'></a></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="time-table table table-bordered" id="tab_logic" border="1"
                                                       width="400px">
                                                    <tr style="background-color: #81888c;color:white">
                                                        <th>Session</th>
                                                        <th style='text-align:center'>Start Time</th>
                                                        <th style='text-align:center'>End Time</th>
                                                        <th width='50px'>
                                                        </th>
                                                    </tr>
                                                    <tr id='addr0'></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <input class="btn btn-success" type="submit" name="btnSubmit" value="Save" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered tabletable-striped" id="dataTables-example">
                            <thead>
                            <tr style="background-color: #81888c;color:white">
                                <th>Doctor</th>
                                <th>Applicable From</th>
                                <th>Days</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $today = date("Y-m-d");
                            $sql="select a.*,b.full_name,b.experience from rosters a,doctors b where a.doctor_id=b.id and b.clinic_id=$clinic_id";
                            if($doctor_id!=0) {
                                $sql=$sql." and doctor_id=$doctor_id";
                            }
                            $sql=$sql." order by applicable_from desc";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)){
                                echo "<tr>";
                                echo "<td style='text-align:left'>".$row['full_name']."-".$row['experience']."</td>";
                                echo "<td>".date("d/m/Y", strtotime($row['applicable_from']))."</td>";
                                $day_no = explode(",",$row['day_no']);
                                $day_name = array();
                                foreach($day_no as $d){
                                    array_push($day_name,date('D', strtotime("Sunday + $d Days")));
                                }
                                $day_name = implode(",",$day_name);
                                echo "<td>".$day_name."</td>";
                                echo "<td>".date('h:i A',strtotime($row['start_time']))."</td>";
                                echo "<td>".date('h:i A',strtotime($row['end_time']))."</td>";
                                echo "<td>";
                                ?>
                                    <a title="Delete" class="btn btn-danger fa fa-trash"
                                       onclick="cancel_confirm(<?php echo $row['id']; ?>)" href="#"></a>
                                <?php
                                echo "</td>";
                                echo "</tr>";
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

var rows = 0;
function add_row() {
    var from_input = $("#start_time_top").val();
    var to_input = $("#end_time_top").val();
    if (from_input == "") {
        alert("Enter session start time");
        return;
    }
    if (to_input == "") {
        alert("Enter session end time");
        return;
    }
    if (parseFloat(convertTo24Hour(to_input)) <= parseFloat(convertTo24Hour(from_input))) {
        alert("Session End Time should be greater than Start Time");
        return
    }

    var start_time = [];
    var end_time = [];
    var index = 0;
    for (var j = 0; j < rows; j++) {
        var start = 0, end = 0, start2 = 0, end2 = 0;
        if ($('#start_time_' + j).val() != undefined) {
            start = $('#start_time_' + j).val();
            start = convertTo24Hour(start);
            end = $('#end_time_' + j).val();
            end = convertTo24Hour(end);
            start_time[index] = start;
            end_time[index] = end;
            index++;
        }
    }
    start_time[index] = convertTo24Hour(from_input);
    end_time[index] = convertTo24Hour(to_input);

    for (var j = 0; j < start_time.length; j++) {
        for (var k = j + 1; k < start_time.length; k++) {
            if ((start_time[j] <= start_time[k] && end_time[j] > start_time[k]) ||
                (start_time[j] < end_time[k] && end_time[j] >= end_time[k])) {
                alert("Time slot is overlapping");
                return;
            }
        }
    }

    var num = 0;
    for (var j = 0; j < rows; j++) {
        if ($("#addr" + (j)).html() != undefined) {
            num++;
        }
    }
    $('#addr' + rows).html("<td class='serial_num'><div class='well sl_no'>" + (num + 1) + "</div></td>"
    + "<td style='text-align:center'><div class=''><input class='timepicker' style='border:none;text-align: center' readonly value='" + from_input + "' id='start_time_" + rows + "' name='start_time[]' type='text'></div></td>"
    + "<td style='text-align:center'><div class=''><input class='timepicker' style='border:none;text-align: center' readonly value='" + to_input + "' id='end_time_" + rows + "' name='end_time[]' type='text'></div></td>"
    + "<td width='50px' valign='top'><a title='Remove Session' style='margin: 2px' class='btn btn-info btn-danger fa fa-remove' href='#' onclick='delete_row(" + rows + ")'></a></td>");
    $('#tab_logic').append('<tr id="addr' + (rows + 1) + '"></tr>');
    $('#start_time_top').val("");
    $('#end_time_top').val("");
    rows++;
    $('#session_top').html(num + 2);
}

function delete_row(row) {
    $("#addr" + (row)).remove();
    var num = 1;
    for (var j = 0; j < rows; j++) {
        if ($("#addr" + (j)).html() != undefined) {
            $('#addr' + j + ' .sl_no').html(num);
            num++;
        }
    }
    $('#session_top').html(num);
}


$(document).ready(function () {
    $('.timepicker').timepicker({minuteStep: 15, showInputs: false, disableFocus: false});
    $('.timepicker').val("");
});

function convertTo24Hour(time) {
    var d = new Date("1/1/2013 " + time);
    return d.getHours() + '.' + d.getMinutes();
}

function overlap_in_db(callback, start_time, end_time, app_date, input_day_no,doctor_id) {
    $.arrayIntersect = function (a, b) {
        return $.grep(a, function (i) {
            return $.inArray(i, b) > -1;
        });
    };

    var overlap_flag = false;
    $break = false;
    //console.log("test");

    $.ajax({
        type:"post",
        data:{applicable_from:app_date,doctor_id:doctor_id},
        dataType: "json",
        async: false,
        url: "overlap_in_db.php",
        success: function (json) {
            //console.log(json);
            for (var i = 0; i < json.length; i++){
                if($break) break;
                var db_start_time=json[i]['start_time'].replace(":",".");
                var db_end_time=json[i]['end_time'].replace(":",".");
                var db_day_no=json[i]['day_no'];
                var overlap_days = $.arrayIntersect(db_day_no.split(","),input_day_no.split(","));
                //console.log(overlap_days.length+"\n");
                //console.log(db_start_time+" "+db_end_time+" "+db_day_no+"\n");
                if(overlap_days.length>0){
                    for (var j = 0; j < start_time.length; j++) {
                        //console.log(start_time[j]+" "+end_time[j]+" "+input_day_no+"\n\n");
                        if (((db_start_time > start_time[j]) && (db_start_time < end_time[j])) ||
                            ((start_time[j] > db_start_time) && (start_time[j] < db_end_time))) {
                            alert("A overlapping record already exists for the applicable date");
                            overlap_flag = true;
                            $break =true;
                        }
                    }
                }
            }
            callback(overlap_flag);
        }
    });

}

function validate_schedule(e) {
    e.preventDefault();
    var app_date = $('#applicable_from').val();
    var doctor_id = $('#doctor_id').val();

    var input_day_no = "";

    var weekday = new Array(7);
    weekday[0] = "Monday";
    weekday[1] = "Tuesday";
    weekday[2] = "Wednesday";
    weekday[3] = "Thursday";
    weekday[4] = "Friday";
    weekday[5] = "Saturday";
    weekday[6] = "Sunday";

    var checkboxes = document.getElementsByName("day_no[]");
    var okay = false;
    for (var j = 0, l = checkboxes.length; j < l; j++) {
        if (checkboxes[j].checked) {
            input_day_no = input_day_no + checkboxes[j].value + ",";
            okay = true;
        }
    }

    if (!okay) {
        alert("Select at least one day of week");
        return false;
    }


    var start_time = [];
    var end_time = [];
    var index = 0;
    for (var j = 0; j < rows; j++) {
        var start = 0, end = 0, start2 = 0, end2 = 0;
        if ($('#start_time_' + j).val() != undefined) {
            start = $('#start_time_' + j).val();
            start = convertTo24Hour(start);
            end = $('#end_time_' + j).val();
            end = convertTo24Hour(end);
            start = parseFloat(start);
            end = parseFloat(end);
            if (end <= start) {
                alert("Session " + (index + 1) + " End Time should be greater than Start Time");
                return false;
            }
            start_time[index] = start;
            end_time[index] = end;
            index++;
        }
    }

    if (index == 0) {
        alert("Atleast one session timing should be defined");
        return false;
    }

    for (var j = 0; j < start_time.length; j++) {
        for (var k = j + 1; k < start_time.length; k++) {
            if (((start_time[j] > start_time[k]) && (start_time[j] < end_time[k])) ||
                ((start_time[k] > start_time[j]) && (start_time[k] < end_time[j]))) {
                alert("Time slot is overlapping");
                return false;
            }
        }
    }

    overlap_in_db(function (result) {
        if (result == false) {
            document.getElementById("roster_form").submit();
        }

    }, start_time, end_time, app_date, input_day_no,doctor_id);

}

function cancel_confirm(id){
    if(confirm("This entry will be deleted. Please confirm.")){
        window.location.href="roster.php?mode=delete&id="+id;
    }
}
</script>
</body>
</html>