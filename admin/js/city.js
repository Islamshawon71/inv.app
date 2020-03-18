$(document).ready(function () {
    // Ajax Call
    var table = $('#CityTable').DataTable({
        "ajax": 'ajax/city.php?action=get',
        ordering: false,
        "columns": [
            { "data": "courierName" },
            { "data": "CityName" },
            {
                "data": null,
                render: function (data) {
                    console.log(data.status);
                    if (data.status == 1) {
                        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' + data.cityID + '">Active</button>';
                    } else {
                        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' + data.cityID + '" >Inactive</button>';
                    }
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button class="btn btn-cyan btn-sm" value="' + data.cityID + '"  type="button"  >Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.cityID + '" type="button" >Delete</button>';

                }
            }
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var title = this.header();
                //replace spaces with dashes
                title = $(title).html().replace(/[\W]/g, '-');
                if (title == 'Courier-Name') {
                    var column = this;
                    var select = $('<select id="' + title + '" multiple class="select2 form-control " width="100%" ></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            //Get the "text" property from each selected data
                            //regex escape the value and store in array
                            var data = $.map($(this).select2('data'), function (value, key) {
                                return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                            });

                            //if no data selected use ""
                            if (data.length === 0) {
                                data = [""];
                            }

                            //join array into string with regex or (|)
                            var val = data.join('|');

                            //search for the option(s) selected
                            column
                                .search(val ? val : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
                    // console.log(title);
                    // console.log("'#"+title+"'");
                    //use column title as selector and placeholder
                    $("#" + title).select2({
                        multiple: true,
                        closeOnSelect: false,
                        placeholder: "Select a " + title
                    });

                    //initially clear select otherwise first option is selected
                    $('.select2').val(null).trigger('change');
                }
            });
        }
    });

    $("#courierID").select2({
        placeholder: "Select a Courier",
        ajax: {
            url: 'ajax/city.php',
            contentType: 'application/json',
            data: function (params) {
                var query = {
                    action: 'getCourier'
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

    $(document).on('click', '.AddNew', function () {

        $('.modal-title').text('Add New City');
        $('.modal-footer .btn-primary').text('Save');
        $('.modal-footer .btn-primary').val('add');
        $('#modal').modal('toggle');

    });

    // Save and update data
    $(document).on("click", "#submit", function () {

        var type = $(this).val();
        var CityName = $('#CityName');
        var cityID = $('#cityID').val();
        var courierID = $('#courierID');
        if (!CityName.val()) {
            swal("Oops...!", "City Name should not empty !", "error");
            return;
        }
        // Add Data
        if (type == 'add') {
            $.ajax({
                type: "post",
                url: 'ajax/city.php',
                data: {
                    'action': 'save',
                    'CityName': CityName.val(),
                    'courierID': courierID.val()
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
        if (!cityID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }
        // Update data
        if (type == 'update') {

            $.ajax({
                type: "post",
                url: 'ajax/city.php',
                data: {
                    'action': 'update',
                    'CityName': CityName.val(),
                    'courierID': courierID.val(),
                    'cityID': cityID
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
        var cityID = $(this).val();
        $.ajax({
            type: "post",
            url: 'ajax/city.php',
            data: {
                'action': 'update_status',
                'status': status,
                'cityID': cityID
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

        var cityID = $(this).val();
        if (cityID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    jQuery.ajax({
                        url: "ajax/city.php",
                        contentType: "application/json",
                        data: {
                            action: "delete",
                            cityID: cityID
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
        var cityID = $(this).val();
        jQuery.ajax({
            url: 'ajax/city.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'cityID': cityID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {


                    // Add data to modal
                    $('#CityName').val(data['CityName']);
                    $('#courierID').val(data['courierID']);
                    $('#cityID').val(cityID);
                    $("#courierID").empty().append('<option value="' + data['courierID'] + '"  >' + data['CourierName'] + '</option>');

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