<?php include "header.php"; ?>
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Users List</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <button type="button" class="btn btn-primary waves-effect waves-light addNew">
                                    Add New Purchase
                                </button>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="purchaseTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Supplier Name</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
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


    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true ">
        <div class="modal-dialog" role="document ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true ">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date </label>
                        <input type="text" id="date" autocomplete="off" class="form-control datepicker">
                    </div>
                    <div class="form-group">
                        <label for="supplierID">Supplier</label>
                        <select id="supplierID" class="form-control" style="width:100%">
                            <option value="">Select Supplier</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productID">Product</label>
                        <select id="productID" class="form-control" style="width:100%">
                            <option value="">Select Product</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quantity </label>
                        <input type="text" id="quantity" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="hidden" id="purchaseID">
                    <input type="hidden" id="oldQuantity">
                    <button type="button" id="modalButton" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function script()
{
    echo '<script src="js/purchase.js"></script>';
} ?>
<?php include "footer.php" ?>