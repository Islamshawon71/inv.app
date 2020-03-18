<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getProducts()
{
    global $con;
    $sql = 'select * from products 
    ORDER BY productID DESC';
    $results = mysqli_query($con, $sql);
    $temp = array();
    while ($result = mysqli_fetch_assoc($results)) {
        $temp[] = $result;
    }

    $data['data'] = $temp;
    echo  json_encode($data);
    die();
}

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



function single()
{
    global $con;
    $productID = $_REQUEST['productID'];
    $sql = " select * from products  where productID = '" . $productID . "'";
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
    $productID = $_REQUEST['productID'];
    $sql = "delete from products where productID=$productID";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $data['status'] = 'success';
        $data['message'] = 'Successfully remove Product';
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessful to remove Product';
    }
    echo  json_encode($data);
    die();
}


function producSync()
{
    global $con;

    $storeID = $_REQUEST['storeID'];

    $sql = "SELECT * FROM store where storeID='$storeID'";
    $results = mysqli_query($con, $sql);
    $result = mysqli_fetch_assoc($results);
    $result['storeURL'] . "wp-json/inventory/v1/products/";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $result['storeURL'] . "wp-json/inventory/v1/products/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    $items = json_decode($response);
    $orderCount = 0;
    foreach ($items->data as $item) {
        $sql = "SELECT * FROM products where productCode='" . $item->sku . "'";
        $results = mysqli_query($con, $sql);
        $result = mysqli_fetch_assoc($results);
        if (count($result) == 0) {

            $productCode = $item->sku;
            $productName = $item->text;
            $productPrice = $item->price;
            $productImage = 'test';
            $sql = "INSERT INTO products values (null, '" . $productCode . "', '" . $productName . "', '" . $productImage . "', '" . $productPrice . "') ";
            mysqli_query($con, $sql);
            $productID = mysqli_insert_id($con);
            // Remote image URL
            $url = $item->image;
            // Image path
            $img = '../images/' . $productID . '.jpg';
            $Dbimg = 'images/' . $productID . '.jpg';
            // Save image 
            file_put_contents($img, file_get_contents($url));
            $sql = "update  products set productImage = '" . $Dbimg . "' where productID='" . $productID . "' ";
            mysqli_query($con, $sql);
            $orderCount++;
        }
    }
    if ($orderCount > 0) {
        $data['status'] = 'success';
        $data['message'] = 'Successfully Sync Orders';
        $data['product'] = $orderCount;
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessfully Sync Orders';
        $data['product'] = $orderCount;
    }

    header("Access-Control-Allow-Origin: *");
    echo  json_encode($data);

    die();
}
