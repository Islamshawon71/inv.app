$(document).ready(function () {
    var table = $("#ordersTable").DataTable({
        ajax:
            "ajax/final.php?action=getOrder" +
            "&&orderType=" +
            $("#ordersTable").attr("data-type"),
        buttons: [
            {
                text: '<i class="far fa-user"></i> Confirm By Customer',
                className: "btn confirm-by-customer btn-info btn-sm waves-effect ",
                action: function (e, dt, node, config) {
                    chnageStatus("Confirm By Customer");
                }
            },
            {
                text: '<i class="fas fa-hand-paper"></i>  Return',
                className: "btn btn-warning  return btn-sm waves-effect",
                action: function (e, dt, node, config) {
                    chnageStatus("Return");
                }
            },
            {
                text: '<i class="far fa-trash-alt"></i>  Courier Lost',
                className: "btn btn-danger courier-lost btn-sm waves-effect",
                action: function (e, dt, node, config) {
                    chnageStatus("Courier Lost");
                }
            },
            {
                text: '<i class="fas fa-check"></i> Paid',
                className: "btn paid btn-success btn-sm",
                action: function (e, dt, node, config) {
                    chnageStatus("Paid");
                }
            },
            {
                text: '<i class="far fa-user"></i> Assign',
                className: "btn btn-info btn-sm waves-effect assign",
                action: function (e, dt, node, config) {
                    var rows_selected = table.column(0).checkboxes.selected();
                    var ids = [];
                    $.each(rows_selected, function (index, rowId) {
                        ids[index] = rowId;
                    });
                    if (ids.length > 0) {
                        jQuery.ajax({
                            url: "ajax/final.php",
                            contentType: "application/json",
                            data: {
                                action: "getAssign"
                            },
                            success: function (response) {
                                $("#assignOrder .modal-body")
                                    .empty()
                                    .append(response);
                                $("#assignOrder").modal("toggle");
                            }
                        });
                    } else {
                        Swal.fire("Select at list one order to assign user");
                    }
                }
            },
            {
                text: '<i class="far fa-trash-alt"></i> Delete',
                className: "btn btn-danger btn-sm delete",
                action: function (e, dt, node, config) {
                    deleteOrder();
                }
            }
        ],
        columns: [
            {
                data: "invoiceID",
                "width": "20px"
            },
            { data: "invoiceID" },
            {
                data: null,
                render: function (data) {
                    return data.CustomerName + '<br>' + data.CustomerPhone + '<br>' + data.CustomerAddress;
                }
            },
            { data: "products" },
            { data: "total" },
            {
                className: "CourierName",
                data: "CourierName"
            },
            { data: "OrderDate" },
            {
                className: "MemoNumber",
                data: "MemoNumber"
            },
            {
                data: "status",
                "width": "50px"
            },
            {
                data: "userName",
                "width": "70px"
            },
            {
                data: null,
                render: function (data) {
                    return '<a class="btn btn-cyan btn-sm" href="edit-order.php?invoiceID=' + data.invoiceID + '" type="button">Edit</a>'
                        + '<button class="btn btn-danger btn-sm" value="' + data.invoiceID + '" type="button" >Delete</button>';

                }

            }
        ],
        ordering: false,
        dom: "<'row'<'col-sm-12 col-md-10'l><'col-sm-12 col-md-2'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

        columnDefs: [
            {
                targets: 0,
                checkboxes: {
                    selectRow: true,
                    className: "select-checkbox"
                }
            }
        ],
        select: {
            style: "multi"
        },
        order: [[1, "asc"]],
        initComplete: function () {
            table
                .buttons()
                .container()
                .appendTo("#ordersTable_wrapper > .row .col-md-10");

            if (userInfo.type != "Admin") {
                $('.assign').hide();
                $('.delete').hide();
                $('.csv').hide();
            }

            if ($("#ordersTable").attr("data-type") == 'Completed') {
                $('.invoice').show();
            } else {
                $('.invoice').hide();
            }
        },
    });

    jQuery("#ordersTable").on("change", function (e) {
        var count = 0;
        $("input:checked").each(function () {
            if (
                $(this)
                    .closest("tr")
                    .find(".CourierName")
                    .text() == ""
            ) {
                count++;
            }
        });

        if (count > 0) {
            $(".btn.completed").prop("disabled", true);
        } else {
            $(".btn.completed").prop("disabled", false);
        }
        // console.log(count);
        e.preventDefault();
    });

    $(document).on("click", ".view", function () {
        var orderID = $(this).val();
        jQuery.ajax({
            url: "ajax/final.php",
            contentType: "application/json",
            data: {
                action: "view",
                orderID: orderID
            },
            success: function (response) {
                $("#viewOrder .modal-body")
                    .empty()
                    .append(response);
                $("#viewOrder .edit").val(orderID);
                $("#viewOrder").modal("toggle");
            }
        });
        $("#viewOrder").modal("toggle");
    });

    function deleteOrder() {
        var rows_selected = table.column(0).checkboxes.selected();
        var ids = [];
        $.each(rows_selected, function (index, rowId) {
            ids[index] = rowId;
        });
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(result => {
            if (result.value) {
                jQuery.ajax({
                    url: "ajax/final.php",
                    contentType: "application/json",
                    data: {
                        action: "deleteOrder",
                        ids: ids
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data["status"] == "success") {
                            Swal.fire(data["message"]);
                            table.ajax.reload();
                        } else {
                            if (data["status"] == "failed") {
                                Swal.fire(data["message"]);
                            } else {
                                Swal.fire("Something wrong ! Please try again.");
                            }
                        }
                    }
                });
            }
        });
    }


    $(document).on("click", "table#ordersTable td.MemoNumber", function (event) {
        if (+$(this).html() >= 0 || $(this).html() == "") {
            var memo = $(this).html();
            var html = '<input type="text" value="' + memo + '" id="MemoNumber" class="form-control">';
            $(this).html(html);
        }
    });
    $(document).on("keypress", "input#MemoNumber", function (e) {
        if (e.keyCode == 13) {
            var invoiceID = $(this)
                .parent("td")
                .parent("tr")
                .find("button.btn-danger")
                .val();
            var MemoNumber = $(this).val();
            jQuery.ajax({
                url: "ajax/final.php",
                contentType: "application/json",
                data: {
                    action: "memoUpdate",
                    MemoNumber: MemoNumber,
                    invoiceID: invoiceID
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["status"] == "success") {
                        swal("Good job!", data["message"], "success");
                        table.ajax.reload();
                    } else {
                        swal("Oops...!", data["message"], "error");
                    }
                }
            });
        }
    });

    // Delete
    $(document).on("click", ".delete", function () {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(result => {
            if (result.value) {
                jQuery.ajax({
                    url: "ajax/final.php",
                    contentType: "application/json",
                    data: {
                        action: "delete",
                        orderID: $(this).val()
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data["status"] == "success") {
                            Swal.fire(data["message"]);
                            $(this)
                                .closest("tr")
                                .remove();
                            table.ajax.reload();
                        } else {
                            if (data["status"] == "failed") {
                                Swal.fire(data["message"]);
                            } else {
                                Swal.fire("Something wrong ! Please try again.");
                            }
                        }
                    }
                });
            }
        });
    });

    function invoice() {
        var rows_selected = table.column(0).checkboxes.selected();
        var ids = [];
        $.each(rows_selected, function (index, rowId) {
            ids[index] = rowId;
        });

        jQuery.ajax({
            url: "ajax/final.php",
            contentType: "application/json",
            data: {
                action: "invoice",
                ids: ids
            },
            success: function (response) {
                window.open("invoice?orderInvoiceID=" + response, "_blank");
            }
        });
    }

    function csv() {
        var rows_selected = table.column(0).checkboxes.selected();
        var ids = [];
        $.each(rows_selected, function (index, rowId) {
            ids[index] = rowId;
        });

        jQuery.ajax({
            url: "ajax/order-list",
            contentType: "application/json",
            data: {
                action: "invoice",
                ids: ids
            },
            success: function (response) {
                window.open("csv.php?orderInvoiceID=" + response, "_blank");
            }
        });
    }


    $(document).on("click", "#userAsssign", function () {
        var rows_selected = table.column(0).checkboxes.selected();
        var ids = [];
        $.each(rows_selected, function (index, rowId) {
            ids[index] = rowId;
        });
        var userID = $("#userID").val();
        // alert(userID);
        jQuery.ajax({
            url: "ajax/final.php",
            contentType: "application/json",
            data: {
                action: "assign",
                assignUserID: userID,
                userID: userInfo.userID,
                ids: ids
            },
            success: function (response) {
                table.ajax.reload();
                $("#assignOrder").modal("toggle");
            }
        });
    });
    $(document).on("click", ".view", function () {
        var orderID = $(this).val();
        jQuery.ajax({
            url: "ajax/final.php",
            contentType: "application/json",
            data: {
                action: "view",
                orderID: orderID
            },
            success: function (response) {
                $("#viewOrder .modal-body")
                    .empty()
                    .append(response);
                $("#viewOrder .edit").val(orderID);
                $("#viewOrder").modal("toggle");
            }
        });
    });

    function chnageStatus(params) {
        var rows_selected = table.column(0).checkboxes.selected();
        var ids = [];
        $.each(rows_selected, function (index, rowId) {
            ids[index] = rowId;
        });
        if (ids.length > 0) {
            jQuery.ajax({
                url: "ajax/final.php",
                contentType: "application/json",
                data: {
                    action: "chnageStatus",
                    status: params,
                    userID: userInfo.userID,
                    ids: ids
                },
                success: function (response) {
                    swal("Good job!", "Order status changed to " + params, "success");
                    table.ajax.reload();
                }
            });
        } else {
            swal("Oops...!", "Select at list one order to " + params, "error");
        }
    }
});
