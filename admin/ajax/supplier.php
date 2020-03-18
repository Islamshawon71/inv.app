<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getSupplier()
{
    global $con;
    $sql = 'select * from suppliers ORDER BY supplierID DESC';
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
    $name =  $_REQUEST['name'];
    $phone =  $_REQUEST['phone'];

    $sql = "insert into suppliers values(null,'$name','$phone')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Successfully Add User';
    } else {
        $response['status'] = 'failed';
        $response['message'] = 'Unsuccessful to add User';
    }
    echo  json_encode($response);
    die();
}

function update()
{
    global $con;
    $name =  $_REQUEST['name'];
    $phone =  $_REQUEST['phone'];
    $supplierID =  $_REQUEST['supplierID'];
    $sql = "update suppliers set supplierName='" . $name . "' , supplierPhone='" . $phone . "'  where supplierID='" . $supplierID . "'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $results['status'] = 'success';
        $results['message'] = 'Successfully Update Suppliers';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to Update Suppliers';
    }

    echo  json_encode($results);
    die();
}

function single()
{
    global $con;
    $supplierID = $_REQUEST['supplierID'];
    $sql = "select * from suppliers where supplierID = '" . $supplierID . "'";
    $results = mysqli_query($con, $sql);
    $result = mysqli_fetch_assoc($results);
    $response = array(
        "status" => 'success',
        "data" =>    $result
    );
    echo  json_encode($response);
    die();
}

function delete()
{
    global $con;
    $data = array();
    $supplierID = $_REQUEST['supplierID'];
    $sql = "delete from suppliers where supplierID=$supplierID";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $data['status'] = 'success';
        $data['message'] = 'Successfully remove Supplier';
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessful to remove Supplier';
    }
    echo  json_encode($data);
    die();
}
