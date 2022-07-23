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
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add";

//echo $mode;die;
if($mode == "delete"){
    $id = $_GET['id'];
    $sql = "DELETE from visit where visit_id=$id  and clinic_id=$clinic_id";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    $visit_date = $_POST['visit_date'];
    $doctor_id = $_POST['doctor_id'];
    $treatment = $_POST['treatment'];
    $remarks = $_POST['remarks'];
    /*$stmt = $conn->prepare("DELETE from patient_health_parameter where visit_date=? and patient_id=?  and clinic_id=?");
    $stmt->bind_param("sss",$visit_date, $patient_id,$clinic_id);
    $stmt->execute();*/

    $stmt = $conn->prepare("INSERT INTO visit (clinic_id,patient_id,doctor_id,visit_date,treatment,remarks) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $clinic_id, $patient_id, $doctor_id, $visit_date, $treatment, $remarks);
    $stmt->execute() or die($stmt->error);

}

$sql = "SELECT id,mobile,full_name,card_no from patients where clinic_id=$clinic_id and id=$patient_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$patient_id = $row['id'];
$patient_name = $row['full_name'];
$mobile = $row['mobile'];
$card_no = $row['card_no'];

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
                            <b>Book Appointment</b>
                        </div>
                        <form method="post" action="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Appointment Date</label>
                                            <input onchange="get_doctor_timings()" value="<?php echo date("Y-m-d"); ?>" type="date" name="app_date" class="form-control" id="app_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Patient Name</label>
                                            <input id="patient_id" value="<?php echo $patient_id; ?>" type="hidden">
                                            <input id="patient_name" readonly value="<?php echo $patient_name; ?>" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Mobile</label>
                                            <input id="mobile" value="<?php echo $mobile; ?>" type="text" class="form-control Number">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">OP No</label>
                                            <input readonly value="<?php echo $card_no; ?>" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="doctor_time_div">                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input onclick="book_appointment()" id="book_appointment_button" required="required" class="btn btn-success text-center"
                                               type="button" name="submit" value="Book Appointment"/>
                                        <a href="patients.php" class="btn btn-success">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    var clinic_token = "<?php echo $clinic_token; ?>";
    $(document).ready(function() {
        get_doctor_timings();
    });



    function get_doctor_timings() {
        var app_date = document.getElementById('app_date').value;
        var payload = {
            "date": app_date,
            "clinic_token": clinic_token

        };
        console.log(JSON.stringify(payload));
        console.log("<?php echo $base_url; ?>");
        
        $.ajax({
            url: "<?php echo $base_url; ?>api/GetTimings.php",
            data: JSON.stringify(payload),
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            success: function(timings) {
                console.log(timings);
                var html = "<table class='table table-striped table-bordered'>";
                html += "<tr style='background-color: steelblue;color:white;font-weight:bold'>";
                html += "<th width='15%'>Doctor</th>";
                html += "<th>Details</th>";
                html += "<th align='left'>Time</th>";
                html += "</tr>";
                for (var j = 0; j < timings['doctors'].length; j++) {
                    //console.log(timings['doctors'][j]);
                    if (timings['doctors'][j].timings.length > 0) {
                        var rand = Math.random();
                        html += "<tr>";
                        html += "<td>";
                        html += "<img width='60' height='60' src='" + timings['doctors'][j].photo + "?" + rand + "' />";
                        html += "</td>";
                        html += "<td align='top'>";
                        html += timings['doctors'][j].full_name;
                        html += "<br>" + timings['doctors'][j].qualification;
                        html += "<br>" + timings['doctors'][j].experience;
                        html += "</td>";
                        html += "<td>";
                        html += "<div class='row'>";
                        for (var k = 0; k < timings['doctors'][j].timings.length; k++) {
                            html += "<div style='padding-bottom: 20px' class='col-xs-12 col-sm-12 col-md-12 col-lg-12 margin'>";
                            html += "<label class='radio-inline border_set'>";
                            var start_time = timings['doctors'][j].timings[k].start_time;
                            var end_time = timings['doctors'][j].timings[k].end_time;
                            var start_time_am_pm = timings['doctors'][j].timings[k].start_time_am_pm;
                            var end_time_am_pm = timings['doctors'][j].timings[k].end_time_am_pm;
                            html += "<input tabindex='4' type='radio' required  name='doctor_slot' value='" + timings['doctors'][j].doctor_token + "~" + start_time + "-" + end_time + "'>" + start_time_am_pm + "-" + end_time_am_pm;
                            html += "</label>";
                            html += "</div>";
                        }
                        html += "</div>";
                        html += "</td>";
                        html += "</tr>";
                    }
                }
                $('#doctor_time_div').html(html);
            }
        });
    }

    function book_appointment() {
        var patient_name = $('#patient_name').val().trim();
        var patient_id = $('#patient_id').val().trim();
        var email = "";
        var mobile = $('#mobile').val().trim();
        var appointment_date = $('#app_date').val().trim();
        if (appointment_date == "") {
            alert("Select Date");
            $('#app_date').focus();
            return false;
        }
        if (mobile == "") {
            alert("Please enter mobile no");
            $('#mobile').focus();
            return false;
        }
        var radioValue = $("input[name='doctor_slot']:checked").val();
        if (radioValue) {
            if(!confirm("Your appointment will be booked.Proceed ?")) {
                return;
            }
            $('#book_appointment_button').attr('disabled', true);
            $('#loading_ajax').html('<img src="website/ajax.gif" align="absmiddle"> Please wait...');
            var radioValue = radioValue.split("~");
            var doctor_token = radioValue[0];
            var time = radioValue[1];
            var payload = {
                "patient_id": patient_id,
                "patient_name": patient_name,
                "email": email,
                "mobile": mobile,
                "date": appointment_date,
                "doctor_token": doctor_token,
                "time": time,
            };
            $.ajax({
                url: '<?php echo $base_url; ?>api/BookAppointment.php',
                data: JSON.stringify(payload),
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                success: function(response) {
                    $('#loading_ajax').html('');
                    alert("Appointment booked successfully with Appointment ID " + response.appointment_id)
                    $('#book_appointment_button').attr('disabled', false);
                    window.location.href = "online.php";
                },
                error: function (error) {
                    console.log(error);
                }
            });
        } else {
            alert("Select Time");
            return false;
        }
    }

    function delete_confirm(id,patient_id) {
        console.log("pa_id"+patient_id);
        if (confirm("This visit will be deleted. Please confirm.")) {
            window.location.href = "visit.php?mode=delete&id="+id+"&patient_id="+patient_id;
        }
    }
</script>
</body>
</html>