<?php
//http://dreamapps.in/clinicassist/api/BookAppointment.php?doctor_token=d97fba7fa9e1143dec13f54bd44bc4a9&date=2018-01-20&patient_name=Santhosh M&mobile=8778193926&email=sukumar.inapp@gmail.com&time=16:00-20:00
header('Access-Control-Allow-Origin: *');
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$response = array();
include "../config.php";
$doctor_token = $data['doctor_token'];
$date = $data['date'];
$patient_name = $data['patient_name'];
$patient_id = $data['patient_id'];
$doctor_name = "";
$doctor_location = "";
$doctor_mobile = "";
$mobile = $data['mobile'];
$email = "";
$time = $data['time'];
$status = "Pending";
$clinic_id = 0;
$clinic_name = "";
$doctor_id = 0;

$sql = "select a.*,b.clinic_name from doctors a,users b where a.clinic_id=b.id and a.doctor_token='$doctor_token' and a.status='Active'";
$response['sql'] = $sql;
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response["valid"] = true;
        $response["message"] = "Appointment booked successfully";
        $doctor_id = $row['id'];
        $clinic_id = $row['clinic_id'];
        $clinic_name = $row['clinic_name'];
        $doctor_name = $row['full_name'];
		$doctor_mobile = $row['mobile'];
		$doctor_location = $row['experience'];
    }
}else{
    $response["valid"] = false;
    $response["message"] = "Invalid doctor token";
    echo json_encode($response);
    die;
}

$time = explode("-", $time);
$start_time = $time[0];
$end_time = $time[1];

$query ="INSERT INTO online_appointments(clinic_id,patient_id,full_name,email,mobile,`date`,doctor_id,start_time,end_time,status)
             VALUES ('$clinic_id',$patient_id,'$patient_name','$email','$mobile','$date','$doctor_id','$start_time','$end_time','$status')";
mysqli_query($conn,$query) or die(mysqli_error($conn));
$appointment_id = $conn->insert_id;
$response["appointment_id"] = $appointment_id;

$date = date("d-M-Y",strtotime($date));
$start_time = date('h:iA', strtotime($start_time));
$end_time = date('h:iA', strtotime($end_time));
$message = "Your appointment at $clinic_name with Dr. $doctor_name is confirmed for $date between $start_time and $end_time. Your appointment ID is $appointment_id";

if(trim($mobile)!=""){
    //send_sms($message,$mobile);
    $stmt = $conn->prepare("INSERT INTO sms (clinic_id,mobile,message) VALUES (?,?,?)");
    $stmt->bind_param("sss",$clinic_id,  $mobile, $message);
    $stmt->execute();
}

$message = "$patient_name has booked an appointment wth you at $doctor_location on $date between $start_time and $end_time. The appointment ID is $appointment_id";

if(trim($doctor_mobile)!=""){
    //send_sms($message,$doctor_mobile);
    $stmt = $conn->prepare("INSERT INTO sms (clinic_id,mobile,message) VALUES (?,?,?)");
    $stmt->bind_param("sss",$clinic_id,  $doctor_mobile, $message);
    $stmt->execute();
}

echo json_encode($response);