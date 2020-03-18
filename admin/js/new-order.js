$(document).ready(function () {
  $("#courierID")
    .select2({
      placeholder: "Select a Courier",
      ajax: {
        url: "ajax/new-order.php",
        contentType: "application/json",
        data: function (params) {
          var query = {
            action: "getCourier",
            search: params.term
          };
          return query;
        },
        processResults: function (data) {
          var data = $.parseJSON(data);
          return {
            results: data.data
          };
        }
      }
    }).trigger("change").on("select2:select", function (e) {
      var data = jQuery.parseJSON(store);
      console.log(data[$(this).val()]['HasCity']);

      if (data[$(this).val()]["HasCity"] == 1) {
        jQuery(".HasCity").show();
      } else {
        jQuery(".HasCity").hide();
      }

      if (data[$(this).val()]["HasZone"] == 1) {
        jQuery(".HasZone").show();
      } else {
        jQuery(".HasZone").hide();
      }

      if ($(this).val() == 1) {
        $("#cityID")
          .empty()
          .append('<option value="11" >Dhaka</option>');
      } else {
        $("#cityID").empty();
      }
      $("#zoneID").empty();
    });
  // Get City List
  $("#cityID").select2({
    placeholder: "Select a City",
    ajax: {
      url: "ajax/new-order.php",
      contentType: "application/json",
      data: function (params) {
        var query = {
          action: "getCity",
          search: params.term,
          courierID: $("#courierID").val()
        };
        return query;
      },
      processResults: function (data) {
        var data = $.parseJSON(data);
        return {
          results: data.data
        };
      }
    }
  }).trigger("change").on("select2:select", function (e) {
    jQuery("#zone").empty();
  });

  // Zone Select
  $("#zoneID").select2({
    placeholder: "Select a Zone",
    ajax: {
      url: "ajax/new-order.php",
      contentType: "application/json",
      data: function (params) {
        var query = {
          action: "getZone",
          search: params.term,
          cityID: $("#cityID").val(),
          courierID: $("#courierID").val()
        };
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

  jQuery("#products").select2({
    templateResult: function (state) {
      if (!state.id) {
        return state.text;
      }
      var $state = $(
        '<span><img width="60px" src="' +
        state.image +
        '" class="img-flag" /> ' +
        state.text +
        "</span>"
      );
      return $state;
    },
    ajax: {
      url: "ajax/new-order.php",
      contentType: "application/json",
      data: function (params) {
        var query = {
          action: "getProducts",
          search: params.term
        };
        return query;
      },
      processResults: function (data) {
        var data = jQuery.parseJSON(data);
        return {
          results: data.data
        };
      }
    }
  }).trigger("change").on("select2:select", function (e) {
    $("table.productTable tbody").append(
      "<tr>" +
      '<td  style="display: none"><input type="text" class="productID" style="width:80px;" value="' + e.params.data.id + '"></td>' +
      '<td><span class="productCode">' + e.params.data.productCode + '</span></td>' +
      '<td><span class="productName">' + e.params.data.text + '</span></td>' +
      '<td><input type="number" class="productQuantity form-control" style="width:80px;" value="1"></td>' +
      '<td><span class="productPrice">' + e.params.data.productPrice + '</span></td>' +
      '<td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
      "</tr>"
    );
    $(this).empty();
    calculation();
  });



  $(document).on("click", ".delete-btn", function () {
    $(this).closest("tr").remove();
    calculation();
  });



  $(".PaymentNumber").hide();
  $(".PaymentAgentNumber").hide();
  $(".PaymentAmount").hide();


  $("#PaymentType").select2().trigger("change").on("select2:select", function (e) {
    if (e.params.data.text == "Select Payment Type") {
      $(".PaymentNumber").hide().val();
      $(".PaymentAgentNumber").hide().val();
      $(".PaymentAmount").hide().val();
    } else if (e.params.data.text == "Cash") {
      $(".PaymentNumber").hide().val();
      $(".PaymentAgentNumber").hide().val();
      $(".PaymentAmount").show().val(0);
    } else {
      $(".PaymentNumber").show();
      $(".PaymentAgentNumber").hide();
      $(".PaymentAmount").hide();
    }
  });

  $("#PaymentNumber").select2({
    allowClear: true,
    placeholder: "Select a Number",
    ajax: {
      url: "ajax/new-order.php",
      contentType: "application/json",
      data: function (params) {
        var query = {
          action: "PaymentNumber",
          PaymentType: $("#PaymentType").val()
        };
        return query;
      },
      processResults: function (data) {
        var data = $.parseJSON(data);
        return {
          results: data.data
        };
      }
    }
  }).trigger("change").on("select2:select", function (e) {
    if (e.params.data.text == "") {
      $(".PaymentAgentNumber").hide();
      $(".PaymentAmount").hide();
    } else {
      $(".PaymentAgentNumber").show();
      $(".PaymentAmount").show();
    }
  }).on("select2:unselect", function (e) {
    $(".PaymentAgentNumber").hide();
    $(".PaymentAmount").hide();
    calculation();
  });


  $("#PaymentAmount").on("input", function () {
    calculation();
  });

  $("#DeliveryCharge").on("input", function () {
    calculation();
  });

  $("#DiscountCharge").on("input", function () {
    calculation();
  });

  // calculation
  calculation();
  function calculation() {
    var subtotal = 0;
    var DiscountCharge = +$("#DiscountCharge").val();
    var DeliveryCharge = +$("#DeliveryCharge").val();
    var PaymentAmount = +$("#PaymentAmount").val();
    $("table.productTable tbody tr").each(function (index) {
      subtotal =
        subtotal +
        +$(this)
          .find(".productPrice")
          .text() *
        +$(this)
          .find(".productQuantity")
          .val();
    });
    $("#subtotal").text(subtotal);
    $("#total").text(subtotal + DeliveryCharge - PaymentAmount - DiscountCharge);

    // console.log(total, delivery, bkash, discount);
  }

  $("#placeOrder").click(function () {
    var invoiceID = $("#invoiceID").val();
    var CustomerName = $("#CustomerName").val();
    var CustomerPhone = $("#CustomerPhone").val();
    var CustomerAddress = $("#CustomerAddress").val();
    var storeID = $("#storeID").val();
    var total = +$("#total").text();
    var DeliveryCharge = +$("#DeliveryCharge").val();
    var DiscountCharge = +$("#DiscountCharge").val();
    var PaymentType = $("#PaymentType").val();
    var PaymentNumber = $("#PaymentNumber").val();
    var PaymentAmount = +$("#PaymentAmount").val();
    var PaymentAgentNumber = +$("#PaymentAgentNumber").val();
    var courierID = +$("#courierID").val();
    var cityID = +$("#cityID").val();
    var zoneID = +$("#zoneID").val();
    var product = [];

    $("table.productTable tbody tr").each(function (index, value) {
      var currentRow = $(this);
      var obj = {};
      obj.productID = currentRow.find(".productID").val();
      obj.productName = currentRow.find(".productName").text();
      obj.productQuantity = currentRow.find(".productQuantity").val();
      obj.productPrice = currentRow.find(".productPrice").text();
      product.push(obj);
    });

    // console.log(product);

    if (CustomerName == "") {
      swal("Oops...!", "Customer Name should not empty !", "error");
    } else if (CustomerPhone == "") {
      swal("Oops...!", "Customer Phone should not empty !", "error");
    } else if (CustomerAddress == "") {
      swal("Oops...!", "Customer Address should not empty !", "error");
    } else if (courierID == "") {
      swal("Oops...!", "Courier should not empty !", "error");
    } else if ($.isEmptyObject(product)) {
      swal("Oops...!", "Products should not empty !", "error");
    } else {
      var data = {};
      data["invoiceID"] = invoiceID;
      data["storeID"] = storeID;
      data["CustomerName"] = CustomerName;
      data["CustomerPhone"] = CustomerPhone;
      data["CustomerAddress"] = CustomerAddress;
      data["total"] = total;
      data["DeliveryCharge"] = DeliveryCharge;
      data["DiscountCharge"] = DiscountCharge;
      data["PaymentType"] = PaymentType;
      data["PaymentNumber"] = PaymentNumber;
      data["PaymentAmount"] = PaymentAmount;
      data["PaymentAgentNumber"] = PaymentAgentNumber;
      data["courierID"] = courierID;
      data["cityID"] = cityID;
      data["zoneID"] = zoneID;
      data["userID"] = 1;
      // data["userID"] = userInfo.userID;
      data["products"] = product;

      // console.log(data);

      $.ajax({
        type: "post",
        url: "ajax/new-order.php",
        data: {
          action: "placeOrder",
          data: data
        },
        success: function (response) {
          var data = JSON.parse(response);

          if (data["status"] == "success") {
            swal("Good job!", data["message"], "success");
            // setTimeout(function() {
            //   window.location.assign("order-list");
            // }, 1000);
          } else {
            if (data["status"] == "failed") {
              swal("Oops...!", data["message"], "error");
            } else {
              swal("Oops...!", "Something wrong ! Please try again.", "error");
            }
          }
        }
      });
    }
  });
});
