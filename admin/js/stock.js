$(document).ready(function () {

    // Courier table ajax load

    var table = $('#purchaseTable').DataTable({
        "ajax": 'ajax/purchase.php?action=getPurchase',
        ordering: false,
        "columns": [
            { "data": "Date" },
            { "data": "supplierName" },
            { "data": "productName" },
            { "data": "Quantity" },
            {
                data: null,
                render: function (data) {
                    return '<button type="button" value="' + data.purchaseID + '" class="btn btn-cyan btn-sm">Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.purchaseID + '" type="button" >Delete</button>';

                }
            }
        ]
    });



    $("#supplierID").select2({
        placeholder: "Select a Supplier",
        ajax: {
            url: 'ajax/purchase.php',
            contentType: 'application/json',
            data: function (params) {
                var query = {
                    action: 'getSupplier'
                }
                return query;
            },
            processResults: function (data) {
                var data = $.parseJSON(data);
                return {
                    results: data.data
                };
            }
        }
    });

    $("#productID").select2({
        placeholder: "Select a Supplier",
        ajax: {
            url: 'ajax/purchase.php',
            contentType: 'application/json',
            data: function (params) {
                var query = {
                    action: 'getProduct'
                }
                return query;
            },
            processResults: function (data) {
                var data = $.parseJSON(data);
                return {
                    results: data.data
                };
            }
        }
    });

    // Add New Popup

    $(document).on("click", ".addNew", function () {
        $('.modal-title').text('Add New Purchase');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');
    });


    // Save & Update
    $(document).on("click", "#modalButton", function () {

        var type = $(this).val();
        var purchaseID = $('#purchaseID').val();
        var date = $('#date');
        var supplierID = $('#supplierID');
        var productID = $('#productID');
        var quantity = $('#quantity');

        if (!date.val()) {
            swal("Oops...!", "Date should not empty !", "error");
            return;
        }
        if (!supplierID.val()) {
            swal("Oops...!", "Supplier should not empty !", "error");
            return;
        }
        if (!productID.val()) {
            swal("Oops...!", "Products should not empty !", "error");
            return;
        }
        if (!quantity.val()) {
            swal("Oops...!", "Quantity should not empty !", "error");
            return;
        }


        // Add Data
        if (type == 'add') {
            $.ajax({
                type: "post",
                url: 'ajax/purchase.php',
                data: {
                    'action': 'save',
                    'date': date.val(),
                    'supplierID': supplierID.val(),
                    'productID': productID.val(),
                    'quantity': quantity.val(),
                },
                success: function (response) {
                    var data = JSON.parse(response);

                    if (data['status'] == 'success') {
                        $('#modal').modal('toggle');
                        table.ajax.reload();
                        swal("Good job!", data["message"], "success");
                    } else {
                        if (data['status'] == 'failed') {
                            swal("Oops...!", data["message"], "error");
                        } else {
                            swal("Oops...!", "Something wrong ! Please try again.", "error");
                        }
                    }
                }
            });
        }
        // ID check
        if (!supplierID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }
        // Update data
        if (type == 'update') {

            $.ajax({
                type: "post",
                url: 'ajax/purchase.php',
                data: {
                    'action': 'update',
                    'date': date.val(),
                    'supplierID': supplierID.val(),
                    'productID': productID.val(),
                    'quantity': quantity.val(),
                    'purchaseID': purchaseID
                },
                success: function (response) {

                    var data = JSON.parse(response);
                    if (data['status'] == 'success') {
                        swal("Good job!", data["message"], "success");
                        table.ajax.reload();
                        $('#modal').modal('toggle');
                    } else {
                        if (data['status'] == 'failed') {
                            swal("Oops...!", data['message'], "error");
                        } else {
                            swal("Oops...!", "Something wrong ! Please try again.", "error");
                        }

                    }

                }
            });

        }

    });


    // Delete
    $(document).on("click", ".btn-danger", function () {
        var purchaseID = $(this).val();
        if (purchaseID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/purchase.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            purchaseID: purchaseID
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

        var purchaseID = $(this).val();
        jQuery.ajax({
            url: 'ajax/purchase.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'purchaseID': purchaseID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {

                    // Add data to modal
                    $('#date').val(data['data']['Date']);
                    $('#quantity').val(data['data']['Quantity']);
                    $("#productID").empty().append('<option value="' + data['data']['productID'] + '"  >' + data['data']['productName'] + '</option>');
                    $("#supplierID").empty().append('<option value="' + data['data']['supplierID'] + '"  >' + data['data']['supplierName'] + '</option>');

                    $('#purchaseID').val(purchaseID);

                    // Modal Action
                    $('.modal-title').text('Edit Purchase');
                    $('.modal-footer .btn-primary').text('Update');
                    $('.modal-footer .btn-primary').val('update');
                    $('#modal').modal('toggle');
                } else {
                    swal("Oops...!", "Something wrong ! Please try again.", "error");
                }
            }
        });
    });



});
