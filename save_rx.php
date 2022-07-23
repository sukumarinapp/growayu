<?php
session_start();
$rx_id = 0;
include "config.php";

$response = array();

$clinic_id = $_REQUEST['clinic_id'];
$visit_id = $_REQUEST['visit_id'];

$rx_data = stripslashes($_REQUEST['rx_data']);
$prescription_array = array();
$prescription_array = json_decode($rx_data);

$sql = "delete from rx_item where clinic_id=$clinic_id and visit_id=$visit_id";
mysqli_query($conn, $sql);
$response['error1'] = mysqli_error($conn);


for ($i = 0; $i < count($prescription_array); $i++) {
    $medicine_name = $prescription_array[$i]->medicine_name;
    $dosage = $prescription_array[$i]->dosage;
    $duration = $prescription_array[$i]->duration;
    $in_take = $prescription_array[$i]->in_take;
    $sql = "INSERT INTO rx_item (clinic_id,medicine_name,dosage,duration,in_take,visit_id) VALUES  ($clinic_id,'$medicine_name','$dosage','$duration','$in_take',$visit_id)";
    mysqli_query($conn, $sql);
    $response['sql2'] = $sql;
    $response['error2'] = mysqli_error($conn);
}
$response['visit_id'] = $visit_id;
echo json_encode($response);