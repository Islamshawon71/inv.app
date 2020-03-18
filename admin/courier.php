<?php include "header.php"; ?>
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Courier List</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <button type="button" class="btn btn-primary waves-effect waves-light addNew">
                                    Add New Courier
                                </button>
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="courierTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Courier Name</th>
                                        <th>Has City</th>
                                        <th>Has Zone</th>
                                        <th>Courier Charge</th>
                                        <th>Status</th>
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
                        <label>Courier Name </label>
                        <input type="text" id="CourierName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Courier Charge </label>
                        <input type="text" id="CourierCharge" value="0" class="form-control">
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="HasCity">
                        <label class="custom-control-label" for="HasCity">Available City</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="HasZone">
                        <label class="custom-control-label" for="HasZone">Available Zone</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="hidden" id="courierID">
                    <button type="button" id="modalButton" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function script()
{
    echo '<script src="js/courier.js"></script>';
} ?>
<?php include "footer.php" ?>