<?php
    ob_start();
    //error_reporting(E_ALL ^ E_NOTICE);
    @session_start();
    ini_set('allow_url_include', 1);
    date_default_timezone_set("Asia/Kolkata");
    set_time_limit(600);
    ini_set('max_execution_time',600);

    include 'includes/settings/constant.php';
    include 'includes/settings/db.php';
    include 'includes/modules/functions.php';

    $function = new FUNCTIONS();

    global $redirect_uri;

    $redirect_uri = $function->getpageurl();
    if(empty($_SESSION['admin_id'])){
        header("Location: login.php?redirect_uri=$redirect_uri");
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>XioShop.com | ADMIN PANEL</title>


    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- FooTable -->
    <link href="assets/css/plugins/footable/footable.core.css" rel="stylesheet">

    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <link href="assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <style>
        table.order-items{width: 100%;}
        table.order-items tr td{ padding: 10px;}
    </style>
    <script src="https://cdn.jsdelivr.net/ace/1.1.01/noconflict/theme-github.js"></script>
    <script src="https://cdn.jsdelivr.net/ace/1.1.01/noconflict/mode-javascript.js"></script>
</head>

<body>
    <div id="wrapper">
        <!--Nav Bar-->
        <?php include 'includes/_templates/navigation.php'; ?>
        <!--End Nav Bar-->

        <div id="page-wrapper" class="gray-bg">
            <!--Header-->
            <?php include 'includes/_templates/header.php'; ?>
            <!--End Header-->

            <!--Breadcrumb-->
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Orders</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Orders</a>
                        </li>
                        <li class="active">
                            <strong>Active Orders</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <!--End Breadcrumb-->

            <!--Content Wrapper-->
            <div class="wrapper wrapper-content animated fadeInRight ecommerce">
                <!--<div class="ibox-content m-b-sm border-bottom">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="order_id">Order ID</label>
                                <input type="text" id="order_id" name="order_id" value="" placeholder="Order ID" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="status">Order status</label>
                                <input type="text" id="status" name="status" value="" placeholder="Status" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="customer">Customer</label>
                                <input type="text" id="customer" name="customer" value="" placeholder="Customer" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="date_added">Date added</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_added" type="text" class="form-control" value="03/04/2014">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="date_modified">Date modified</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_modified" type="text" class="form-control" value="03/06/2014">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="amount">Amount</label>
                                <input type="text" id="amount" name="amount" value="" placeholder="Amount" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>-->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Filter orders based on their status</h5>
                                <div class="pull-right">
                                    <div class="switch-toggle switch-3 switch-candy">
                                        <input id="order_all" name="order_type" type="radio" value="all" checked>
                                        <label for="all_listing" onclick="">All</label>

                                        <input id="order_to_pack" name="order_type" value="to_pack" type="radio">
                                        <label for="order_to_pack" onclick="">To packed</label>

                                        <input id="order_to_ship" name="order_type" value="to_ship" type="radio">
                                        <label for="order_to_ship" onclick="">To Ship</label>

                                        <input id="order_to_deliver" name="order_type" value="to_deliver" type="radio">
                                        <label for="order_to_deliver" onclick="">To Deliver</label>

                                        <input id="order_delivered" name="order_type" value="delivered" type="radio">
                                        <label for="order_delivered" onclick="">Delivered</label>

                                        <input id="order_cancelled" name="order_type" value="cancelled" type="radio">
                                        <label for="order_cancelled" onclick="">Canceled</label>

                                        <a></a>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control input-sm m-b-xs" id="filter" placeholder="Search in table">
                                    <table class="footable table table-stripped table-bordered" data-page-size="8" data-filter=#filter>
                                        <thead>
                                            <tr>

                                                <th>ID</th>
                                                <th data-hide="phone">Order&nbsp;Id</th>
                                                <th data-hide="phone">User&nbsp;Id</th>
                                                <th data-hide="phone,tablet">Item&nbsp;Count</th>
                                                <th data-hide="phone">Order&nbsp;Amt.</th>
                                                <th data-hide="phone,tablet">Shipping&nbsp;Charges</th>
                                                <th data-hide="phone,tablet">XioCash&nbsp;Used</th>
                                                <th data-hide="phone,tablet">Coupon&nbsp;Code</th>
                                                <th data-hide="phone,tablet">Discount</th>
                                                <th data-hide="phone,tablet">Payable&nbsp;Amount</th>
                                                <th data-hide="phone,tablet">Shipping&nbsp;Address</th>
                                                <th data-hide="phone,tablet">Order&nbsp;Dt.</th>
                                                <th data-hide="phone,tablet">Delivery&nbsp;Dt.</th>
                                                <th data-hide="phone,tablet">Delivery&nbsp;Slot</th>
                                                <th data-hide="phone,tablet">Payment&nbsp;Mode</th>
                                                <th data-hide="phone,tablet">Items</th>
                                                <th data-hide="phone">Status</th>
                                                <th class="text-right">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $orders = $function->getOrders(NULL, NULL, NULL);

                                                for($i=0;$i<$orders['count'];$i++){
                                                    $user = $function->getUser($orders['userid'][$i]);

                                                    $userdetails =   "Name: ".$user["fname"][0]." ".$user["lname"][0]." "
                                                                    ."Email: ".$user['email'][0]." "
                                                                    ."Mobile: ".$user['mobile'][0]." ";


                                                    if($orders['isactive'][$i]==0){
                                                        $status_msg = "Cancelled";
                                                        $status_class = "label-danger";
                                                    }else{
                                                        switch ($orders['status'][$i]){
                                                            case 0:
                                                                $status_msg = "Pending";
                                                                $status_class = "label-warning";
                                                                break;
                                                            case 1:
                                                                $status_msg = "Packed";
                                                                $status_class = "label-success";
                                                                break;
                                                            case 2:
                                                                $status_msg = "Shipped";
                                                                $status_class = "label-success";
                                                                break;
                                                            case 3:
                                                                $status_msg = "Delivered";
                                                                $status_class = "label-primary";
                                                                break;
                                                        }
                                                    }

                                                    $del_slot = $function->getDeliverySlots($orders['deleveryslot'][$i]);
                                            ?>
                                            <tr class="<?=($orders['isactive'][$i]==1)?'active-product':'inactive-product';?>">
                                                <td><?=$orders['id'][$i];?></td>
                                                <td><?=$orders['orderid'][$i];?></td>
                                                <td>
                                                    <?=$orders['userid'][$i];?>&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-xs btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="<?=$userdetails;?>">
                                                        <i class="fa fa-2x fa-info-circle"> </i>
                                                    </button>
                                                </td>
                                                <td><?=$orders['cartcount'][$i];?></td>
                                                <td>Rs. <?=sprintf("%01.2f",$orders['orderamount'][$i]);?></td>
                                                <td><?=$orders['shippingcharges'][$i];?></td>
                                                <td><?=$orders['xiocashused'][$i];?></td>
                                                <td><?=(!empty($orders['couponcode'][$i]))?$orders['couponcode'][$i]:'NA';?></td>
                                                <td><?=$orders['discount'][$i];?></td>
                                                <td><?=$orders['payableamount'][$i];?></td>
                                                <td><?=$orders['shippingaddress'][$i];?></td>
                                                <td><?=date('d-m-Y H:i:s A', strtotime($orders['orderdate'][$i]));?></td>
                                                <td><?=date('d-m-Y',  strtotime($orders['deliverydate'][$i]));?></td>
                                                <td><?=$del_slot['display'][0];?></td>
                                                <td><?=$orders['paymentmode'][$i];?></td>
                                                <td>
                                                    <table class="">
                                                        <thead>
                                                        <tr>
                                                            <td>PID</td>
                                                            <td>Title</td>
                                                            <td>MRP</td>
                                                            <td>Selling&nbsp;Price</td>
                                                            <td>Qty.</td>
                                                            <td>Weight</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $items = (array) json_decode($orders['items'][$i],true);
                                                            for($j=0;$j<count($items['items']);$j++){
                                                        ?>
                                                        <tr>
                                                            <td><?=$items['items'][$j]['pid'];?></td>
                                                            <td><?=$items['items'][$j]['title'];?></td>
                                                            <td><?=$items['items'][$j]['mrp'];?></td>
                                                            <td><?=$items['items'][$j]['sellingprice'];?></td>
                                                            <td><?=$items['items'][$j]['qty'];?></td>
                                                            <td><?=$items['items'][$j]['weight'];?></td>
                                                        </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <span class="label <?=$status_class;?>"><?=$status_msg;?></span>
                                                </td>
                                                <td class="text-right">
                                                    <div id="btnSetStatus" class="btn-group" data-orderid="<?=$orders['orderid'][$i];?>" data-userid="<?=$orders['userid'][$i];?>">
                                                        <button class="btn-white btn btn-xs" data-status="1">Packed</button>
                                                        <button class="btn-white btn btn-xs" data-status="2">Shipped</button>
                                                        <button class="btn-white btn btn-xs" data-status="3">Delivered</button>
                                                    </div>
                                                    <div id="btnCancelOrder" class="btn-group" data-orderid="<?=$orders['orderid'][$i];?>" data-userid="<?=$orders['userid'][$i];?>">
                                                        <button class="btn-white btn-danger btn btn-xs">Cancel Order</button>
                                                    </div>
                                                    <?php
                                                        $invoice_html = $function->getOrderConfirmationMail($orders['orderid'][$i], $orders['userid'][$i]);
                                                    ?>
                                                    <div id="btnPrintInvoice" class="btn-group" data-orderid="<?=$orders['orderid'][$i];?>" data-userid="<?=$orders['userid'][$i];?>" data-html='<?=$invoice_html;?>'>
                                                        <button class="btn-primary btn btn-xs">Print Invoice</button>
                                                        <div class="invoice-html hidden"><?=$invoice_html;?></div>

                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <ul class="pagination pull-right"></ul>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="clearfix"></div>
                                </div><div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--End Content Wrapper-->
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="assets/js/jquery-2.1.1.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>

    <!-- Data picker -->
    <script src="assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- FooTable -->
    <script src="assets/js/plugins/footable/footable.all.min.js"></script>

    <!--jsPDF-->
    <script src="http://html2canvas.hertzen.com/build/html2canvas.js"></script>
    <script src="https://cdn.jsdelivr.net/ace/1.1.01/noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="assets/js/jsPDF/dist/jspdf.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

            $('#date_added').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

        $('#btnSetStatus button').click(function(){
            var status = $(this).attr('data-status');
            var oid = $(this).parents().attr('data-orderid');
            var uid = $(this).parents().attr('data-userid'); //alert(uid);

            $.ajax({
                url: "<?=$adminbasepath;?>includes/modules/ajax-updateOrderStatus.php",
                type: "POST", //can be post or get
                data: {action: 'setOrderStatus', status: status, orderid: oid, userid: uid},
                success: function(data){
                    alert(data);
                    window.location.href=window.location.href;
                }
            });
        });

        $('input[name=listing_type]').change(function(){
            var filter_type = $(this).val();
            //alert($(this).val());
            switch(filter_type){
                case 'all':
                    $('#product-list tr.inactive-product, #product-list tr.active-product').show();
                    break;
                case 'active':
                    $('#product-list tr.active-product').show();
                    $('#product-list tr.inactive-product').hide();
                    break;
                case 'inactive':
                    $('#product-list tr.active-product').hide();
                    $('#product-list tr.inactive-product').show();
                    break;
            }
        });
    </script>

    <script>

        $("#btnPrintInvoice button").click(function(){
            var oid = $(this).parents().attr('data-orderid');
            var uid = $(this).parents().attr('data-userid');
            var _html = $(this).parents().children('.invoice-html').html();
            var url = "<?=$basePath;?>docs/invoice.php?orderid="+oid+"&userid="+uid;
            var win = window.open(url, '_blank');
            win.focus();
            //printHTML(_html);
        });
        function printHTML(html) {
            //Get the HTML of div
            var divElements = html;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;


        }
    </script>
</body>

</html>
