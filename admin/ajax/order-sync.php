<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}
function ordersync()
{
    global $con;

    $users = "SELECT * FROM users where type like 'User' and status='1'";
    $user = mysqli_query($con, $users);
    $user_ids =  array();
    while ($userresult = mysqli_fetch_assoc($user)) {
        $user_ids[] = $userresult['userID'];
    }
    $usercount = count($user_ids);
    $sql = 'select * from store where status = 1';
    $stores = mysqli_query($con, $sql);
    $orderCount = 0;
    while ($store = mysqli_fetch_assoc($stores)) {
        $orders = json_decode(getorders($store['storeURL']));
        foreach ($orders as $order) {

            $sql = "SELECT * FROM orders where webID='" . $order->wp_id . "'";
            $results = mysqli_query($con, $sql);
            $result = mysqli_fetch_assoc($results);
            if (!$result) {
                if ($usercount == 0) {
                    $userID  = 1;
                } else {
                    $userID = $user_ids[rand(0, $usercount - 1)];
                }
                $today = date("dm");
                $rand = strtoupper(substr(uniqid(md5($order->customer->phone)), 0, 4));
                $invoiceID = $today . $rand;
                $total = $order->total;
                $DeliveryCharge = 100;
                $DiscountCharge = 0;
                $storeID = $store['storeID'];
                $userID = $userID;
                $courierID = NULL;
                $cityID = NULL;
                $zoneID = NULL;
                $CustomerName = $order->customer->first_name;
                $CustomerPhone = $order->customer->phone;
                $CustomerAddress = $order->customer->address_1;
                $PaymentType = NULL;
                $PaymentNumber = NULL;
                $PaymentAmount = NULL;
                $PaymentAgentNumber = NULL;
                $products = $order->products;
                $webID = $order->wp_id;

                $orderData = "INSERT INTO orders values (null, '" . $total . "',  '" . $DeliveryCharge . "',  '" . $DiscountCharge . "',  '" . $PaymentAmount . "', CURDATE(),  null , null,  null ,  '" . $invoiceID . "', '" . $webID . "',  '" . $userID . "',  '" . $storeID . "',  '0','Processing') ";
                mysqli_query($con, $orderData);
                $orderID = mysqli_insert_id($con);
                $orderDetails = "INSERT INTO orderDetails values ('" . $orderID . "','" . $CustomerName . "', '" . $CustomerPhone . "', '" . $CustomerAddress . "', '" . $courierID . "',  '" . $cityID . "',  '" . $zoneID . "', '" . $PaymentType . "',  '" . $PaymentNumber . "', '" . $PaymentAgentNumber . "') ";
                $orderDetails = mysqli_query($con, $orderDetails);
                for ($i = 0; $i < count($products); $i++) {

                    $productsCheck = "SELECT * FROM products where productCode='" . $products[$i]->sku . "'";
                    $productsCheck = mysqli_query($con, $productsCheck);
                    $productID = mysqli_fetch_assoc($productsCheck);

                    $orderProduct = "INSERT INTO orderProduct values ('" . $orderID . "', '" . $productID['productID']  . "', '" . $products[$i]->product_name  . "','" . $products[$i]->price  . "' , '" . $products[$i]->quantity  . "') ";
                    $orderProduct = mysqli_query($con, $orderProduct);
                }
                if ($orderID && $orderDetails && $orderProduct) {
                    $result['status'] = 'success';
                    $result['message'] = 'Successfully Place Order';
                    $orderCount++;
                    $statusComments =  "Order Created ";
                    comments($invoiceID, $statusComments, 1);
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
            }
        }
    }


    // var_dump($items);


    if ($orderCount > 0) {
        $data['status'] = 'success';
        $data['message'] = 'Successfully Sync Orders';
        $data['order'] = $orderCount;
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessfully Sync Orders';
        $data['order'] = $orderCount;
    }

    header("Access-Control-Allow-Origin: *");
    echo  json_encode($data);

    die();
}
function comments($orderID, $comments, $userID)
{
    global $con;
    $sql =  "INSERT INTO comments VALUES (null,'" . $orderID . "','" . $comments . "','" . $userID . "',now(),'0')";
    mysqli_query($con, $sql);
}

function getorders($url)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . "wp-json/inventory/v1/order/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    return $response;
}
