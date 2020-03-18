$(document).ready(function () {

    var table = $("#storeTable").DataTable({
        ajax: "ajax/store.php?action=getStore",
        ordering: false,
        columns: [
            { data: "storeID" },
            { data: "storeName" },
            { data: "storeURL" },
            { data: "total" },
            {
                "data": null,
                render: function (data) {
                    console.log(data.status);
                    if (data.status == 1) {
                        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' + data.storeID + '">Active</button>';
                    } else {
                        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' + data.storeID + '" >Inactive</button>';
                    }
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button type="button" value="' + data.storeID + '" class="btn btn-cyan btn-sm">Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.storeID + '" type="button" >Delete</button>';

                }
            }

        ],
        columnDefs: [
            {
                targets: 0,
                checkboxes: {
                    selectRow: true
                }
            },
            {
                orderable: false,
                targets: [0, 1]
            }
        ],
    });


    $(document).on("click", ".AddNew", function () {
        $('.modal-title').text('Add New Store');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');
    });


    // Save & Update
    $(document).on("click", "#submit", function () {
        var type = $(this).val();
        var storeName = $('#storeName');
        var storeURL = $('#storeURL');
        var storeID = $('#storeID').val();
        if (!storeName.val()) {
            swal("Oops...!", "Store Name should not empty !", "error");
            return;
        }
        if (!storeURL.val()) {
            swal("Oops...!", "Store Link should not empty !", "error");
            return;
        }
        // Add Data
        if (type == 'add') {
            $.ajax({
                url: "ajax/store.php",
                contentType: "application/json",
                data: {
                    action: "save",
                    storeName: storeName.val(),
                    storeURL: storeURL.val()
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["status"] == "success") {
                        swal("Good job!", data["message"], "success");
                    } else {
                        swal("Oops...!", data["message"], "error");
                    }
                    $("#modal").modal("toggle");
                    table.ajax.reload();
                }
            });
        }

        // ID check
        if (!storeID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }

        // Update data
        if (type == 'update') {
            jQuery.ajax({
                url: "ajax/store.php",
                contentType: "application/json",
                data: {
                    action: "update",
                    storeID: storeID,
                    storeName: storeName.val(),
                    storeURL: storeURL.val()
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["status"] == "success") {
                        swal("Good job!", data["message"], "success");
                    } else {
                        swal("Oops...!", data["message"], "error");
                    }
                    $("#modal").modal("toggle");
                    table.ajax.reload();
                }
            });
        }

    });



    function validURL(str) {
        var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        if (!regex.test(str)) {
            return false;
        } else {
            return true;
        }
    }

    //  Delete
    $(document).on("click", ".btn-danger", function () {
        var storeID = $(this).val();
        if (storeID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/store.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            storeID: storeID
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
        var storeID = $(this).val();
        jQuery.ajax({
            url: 'ajax/store.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'storeID': storeID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {
                    $('#update').val(storeID);
                    $('#storeName').val(data['data']['storeName']);
                    $('#storeURL').val(data['data']['storeURL']);
                    $('#modal').modal('toggle');
                } else {
                    swal("Oops...!", "Something wrong ! Please try again.", "error");
                }
            }
        });
    });

    // Status Change
    $(document).on('click', '.status', function () {
        var status = $(this).attr('data-status');
        var storeID = $(this).val();
        $.ajax({
            type: "post",
            url: 'ajax/store.php',
            data: {
                'action': 'changeStatus',
                'status': status,
                'storeID': storeID
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
});
