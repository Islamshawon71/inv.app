<?php
function countOrderByStatus($status)
{
    global $con;
    $sql = "select * from orders ";
    if ($status != 'All') {
        $sql = $sql . "where orders.status like '" . $status . "'";
    } else {
        $sql = $sql . " where orders.status like 'Processing' or orders.status like 'On Hold' or orders.status like 'Cancelled'  or orders.status like 'Pending Payment'  or orders.status like 'Completed'  ORDER BY orderID DESC";
    }
    $results = mysqli_query($con, $sql);
    return mysqli_num_rows($results);
}
function countOrderByStatusInvoiceOrder($status)
{
    global $con;
    $sql = "select * from orders ";
    if ($status != 'All') {
        $sql = $sql . "where orders.status like '" . $status . "'";
    } else {
        $sql = $sql . "where orders.status like 'Invoiced' or orders.status like 'Stock Out' or orders.status like 'Pending Invoice' ";
    }
    $results = mysqli_query($con, $sql);
    return mysqli_num_rows($results);
}
function orders($status = null)
{

    global $con;
    $sql = "SELECT orders.*,
            customerDetails.customerName,customerDetails.customerPhone,customerDetails.customerAddress ,
            orderBkashLoad.orderBkashLoad,orderBkashLoad.orderBkashLoadNumber,orderBkashLoad.orderBkashLoadAmount,
            courier.courierName,
            city.cityName,
            zone.zoneName,
            users.name as userName
            FROM orders 
            LEFT JOIN customerDetails on (orders.orderID = customerDetails.orderID) 
            LEFT JOIN orderBkashLoad on (orders.orderID = orderBkashLoad.orderID)
            LEFT JOIN orderCourier on (orders.orderID = orderCourier.orderID)
            LEFT JOIN courier on (orderCourier.courierID = courier.courierID)
            LEFT JOIN city on (orderCourier.cityID = city.cityID)
            LEFT JOIN zone on (orderCourier.zoneID = zone.zoneID)
            LEFT JOIN users on (orders.userID = users.userID)";

    if (isset($status) && $status != 'All') {
        $sql = $sql . " where orders.status like '" . $status . "'";
    }
    $results = mysqli_query($con, $sql);
    $products = "select * from orderDetails where orderID='" . $result['orderID'] . "'";
    $product = mysqli_query($con, $products);
    $array = '';
    while ($item = mysqli_fetch_assoc($product)) {
        $array  =  $array . $item['productName'] . ' (' . $item['productID'] . ' )<br>';
    }
    $results['products'] = $array;


    return ($results);
}
function invoiceID()
{
    $today = date("Ym");
    $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
    return $unique = $today . $rand;
}
function orderStatus()
{
    $status = array(
        'Processing' => [
            'text' => 'Processing',
            'badge' => '<span class="badge badge-success"> Processing </span>'
        ],
        'Pending Payment' => [
            'text' => 'Pending Payment',
            'badge' => '<span class="badge badge-info"> Pending Payment </span>'
        ],
        'On Hold' => [
            'text' => 'On Hold',
            'badge' => '<span class="badge badge-warning"> On Hold </span>'
        ],
        'Cancelled' => [
            'text' => 'Cancelled',
            'badge' => '<span class="badge badge-danger"> Cancelled </span>'
        ],
        'Completed' => [
            'text' => 'Completed',
            'badge' => '<span class="badge badge-success"> Completed </span>'
        ],
        'Pending Invoice' => [
            'text' => 'Pending Invoice',
            'badge' => '<span class="badge badge-success"> Pending Invoice</span>'
        ],
        'Invoiced' => [
            'text' => 'Invoiced',
            'badge' => '<span class="badge badge-success"> Invoiced</span>'
        ],
        'Stock Out' => [
            'text' => 'Stock Out',
            'badge' => '<span class="badge badge-warning"> Stock Out</span>'
        ],
        'Delivered' => [
            'text' => 'Delivered',
            'badge' => '<span class="badge badge-warning"> Delivered</span>'
        ],
        'Confirm By Customer' => [
            'text' => 'Confirm By Customer',
            'badge' => '<span class="badge badge-info"> Confirm By Customer</span>'
        ],
        'Paid' => [
            'text' => 'Paid',
            'badge' => '<span class="badge badge-success"> Paid</span>'
        ],
        'Return' => [
            'text' => 'Return',
            'badge' => '<span class="badge badge-warning"> Return</span>'
        ],
        'courier Lost' => [
            'text' => 'courier Lost',
            'badge' => '<span class="badge badge-warning"> courier Lost</span>'
        ]
    );
    return $status;
}
function invoiceOrderStatus()
{
    $status = array(
        'Invoice' => [
            'text' => 'Invoice',
            'badge' => '<span class="badge badge-success"> Invoice</span>'
        ],
        'Stock Out' => [
            'text' => 'Stock Out',
            'badge' => '<span class="badge badge-info"> Stock Out</span>'
        ],
        'Delivered' => [
            'text' => 'Delivered',
            'badge' => '<span class="badge badge-warning"> Delivered </span>'
        ]
    );
    return $status;
}
