$(document).ready(function () {

    // Courier table ajax load

    var table = $('#productsTable').DataTable({
        "ajax": 'ajax/products.php?action=getProducts',
        ordering: false,
        "pageLength": 50,
        "columns": [
            { "data": "productCode", },
            {
                "data": null,
                render: function (data) {
                    return '<img src="' + data.productImage + '" style="width:120px;">';
                }
            },
            { "data": "productName", },
            { "data": "productPrice" },
            {
                data: null,
                render: function (data) {
                    return '<button type="button" value="' + data.productID + '" class="btn btn-cyan btn-sm">Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.productID + '" type="button" >Delete</button>';

                }
            }
        ]
    });

    // Add New Popup

    $(document).on("click", ".addNew", function () {
        $('.modal-title').text('Add New Product');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');
    });


    // Delete
    $(document).on("click", ".btn-danger", function () {
        var productID = $(this).val();
        if (productID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/products.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            productID: productID
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data["status"] == "success") {
                                swal("Good job!", data["message"], "success");
                            } else {
                                swal("Oops...!", data["message"], "error");
                            }
                            table.ajax.reload();
                        }
                    });


                } else {
                    swal("Your data file is safe!");
                }
            });
        }
        else {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
        }
    });

    // Edit
    $(document).on('click', '.btn-cyan', function () {

        var productID = $(this).val();
        jQuery.ajax({
            url: 'ajax/products.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'productID': productID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {

                    // Add data to modal
                    $('#productCode').val(data['data']['productCode']);
                    $('#productName').val(data['data']['productName']);
                    $('#productPrice').val(data['data']['productPrice']);
                    $('#productID').val(productID);

                    // Modal Action
                    $('.modal-title').text('Edit Product');
                    $('.modal-footer .btn-primary').text('Update');
                    $('.modal-footer .btn-primary').val('update');
                    $('#modal').modal('toggle');
                } else {
                    swal("Oops...!", "Something wrong ! Please try again.", "error");
                }
            }
        });
    });

    $(document).on('click', '.syncProduct', function () {
        var storeID = $(this).attr('data-storeID');
        swal({
            title: "Loading...",
            text: "Please wait",
            icon: "../assets/images/loading.gif",
            button: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });

        jQuery.ajax({
            url: 'ajax/products.php',
            contentType: 'application/json',
            data: {
                'action': 'producSync',
                'storeID': storeID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {
                    swal("Good job!", data['product'] +
                        ' Orders Sync.', "success").then(function () {
                            location.reload();
                        });
                } else {
                    swal("Good job!", data['product'] +
                        ' Orders Sync.', "success");

                }
            }
        });
    });



});
