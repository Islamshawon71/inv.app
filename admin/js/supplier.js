$(document).ready(function () {

    // Courier table ajax load

    var table = $('#supplierTable').DataTable({
        "ajax": 'ajax/supplier.php?action=getSupplier',
        ordering: false,
        "columns": [
            { "data": "supplierName" },
            { "data": "supplierPhone" },
            {
                data: null,
                render: function (data) {
                    return '<button type="button" value="' + data.supplierID + '" class="btn btn-cyan btn-sm">Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.supplierID + '" type="button" >Delete</button>';

                }
            }
        ]
    });

    // Add New Popup

    $(document).on("click", ".addNew", function () {
        $('.modal-title').text('Add New Supplier');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');
    });


    // Save & Update
    $(document).on("click", "#modalButton", function () {

        var type = $(this).val();
        var supplierID = $('#supplierID').val();
        var name = $('#name');
        var phone = $('#phone');

        if (!name.val()) {
            swal("Oops...!", "User Name should not empty !", "error");
            return;
        }
        if (!phone.val()) {
            swal("Oops...!", "User Number should not empty !", "error");
            return;
        }


        // Add Data
        if (type == 'add') {
            $.ajax({
                type: "post",
                url: 'ajax/supplier.php',
                data: {
                    'action': 'save',
                    'name': name.val(),
                    'phone': phone.val(),
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
                url: 'ajax/supplier.php',
                data: {
                    'action': 'update',
                    'name': name.val(),
                    'phone': phone.val(),
                    'supplierID': supplierID
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
        var supplierID = $(this).val();
        if (supplierID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/supplier.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            supplierID: supplierID
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

        var supplierID = $(this).val();
        jQuery.ajax({
            url: 'ajax/supplier.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'supplierID': supplierID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {

                    // Add data to modal
                    $('#name').val(data['data']['supplierName']);
                    $('#phone').val(data['data']['supplierPhone']);

                    $('#supplierID').val(supplierID);

                    // Modal Action
                    $('.modal-title').text('Edit Supplier');
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
