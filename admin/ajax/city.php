<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function get()
{
    global $con;
    $sql = "select city.*,courier.courierName as courierName from city LEFT JOIN courier on (city.courierID=courier.courierID)";
    $results = mysqli_query($con, $sql);
    $temp = array();
    while ($result = mysqli_fetch_assoc($results)) {
        $temp[] = $result;
    }
    $data['data'] = $temp;
    echo  json_encode($data);
    die();
}

// Get Courier 
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

// Save Data
function save()
{
    global $con;
    $CityName =  $_REQUEST['CityName'];
    $courierID =  $_REQUEST['courierID'];
    $results = array();
    $sql = "INSERT INTO city VALUES (NULL, '" . $courierID . "', '" . $CityName . "', '1')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $results['status'] = 'success';
        $results['message'] = 'Successfully Add City';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to add City';
    }
    echo  json_encode($results);
    die();
}
function update()
{
    global $con;
    $CityName =  $_REQUEST['CityName'];
    $courierID =  $_REQUEST['courierID'];
    $cityID =  $_REQUEST['cityID'];
    $results = array();
    if (!empty($cityID)) {
        $sql = "update city set CityName='" . $CityName . "' , courierID='" . $courierID . "' where cityID='" . $cityID . "'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $results['status'] = 'success';
            $results['message'] = 'Successfully Update City';
        } else {
            $results['status'] = 'failed';
            $results['message'] = 'Unsuccessful to Update City';
        }
    }
    echo  json_encode($results);
    die();
}
function single()
{
    global $con;
    if ($_REQUEST['cityID']) {
        $sql = "select * from city LEFT JOIN courier ON (city.courierID=courier.courierID) where city.cityID = '" . $_REQUEST['cityID'] . "'";
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
    if (isset($_REQUEST['status'])) {
        if (!empty($_REQUEST['cityID'])) {
            $sql = "update city set status='" . $_REQUEST['status'] . "' where cityID='" . $_REQUEST['cityID'] . "'";
            $results = mysqli_query($con, $sql);
            if ($results) {
                $result['status'] = 'success';
                $result['message'] = 'Successfully Update City';
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Unsuccessful to Update City';
            }
        }
    } else {
        $result['status'] = 'failed';
    }
    echo  json_encode($result);
    die();
}

function delete()
{
    global $con;
    $data = array();
    $cityID = $_REQUEST['cityID'];
    if ($cityID) {
        $sql = "delete from city where cityID=$cityID";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $data['status'] = 'success';
            $data['message'] = 'Successfully remove Courier';
        } else {
            $data['status'] = 'failed';
            $data['message'] = 'Unsuccessful to remove Courier';
        }
    }
    echo  json_encode($data);
    die();
}
