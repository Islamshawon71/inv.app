<?php include "header.php" ?>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Orders</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="btn btn-primary waves-effect waves-light" href="new-order.php" role="button">
                                    <i class="fas fa-plus"></i> Add New Order
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <button class="btn btn-warning arrow-none waves-effect waves-light ordersync" type="button">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Sync Order
                                </button>
                            </li>
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
            <!-- Column -->
            <div class="col-md-6 col-lg-2">
                <div class="card card-hover">
                    <a href="?orders=All">
                        <div class="box bg-cyan text-center">
                            <h1 class="font-light text-white"><?php echo countOrderByStatus('All'); ?></h1>
                            <h6 class="text-white">Total</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2">
                <div class="card card-hover">
                    <a href="?orders=Processing">
                        <div class="box bg-primary text-center">
                            <h1 class="font-light text-white"><?php echo countOrderByStatus('Processing'); ?></h1>
                            <h6 class="text-white">Processing</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2">
                <div class="card card-hover">
                    <a href="?orders=Pending Payment">
                        <div class="box bg-info text-center">
                            <h1 class="font-light text-white"><?php echo countOrderByStatus('Pending Payment'); ?></h1>
                            <h6 class="text-white">Pending Payment</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2">
                <div class="card card-hover">
                    <a href="?orders=On Hold">
                        <div class="box bg-warning text-center">
                            <h1 class="font-light text-white"><?php echo countOrderByStatus('On Hold'); ?></h1>
                            <h6 class="text-white">On Hold</h6>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-2">
                <div class="card card-hover">
                    <a href="?orders=Cancelled">
                        <div class="box bg-danger text-center">

                            <h1 class="font-light text-white"><?php echo countOrderByStatus('Cancelled'); ?></h1>
                            <h6 class="text-white">Cancel</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2">
                <div class="card card-hover">
                    <a href="?orders=Completed">
                        <div class="box bg-success text-center">
                            <h1 class="font-light text-white"><?php echo countOrderByStatus('Completed'); ?></h1>
                            <h6 class="text-white">Completed</h6>
                        </div>
                    </a>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="ordersTable" data-type="<?php echo $_REQUEST['orders'] ? $_REQUEST['orders'] : 'All'; ?>" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Products</th>
                                        <th>Total</th>
                                        <th>Courier</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>User</th>
                                        <th>Action</th>
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
    </div>
</div>
<?php
function script()
{
    echo '<script src="js/orders.js"></script>';
?>
<?php
} ?>
<?php include "footer.php" ?>