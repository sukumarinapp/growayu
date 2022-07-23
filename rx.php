<?php
session_start();
$module = "prescription";
include "timeout.php";
include "config.php";
$page = "patient";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$speciality = $_SESSION['speciality'];
$visit_id = isset($_GET['visit_id']) ? $_GET['visit_id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add";

if (isset($_POST['submit'])) {
    $visit_date = $_POST['visit_date'];
    $doctor_id = $_POST['doctor_id'];
    $stmt = $conn->prepare("DELETE from patient_health_parameter where visit_date=? and patient_id=?  and clinic_id=?");
    $stmt->bind_param("sss", $visit_date, $patient_id, $clinic_id);
    $stmt->execute();

    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 5) == "param") {
            $param_id = explode("_", $key);
            $param_id = $param_id[1];
            if (trim($value) != "") {
                $stmt = $conn->prepare("INSERT INTO patient_health_parameter (clinic_id,patient_id,doctor_id,visit_date,param_id,param_value) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("ssssss", $clinic_id, $patient_id, $doctor_id, $visit_date, $param_id, $value);
                $stmt->execute() or die($stmt->error);
            }
        }
    }
    //header("location: health.php?patient_id=$patient_id");
}

$sql = "SELECT a.*,b.visit_id from patients a,visit b where a.id=b.patient_id and a.clinic_id=$clinic_id and b.visit_id=$visit_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$patient_id = $row['id'];
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
                            <b>Prescription</b>
                        </div>
                        <form method="post" action="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Visit Date</label>
                                            <input value="<?php echo date("Y-m-d"); ?>" type="date" name="visit_date"
                                                   class="form-control" id="visit_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Patient Name</label>
                                            <input readonly value="<?php echo $patient_name; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">OP No</label>
                                            <input readonly value="<?php echo $card_no; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Mobile</label>
                                            <input readonly value="<?php echo $mobile; ?>" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-3">
                                        <INPUT type="text" id="medicine_name" name="medicine_name"
                                               placeholder="Medicine Name"
                                               maxlength="50"
                                               class="form-control" required/>
                                    </div>
                                    <div class="col-md-3">
                                        <INPUT type="text" id="dosage" name="dosage" placeholder="Dosage (eg: 1-0-1)"
                                               maxlength="50"
                                               class="form-control" required/>
                                    </div>

                                    <div class="col-md-3">
                                        <INPUT type="text" id="duration" name="duration"
                                               placeholder="Course Duration (eg: 1 week)" maxlength="50"
                                               class="form-control" required/>
                                    </div>

                                    <div class="col-md-2">
                                        <SELECT id="in_take" name="in_take" class="form-control"
                                                style="width: 100%">
                                            <OPTION value="After Food">After Food</OPTION>
                                            <OPTION value="Before Food">Before Food</OPTION>
                                            <option value="SoS">SoS</option>
                                        </SELECT>
                                    </div>
                                    <div class="col-md-1">
                                        <input onclick="add_row()" id="save_button" required="required" class="btn
                                        btn-success text-center"
                                               type="button" name="submit" value="Add"/>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="tab_logic">
                            <thead>
                            <tr style="background-color: #81888c;color:white">
                                <td class="text-center">
                                    S.No
                                </td>
                                <td style='text-align: left'>
                                    Medicine
                                </td>
                                <td class="text-right">
                                    Dosage
                                </td>
                                <td class="text-right">
                                    Duration
                                </td>
                                <td class="text-right">
                                    Intake
                                </td>
                                <td width="50px" class="text-center">
                                    Remove
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id='addr0'></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <input onclick="submit_data()" id="save_button" required="required"
                               class="btn btn-success text-center"
                               type="button"
                               name="save" value="Save"/>
                        <a href="visit.php?patient_id=<?php echo $patient_id; ?>" class="btn btn-success">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    var i = 0;

    function add_row() {
        var num = 0;
        for (var j = 0; j < i; j++) {
            if ($("#addr" + (j)).html() != undefined) {
                num++;
            }
        }
        // alert("This feature is under development");return;
        var medicine_name = $('#medicine_name').val();
        var dosage = $('#dosage').val();
        var duration = $('#duration').val();
        var in_take = $("#in_take option:selected").val();

        $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) + "</span></td>"
            + "<td style='text-align: left'><input value='" + medicine_name + "' name='medicine_name[]' type='hidden'>"
            + "<input value='" + dosage + "' name='dosage[]' type='hidden'>"
            + "<input value='" + duration + "' name='duration[]' type='hidden'>"
            + "<input value='" + in_take + "' name='in_take[]' type='hidden'>"
            + medicine_name + "</td>"
            + "<td style='text-align: right'>" + dosage + "</td>"
            + "<td style='text-align: right'>" + duration + "</td>"
            + "<td style='text-align: right'>" + in_take + "</td>"
            + "<td width='50px' style='text-align: center' valign='middle'><a title='Remove' class='btn btn-info btn-danger fa fa-remove' href='#' onclick='delete_row(" + i + ")'></a></td>");
        $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
        i++;
        $('#medicine_name').val("");
        $('#medicine_name').focus();
    }

    function delete_row(row) {
        $("#addr" + (row)).remove();
        var num = 1;
        for (var j = 0; j < i; j++) {
            if ($("#addr" + (j)).html() != undefined) {
                $('#addr' + j + ' .sl_no').html(num);
                num++;
            }
        }
    }

    function submit_data() {
        var items = false;
        var prescription = new Array();
        var inp_medicine_name = $('input[name="medicine_name[]"]');
        var inp_dosage = $('input[name="dosage[]"]');
        var inp_duration = $('input[name="duration[]"]');
        var inp_in_take = $('input[name="in_take[]"]');
        for (var j = 0; j < i; j++) {
            var items = true;
            var record = {
                'medicine_name': inp_medicine_name.eq(j).val(),
                'dosage': inp_dosage.eq(j).val(),
                'duration': inp_duration.eq(j).val(),
                'in_take': inp_in_take.eq(j).val(),
            };
            prescription.push(record);
        }
        if(!items){
            alert("Medicine not added in prescription");
            return;
        }
        var visit_id = "<?php echo $visit_id; ?>";
        var clinic_id = "<?php echo $clinic_id; ?>";
        $("#save_button").prop("disabled", true);

        var rx_data = JSON.stringify(prescription);

        var payload ={
            clinic_id: clinic_id,
            visit_id: visit_id,
            rx_data: rx_data
        };

        $.ajax({
            type: 'POST',
            url: 'save_rx.php',
            data: payload,
            success: function (response) {
                $("#save_button").prop("disabled", false);
                var response = JSON.parse(response);
                show_rx(response.visit_id);

            },
            error: function (error) {
                $("#save_button").prop("disabled", false);
                console.log(error);
            }
        });
    }

    function show_rx(visit_id) {
        var clinic_id = "<?php echo $clinic_id; ?>";
        $.ajax({
            url: "rxprint.php",
            type: "get",
            data: {visit_id: visit_id, clinic_id: clinic_id},
            success: function (html) {
                $('#section-to-print').html(html);
                $('#myModal').modal('show');
            }
        });
    }


</script>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-center" id="section-to-print">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input onclick="window.print()" class="btn btn-success"
                       type="submit"
                       name="proceed" value="Print"/>
                <button type="button" class="btn btn-success" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>