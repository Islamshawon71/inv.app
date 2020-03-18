<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function GetPayment()
{
    global $con;
    $sql = "select * from payment";
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
    $PaymentNumbe =  $_REQUEST['PaymentNumbe'];
    $PaymentType =  $_REQUEST['PaymentType'];
    $results = array();
    $sql = "INSERT INTO payment VALUES (NULL, '" . $PaymentType . "', '" . $PaymentNumbe . "', '1')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $results['status'] = 'success';
        $results['message'] = 'Successfully Add Payment';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to add Payment';
    }
    echo  json_encode($results);
    die();
}
function update()
{
    global $con;
    $PaymentNumbe =  $_REQUEST['PaymentNumbe'];
    $PaymentType =  $_REQUEST['PaymentType'];
    $paymentID =  $_REQUEST['paymentID'];
    $results = array();
    if (!empty($paymentID)) {
        $sql = "update payment set PaymentNumbe='" . $PaymentNumbe . "' , PaymentType='" . $PaymentType . "' where paymentID='" . $paymentID . "'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $results['status'] = 'success';
            $results['message'] = 'Successfully Update Payment';
        } else {
            $results['status'] = 'failed';
            $results['message'] = 'Unsuccessful to Update Payment';
        }
    }
    echo  json_encode($results);
    die();
}
function single()
{
    global $con;
    if ($_REQUEST['paymentID']) {
        $sql = "select * from payment where paymentID = '" . $_REQUEST['paymentID'] . "'";
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

    $sql = "update payment set status='" . $_REQUEST['status'] . "' where paymentID='" . $_REQUEST['paymentID'] . "'";
    $results = mysqli_query($con, $sql);
    if ($results) {
        $result['status'] = 'success';
        $result['message'] = 'Successfully Update City';
    } else {
        $result['status'] = 'failed';
        $result['message'] = 'Unsuccessful to Update City';
    }


    echo  json_encode($result);
    die();
}

function delete()
{
    global $con;
    $data = array();
    $paymentID = $_REQUEST['paymentID'];
    if ($paymentID) {
        $sql = "delete from payment where paymentID=$paymentID";
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
