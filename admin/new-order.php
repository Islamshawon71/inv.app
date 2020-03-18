<?php include "header.php" ?>

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">New Order</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <button type="submit" class="btn btn-block btn-danger">Cancel</button>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Info</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Name</label>
                                    <select class="select2 form-control custom-select" id="storeID" style="width: 100%;">
                                        <option value="1">In House</option>
                                        <?php
                                        $sql = "select * from store";
                                        $stores = mysqli_query($con, $sql);
                                        while ($store = mysqli_fetch_assoc($stores)) {
                                            echo '<option value="' . $store['storeID'] . '">' . $store['storeName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice ID </label>
                                    <input type="text" id="invoiceID" value="<?php echo invoiceID(); ?>" readonly class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" id="CustomerName" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Customer Phone</label>
                                        <input type="text" id="CustomerPhone" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Customer Address</label>
                                <textarea id="CustomerAddress" class="form-control"></textarea>
                            </div>

                        </div>

                        <!--   
                            Courier Details
                        -->
                        <div class="form-group">
                            <label>Courier Name</label>
                            <select class="form-control" id="courierID">
                                <option value="">Select Courier</option>
                            </select>
                        </div>
                        <div class="form-group HasCity">
                            <label>City Name</label>
                            <select class="form-control" id="cityID">
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="form-group HasZone">
                            <label>Zone Name</label>
                            <select class="form-control" id="zoneID">
                                <option value="">Select Zone</option>
                            </select>
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
                                        <option value="">Select Payment Type</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Load">Load</option>
                                    </select>
                                </div>
                                <div class="form-group PaymentNumber">
                                    <select id="PaymentNumber" class="form-control" style="width: 100%;">
                                        <option value="">Select Number</option>
                                    </select>
                                </div>
                                <div class="form-group PaymentAgentNumber">
                                    <input type="text" class="form-control" id="PaymentAgentNumber" placeholder="Enter Bkash Agent Number">
                                </div>
                                <div class="form-group hide">
                                    <label>Memo Number</label>
                                    <input type="text" class="form-control" id="MemoNumber" placeholder="Enter Memo Number">
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
                            <button type="button" id="placeOrder" class="btn btn-block btn-lg btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
function script()
{
    echo '<script src="js/new-order.js"></script>';
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