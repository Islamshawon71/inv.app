<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getPurchase()
{
    global $con;
    $sql = 'select * from purchase 
    left join suppliers on (purchase.supplierID = suppliers.supplierID) 
    left join products on (purchase.productID = products.productID) 
    ORDER BY purchaseID DESC';
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
function getSupplier()
{
    global $con;
    $sql = $search = '';
    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $search = " where supplierName like '%" . $search . "%'";
        }
    }
    $sql = "select * from suppliers " . $search;
    $results = mysqli_query($con, $sql);
    $responseArray = array();
    while ($result = mysqli_fetch_assoc($results)) {

        $responseArray[] = array(
            "id" => $result['supplierID'],
            "text" => $result['supplierName']
        );
    }
    $data['data'] = $responseArray;
    echo  json_encode($data);
    die();
}

// Get Courier 
function getProduct()
{
    global $con;
    $sql = $search = '';
    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $search = " where productName like '%" . $search . "%'";
        }
    }
    $sql = "select * from products " . $search;
    $results = mysqli_query($con, $sql);
    $responseArray = array();
    while ($result = mysqli_fetch_assoc($results)) {

        $responseArray[] = array(
            "id" => $result['productID'],
            "text" => $result['productName']
        );
    }
    $data['data'] = $responseArray;
    echo  json_encode($data);
    die();
}



function save()
{
    global $con;
    $date =  $_REQUEST['date'];
    $supplierID =  $_REQUEST['supplierID'];
    $productID =  $_REQUEST['productID'];
    $quantity =  $_REQUEST['quantity'];

    $sql = "insert into purchase values(null,'$supplierID','$productID','$quantity','$date')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Successfully Add Purchase';
    } else {
        $response['status'] = 'failed';
        $response['message'] = 'Unsuccessful to add Purchase';
    }
    echo  json_encode($response);
    die();
}
function update()
{
    global $con;
    $date =  $_REQUEST['date'];
    $supplierID =  $_REQUEST['supplierID'];
    $productID =  $_REQUEST['productID'];
    $quantity =  $_REQUEST['quantity'];
    $purchaseID =  $_REQUEST['purchaseID'];

    $sql = "update purchase set supplierID='" . $supplierID . "' , productID='" . $productID . "' , quantity='" . $quantity . "' , date='" . $date . "'   where purchaseID='" . $purchaseID . "'";
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
    $purchaseID = $_REQUEST['purchaseID'];
    $sql = " select purchase.*,suppliers.supplierName as supplierName,products.productName as productName
    from purchase 
    left join suppliers on (purchase.supplierID = suppliers.supplierID) 
    left join products on (purchase.productID = products.productID) where purchase.purchaseID = '" . $purchaseID . "'";
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

function delete()
{
    global $con;
    $data = array();
    $purchaseID = $_REQUEST['purchaseID'];
    $sql = "delete from purchase where purchaseID=$purchaseID";
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
