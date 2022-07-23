<?php
//http://192.168.43.87:81/clinic/api/GetTimings.php?clinic_token=5836d45ea6571188bbfadfa51c14da08&date=2020-06-10
header('Access-Control-Allow-Origin: *');
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$response = array();
include "../config.php";
$clinic_token = $data['clinic_token'];
$date = $data['date'];

$response = array();
$response["doctors"] = array();
$today = date("Y-m-d");
if($date < $today){
    $response["valid"] = true;
    $response["message"] = "";
    echo json_encode($response);
    die;
}
$current_week_day = date('w', strtotime($date));
$clinic_id =0;
$sql = "select * from users where clinic_token='$clinic_token' and status='Active'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response["valid"] = true;
        $response["message"] = "";
        $clinic_id = $row['id'];
        $response['clinic_token'] = $clinic_token;
        $response['clinic_name'] = $row['clinic_name'];
        $response['address_line1'] = $row['address_line1'];
        $response['address_line2'] = $row['address_line2'];
        $response['address_line3'] = $row['address_line3'];
        $response['pincode'] = $row['pincode'];
        $response['logo'] = trim($row['logo']) != "" ? $base_url . "logo/" . $row['logo'] : "";
        //$response['date'] = $date;
    }
}else{
    $response["valid"] = false;
    $response["message"] = "Invalid token";
    echo json_encode($response);
    die;
}

$sql = "select * from holiday where clinic_id=$clinic_id and date='$date'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if ($count > 0) {
    echo json_encode($response);
    die;
}

$sql = "select id,doctor_token,full_name,gender,qualification,experience,photo
from doctors where clinic_id=$clinic_id and status='Active' order by id";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $doctor_id = $row['id'];
    $sql2 = "select * from `leave` where from_date<='$date' and to_date>='$date' and doctor_id=$doctor_id";
    $result2 = mysqli_query($conn, $sql2);
    $count = mysqli_num_rows($result2);
    if ($count > 0) {

    }else{
        $doctors = $row;
        $doctors['full_name'] = ucwords(strtolower($row['full_name']));
        unset($doctors['id']);
        if (trim($row['photo']) != "") $doctors['photo'] = $base_url . "photo/" . $row['photo'];        
        $sql3 = "select start_time,end_time,day_no from rosters where doctor_id = $doctor_id and applicable_from <= (select max(applicable_from)
    from rosters where doctor_id = $doctor_id and applicable_from<='$date') order by start_time";
        $result3 = mysqli_query($conn, $sql3);
        $timings = array();
        if (mysqli_num_rows($result3) > 0) {
            $i = 0;
            while ($row3 = mysqli_fetch_assoc($result3)) {
                if (in_array($current_week_day, explode(",", $row3['day_no']))){
                    $start_time = $row3['start_time'];
                    $end_time = $row3['end_time'];
                    $timings[$i]['start_time'] = $start_time;
                    $timings[$i]['end_time'] = $end_time;
                    $timings[$i]['start_time_am_pm'] = date('h:iA', strtotime($start_time));
                    $timings[$i]['end_time_am_pm'] = date('h:iA', strtotime($end_time));
                    $i++;
                }
            }
        }
        $doctors['timings'] = $timings;
        array_push($response["doctors"], $doctors);
    }
}

echo json_encode($response, JSON_UNESCAPED_SLASHES);
