<?php include "header.php"; ?>
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">City List</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <button type="button" class="btn AddNew btn-primary waves-effect waves-light">
                                    Add New City
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
                            <table id="CityTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Courier Name</th>
                                        <th>City Name</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">New City</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true ">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="CityName">City Name</label>
                        <input type="text" name="CityName" class="form-control" id="CityName">
                    </div>
                    <div class="form-group">
                        <label for="courierID">Courier Name</label>
                        <select name="" id="courierID" style="width:100%" class="form-control">
                            <option value="">Select Courier</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save changes</button>
                    <input type="hidden" id="cityID">
                </div>
            </div>
        </div>
    </div>


</div>
<?php
function script()
{
    echo '<script src="js/city.js"></script>';
} ?>
<?php include "footer.php" ?>