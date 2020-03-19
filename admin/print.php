<?php
session_start();
include_once '../inc/databaseConnection.php';
global $con;
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }

        table {
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        table#t01 tr:nth-child(even) {
            background-color: #eee;
        }

        table#t01 tr:nth-child(odd) {
            background-color: #fff;
        }

        table#t01 th {
            background-color: black;
            color: white;
        }

        hr.new3 {
            border-top: 1px dashed red;
        }

        table.table-with-info,
        table.table-with-info td,
        table.table-with-info th {
            border: 0px solid black;
            border-collapse: collapse;
        }

        @media print {

            .section {
                display: flex;
                flex-direction: column;
                width: 100%;
                height: 100vh;
                justify-content: space-between;
            }
        }
    </style>

</head>

<body>
    <?php
    $InvoiceIDs = explode(',', $_REQUEST['invoiceID']);
    $j = 1;
    foreach ($InvoiceIDs as $InvoiceID) {
        $sql = "SELECT orders.*,
            orderDetails.*,
            courier.CourierName, 
            users.name as userName
            FROM orders 
            LEFT JOIN orderDetails on (orders.orderID = orderDetails.orderID)  
            LEFT JOIN courier on (orderDetails.courierID = courier.courierID) 
            LEFT JOIN users on (orders.userID = users.userID) where orders.invoiceID = '" . $InvoiceID . "'";
        $results = mysqli_query($con, $sql);
        $data = mysqli_fetch_assoc($results);
        if ($j == 1) {
            echo '<div class="section">';
        }
    ?>



        <div class="div-section">
            <table class="table-with-info" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 50%;top: 0;display: block;">
                        <p>invoice #<?php echo $data['invoiceID'] ?></p>

                        <strong><?php echo $data['CustomerName'] ?> <br>
                            <?php echo $data['CustomerPhone'] ?><br>
                            <?php echo $data['CustomerAddress'] ?></strong>
                    </td>
                    <td style="width: 50%">
                        Order Date : <?php echo $data['OrderDate'] ?><br>
                        <strong>Dorkarishop.com</strong><br>
                        375/1, 60 Feet Road <br>
                        Mirpur-2 Dhaka <br>
                        Contact No : 017918732244 <br>

                    </td>
                </tr>
            </table>
            <table class="table table-striped">

                <tr>
                    <th style="width: 60%">Product</th>
                    <th style="width: 20%">Quantity</th>
                    <th style="width: 20%">Price</th>
                </tr>


                <?php
                $sql = "SELECT * FROM orderProduct where orderID = '" . $data['orderID'] . "'";
                $results = mysqli_query($con, $sql);

                while ($result = mysqli_fetch_assoc($results)) { ?>

                    <tr>
                        <td><?php echo $result['productName']; ?></td>
                        <td><?php echo $result['productQuantity']; ?></td>
                        <td><?php echo $result['productPrice']; ?></td>
                    </tr>
                <?php  } ?>
                <tfoot>
                    <tr>
                        <td colspan="1" style="border: none;"></td>
                        <th>Delivery : </th>
                        <td><?php echo $data['deliveryCharge'] ?> Tk</td>
                    </tr>
                    <?php if ($data['orderBkashLoad']) { ?>
                        <tr>
                            <td colspan="1" style="border: none;"></td>
                            <th><?php echo $data['orderBkashLoad']; ?> : </th>
                            <td><?php echo $data['orderBkashLoadAmount']; ?> Tk</td>
                        </tr>
                    <?php } ?>
                    <?php if ($orderBkashLoad) { ?>
                        <tr>
                            <td colspan="1" style="border: none;"></td>
                            <th>Bkash/ : </th>
                            <td><?php echo $data['deliveryCharge'] ?> Tk</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="1" style="border: none;"></td>
                        <th>Total : </th>
                        <td><?php echo $data['total'] ?> Tk</td>
                    </tr>

            </table>
            <div style=" display: flex; flex-direction: row; justify-content: space-between; ">
                <p>NB: This invoice will be used as a Warranty Card from purchase date (<?php echo $data['OrderDate'] ?>). </p>
                <p>Order Recived By : <?php echo $data['userName'] ?></p>
            </div>
        </div>
    <?php
        if ($j == 3) {
            echo '</div>';
            $j = 1;
        } else {
            $j++;
            echo "<hr>";
        }
    } ?>


    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>

    <script>
        $(function() {
            // alert();
            window.print();
            // window.close();

            window.onfocus = function() {
                window.close();
            }
            window.onafterprint = function() {
                window.close()
            };

        });
    </script>
</body>

</html>