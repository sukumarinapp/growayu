<?php
include "config.php";
$id = $_REQUEST['id'];
$sql = "select * from services where id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo $row["price"];
