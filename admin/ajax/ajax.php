<?php

include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}
function fixzone()
{
    global $con;
    $sql = "select * from city where courierID = 4";
    $results = mysqli_query($con, $sql);
    while ($result = mysqli_fetch_assoc($results)) {
        // echo $result['CityName'];
        // echo $result['cityID'];
        $sql2 = "select * from zone where cityID = '" . $result['cityID'] . "'";
        $results2 = mysqli_query($con, $sql2);
        while ($result2 = mysqli_fetch_assoc($results2)) {
            // echo $result['CityName'];
            echo $result2['zoneID'];
            echo "<br>";
            echo $result2['ZoneName'];
            echo "<br>";
            echo $result2['courierID'];
            echo "<br>";
            $sql3 = "update zone set courierID='4' where cityID = '" . $result['cityID'] . "'";
            // $results2 = mysqli_query($con, $sql3);
        }
    }
}
