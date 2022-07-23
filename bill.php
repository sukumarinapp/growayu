<?php
session_start();
$module = "billing";

include "timeout.php";
include "config.php";
$page = "billing";
//if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$bill_id = isset($_GET['id']) ? $_GET['id'] : 0 ;

$sql1 = "select * from users where user_clinic_id=$clinic_id";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);

$sql2 = "SELECT a.*,b.mobile,b.full_name,b.card_no from billing a,patients b where a.clinic_id=$clinic_id
         and a.id=$bill_id and a.patient_id=b.id";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);

$sql3 = "SELECT a.*,b.description,b.price from bill_items a,services b where a.item_id=b.id
         and a.bill_id=$bill_id and a.clinic_id=$clinic_id";
$result3 = mysqli_query($conn, $sql3);

?>
<?php
$bill_template = $row1['bill_template'];
include "bill_template/$bill_template";
?>
