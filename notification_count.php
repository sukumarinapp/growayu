<?php
session_start();
include "config.php";
$clinic_id=$_SESSION['clinic_id'];
$date_notification7 = date("Y-m-d");
$notification_sql = "SELECT * from online_appointments where date>='$date_notification7' 
    and date<='$date_notification7' and clinic_id=$clinic_id and status='Pending'";
$notification_result = mysqli_query($conn, $notification_sql) or die(mysqli_error($conn));
$notification_count = mysqli_num_rows($notification_result);
echo $notification_count;