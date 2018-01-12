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
                            <a href="#">Users</a>
                        </li>
                        <li class="active">
                            <strong>All Users</strong>
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
                                <h5>List of all registered users</h5>

                            </div>
                            <div class="ibox-content">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control input-sm m-b-xs" id="filter" placeholder="Search in table">
                                    <table class="footable table table-stripped table-bordered" data-page-size="25" data-filter=#filter>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th data-hide="phone">UID</th>
                                                <th data-hide="phone">NAME</th>
                                                <th data-hide="phone">EMAIL</th>
                                                <th data-hide="phone">MOBILE</th>
                                                <th data-hide="phone">PINCODE</th>
                                                <th data-hide="phone,tablet">STATUS</th>
                                                <th data-hide="phone,tablet">IPADDRESS</th>
                                                <th data-hide="phone,tablet">REG.&nbsp;DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $user = $function->getUser(NULL,NULL,NULL,NULL,NULL,NULL,'publishdate DESC');

                                                for($i=0;$i<$user['count'];$i++){
                                            ?>
                                            <tr class="<?=($user['isactive'][$i]==1)?'active-user':'inactive-user';?>">
                                                <td><?=($i+1);?></td>
                                                <td><?=$user['uid'][$i];?></td>
                                                <td><?=$user['fname'][$i].' '.$user['lname'][$i];?></td>
                                                <td><?=$user['email'][$i];?></td>
                                                <td><?=$user['mobile'][$i];?></td>
                                                <td><?=$user['pincode'][$i];?></td>
                                                <td><?=$user['status'][$i];?></td>
                                                <td><?=$user['ipaddress'][$i];?></td>
                                                <td><?=date('d M Y h:i:s A', strtotime($user['publishdate'][$i]));?></td>
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
        });
    </script>
</body>

</html>
