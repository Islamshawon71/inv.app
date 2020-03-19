<?php include "header.php";
include_once '../../inc/databaseConnection.php';
$invoiceID = $_REQUEST['invoiceID'];
$sql = "SELECT orders.*,
            orderDetails.*,
            courier.CourierName, 
            city.CityName, 
            zone.ZoneName, 
            users.name as userName
            FROM orders 
            LEFT JOIN orderDetails on (orders.orderID = orderDetails.orderID)  
            LEFT JOIN courier on (orderDetails.courierID = courier.courierID) 
            LEFT JOIN city on (orderDetails.cityID = city.cityID) 
            LEFT JOIN zone on (orderDetails.zoneID = zone.zoneID) 
            LEFT JOIN users on (orders.userID = users.userID) where orders.invoiceID= '" . $invoiceID . "'";
$results = mysqli_query($con, $sql);
$result = mysqli_fetch_assoc($results);
?>

<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Edit Order</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class=" btn btn-block btn-danger">Cancel</a>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Info</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Name</label>
                                    <select class="select2 form-control custom-select" disabled id="storeID" style="width: 100%;">
                                        <option value="1">In House</option>
                                        <?php
                                        $sql = "select * from store";
                                        $stores = mysqli_query($con, $sql);
                                        while ($store = mysqli_fetch_assoc($stores)) {
                                            if ($result['storeID'] == $store['storeID']) {
                                                echo '<option value="' . $store['storeID'] . '" selected>' . $store['storeName'] . '</option>';
                                            } else {
                                                echo '<option value="' . $store['storeID'] . '" >' . $store['storeName'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice ID </label>
                                    <input type="text" id="invoiceID" value="<?php echo $result['invoiceID']; ?>" readonly class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" id="CustomerName" value="<?php echo $result['CustomerName']; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Customer Phone</label>
                                        <input type="text" id="CustomerPhone" value="<?php echo $result['CustomerPhone']; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Customer Address</label>
                                <textarea id="CustomerAddress" class="form-control"><?php echo $result['CustomerAddress']; ?></textarea>
                            </div>

                        </div>
                        <div class="form-group">
                            <label>Courier Name</label>
                            <select class="form-control" id="courierID">
                                <?php echo '<option value="' . $result['courierID'] . '">' . $result['CourierName'] . '</option>'; ?>
                            </select>
                        </div>
                        <div class="form-group HasCity">
                            <label>City Name</label>
                            <select class="form-control" id="cityID"> <?php echo $result['invoiceID']; ?>
                                <?php echo '<option value="' . $result['cityID'] . '">' . $result['CityName'] . '</option>'; ?>
                            </select>
                        </div>
                        <div class="form-group HasZone">
                            <label>Zone Name</label>
                            <select class="form-control" id="zoneID"> <?php echo $result['invoiceID']; ?>
                                <?php echo '<option value="' . $result['zoneID'] . '">' . $result['ZoneName'] . '</option>'; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Order Date</label>
                            <input type="text" id="OrderDate" value="<?php echo $result['OrderDate']; ?>" class="form-control datepicker">
                        </div>
                        <div class="form-group <?php if ($result['DeliveryDate'] == '') {
                                                    echo 'hide';
                                                } ?> ">
                            <label>Delivery Date</label>
                            <input type="text" id="DeliveryDate" value="<?php echo $result['DeliveryDate']; ?>" class="form-control datepicker">
                        </div>
                        <div class="form-group <?php if ($result['PaidReturnDate'] == '') {
                                                    echo 'hide';
                                                } ?> ">
                            <label>Last Update Date</label>
                            <input type="text" id="PaidReturnDate" value="<?php echo $result['PaidReturnDate']; ?>" class="form-control datepicker">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Details</h5>
                        <div class="form-group">
                            <table class="table productTable">
                                <thead>
                                    <tr>
                                        <th style="display: none"></th>
                                        <th>Code</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $orderProduct = "SELECT orderProduct.*,products.productCode FROM orderProduct LEFT JOIN products on (orderProduct.productID=products.productID) where orderProduct.orderID= '" . $result['orderID'] . "'";
                                    $products = mysqli_query($con, $orderProduct);
                                    while ($product = mysqli_fetch_assoc($products)) {
                                    ?>
                                        <tr>
                                            <td style="display: none"><input type="text" class="productID" style="width:80px;" value="<?php echo $product['productID']; ?>"></td>
                                            <td><span class="productCode"><?php echo $product['productCode']; ?></span></td>
                                            <td><span class="productName"><?php echo $product['productName']; ?></span></td>
                                            <td><input type="number" class="productQuantity form-control" value="<?php echo $product['productQuantity']; ?>"></td>
                                            <td><span class="productPrice"><?php echo $product['productPrice']; ?></span></td>
                                            <td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="padding:10px 0px">
                                            <select id="products" class="form-control">
                                                <option value="">Select Products</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment</label>
                                    <select id="PaymentType" class="form-control select2">
                                        <?php echo $result['invoiceID']; ?>
                                        <option value="">Select Payment Type</option>
                                        <option value="Cash" <?php if ($result['PaymentType'] == 'Cash') {
                                                                    echo 'selected';
                                                                } ?>>Cash</option>
                                        <option value="Bkash" <?php if ($result['PaymentType'] == 'Bkash') {
                                                                    echo 'selected';
                                                                } ?>>Bkash</option>
                                        <option value="Load" <?php if ($result['PaymentType'] == 'Load') {
                                                                    echo 'selected';
                                                                } ?>>Load</option>
                                    </select>
                                </div>
                                <div class="form-group PaymentNumber">
                                    <select id="PaymentNumber" class="form-control" style="width: 100%;">
                                        <?php echo '<option value="' . $result['PaymentID'] . '">' . $result['PaymentNumber'] . '</option>'; ?>
                                    </select>
                                </div>
                                <div class="form-group PaymentAgentNumber">
                                    <input type="text" class="form-control" id="PaymentAgentNumber" value="<?php echo $result['PaymentAgentNumber']; ?>" placeholder="Enter Bkash Agent Number">
                                </div>
                                <div class="form-group">
                                    <label>Memo Number</label>
                                    <input type="text" class="form-control" id="MemoNumber" value="<?php echo $result['MemoNumber']; ?>" placeholder="Enter Memo Number">
                                </div>
                                <div class="form-group">
                                    <label>Order Status</label>
                                    <select name="status" id="orderStatus" class="form-control">


                                        <option value="Processing" <?php if ($result['status'] == 'Processing') {
                                                                        echo 'selected';
                                                                    } ?>>Processing</option>
                                        <option value="Pending Payment" <?php if ($result['status'] == 'Pending Payment') {
                                                                            echo 'selected';
                                                                        } ?>>Pending Payment</option>
                                        <option value="On Hold" <?php if ($result['status'] == 'On Hold') {
                                                                    echo 'selected';
                                                                } ?>>On Hold</option>
                                        <option value="Cancelled" <?php if ($result['status'] == 'Cancelled') {
                                                                        echo 'selected';
                                                                    } ?>>Cancelled</option>
                                        <option value="Completed" <?php if ($result['status'] == 'Completed') {
                                                                        echo 'selected';
                                                                    } ?>>Completed</option>
                                        <option value="Pending Invoice" <?php if ($result['status'] == 'Pending Invoice') {
                                                                            echo 'selected';
                                                                        } ?>>Pending Invoice</option>
                                        <option value="Delivered" <?php if ($result['status'] == 'Delivered') {
                                                                        echo 'selected';
                                                                    } ?>>Delivered</option>

                                        <option value="Confirmed By Customer" <?php if ($result['status'] == 'Confirmed By Customer') {
                                                                                    echo 'selected';
                                                                                } ?>>Confirmed By Customer</option>
                                        <option value="Paid" <?php if ($result['status'] == 'Paid') {
                                                                    echo 'selected';
                                                                } ?>>Paid</option>
                                        <option value="Return" <?php if ($result['status'] == 'Return') {
                                                                    echo 'selected';
                                                                } ?>>Return</option>
                                        <option value="Courier Lost" <?php if ($result['status'] == 'Courier Lost') {
                                                                            echo 'selected';
                                                                        } ?>>Courier Lost</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Sub Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="subtotal">100</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Delivery</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="100" id="DeliveryCharge">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="DiscountCharge">
                                    </div>
                                </div>

                                <div class="form-group row PaymentAmount">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Payment</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="PaymentAmount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="total">100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body text-center">
                            <input type="hidden" id="orderID" value="<?php echo $result['orderID']; ?>">
                            <button type="button" id="placeOrder" class="btn btn-block btn-lg btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Old Order</h4>
                    <table id="oldOrder" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Courier</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function script()
{
    echo '<script src="js/edit-order.js"></script>';
?>
    <script>
        <?php
        global $con;
        $sql = "select * from courier";
        $stores = mysqli_query($con, $sql);
        $temp = array();
        while ($store = mysqli_fetch_assoc($stores)) {
            $temp[$store['courierID']] = $store;
        }
        ?>
        var store = '<?php echo json_encode($temp); ?>';
    </script>

<?php
} ?>
<?php include "footer.php" ?>