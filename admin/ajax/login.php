<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function login()
{
    global $con;
    $email = $_REQUEST['email'];
    $password = md5($_REQUEST['password']);
    $qc = mysqli_query($con, "SELECT * FROM users WHERE email='" . $email . "' AND password='" . $password . "' ");
    if (mysqli_num_rows($qc) == 1) {
        $results = mysqli_fetch_assoc($qc);
        $status = $results['status'];
        if ($status == 1) {
            session_start();
            $userID = $results['userID'];
            $name = $results['name'];
            $email = $results['email'];
            $pass = $results['password'];
            $status = $results['status'];
            $type = $results['type'];
            $enfn = md5($name);
            $_SESSION['userID'] = $userID;
            $_SESSION['enfn'] = $enfn;
            $_SESSION['type'] = $type;
            $_SESSION['name'] = $name;
            $_SESSION['password'] = $pass;
            $_SESSION['email'] = $email;
            $result['status'] = 'success';
            $result['message'] = 'Successfully Loged In';
        } else {
            $status = $results['status'];
            $result['status'] = 'failed';
            $result['message'] = 'Sorry your account deactivated !';
        }
    } else {
        $result['status'] = 'failed';
        $result['message'] = 'Wrong ID or Password!';
    }

    echo  json_encode($result);

    die();
}
