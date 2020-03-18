$(document).ready(function () {

    // Courier table ajax load

    var table = $('#courierTable').DataTable({
        "ajax": 'ajax/courier.php?action=getCourier',
        ordering: false,
        "columns": [
            { "data": "CourierName" },
            {
                "data": null,
                render: function (data) {
                    if (data.HasCity == 1) {
                        return 'True';
                    } else {
                        return 'False';
                    }
                }
            },
            {
                "data": null,
                render: function (data) {
                    if (data.HasZone == 1) {
                        return 'True';
                    } else {
                        return 'False';
                    }
                }
            },
            { "data": "CourierCharge" },
            {
                "data": null,
                render: function (data) {
                    console.log(data.status);
                    if (data.status == 1) {
                        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' + data.courierID + '">Active</button>';
                    } else {
                        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' + data.courierID + '" >Inactive</button>';
                    }
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button type="button" value="' + data.courierID + '" class="btn btn-cyan btn-sm">Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.courierID + '" type="button" >Delete</button>';

                }
            }
        ]
    });

    // Add New Popup

    $(document).on("click", ".addNew", function () {
        $('.modal-title').text('Add New Courier');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');
    });


    // Save & Update
    $(document).on("click", "#modalButton", function () {

        var type = $(this).val();
        var CourierName = $('#CourierName');
        var courierID = $('#courierID').val();
        var CourierCharge = $('#CourierCharge');
        var HasCity = $('#HasCity').is(":checked");
        var HasZone = $('#HasZone').is(":checked");

        if (!CourierName.val()) {
            swal("Oops...!", "Courier Name should not empty !", "error");
            return;
        }

        // Add Data
        if (type == 'add') {
            $.ajax({
                type: "post",
                url: 'ajax/courier.php',
                data: {
                    'action': 'save',
                    'CourierName': CourierName.val(),
                    'CourierCharge': CourierCharge.val(),
                    'HasCity': HasCity,
                    'HasZone': HasZone
                },
                success: function (response) {
                    var data = JSON.parse(response);

                    if (data['status'] == 'success') {
                        $('#new').modal('toggle');
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
        if (!courierID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }
        // Update data
        if (type == 'update') {
            $.ajax({
                type: "post",
                url: 'ajax/courier.php',
                data: {
                    'action': 'update',
                    'CourierName': CourierName.val(),
                    'CourierCharge': CourierCharge.val(),
                    'courierID': courierID,
                    'HasCity': HasCity,
                    'HasZone': HasZone
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


    // Status Change
    $(document).on('click', '.status', function () {
        var status = $(this).attr('data-status');
        var courierID = $(this).val();
        $.ajax({
            type: "post",
            url: 'ajax/courier.php',
            data: {
                'action': 'changeStatus',
                'status': status,
                'courierID': courierID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {
                    swal("Good job!", data["message"], "success");
                    table.ajax.reload();
                } else {
                    if (data['status'] == 'failed') {
                        swal("Good job!", data["message"], "success");
                    } else {
                        swal("Oops...!", data["message"], "error");
                    }
                }
            }
        });
    });


    // Delete
    $(document).on("click", ".btn-danger", function () {
        var courierID = $(this).val();
        if (courierID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/courier.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            courierID: courierID
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

        var courierID = $(this).val();
        jQuery.ajax({
            url: 'ajax/courier.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'courierID': courierID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {

                    // Add data to modal
                    $('#CourierName').val(data['data']['CourierName']);
                    $('#CourierCharge').val(data['data']['CourierCharge']);
                    if (data['data']['HasCity'] == 1) {
                        $('#HasCity').prop("checked", true);
                    }
                    if (data['data']['HasZone'] == 1) {
                        $('#HasZone').prop("checked", true);
                    }
                    $('#courierID').val(courierID);

                    // Modal Action
                    $('.modal-title').text('Edit Courier');
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
