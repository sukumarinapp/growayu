<?php
include "config.php";
$query = trim($_REQUEST['query']);
$query = explode("~", $query);
$query1 = $query[0];
$query2 = $query[1];
$query = "select * from services where nationality='$query2' and (code like '%$query1%' OR description like '%$query1%') order by description LIMIT 20";
$result = mysqli_query($conn, $query);
$array = [];
while ($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
}
echo json_encode($array);
