$(document).ready(function () {

    // Ajax Call
    var table = $('#ZoneTable').DataTable({
        "ajax": 'ajax/zone.php?action=get',
        ordering: false,
        "columns": [
            { "data": "CourierName" },
            { "data": "CityName" },
            { "data": "ZoneName" },
            {
                "data": null,
                render: function (data) {
                    if (data.status == 1) {
                        return '<button type="button" class="btn btn-success btn-sm status" data-status="0" name="status" value="' + data.zoneID + '">Active</button>';
                    } else {
                        return '<button type="button" class="btn btn-warning btn-sm status" data-status="1" name="status" value="' + data.zoneID + '" >Inactive</button>';
                    }
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button class="btn btn-cyan btn-sm" value="' + data.zoneID + '"  type="button"  >Edit</button>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.zoneID + '" type="button" >Delete</button>';

                }
            }
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var title = this.header();
                //replace spaces with dashes

                title = $(title).html().replace(/[\W]/g, '-');
                if (
                    title == 'City' ||
                    title == 'Courier'
                ) {
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
                    //use column title as selector and placeholder
                    $("#" + title).select2({
                        multiple: true,
                        closeOnSelect: false,
                        allowHtml: true,
                        allowClear: true,
                        tags: true,
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
            url: 'ajax/zone.php',
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
    }).trigger("change")
        .on("select2:select", function (e) {
            $("#cityID").empty();
        });


    $("#cityID").select2({
        placeholder: "Select a Courier",
        ajax: {
            url: 'ajax/zone.php',
            contentType: 'application/json',
            data: function (params) {
                var query = {
                    action: 'getCity',
                    courierID: $("#courierID").val()
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
        var ZoneName = $('#ZoneName');
        var courierID = $('#courierID');
        var cityID = $('#cityID');
        var zoneID = $('#zoneID').val();

        if (!ZoneName.val()) {
            swal("Oops...!", "Zone Name should not empty !", "error");
            return;
        }
        // Add Data
        if (type == 'add') {

            $.ajax({
                type: "post",
                url: 'ajax/zone.php',
                data: {
                    'action': 'save',
                    'ZoneName': ZoneName.val(),
                    'courierID': courierID.val(),
                    'cityID': cityID.val()
                },
                success: function (response) {
                    var data = JSON.parse(response);

                    if (data['status'] == 'success') {

                        $('#modal').modal('toggle');
                        swal("Good job!", data["message"], "success");
                        table.ajax.reload();

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
        // ID check
        if (!zoneID) {
            swal("Oops...!", "Something wrong ! Please try again.", "error");
            return;
        }
        // Update data
        if (type == 'update') {


            $.ajax({
                type: "post",
                url: 'ajax/zone.php',
                data: {
                    'action': 'update',
                    'ZoneName': ZoneName.val(),
                    'courierID': courierID.val(),
                    'cityID': cityID.val(),
                    'zoneID': zoneID
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

    });

    // Status
    $(document).on('click', '.status', function () {
        var status = $(this).attr('data-status');
        var zoneID = $(this).val();
        $.ajax({
            type: "post",
            url: 'ajax/zone.php',
            data: {
                'action': 'update_status',
                'status': status,
                'zoneID': zoneID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {
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
    });
    // Delete
    $(document).on('click', '.btn-danger', function () {
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
                        url: 'ajax/zone.php',
                        contentType: 'application/json',
                        data: {
                            'action': 'delete',
                            'zoneID': $(this).val()
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data['status'] == 'success') {
                                swal("Good job!", data["message"], "success");
                                $(this).closest('tr').remove();
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
        var zoneID = $(this).val();
        jQuery.ajax({
            url: 'ajax/zone.php',
            contentType: 'application/json',
            data: {
                'action': 'single',
                'zoneID': zoneID
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data['status'] == 'success') {


                    // Add data to modal
                    $('#ZoneName').val(data['ZoneName']);
                    $('#zoneID').val(zoneID);
                    $("#courierID").empty().append('<option value="' + data['courierID'] + '"  >' + data['CourierName'] + '</option>');
                    $("#cityID").empty().append('<option value="' + data['cityID'] + '"  >' + data['CityName'] + '</option>');

                    // Modal Action
                    $('.modal-title').text('Edit Zone');
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