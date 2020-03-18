<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getUsers()
{
    global $con;
    $sql = 'select * from users ORDER BY userID DESC';
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
    $email =  $_REQUEST['email'];
    $password =  $_REQUEST['password'];
    $userType =  $_REQUEST['userType'];


    $hasPassword = md5($password);
    $sql = "insert into users values(null,'$name','$phone','$email','$hasPassword','$userType','1')";
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
    $email =  $_REQUEST['email'];
    $password =  $_REQUEST['password'];
    $userType =  $_REQUEST['userType'];
    $userID =  $_REQUEST['userID'];
    $pass = '';
    if ($password) {
        $hasPassword = md5($password);
        $pass = " , password='" . $hasPassword . "' ";
    }
    $results = array();

    $sql = "update users set name='" . $name . "' , email='" . $email . "' , phone='" . $phone . "' , type='" . $userType . "'  $pass   where userID='" . $userID . "'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $results['status'] = 'success';
        $results['message'] = 'Successfully Update Users';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to Update Users';
    }

    echo  json_encode($results);
    die();
}

function single()
{
    global $con;
    $userID = $_REQUEST['userID'];
    $sql = "select * from users where userID = '" . $_REQUEST['userID'] . "'";
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
    $sql = "update users set status='" . $_REQUEST['status'] . "' where userID='" . $_REQUEST['userID'] . "'";
    $results = mysqli_query($con, $sql);
    if ($results) {
        $result['status'] = 'success';
        $result['message'] = 'Successfully Update User';
    } else {
        $result['status'] = 'failed';
        $result['message'] = 'Unsuccessful to Update User';
    }
    echo  json_encode($result);
    die();
}

function delete()
{
    global $con;
    $data = array();
    $userID = $_REQUEST['userID'];
    $sql = "delete from users where userID=$userID";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $data['status'] = 'success';
        $data['message'] = 'Successfully remove User';
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessful to remove User';
    }
    echo  json_encode($data);
    die();
}
