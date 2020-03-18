<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}
function save()
{
    global $con;
    $storeName = $_REQUEST['storeName'];
    $storeURL = $_REQUEST['storeURL'];
    $sql = "INSERT INTO store VALUES (NULL, '" . $storeName . "', '" . $storeURL . "', '1')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Successfully Add Store';
    } else {
        $response['status'] = 'failed';
        $response['message'] = 'Unsuccessful to add Store';
    }
    echo  json_encode($response);
    die();
}
function getStore()
{
    global $con;
    $sql = "SELECT * FROM store ORDER by storeID DESC";
    $results = mysqli_query($con, $sql);
    $data = array();

    while ($result = mysqli_fetch_assoc($results)) {
        $result['total'] = $result['storeID'];
        $data[] = $result;
    }

    $response = array(
        "data" =>    $data
    );

    echo  json_encode($response);
    die();
}
function delete()
{
    global $con;
    $storeID = $_REQUEST['storeID'];
    $sql = "delete from store where storeID=$storeID";
    if (mysqli_query($con, $sql)) {
        $data['status'] = 'success';
        $data['message'] = 'Successfully Remove Store';
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Something went wrong!';
    }
    echo  json_encode($data);
    die();
}
function single()
{
    global $con;
    $storeID = $_REQUEST['storeID'];
    $sql = "SELECT * FROM store where storeID='" . $storeID . "'";
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
function update()
{
    global $con;
    $storeID =  $_REQUEST['storeID'];
    $storeName =  $_REQUEST['storeName'];
    $storeURL =  $_REQUEST['storeURL'];
    $results = array();
    if (!empty($storeID)) {
        $sql = "update store set storeName='" . $storeName . "' , storeURL='" . $storeURL . "' where storeID='" . $storeID . "'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $results['status'] = 'success';
            $results['message'] = 'Successfully Update Store';
        } else {
            $results['status'] = 'failed';
            $results['message'] = 'Something went wrong!';
        }
    }
    echo  json_encode($results);
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
function changeStatus()
{
    global $con;
    $storeID =  $_REQUEST['storeID'];
    $status =  $_REQUEST['status'];
    if ($storeID) {
        $sql = "update store set status='" . $status . "' where storeID='" . $storeID . "'";
        if (mysqli_query($con, $sql)) {
            $result['status'] = 'success';
            if ($status == 1) {
                $result['message'] = 'Store Activated';
            } else {
                $result['message'] = 'Store Deactivated';
            }
        } else {
            $result['status'] = 'failed';
            $result['message'] = 'Something went wrong!';
        }
    }
    echo  json_encode($result);
    die();
}
