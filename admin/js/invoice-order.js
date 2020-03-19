$(document).ready(function () {
  var table = $("#ordersTable").DataTable({
    ajax:
      "ajax/invoice-order.php?action=getOrder" +
      "&&orderType=" +
      $("#ordersTable").attr("data-type"),
    buttons: [

      {
        text: '<i class="far fa-file-alt"></i> Pending Invoice',
        className: "btn pending-payment btn-info btn-sm waves-effect ",
        action: function (e, dt, node, config) {
          chnageStatus("Pending Invoice");
        }
      },
      {
        text: '<i class="far fa-file-alt"></i> Invoiced',
        className: "btn pending-payment btn-info btn-sm waves-effect ",
        action: function (e, dt, node, config) {
          chnageStatus("Invoiced");
        }
      },
      {
        text: '<i class="fas fa-box-open"></i> Stock Out',
        className: "btn stock-out btn-warning btn-sm waves-effect",
        action: function (e, dt, node, config) {
          chnageStatus("Stock Out");
        }
      },
      {
        text: '<i class="fas fa-print"></i> Invoice Print',
        className: "btn invoice-print btn-info btn-sm waves-effect ",
        action: function (e, dt, node, config) {

          var data = table.$('input, select').serialize();
          var rows_selected = table.column(0).checkboxes.selected();
          var ids = [];
          $.each(rows_selected, function (index, rowId) {
            ids[index] = rowId;
          });
          window.open('print.php?invoiceID=' + ids, '_blank')

          // document.location.href = 'print.php?invoiceID=' + ids;

          if ($("#ordersTable").attr("data-type") != 'Invoiced') {

            swal({
              title: "Are you sure?",
              text: "All inveoice Printed !",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                chnageStatus("Invoiced");
              } else {
                swal("Invoice Stay Pending !");
              }
            });
          }

        }
      },
      {
        text: '<i class=" fas fa-truck"></i> Delivered',
        className: "btn btn-danger delivered btn-sm waves-effect",
        action: function (e, dt, node, config) {
          chnageStatus("Delivered");
        }
      },
      {
        text: '<i class="fas fa-table"></i> CSV',
        className: "btn btn-success btn-sm csv",
        action: function (e, dt, node, config) {
          csv();
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
              url: "ajax/order-list",
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
      {
        data: "total",
        "width": "50px"
      },
      {
        className: "CourierName",
        data: "CourierName",
        "width": "50px"

      },
      {
        data: "OrderDate",
        "width": "100px"

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

      if ($("#ordersTable").attr("data-type") == 'Invoiced') {
        $('.delivered').show();
      } else {
        $('.delivered').hide();
      }
      if ($("#ordersTable").attr("data-type") == 'Stock Out') {
        $('.stock-out').hide();
      } else {
        $('.stock-out').show();
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
      url: "ajax/invoice-order.php",
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
          url: "ajax/invoice-order.php",
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
          url: "ajax/invoice-order.php",
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
      url: "ajax/invoice-order.php",
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
      url: "ajax/invoice-order.php",
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
      url: "ajax/invoice-order.php",
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
      url: "ajax/invoice-order.php",
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
        url: "ajax/invoice-order.php",
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
