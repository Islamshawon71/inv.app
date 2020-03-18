<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="../assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="../dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="../dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="../dist/js/custom.min.js"></script>
<!-- this page js -->
<script src="../assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
<script src="../assets/extra-libs/multicheck/jquery.multicheck.js"></script>
<script src="../assets/extra-libs/DataTables/datatables.min.js"></script>
<script src="../dist/js/dataTables.buttons.min.js"></script>
<script src="../dist/js/buttons.flash.min.js"></script>
<script src="../dist/js/jszip.min.js"></script>
<script src="../dist/js/pdfmake.min.js"></script>
<script src="../dist/js/vfs_fonts.js"></script>
<script src="../dist/js/buttons.html5.min.js"></script>
<script src="../dist/js/buttons.print.min.js"></script>
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

<!-- This Page JS -->
<script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<script src="../dist/js/pages/mask/mask.init.js"></script>
<script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
<script src="../assets/libs/select2/dist/js/select2.min.js"></script>
<script src="../assets/libs/jquery-asColor/dist/jquery-asColor.min.js"></script>
<script src="../assets/libs/jquery-asGradient/dist/jquery-asGradient.js"></script>
<script src="../assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js"></script>
<script src="../assets/libs/jquery-minicolors/jquery.minicolors.min.js"></script>
<script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../assets/libs/quill/dist/quill.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<?php if (function_exists('script')) {
    script();
} ?>

<script>
    <?php
    $userInfo = array(
        'userID' => 1,
        'type' => 'Admin',
        'status' => 1
    );
    echo ' var userInfo = ' . json_encode($userInfo);
    ?>
    /****************************************
     *       Basic Table                   *
     ****************************************/
    $('#zero_config').DataTable({
        ordering: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>

<script>
    $(function() {
        $(document).on('click', '.ordersync', function() {
            var id = $(this).val();

            swal({
                title: "Loading...",
                text: "Please wait",
                icon: "../assets/images/loading.gif",
                button: false,
                closeOnClickOutside: false,
                closeOnEsc: false
            });

            jQuery.ajax({
                url: 'ajax/order-sync.php',
                contentType: 'application/json',
                data: {
                    'action': 'ordersync'
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data['status'] == 'success') {
                        swal("Good job!", data['order'] +
                            ' Orders Sync.', "success").then(function() {
                            location.reload();
                        });
                    } else {
                        swal("Good job!", data['order'] +
                            ' Orders Sync.', "success");

                    }
                }
            });
        });
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        })
    });
</script>

<script>
    //***********************************//
    // For select 2
    //***********************************//
    $(".select2").select2();

    /*colorpicker*/
    $('.demo').each(function() {
        //
        // Dear reader, it's actually very easy to initialize MiniColors. For example:
        //
        //  $(selector).minicolors();
        //
        // The way I've done it below is just for the demo, so don't get confused
        // by it. Also, data- attributes aren't supported at this time...they're
        // only used for this demo.
        //
        $(this).minicolors({
            control: $(this).attr('data-control') || 'hue',
            position: $(this).attr('data-position') || 'bottom left',

            change: function(value, opacity) {
                if (!value) return;
                if (opacity) value += ', ' + opacity;
                if (typeof console === 'object') {
                    console.log(value);
                }
            },
            theme: 'bootstrap'
        });

    });
</script>

</body>

</html>