<?php
include ('config.php');
$sql = "SELECT name FROM strings WHERE id = 1";
$result = $connect->query($sql);
$row = $result->fetch_assoc();
?>