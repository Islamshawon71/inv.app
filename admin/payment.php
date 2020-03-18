<?php include "header.php"; ?>
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Payment List</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <button type="button" class="btn btn-primary waves-effect waves-light AddNew">
                                    Add New Payment
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

                            <table id="paymentTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Payment Type</th>
                                        <th>Number</th>
                                        <th>Status</th>
                                        <th>Status</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">New Payment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true ">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Payment Type </label>
                        <select id="PaymentType" class="form-control" style="width: 100%">
                            <option value="">Select Payment Type</option>
                            <option value="Cash">Cash</option>
                            <option value="Bkash">Bkash</option>
                            <option value="Load">Load</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone Number </label>
                        <input type="text" id="PaymentNumbe" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save changes</button>
                    <input type="hidden" id="paymentID">
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function script()
{
    echo '<script src="js/payment.js"></script>';
} ?>
<?php include "footer.php" ?>