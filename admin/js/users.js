$(document).ready(function () {

    // Courier table ajax load

    var table = $('#usersTable').DataTable({
        "ajax": 'ajax/users.php?action=getUsers',
        ordering: false,
        "columns": [
            { "data": "name" },
            { "data": "phone" },
            { "data": "email" },
            { "data": "type" },
            {
                "data": null,
                render: function (data) {
                    if (data.status == 1) {
                        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' + data.userID + '">Active</button>';
                    } else {
                        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' + data.userID + '" >Inactive</button>';
                    }
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button type="button" value="' + data.userID + '" class="btn btn-cyan btn-sm">Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.userID + '" type="button" >Delete</button>';

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
        var userID = $('#userID').val();
        var name = $('#name');
        var phone = $('#phone');
        var email = $('#email');
        var password = $('#password');
        var userType = $('#userType');

        if (!name.val()) {
            swal("Oops...!", "User Name should not empty !", "error");
            return;
        }
        if (!phone.val()) {
            swal("Oops...!", "User Number should not empty !", "error");
            return;
        }
        if (!email.val()) {
            swal("Oops...!", "User Email should not empty !", "error");
            return;
        }
        if (!password.val() && type == 'add') {
            swal("Oops...!", "User Password should not empty !", "error");
            return;
        }
        if (!userType.val()) {
            swal("Oops...!", "User Type should not empty !", "error");
            return;
        }


        // Add Data
        if (type == 'add') {
            $.ajax({
                type: "post",
                url: 'ajax/users.php',
                data: {
                    'action': 'save',
                    'name': name.val(),
                    'phone': phone.val(),
                    'email': email.val(),
                    'password': password.val(),
                    'userType': userType.val()
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
        if (!userID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }
        // Update data
        if (type == 'update') {

            $.ajax({
                type: "post",
                url: 'ajax/users.php',
                data: {
                    'action': 'update',
                    'name': name.val(),
                    'phone': phone.val(),
                    'email': email.val(),
                    'password': password.val(),
                    'userType': userType.val(),
                    'userID': userID
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
        var userID = $(this).val();
        $.ajax({
            type: "post",
            url: 'ajax/users.php',
            data: {
                'action': 'changeStatus',
                'status': status,
                'userID': userID
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
        var userID = $(this).val();
        if (userID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/users.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            userID: userID
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

        var userID = $(this).val();
        jQuery.ajax({
            url: 'ajax/users.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'userID': userID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {

                    // Add data to modal
                    $('#name').val(data['data']['name']);
                    $('#phone').val(data['data']['phone']);
                    $('#email').val(data['data']['email']);
                    $('#userType').val(data['data']['type']);

                    $('#userID').val(userID);

                    // Modal Action
                    $('.modal-title').text('Edit User');
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
