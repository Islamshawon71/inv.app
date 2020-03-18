<?php include "header.php";




?>
<div class="page-wrapper">

    <?php

    if (isset($_POST['save'])) {
        if ($_POST['save'] == 'add') {
            $productCode = $_POST['productCode'];
            $productName = $_POST['productName'];
            $productPrice = $_POST['productPrice'];
            $productImage = 'test';
            $sql = "INSERT INTO products values (null, '" . $productCode . "', '" . $productName . "', '" . $productImage . "', '" . $productPrice . "') ";
            mysqli_query($con, $sql);
            $productID = mysqli_insert_id($con);
            $targetDir = "images/";
            $targetFile = $targetDir . $productID . '.jpg';
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                // echo "The file " . basename($_FILES["productImage"]["name"]) . " has been uploaded.";
                $sql = "update  products set productImage = '" . $targetFile . "' where productID='" . $productID . "' ";
                mysqli_query($con, $sql);
                $message = '<div class="alert alert-success" role="alert">
                                  Successfully Add Product
                                </div>';
            } else {
                $message = '<div class="alert alert-danger" role="alert">
                                    Unsuccessfully Update Product
                                 </div>';
            }
        }
        if ($_POST['save'] == 'update') {
            $productCode = $_POST['productCode'];
            $productName = $_POST['productName'];
            $productPrice = $_POST['productPrice'];
            $productID = $_POST['productID'];
            $sql = "update  products set productCode = '" . $productCode . "' ,  productName = '" . $productName . "'  ,  productPrice = '" . $productPrice . "' where productID='" . $productID . "' ";
            mysqli_query($con, $sql);
            if ($_FILES["productImage"]["tmp_name"]) {
                $targetDir = "images/";
                $targetFile = $targetDir . $productID . '.jpg';
                if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                    // echo "The file " . basename($_FILES["productImage"]["name"]) . " has been uploaded.";
                    $sql = "update  products set productImage = '" . $targetFile . "' where productID='" . $productID . "' ";
                    mysqli_query($con, $sql);
                    $message = '<div class="alert alert-success" role="alert">
                                  Successfully Update Product
                                </div>';
                } else {
                    $message = '<div class="alert alert-danger" role="alert">
                                    Unsuccessfully Update Product
                                 </div>';
                }
            }
        }
    }


    ?>

    <div class="page-breadcrumb">
        <div class="row">
            <?php if (isset($message)) {
                echo $message;
            } ?>
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Product List</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <button type="button" class="btn btn-primary waves-effect waves-light addNew">
                                    Add New Product
                                </button>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-spinner fa-spin"></i> Sync Product</button>
                                    <div class="dropdown-menu">
                                        <?php

                                        $sql = "SELECT * FROM store where status='1'";
                                        $results = mysqli_query($con, $sql);
                                        $data = array();

                                        while ($result = mysqli_fetch_assoc($results)) {
                                            echo '<a class="dropdown-item syncProduct" data-storeID="' . $result['storeID'] . '" href="#">' . $result['storeName'] . '</a>';
                                        } ?>
                                    </div>
                                </div>
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
                            <table id="productsTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
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
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true ">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product Name </label>
                            <input type="text" id="productName" name="productName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Product Code </label>
                            <input type="text" id="productCode" name="productCode" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Product Price </label>
                            <input type="text" id="productPrice" name="productPrice" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Product Image </label>
                            <input type="file" id="productImage" name="productImage" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="hidden" name="productID" id="productID">
                        <button type="submit" name="save" id="modalButton" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
function script()
{
    echo '<script src="js/products.js"></script>';
} ?>
<?php include "footer.php" ?>