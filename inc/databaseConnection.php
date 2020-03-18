<?php
$con = mysqli_connect("localhost", "root", "root", "inventory.app");
global $con;
if (!$con) {
    echo "not connect";
    die();
} else {
    date_default_timezone_set("Asia/Dhaka");
}
// include "fn.php";
