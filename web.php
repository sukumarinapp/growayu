<?php
session_start();
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$sql = "SELECT * FROM users where id = $clinic_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<?php if(trim($row['clinic_name'])!=""){ ?>
<title><?php echo $row['clinic_name']; ?></title>
<?php } ?>
</head>
<body>
<?php if(trim($row['domain_name'])!=""){ ?>
<iframe scrolling="no" style="overflow:hidden;height:2000px;width:100%" src="<?php echo $row['domain_name']; ?>" ></iframe>
<?php } ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<style>
#myModal{
    
    overflow-y: scroll!important;
}
.form-control {
    padding: 22px 4px;
    outline: none;
    color: #5A5A5A;
    margin: 0;
    width: 210px;
    max-width: 100%;
    display: block;
    margin-bottom: 20px;
    background: #fff;
    font-size: inherit;
    border-radius: 0px !important;
    width: 99%;
}

.modal-dialog {
    width: 800px !important;
}

.modal-dialog {
    overflow-y: initial !important
}

.modal-body {
    height: 600px;
    overflow-x: hidden!important; 
    overflow-y: auto!important;
}

.left,
.right {
    position: fixed;
    top: 0;
    bottom: 0;
    height: 2.5em;
    margin: auto;
    background: red;
}

.left {
    left: 0;
    -webkit-transform-origin: 0 50%;
    -moz-transform-origin: 0 50%;
    -ms-transform-origin: 0 50%;
    -o-transform-origin: 0 50%;
    transform-origin: 0 50%;
    -webkit-transform: rotate(-90deg) translate(-50%, 50%);
    -moz-transform: rotate(-90deg) translate(-50%, 50%);
    -ms-transform: rotate(-90deg) translate(-50%, 50%);
    -o-transform: rotate(-90deg) translate(-50%, 50%);
    transform: rotate(-90deg) translate(-50%, 50%);
}
</style>

<div class="modal fade" role="dialog" id="myModal" style="background-repeat: no-repeat;">
    <div class="modal-dialog" style="max-height: 400px; ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Book Appointment</h4>
            </div>
                <form role="form" method="post">
                    <div class="modal-body">
                        <div class="container">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="control-label">Date</label>
                                        <input tabindex="3" onkeydown="return false"
                                               onchange="get_doctor_timings()"
                                               id="appointment_date"
                                               required="required" type="date" name="date"
                                               class="form-control"/>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Full Name</label>
                                        <input tabindex="1" required="required" class="form-control"
                                               maxlength="50"
                                               id="full_name" placeholder="Full Name" name="full_name"
                                               type="text" autofocus>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Mobile</label>
                                        <input tabindex="2" required="required" class="form-control"
                                               maxlength="20"
                                               placeholder="Mobile" id="mobile" name="mobile" type="number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="" id="doctor_time_div" style="overflow-y:scroll!important;overflow-x: hidden!important; ">
                                            <table class="table table-striped table-bordered">
                                                <tr style='background-color: #5bc0de;color:white;font-weight:bold'>
                                                    <th width="15%">Doctor</th>
                                                    <th>Details</th>
                                                    <th align='"left'>Time</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <br>
                                        <div style="text-align:center;" class="form-group">
                                            <input onclick="book_appointment()" class="btn btn-info" type="button"
                                                   name="book" id="book_appointment_button"
                                                   value="Book Appointment"/>
                                            <input data-dismiss="modal" class="btn btn-info" type="button"
                                                   name="close" value="Close"/>

                                            <div id="loading_ajax"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
</div>

<button style="z-index:5000;font-size: 18px;background-color: darkgreen;font-weight: bold;" onclick="show_book()"
    class="btn btn-large btn-warning left">
BOOK APPOINTMENT
</button>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>

<script>
var today = new Date().toISOString().split('T')[0];
document.getElementById("appointment_date").setAttribute('min', today);
var clinic_token = "<?php echo $row['clinic_token']; ?>";
var app_date = new Date(),
        month = '' + (app_date.getMonth() + 1),
        day = '' + app_date.getDate(),
        year = app_date.getFullYear();
if (month.length < 2) month = '0' + month;
if (day.length < 2) day = '0' + day;
app_date = [year, month, day].join('-');
document.getElementById('appointment_date').valueAsDate = new Date();

function get_doctor_timings() {
    var app_date = document.getElementById('appointment_date').value;
    $.ajax({
        url: "http://dreamapps.in/clinicassist/api/GetTimings.php",
        type: "get",
        data: {date: app_date, clinic_token: clinic_token},
        success: function (response) {
            var timings = JSON.parse(response);
            var html = "<table class='table table-striped table-bordered'>";
            html += "<tr style='background-color: #5bc0de;color:white;font-weight:bold'>";
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
                    html += "<img width='60' height='60' src='" + timings['doctors'][j].photo + "?"+rand+"' />";
                    html += "</td>";
                    html += "<td align='top'>";
                    html += timings['doctors'][j].full_name;
                    html += "<br>" + timings['doctors'][j].qualification;
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
get_doctor_timings();
function show_book() {
    $("#myModal").modal('show');
}
function book_appointment() {
    var patient_name = $('#full_name').val().trim();
    var email = "";
    var mobile = $('#mobile').val().trim();
    var appointment_date = $('#appointment_date').val().trim();
    if (patient_name == "") {
        alert("Enter Name");
        $('#full_name').focus();
        return false;
    } else if (mobile == "") {
        alert("Enter Mobile");
        $('#mobile').focus();
        return false;
    } else if (appointment_date == "") {
        alert("Select Date");
        $('#appointment_date').focus();
        return false;
    }
    var radioValue = $("input[name='doctor_slot']:checked").val();
    if (radioValue) {
        $('#book_appointment_button').attr('disabled', true);
        $('#loading_ajax').html('<img src="http://dreamapps.in/clinicassist/ajax.gif" align="absmiddle"> Please wait...');
        var radioValue = radioValue.split("~");
        var doctor_token = radioValue[0];
        var time = radioValue[1];
        $.ajax({
            type: 'POST',
            url: 'http://dreamapps.in/clinicassist/api/BookAppointment.php',
            data: {
                patient_name: patient_name,
                email: email,
                mobile: mobile,
                date: appointment_date,
                doctor_token: doctor_token,
                time: time
            },
            success: function (response) {
                $('#loading_ajax').html('');
                var appointment = JSON.parse(response);
                alert("Appointment booked successfully with Appointment ID " + appointment.appointment_id)
                $('#book_appointment_button').attr('disabled', false);
                window.location.reload();
            }
        });
    } else {
        alert("Select Time");
        return false;
    }
}
</script>
</body>
</html>