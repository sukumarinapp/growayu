<?php
session_start();
$billnum = 0;
include "config.php";
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_REQUEST['patient_id'];
$doctor_id = $_REQUEST['doctor_id'];
$total = $_REQUEST['amount'];
$net_amount = $_REQUEST['net_amount'];
$discount_amount = $_REQUEST['discount_amount'];
$bill_date = $_REQUEST['bill_date'];
$sales = $_REQUEST['sales'];
$sales = stripslashes($sales);
$sales_array = array();
$sales_array = json_decode($sales);
$sql = "select max(billnum) as billnum from billing where clinic_id=$clinic_id";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $billnum = $row['billnum'] + 1;
}
$stmt = $conn->prepare("INSERT INTO billing (clinic_id,billnum,bill_date,patient_id,doctor_id,total,net_amount,discount_amount) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss",$clinic_id, $billnum,$bill_date,$patient_id, $doctor_id, $total,$net_amount,$discount_amount);
$stmt->execute() or die($stmt->error);
$bill_id = $stmt->insert_id;
for ($i = 0; $i < count($sales_array); $i++) {
    $item_id = $sales_array[$i]->item_id;
    $item_quantity = $sales_array[$i]->item_quantity;
    $item_amount = $sales_array[$i]->item_amount;
    $discount = $sales_array[$i]->discount;
    $sql = "INSERT INTO bill_items (clinic_id,bill_id,item_id,quantity,amount,discount) VALUES  ($clinic_id,$bill_id,'$item_id',$item_quantity,$item_amount,$discount)";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}
$response = array();
$response['bill_id'] = $bill_id;
$response['billnum'] = $billnum;
echo json_encode($response);