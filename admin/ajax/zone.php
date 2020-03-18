<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getCourier()
{
    global $con;
    $sql = $search = '';
    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $search = " where courierName like '%" . $search . "%'";
        }
    }
    $sql = "select * from courier " . $search;
    $results = mysqli_query($con, $sql);
    $responseArray = array();
    while ($result = mysqli_fetch_assoc($results)) {

        $responseArray[] = array(
            "id" => $result['courierID'],
            "text" => $result['CourierName']
        );
    }
    $data['data'] = $responseArray;
    echo  json_encode($data);
    die();
}



function get()
{
    global $con;
    $sql = "select zone.*,city.cityID as cityID, city.CityName as CityName,courier.courierID as courierID,courier.CourierName as CourierName from zone LEFT JOIN city on (zone.cityID=city.cityID) LEFT JOIN courier on (zone.courierID=courier.courierID)";
    $results = mysqli_query($con, $sql);
    $temp = array();
    while ($result = mysqli_fetch_assoc($results)) {
        $temp[] = $result;
    }
    $data['data'] = $temp;
    echo  json_encode($data);
    die();
}
function save()
{
    global $con;
    $ZoneName =  $_REQUEST['ZoneName'];
    $courierID =  $_REQUEST['courierID'];
    $cityID =  $_REQUEST['cityID'];
    $results = array();
    $sql = "INSERT INTO zone VALUES (NULL, '" . $courierID . "', '" . $cityID . "', '" . $ZoneName . "', '1')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $results['status'] = 'success';
        $results['message'] = 'Successfully Add Zone';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to add Zone';
    }
    echo  json_encode($results);
    die();
}
function update()
{
    global $con;
    $ZoneName =  $_REQUEST['ZoneName'];
    $courierID =  $_REQUEST['courierID'];
    $cityID =  $_REQUEST['cityID'];
    $zoneID =  $_REQUEST['zoneID'];
    $results = array();
    if ($zoneID) {
        $sql = "update zone set ZoneName='" . $ZoneName . "' , courierID='" . $courierID . "' , cityID='" . $cityID . "' where zoneID='" . $zoneID . "'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $results['status'] = 'success';
            $results['message'] = 'Successfully Update Zone';
        } else {
            $results['status'] = 'failed';
            $results['message'] = 'Unsuccessful to Update Zone';
        }
    }
    echo  json_encode($results);
    die();
}
function single()
{
    global $con;
    if ($_REQUEST['zoneID']) {
        $sql = "select * from zone LEFT JOIN city ON (zone.cityID=city.cityID) LEFT JOIN courier ON (zone.courierID=courier.courierID) where zone.zoneID = '" . $_REQUEST['zoneID'] . "'";
        $results = mysqli_query($con, $sql);
        $result = mysqli_fetch_assoc($results);
        $result['status'] = 'success';
        echo  json_encode($result);
    } else {
        $results['status'] = 'failed';
        echo  json_encode($results);
    }
    die();
}
function update_status()
{
    global $con;
    $result = array();
    if ($_REQUEST['zoneID']) {
        $sql = "update zone set status='" . $_REQUEST['status'] . "' where zoneID='" . $_REQUEST['zoneID'] . "'";
        $results = mysqli_query($con, $sql);
        if ($results) {
            $result['status'] = 'success';
            $result['message'] = 'Successfully Update Zone';
        } else {
            $result['status'] = 'failed';
            $result['message'] = 'Unsuccessful to Update Zone';
        }
    }

    echo  json_encode($result);
    die();
}

function delete()
{
    global $con;
    $data = array();
    $zoneID = $_REQUEST['zoneID'];
    if (!empty($zoneID)) {
        $sql = "delete from zone where zoneID=$zoneID";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $data['status'] = 'success';
            $data['message'] = 'Successfully remove Zone';
        } else {
            $data['status'] = 'failed';
            $data['message'] = 'Unsuccessful to remove Zone';
        }
    }
    echo  json_encode($data);
    die();
}

function getCity()
{
    global $con;
    $courierID = $_REQUEST['courierID'];
    $sql = "select * from city where courierID='" . $courierID . "' and status='1'";
    $results = mysqli_query($con, $sql);
    $responseArray = array();
    while ($result = mysqli_fetch_assoc($results)) {

        $responseArray[] = array(
            "id" => $result['cityID'],
            "text" => $result['CityName']
        );
    }
    $data['data'] = $responseArray;
    echo  json_encode($data);
    die();
}
