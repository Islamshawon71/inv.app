<?php
include_once '../../inc/databaseConnection.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}

function getPurchase()
{
    global $con;
    $sql = 'select * from products
    left join productStock on (products.productID = productStock.productID) 
     ORDER BY productStock.stock DESC';
    $results = mysqli_query($con, $sql);
    $temp = array();
    while ($result = mysqli_fetch_assoc($results)) {

        $sumSql = "SELECT SUM(quantity) FROM purchase where  productID = '" . $result['productID'] . "'";
        $sumResults = mysqli_query($con, $sumSql);
        $sumResult = mysqli_fetch_assoc($sumResults);
        $result['purchase'] = $sumResult['SUM(quantity)'];
        $temp[] = $result;
    }

    $data['data'] = $temp;
    echo  json_encode($data);
    die();
}
