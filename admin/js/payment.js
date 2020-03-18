$(document).ready(function () {
    // Ajax Call
    var table = $('#paymentTable').DataTable({
        "ajax": 'ajax/payment.php?action=GetPayment',
        ordering: false,
        "columns": [
            { "data": "PaymentType" },
            { "data": "PaymentNumbe" },
            {
                "data": null,
                render: function (data) {
                    console.log(data.status);
                    if (data.status == 1) {
                        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' + data.paymentID + '">Active</button>';
                    } else {
                        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' + data.paymentID + '" >Inactive</button>';
                    }
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button class="btn btn-cyan btn-sm" value="' + data.paymentID + '"  type="button"  >Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.paymentID + '" type="button" >Delete</button>';

                }
            }
        ]
    });



    // Add New Popup

    $(document).on('click', '.AddNew', function () {

        $('.modal-title').text('Add New Payment');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');

    });

    // Save and update data
    $(document).on("click", "#submit", function () {

        var type = $(this).val();
        var PaymentNumbe = $('#PaymentNumbe');
        var PaymentType = $('#PaymentType');
        var paymentID = $('#paymentID').val();

        if (!PaymentType.val()) {
            swal("Oops...!", "Payment Type should not empty !", "error");
            return;
        }
        if (!PaymentNumbe.val()) {
            swal("Oops...!", "Payment Number should not empty !", "error");
            return;
        }
        // Add Data
        if (type == 'add') {
            $.ajax({
                type: "post",
                url: 'ajax/payment.php',
                data: {
                    'action': 'save',
                    'PaymentType': PaymentType.val(),
                    'PaymentNumbe': PaymentNumbe.val()
                },
                success: function (response) {
                    var data = JSON.parse(response);

                    if (data['status'] == 'success') {
                        swal("Good job!", data["message"], "success");
                        table.ajax.reload();
                        $('#modal').modal('toggle');
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
        if (!paymentID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }
        // Update data
        if (type == 'update') {

            $.ajax({
                type: "post",
                url: 'ajax/payment.php',
                data: {
                    'action': 'update',
                    'PaymentNumbe': PaymentNumbe.val(),
                    'PaymentType': PaymentType.val(),
                    'paymentID': paymentID
                },
                success: function (response) {
                    var data = JSON.parse(response);

                    if (data['status'] == 'success') {
                        swal("Good job!", data["message"], "success");
                        table.ajax.reload();
                        $('#modal').modal('toggle');
                    } else {
                        if (data['status'] == 'failed') {
                            swal("Oops...!", data["message"], "error");
                        } else {
                            swal("Oops...!", "City Name should not empty !", "error");
                        }

                    }

                }
            });
        }

    });

    // Status
    $(document).on('click', '.status', function () {
        var status = $(this).attr('data-status');
        var paymentID = $(this).val();
        $.ajax({
            type: "post",
            url: 'ajax/payment.php',
            data: {
                'action': 'update_status',
                'status': status,
                'paymentID': paymentID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {
                    swal("Good job!", data["message"], "success");
                    table.ajax.reload();
                } else {
                    if (data['status'] == 'failed') {
                        swal("Oops...!", data["message"], "error");
                    } else {
                        swal("Oops...!", "Something wrong ! Please try again.", "error");
                    }

                }

            }
        });
    });

    // Delete
    $(document).on("click", ".btn-danger", function () {

        var paymentID = $(this).val();
        if (paymentID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/payment.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            paymentID: paymentID
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
        var paymentID = $(this).val();
        jQuery.ajax({
            url: 'ajax/payment.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'paymentID': paymentID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {


                    // Add data to modal
                    $('#PaymentType').val(data['PaymentType']);
                    $('#PaymentNumbe').val(data['PaymentNumbe']);
                    $('#paymentID').val(paymentID);

                    // Modal Action
                    $('.modal-title').text('Edit Payment');
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