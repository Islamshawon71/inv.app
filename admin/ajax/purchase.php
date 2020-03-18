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

        $sql = "SELECT * FROM productStock";
        $results = mysqli_query($con, $sql);
        $result = mysqli_fetch_assoc($results);
        if ($result) {
            $stock = $result['stock'] + $quantity;
            $update = "update productStock set stock='" . $stock . "' where productID='" . $productID . "' ";
            mysqli_query($con, $update);
        } else {
            $sql = "insert into productStock values(null,'$productID',$quantity)";
            mysqli_query($con, $sql);
        }
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
    $oldQuantity =  $_REQUEST['oldQuantity'];
    $purchaseID =  $_REQUEST['purchaseID'];

    $sql = "update purchase set supplierID='" . $supplierID . "' , productID='" . $productID . "' , quantity='" . $quantity . "' , date='" . $date . "'   where purchaseID='" . $purchaseID . "'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $sql = "SELECT * FROM productStock where productID='" . $productID . "' ";
        $productStock = mysqli_query($con, $sql);
        $productStockResult = mysqli_fetch_assoc($productStock);
        if ($productStockResult) {
            $stock = $productStockResult['stock'] - $oldQuantity + $quantity;
            $stockUpdate = "update productStock set stock='" . $stock . "' where productID='" . $productID . "' ";
            mysqli_query($con, $stockUpdate);
        } else {
            $sql = "insert into productStock values(null,'$productID',$quantity)";
            mysqli_query($con, $sql);
        }
        $results['status'] = 'success';
        $results['message'] = 'Successfully Update Purchase';
    } else {
        $results['status'] = 'failed';
        $results['message'] = 'Unsuccessful to Update Purchase';
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
    $purchaseID = $_REQUEST['purchaseID'];
    $purchaseProductSql = "SELECT * FROM purchase where purchaseID='" . $purchaseID . "' ";
    $purchaseProducts = mysqli_query($con, $purchaseProductSql);
    $purchaseProduct = mysqli_fetch_assoc($purchaseProducts);
    $productID = $purchaseProduct['productID'];
    $quantity = $purchaseProduct['quantity'];
    $sql = "delete from purchase where purchaseID=$purchaseID";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $sql = "SELECT * FROM productStock where productID='" . $productID . "' ";
        $productStock = mysqli_query($con, $sql);
        $productStockResult = mysqli_fetch_assoc($productStock);
        if ($productStockResult) {
            $stock = $productStockResult['stock'] - $quantity;
            $stockUpdate = "update productStock set stock='" . $stock . "' where productID='" . $productID . "' ";
            mysqli_query($con, $stockUpdate);
        }
        $data['status'] = 'success';
        $data['message'] = 'Successfully remove Purchase';
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessful to remove Purchase';
    }
    echo  json_encode($data);
    die();
}
