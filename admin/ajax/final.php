<?php
session_start();
include_once '../../inc/databaseConnection.php';
include_once '../../inc/fn.php';
if (isset($_REQUEST['action'])) {
    $_REQUEST['action']();
}
function getOrder()
{
    global $con;
    $sql = "SELECT orders.*,
            orderDetails.*,
            courier.CourierName, 
            users.name as userName
            FROM orders 
            LEFT JOIN orderDetails on (orders.orderID = orderDetails.orderID)  
            LEFT JOIN courier on (orderDetails.courierID = courier.courierID) 
            LEFT JOIN users on (orders.userID = users.userID)";


    $orderType = $_REQUEST['orderType'];

    if ($orderType != 'All') {
        $sql = $sql . " where orders.status like '" . $orderType . "' ORDER BY orders.orderID DESC";
    } else {
        $sql = $sql . " where orders.status like 'Processing' or orders.status like 'On Hold' or orders.status like 'Cancelled'  or orders.status like 'Pending Payment'  or orders.status like 'Completed'  ORDER BY orders.orderID DESC";
    }
    // echo $sql;
    $results = mysqli_query($con, $sql);
    $temp = array();
    while ($result = mysqli_fetch_assoc($results)) {


        $products = "select * from orderProduct where orderID='" . $result['orderID'] . "'";
        $product = mysqli_query($con, $products);
        $array = '';
        while ($item = mysqli_fetch_assoc($product)) {
            $array  =  $array . $item['productName'];
        }
        $status = orderStatus();
        $result['status'] = $status[$result['status']]['badge'];

        $result['products'] = $array;
        $temp[] = $result;
    }

    $response = array(
        "data" =>    $temp
    );

    echo  json_encode($response);
    die();
}

function assign()
{
    global $con;
    $result = array();
    $assignUserID =  $_REQUEST['assignUserID'];
    $userID =  $_REQUEST['userID'];
    $ids =  $_REQUEST['ids'];
    if (isset($userID) && !empty($ids)) {

        for ($i = 0; $i < count($ids); $i++) {
            $orderID = $ids[$i];
            $sql = "update orders set userID='" . $assignUserID . "' where orderID='" . $orderID . "'";
            $results = mysqli_query($con, $sql);
            $comments = getUser($userID) . ' Assign this order to ' . getUser($assignUserID);
            comments($orderID, $comments, $userID);
        }

        if ($results) {
            $result['status'] = 'success';
            $result['message'] = 'Successfully Update Courier';
        } else {
            $result['status'] = 'failed';
            $result['message'] = 'Unsuccessful to Update Courier';
        }
    } else {
        $result['status'] = 'failed';
    }
    echo  json_encode($result);
    die();
}

function deleteOrder()
{
    global $con;
    $ids = $_REQUEST['ids'];
    $data = array();
    if ($ids) {
        for ($i = 0; $i < count($ids); $i++) {
            $orderID = $ids[$i];
            if (!empty($orderID)) {
                $sql = "delete from orders where orderID=$orderID";
                mysqli_query($con, $sql);
                $sql = "delete from orderCourier where orderID=$orderID";
                mysqli_query($con, $sql);
                $sql = "delete from customerDetails where orderID=$orderID";
                mysqli_query($con, $sql);
                $sql = "delete from orderBkashLoad where orderID=$orderID";
                mysqli_query($con, $sql);
                $sql = "delete from orderDetails where orderID=$orderID";
                mysqli_query($con, $sql);
                $data['status'] = 'success';
                $data['message'] = 'Successfully remove Orders';
            } else {
                $data['status'] = 'failed';
                $data['message'] = 'Unsuccessful to remove Orders';
            }
        }
        echo  json_encode($data);
        die();
    }
}
function delete()
{
    global $con;
    $data = array();
    $orderID = $_REQUEST['orderID'];
    if (!empty($orderID)) {
        $sql = "delete from orders where orderID=$orderID";
        mysqli_query($con, $sql);
        $sql = "delete from orderCourier where orderID=$orderID";
        mysqli_query($con, $sql);
        $sql = "delete from customerDetails where orderID=$orderID";
        mysqli_query($con, $sql);
        $sql = "delete from orderBkashLoad where orderID=$orderID";
        mysqli_query($con, $sql);
        $sql = "delete from orderDetails where orderID=$orderID";
        mysqli_query($con, $sql);
        $data['status'] = 'success';
        $data['message'] = 'Successfully remove Order';
    } else {
        $data['status'] = 'failed';
        $data['message'] = 'Unsuccessful to remove Order';
    }
    echo  json_encode($data);
    die();
}

function chnageStatus()
{
    global $con;
    $status = $_REQUEST['status'];
    $userID = $_REQUEST['userID'];
    $ids = $_REQUEST['ids'];
    $result = array();
    $count = 0;
    foreach ($ids as $invoiceID) {
        if ($status == 'Completed') {
            $sql = "update orders set OrderDate= CURDATE(), status='Completed'  where invoiceID='" . $invoiceID . "'";
        } else {
            $sql = "update orders set status='" . $status . "'  where invoiceID='" . $invoiceID . "'";
        }
        $results = mysqli_query($con, $sql);
        $comments = getUser($userID) . " Update order Status to " . $status;
        comments($invoiceID, $comments, $userID);
        if ($results) {
            $count++;
        }
    }
    if ($count > 0) {
        $result['status'] = 'success';
        $result['message'] = 'Successfully Update Courier';
    } else {
        $result['status'] = 'failed';
        $result['message'] = 'Unsuccessful to Update Courier';
    }
    echo  json_encode($result);
    die();
}

function single($orderID)
{
    global $con;
    if ($orderID) {
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
        LEFT JOIN users on (orders.userID = users.userID) where orders.orderID = '" . $orderID . "'";
        $results = mysqli_query($con, $sql);
        $result = mysqli_fetch_assoc($results);
        return $result;
    } else {
        return 'Something Wrong !';
    }
    die();
}

function updateStatus()
{

    global $con;
    $status = $_REQUEST['status'];
    $userID = $_REQUEST['userID'];
    $orderID = $_REQUEST['orderID'];
    $comments = $_REQUEST['comments'];
    $result = array();
    $count = 0;
    if (!empty($status)) {
        if ($status == 'Delivered') {
            $sql = "update orders set DeliveryDate= CURDATE(), status='Delivered'  where orderID='" . $orderID . "'";
        } else if ($status == 'Paid') {
            $sql = "update orders set paidReturnDate= CURDATE(), status='Paid'  where orderID='" . $orderID . "'";
        } else if ($status == 'Return') {
            $sql = "update orders set paidReturnDate= CURDATE(), status='Return'  where orderID='" . $orderID . "'";
        } else {
            $sql = "update orders set status='" . $status . "'  where orderID='" . $orderID . "'";
        }
        $results = mysqli_query($con, $sql);
        $statusComments = getUser($userID) . " Update order Status to " . $status;
        comments($orderID, $statusComments, $userID);
    }
    if (!empty($comments)) {
        comments($orderID, $comments, $userID);
    }

    $result['status'] = 'success';
    $result['message'] = 'Successfully Update Courier';

    echo  json_encode($result);
    die();
}



function getProductDetails($orderID)
{
    global $con;
    if ($orderID) {
        $sql = "SELECT * FROM orderDetails where orderID = '" . $orderID . "'";
        $results = mysqli_query($con, $sql);
        return $results;
    } else {
        return 'Something Wrong !';
    }
    die();
}

function view()
{
    global $con;
    $orderID = $_REQUEST['orderID'];
    $data = single($orderID); // var_dump($data)
?>

    <div class="row">
        <div class="col-12">
            <div class="invoice-title">
                <div class="row">
                    <div class="col-6">
                        <strong>Order Date # <?php echo $data['orderDate']; ?></strong>
                    </div>
                    <div class="col-6 text-right">
                        <strong>Order ID # <?php echo $data['orderID']; ?></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <table>
                        <tr>
                            <th>Customer Name:</th>
                            <td><strong><?php echo $data['customerName'] ?></strong></td>
                        </tr>
                        <tr>
                            <th>Customer Phone: </th>
                            <td><strong><?php echo $data['customerPhone'] ?></strong></td>
                        </tr>
                        <tr>
                            <th>Customer Address: </th>
                            <td><strong><?php echo $data['customerAddress'] ?></strong></td>
                        </tr>
                        <?php if ($data['courierName']) { ?>
                            <tr>
                                <th>Courier Root: </th>
                                <td><?php echo $data['courierName'] . ' > ' . $data['cityName'] . ' > ' . $data['zoneName'] ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <br>
                </div>
                <div class="col-6 text-right">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td class="text-center"><strong>Item</strong></td>
                            <td class="text-center"><strong>Product</strong></td>
                            <td class="text-center"><strong>Quantity</strong></td>
                            <td class="text-center"><strong>Price</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $results = getProductDetails($orderID);
                        $i = 1;
                        while ($result = mysqli_fetch_assoc($results)) { ?>
                            <tr>
                                <td class="text-center"> <?php echo $i; ?></td>
                                <td class="text-center"><?php echo $result['productName'] ?></td>
                                <td class="text-center"><?php echo $result['productQuantity'] ?></td>
                                <td class="text-center"><?php echo $result['productPrice'] ?></td>
                            </tr>
                        <?php $i++;
                        } ?>

                        <tr>
                            <td class="no-line" colspan="2"></td>
                            <td class="no-line text-center">
                                <strong>Delivery Charge</strong>
                                <?php if ($data['discount'] > 0) { ?>
                                    <strong>Discount</strong>
                                <?php } ?>
                                <?php if ($data['orderBkashLoadNumber']) { ?>
                                    <strong>Bkash</strong>
                                <?php } ?>
                            </td>
                            <td class="no-line text-center">
                                <?php echo $data['deliveryCharge'] ?>
                                <?php if ($data['discount'] > 0) { ?>
                                    <?php echo $data['discount'] ?>
                                <?php } ?>
                                <?php if ($data['orderBkashLoadNumber']) { ?>
                                    <?php echo $data['orderBkashLoadNumber'] ?> , <?php echo $data['orderBkashLoadAmount'] ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="no-line" colspan="2"></td>
                            <td class="no-line text-center"><strong>Total</strong></td>
                            <td class="no-line text-center">
                                <strong><?php echo $data['total'] ?> TK</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="comments" placeholder="Enter any comments">
                    <br>
                    <button type="button" class="btn btn-block btn-info waves-effect" value="<?php echo $orderID; ?>" id="updateStatus">Submit</button>
                </div>
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td><strong>DateTime</strong></td>
                                    <td><strong>Uesr</strong></td>
                                    <td><strong>Commets</strong></td>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $sql = "SELECT comments.*,users.name FROM comments LEFT JOIN users ON (comments.userID=users.userID)  where orderID = '" . $orderID . "'";
                                $results = mysqli_query($con, $sql);
                                while ($result = mysqli_fetch_assoc($results)) {
                                ?>
                                    <tr>
                                        <td><?php echo $result['dateTime']; ?></td>
                                        <td><?php echo $result['name']; ?></td>
                                        <td><?php echo $result['comments']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-print-none">
                <div class="float-right">
                    <a href="order-edit?orderID=<?php echo $orderID; ?>" class="btn btn-primary waves-effect waves-light"><i class="fas fa-pencil-alt"></i> Edit</a>
                </div>
            </div>
        </div>
    </div>


<?php
    die();
}
function getAssign()
{
    global $con;
    $sql = "SELECT * FROM users where type='User'";
    $results = mysqli_query($con, $sql);
?>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label>Assign to User</label>
                <select class="form-control" id="userID">
                    <option value="">Select User</option>
                    <?php while ($result = mysqli_fetch_assoc($results)) { ?>
                        <option value="<?php echo $result['userID']; ?>"><?php echo $result['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
<?php }

function invoice()
{
    global $con;
    $ids = serialize($_REQUEST['ids']);
    if ($ids) {
        $sql = "insert into orderInvoice values(null,'" . $ids . "' )";
        $results = mysqli_query($con, $sql);
        echo $order_id = mysqli_insert_id($con);
    }
    die();
}
function comments($orderID, $comments, $userID)
{
    global $con;
    $sql =  "INSERT INTO comments VALUES (null,'" . $orderID . "','" . $comments . "','" . $userID . "',now(),'0')";
    mysqli_query($con, $sql);
}

function getUser($userID)
{
    global $con;
    $sql = "SELECT * FROM users where userID='" . $userID . "' ";
    $results = mysqli_query($con, $sql);
    $result = mysqli_fetch_assoc($results);
    return $result['name'];
}


function memoUpdate()
{
    global $con;
    $result = array();
    $MemoNumber = $_REQUEST['MemoNumber'];
    $invoiceID = $_REQUEST['invoiceID'];

    $sql = "update orders set MemoNumber='" . $MemoNumber . "' where invoiceID='" . $invoiceID . "'";
    $results = mysqli_query($con, $sql);
    if ($results) {
        $result['status'] = 'success';
        $result['message'] = 'Successfully Update Memo';
    } else {
        $result['status'] = 'failed';
        $result['message'] = 'Unsuccessful to Update Memo';
    }
    echo  json_encode($result);
    die();
}
