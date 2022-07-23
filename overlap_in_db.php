<?php
session_start();
include "config.php";
$doctor_id = $_REQUEST['doctor_id'];
$applicable_from = $_REQUEST['applicable_from'];
$response=array();
$sql="select * from rosters where doctor_id=$doctor_id and applicable_from='$applicable_from'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    array_push($response,$row);
}
echo json_encode($response);