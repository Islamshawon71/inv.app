<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getCourier()
{
    global $con;
    $sql = "select * from courier";
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
    $CourierName =  $_REQUEST['CourierName'];
    $CourierCharge =  $_REQUEST['CourierCharge'];
    $HasCity =  $_REQUEST['HasCity'] == "true" ? '1' : '0';
    $HasZone =  $_REQUEST['HasZone'] == "true" ? '1' : '0';

    $results = array();
    $sql = "INSERT INTO courier VALUES (NULL,'" . $CourierName . "', '" . $HasCity . "','" . $HasZone . "', '" . $CourierCharge . "', '1')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $results['status'] = 'success';
        $results['message'] = 'Successfully Add Courier';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to add Courier';
    }
    echo  json_encode($results);
    die();
}
function update()
{
    global $con;
    $CourierName =  $_REQUEST['CourierName'];
    $CourierCharge =  $_REQUEST['CourierCharge'];
    $HasCity =  $_REQUEST['HasCity'] == "true" ? '1' : '0';
    $HasZone =  $_REQUEST['HasZone'] == "true" ? '1' : '0';
    $courierID =  $_REQUEST['courierID'];
    if ($courierID) {
        $sql = "UPDATE courier SET CourierName='" . $CourierName . "',HasCity='" . $HasCity . "',HasZone='" . $HasZone . "',CourierCharge='" . $CourierCharge . "' WHERE courierID='" . $courierID . "' ";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $results['status'] = 'success';
            $results['message'] = 'Successfully Update Courier';
        } else {
            $results['status'] = 'failed';
            $results['message'] = 'Unsuccessful to Update Courier';
        }
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to Update Courier';
    }
    echo  json_encode($results);


    die();
}
function single()
{
    global $con;
    $storeID = $_REQUEST['courierID'];
    $sql = "select * from courier where courierID = '" . $_REQUEST['courierID'] . "'";
    $results = mysqli_query($con, $sql);
    $data = array();
    $result = mysqli_fetch_assoc($results);
    $response = array(
        "status" => 'success',
        "data" =>    $result
    );
    echo  json_encode($response);
    die();
}
function changeStatus()
{
    global $con;
    $result = array();
    if (isset($_REQUEST['status'])) {
        if (!empty($_REQUEST['courierID'])) {
            $sql = "update courier set status='" . $_REQUEST['status'] . "' where courierID='" . $_REQUEST['courierID'] . "'";
            $results = mysqli_query($con, $sql);
            if ($results) {
                $result['status'] = 'success';
                $result['message'] = 'Successfully Update Courier';
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Unsuccessful to Update Courier';
            }
        }
    } else {
        $result['status'] = 'failed';
    }
    echo  json_encode($result);
    die();
}
function action($id)
{
    return '<button type="button" value="' . $id . '" class="btn btn-cyan btn-sm">Edit</button>
            <button type="button" value="' . $id . '" class="btn btn-danger btn-sm">Delete</button>';
}
function status($id, $status)
{
    if ($status) {
        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' . $id . '">Active</button>';
    } else {
        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' . $id . '" >Inactive</button> ';
    }
}
function delete()
{
    global $con;
    $data = array();
    $courierID = $_REQUEST['courierID'];
    if (!empty($courierID)) {
        $sql = "delete from courier where courierID=$courierID";
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
