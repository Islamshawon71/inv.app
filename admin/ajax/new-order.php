<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getTigerDelivery($id)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://adcore.ajkerdeal.com/api/Other/GetAllDistrictFromApi/" . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Access-Control-Allow-Origin:  *",
            "Accept: application/json",
            "Origin:  https://deliverytiger.com.bd",
            "Authorization:  Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjU1NzUiLCJyb2xlIjoiRGVsaXZlcnlUaWdlciIsIm5iZiI6MTU3OTA3OTUzMSwiZXhwIjoxNTc5MTY1OTMxLCJpYXQiOjE1NzkwNzk1MzF9.ntLwCCiauTUDLAGN0NHfAZYqVO1z8b6eI_6fjhs0Wag",
            "User-Agent:  Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36",
            "Content-Type:  application/json",
            "Sec-Fetch-Site:  cross-site",
            "Sec-Fetch-Mode:  cors",
            "Referer:  https://deliverytiger.com.bd/add-order",
            "Accept-Encoding:  gzip, deflate, br",
            "Accept-Language:  en-US,en;q=0.9"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

function getCourier()
{
    global $con;
    $sql = $search = '';
    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $search = " where CourierName like '%" . $search . "%'";
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
    echo json_encode($data);
    die();
}

// 
function getCity()
{
    global $con;
    $responseArray = array();

    $sql = $search = '';
    $courierID = $_REQUEST['courierID'];
    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $search = " and CityName like '%" . $search . "%'";
        }
    }
    $sql = "select * from city where courierID='" . $courierID . "' " . $search;
    $results = mysqli_query($con, $sql);

    while ($result = mysqli_fetch_assoc($results)) {

        $responseArray[] = array(
            "id" => $result['cityID'],
            "text" => $result['CityName']
        );
    }
    $data['data'] = $responseArray;
    echo json_encode($data);
    die();
}

function getZone()
{
    global $con;
    $sql = $search = '';
    $cityID = $_REQUEST['cityID'];
    $courierID = $_REQUEST['courierID'];

    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $search = " and ZoneName like '%" . $search . "%'";
        }
    }
    $sql = "select * from zone where cityID='" . $cityID . "' " . $search;
    $results = mysqli_query($con, $sql);
    $responseArray = array();
    while ($result = mysqli_fetch_assoc($results)) {
        $responseArray[] = array(
            "id" => $result['zoneID'],
            "text" => $result['ZoneName']
        );
    }

    $data['data'] = $responseArray;
    echo json_encode($data);
    die();
}

// get bkash load numbers
function PaymentNumber()
{
    global $con;
    $PaymentType = $_REQUEST['PaymentType'];
    $sql = "select * from payment where PaymentType like '" . $PaymentType . "' and status='1'";
    $results = mysqli_query($con, $sql);
    $responseArray = array();
    while ($result = mysqli_fetch_assoc($results)) {
        $responseArray[] = array(
            "id" => $result['paymentID'],
            "text" => $result['PaymentNumbe']
        );
    }
    $data['data'] = $responseArray;
    echo json_encode($data);
    die();
}

// place order
function placeOrder()
{

    $invoiceID = $_REQUEST['data']['invoiceID'];
    $total = $_REQUEST['data']['total'];
    $DiscountCharge = $_REQUEST['data']['DiscountCharge'];
    $DeliveryCharge = $_REQUEST['data']['DeliveryCharge'];
    $storeID = $_REQUEST['data']['storeID'];
    $userID = $_REQUEST['data']['userID'];
    $courierID = $_REQUEST['data']['courierID'];
    $cityID = $_REQUEST['data']['cityID'];
    $zoneID = $_REQUEST['data']['zoneID'];
    $CustomerName = $_REQUEST['data']['CustomerName'];
    $CustomerPhone = $_REQUEST['data']['CustomerPhone'];
    $CustomerAddress = $_REQUEST['data']['CustomerAddress'];
    $PaymentType = $_REQUEST['data']['PaymentType'];
    // $PaymentNumber = $_REQUEST['data']['PaymentNumber'];
    $PaymentNumber = !empty($_REQUEST['data']['PaymentNumber']) ? $_REQUEST['data']['PaymentNumber'] : 'null';
    $PaymentAmount = isset($_REQUEST['data']['PaymentAmount']) ? $_REQUEST['data']['PaymentAmount'] : 'null';
    $PaymentAgentNumber = !empty($_REQUEST['data']['PaymentAgentNumber']) ? $_REQUEST['data']['PaymentAgentNumber'] : 'null';
    $products = $_REQUEST['data']['products'];

    global $con;


    $order = "INSERT INTO orders values (null, '" . $total . "',  '" . $DeliveryCharge . "',  '" . $DiscountCharge . "',  '" . $PaymentAmount . "', CURDATE(),  null , null,  null ,  '" . $invoiceID . "', null,  '" . $userID . "',  '" . $storeID . "',  '1','Processing') ";
    mysqli_query($con, $order);
    $orderID = mysqli_insert_id($con);
    $orderDetails = "INSERT INTO orderDetails values ('" . $orderID . "','" . $CustomerName . "', '" . $CustomerPhone . "', '" . $CustomerAddress . "', '" . $courierID . "',  '" . $cityID . "',  '" . $zoneID . "', '" . $PaymentType . "',  '" . $PaymentNumber . "', '" . $PaymentAgentNumber . "') ";
    $orderDetails = mysqli_query($con, $orderDetails);
    for ($i = 0; $i < count($products); $i++) {
        $orderProduct = "INSERT INTO orderProduct values ('" . $orderID . "', '" . $products[$i]['productID']  . "', '" . $products[$i]['productName']  . "','" . $products[$i]['productPrice']  . "' , '" . $products[$i]['productQuantity']  . "') ";
        $orderProduct = mysqli_query($con, $orderProduct);
    }
    if ($orderID && $orderDetails && $orderProduct) {
        $result['status'] = 'success';
        $result['message'] = 'Successfully Place Order';
    } else {
        $sql = "delete from orders where orderID='" . $orderID . "'";
        mysqli_query($con, $sql);
        $sql = "delete from orderDetails where orderID='" . $orderID . "'";
        mysqli_query($con, $sql);
        $sql = "delete from orderProduct where orderID='" . $orderID . "'";
        mysqli_query($con, $sql);

        $result['status'] = 'failed';
        $result['message'] = 'Unsuccessful to Place Order';
    }
    echo json_encode($result);
    die();
}
function getProducts()
{
    global $con;
    $sql = "SELECT * FROM products";

    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        if ($search) {
            $sql = $sql . " where productName like '%" . $search . "%'";
        }
    }
    $results = mysqli_query($con, $sql);
    while ($result = mysqli_fetch_assoc($results)) {
        $responseArray[] = array(
            "id" => $result['productID'],
            "text" => $result['productName'],
            "image" => $result['productImage'],
            "productCode" => $result['productCode'],
            "productPrice" => $result['productPrice']
        );
    }
    $data['data'] = $responseArray;
    echo json_encode($data);
    die();
}
