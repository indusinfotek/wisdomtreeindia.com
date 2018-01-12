<?php
    ob_start();
    //error_reporting(E_ALL ^ E_NOTICE);
    @session_start();
    ini_set('allow_url_include', 1);    
    date_default_timezone_set("Asia/Kolkata");
    set_time_limit(600);
    ini_set('max_execution_time',600);
    
    include '_includes/_settings/constant.php';
    include '_includes/_settings/db.php';
    include '_includes/_modules/functions.php';
    
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

    <link href="_assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="_assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="_assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    
    <!-- Data Tables -->
    <link href="_assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="_assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="_assets/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
    
    <!--JSwitch-->
    <link href="_assets/css/plugins/switchery/switchery.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="_assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="_assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    
    <!-- Morris -->
    <link href="_assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    
    <!--Tags Input-->
    <link href="_assets/css/bootstrap-tagsinput.css" rel="stylesheet">
    
    <link href="_assets/css/animate.css" rel="stylesheet">
    <link href="_assets/css/style.css" rel="stylesheet"> 
    
    <link href="_assets/css/plugins/iCheck/custom.css" rel="stylesheet">    
    
    <!--Toggle Switch-->
    <link href="_assets/css/plugins/toggleSwitch/css/toggle-switch.css" rel="stylesheet">
    
    <!--Ladda Loading Buttons-->
    <link href="_assets/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
    
    <link href="_assets/css/animate.css" rel="stylesheet">
    <link href="_assets/css/style.css" rel="stylesheet">
    
    <style>
        .project-people img{max-height:70px; max-width:70px; height: auto!important; width: auto!important;}
        .switch-toggle.switch-3 label {width:100px;outline: 0;}
        .switch-toggle.switch-3 label:active, .switch-toggle.switch-3 label:focus, .switch-toggle.switch-3 label:hover{outline: 0;}
        *:focus, *:active {
            outline: 0;            
        }
        
        <!--Data Table CSS--->
        body.DTTT_Print {
            background: #fff;

        }
        .DTTT_Print #page-wrapper {
            margin: 0;
            background:#fff;
        }

        button.DTTT_button, div.DTTT_button, a.DTTT_button {
            border: 1px solid #e7eaec;
            background: #fff;
            color: #676a6c;
            box-shadow: none;
            padding: 6px 8px;
        }
        button.DTTT_button:hover, div.DTTT_button:hover, a.DTTT_button:hover {
            border: 1px solid #d2d2d2;
            background: #fff;
            color: #676a6c;
            box-shadow: none;
            padding: 6px 8px;
        }

        .dataTables_filter label {
            margin-right: 5px;            
        }
        .dataTables-products tbody tr td a img {max-width: 120px;}
        .center{text-align: center;}
    </style>
</head>

<body>
    <div id="wrapper">
        <!--Nav Bar-->
        <?php include '_includes/_templates/navigation.php'; ?>
        <!--End Nav Bar-->
        
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <!--Header-->
            <?php include '_includes/_templates/header.php'; ?>
            <!--End Header-->
            
            <!--Breadcrumb-->
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Listing</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Listing</a>
                        </li>
                        <li class="active">
                            <strong>My Listings</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <!--End Breadcrumb-->
            
            <!--Content Wrapper-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content animated fadeInUp">

                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Filter products listed in your account</h5>
                                <div class="pull-right">
                                    <div class="switch-toggle switch-3 switch-candy">
                                        <input id="all_listing" name="listing_type" type="radio" value="all" checked>
                                        <label for="all_listing" onclick="">All</label>

                                        <input id="active_listing" name="listing_type" value="active" type="radio">
                                        <label for="active_listing" onclick="">Active</label>

                                        <input id="inactive_listing" name="listing_type" value="inactive" type="radio">
                                        <label for="inactive_listing" onclick="">Inactive</label>

                                        <a></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ibox-content">
                                <table id="product-list" class="table table-striped table-bordered table-hover dataTables-products">
                                    <div id="bulkActions" class="col-md-12" style="padding: 0;">
                                        <h3>Bulk Actions</h3>
                                        <a href="javascript:void(0);" data-status="1" id="a_mylist_active" role="button" class="btn btn-sm btn-primary">Active</a>
                                        <a href="javascript:void(0);" data-status="0" id="a_mylist_inactive" role="button" class="btn btn-sm btn-warning">Inactive</a>
                                        <!--<a href="javascript:void(0);" data-status="2" id="a_mylist_delete" role="button" class="btn btn-danger">Delete</a>-->
                                        <div class="hr-line-dashed m-t-none"></div>  
                                    </div>
                                    
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><div class="checkbox i-checks"><label><input type="checkbox" class="checkall"></label></div></th>
                                            <th>STATUS</th>
                                            <th>IMAGE</th>
                                            <th>PRODUCT DETAILS</th>
                                            <th>QTY</th>
                                            <th>MRP</th>
                                            <th>SELLING PRICE</th>
                                            <th data-hide="phone,tablet">KEYWORDS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_product">
                                        <?php  
                                            $products = $function->getProducts(NULL, NULL, NULL, NULL, NULL);

                                            for($i=0;$i<$products['count'];$i++){                                                    
                                        ?>
                                        <tr class="<?=($products['isactive'][$i]==1)?'active-product':'inactive-product';?>">
                                            <td></td>
                                            <td><div class="checkbox i-checks"><label><input id="check-<?=$products['pid'][$i];?>" name="check[]" data-pid="<?=$products['pid'][$i];?>" type="checkbox" class="check"></label></div></td>
                                            <td class="project-status">
                                                <span class="label label-<?=($products['isactive'][$i]==1)?'primary':'warning';?>"><?=($products['isactive'][$i]==1)?'Active':'Inactive';?></span>
                                            </td>
                                            <td class="project-people">
                                                <a href="javascript:void(0);">
                                                    <img class="lazy" data-original="<?=(!empty($products['image'][$i]))?$adminbasepath.'_uploads/products/'.$products['image'][$i]:'';?>" width="70" height="70" />
                                                </a>
                                            </td>
                                            <td class="project-title">
                                                <a href="#">
                                                    <?=$products['title'][$i];?>
                                                </a>
                                                <br/>
                                                <small><b>PID</b> : <?=($products['pid'][$i])?$products['pid'][$i]:'NA';?></small><br/>
                                                <small><b>SKU</b> : <?=($products['sku'][$i])?$products['sku'][$i]:'NA';?></small>
                                            </td>

                                            <td class="project-completion">
                                                <div class="input-group" style="max-width:120px;">
                                                    <?=$products['product_weight'][$i];?>
                                                </div>
                                            </td>

                                            <td class="project-completion">
                                                <div class="input-group" style="max-width:120px;"> 
                                                    <?=$products['mrp'][$i];?>
                                                </div>
                                            </td>

                                            <td class="project-completion">
                                                <div class="input-group" style="max-width:120px;">
                                                    <?=$products['selling_price'][$i];?>
                                                </div>
                                            </td>
                                            
                                            <td class="project-completion">
                                                <div class="input-group" style="max-width:120px;">
                                                    <?=$products['tags'][$i];?>
                                                </div>
                                            </td>

                                            <td class="project-actions">
                                                <a href="single-listing.php?action=edit&pid=<?=$products['pid'][$i];?>" class="btn btn-white btn-sm btnEdit" target="_blank"><i class="fa fa-pencil"></i> Edit </a>                                                    
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th><div class="checkbox i-checks"><label><input type="checkbox" class="checkall"></label></div></th>
                                            <th>STATUS</th>
                                            <th>IMAGE</th>
                                            <th>PRODUCT DETAILS</th>
                                            <th>QTY</th>
                                            <th>MRP</th>
                                            <th>SELLING PRICE</th>
                                            <th>KEYWORDS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Content Wrapper-->
        </div>
    </div>
    
    <!-- Mainly scripts -->
    <script src="_assets/js/jquery-2.1.1.min.js"></script>
    <!--Lazy Load JS-->
    <script src="_assets/js/jquery.lazyload.js" type="text/javascript"></script>
    <!--End Lazy Load JS-->
    <script src="_assets/js/bootstrap.min.js"></script>
    <script src="_assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="_assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    
    <!-- Data Tables -->
    <script src="_assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="_assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="_assets/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="_assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
    
    <!-- Peity -->
    <script src="_assets/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="_assets/js/demo/peity-demo.js"></script>
	
    <!-- Custom and plugin javascript -->
    <script src="_assets/js/inspinia.js"></script>
    <script src="_assets/js/plugins/pace/pace.min.js"></script>
    
    <!-- jQuery UI -->
    <script src="_assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- iCheck -->
    <script src="_assets/js/plugins/iCheck/icheck.min.js"></script>
    
    <!-- Toastr (Notification/Alert Message)-->
    <script src="_assets/js/plugins/toastr/toastr.min.js"></script>
    
    
    
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
                        
            $('.checkall').on('ifChecked', function(event){
                $('.check').iCheck('check');
            });
            
            $('.checkall').on('ifUnchecked', function(event){
                $('.check').iCheck('uncheck');
            });
            
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.dataTables-products').dataTable({
                responsive: true,
                columns: [
                    { orderable: false },
                    { orderable: false },
                    { data: 'STATUS' },
                    { data: 'IMAGE', orderable: false },
                    { data: 'PRODUCT DETAILS' },
                    { data: 'QTY' },
                    { data: 'MRP' },
                    { data: 'SELLING PRICE' },
                    { data: 'KEYWORDS' },
                    { data: 'Actions', orderable: false }
                ],
                order: [ 2, 'asc' ],
                dom: 'T<"clear">lfrtip',                
                tableTools: {
                    //"sRowSelect": "os",
                    "aButtons": [ 
                        "copy", 
                        "print", 
                        {
                            "sExtends":    "collection",
                            "sButtonText": "Save",
                            "aButtons":    [ 
                                //{
                                    //"sExtends": "csv",
                                    //"mColumns": [1, 3, 4, 5, 6, 7]
                                //},
                                {
                                    "sExtends": "xls",
                                    "mColumns": [2, 3, 4, 5, 6, 7, 8]
                                },
                                {
                                    "sExtends": "pdf",
                                    "mColumns": [2, 3, 4, 5, 6, 7, 8]
                                },
                            ]
                        }, 
                        //"select_all", 
                        //"select_none" 
                    ],
                    //sRowSelector: 'td:first-child',
                    "sSwfPath": "<?=$adminbasepath;?>_assets/js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                }
            });
            
            var table = $('.dataTables-products').DataTable();
 
            table.on( 'draw', function () {
                $("img.lazy").lazyload({
                    effect : "fadeIn"
                });
            } );
            
            
            $('#loading-example-btn').click(function () {
                btn = $(this);
                simpleLoad(btn, true)
                $('#product-list').load().always(function(){
                    simpleLoad($(this), false)
                });
                // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

                simpleLoad(btn, false)
            });
        });

        function simpleLoad(btn, state) {
            if (state) {
                btn.children().addClass('fa-spin');
                btn.contents().last().replaceWith(" Loading");
            } else {
                setTimeout(function () {
                    btn.children().removeClass('fa-spin');
                    btn.contents().last().replaceWith(" Refresh");
                }, 2000);
            }
        }
        
        $("#bulkActions a").click(function(){
            var chkids = []; 
            var status = $(this).attr('data-status');
            $(".check:checked").each(function(){
                if($(this).parents("tr").is(":visible")){
                    chkids.push("pid='"+$(this).attr("data-pid")+"'");
                }
            });
            console.log(chkids);
            var condition = chkids.join(' OR ');
            
            if(chkids.length>0){
                $.ajax({
                   type: 'POST',
                   url: "<?=$adminbasepath;?>_includes/_modules/ajax-update-product-status.php",
                   data: {status:status, condition:condition},
                   success: function (data, textStatus, jqXHR) {
                        alert(data);
                        console.log(data);
                        console.log(textStatus);
                        console.log(jqXHR);
                        window.location.href=window.location.href;
                    }
                });
            }
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
        
        $(function() {
            $("img.lazy").lazyload({
                effect : "fadeIn"
            });
        });
    </script>
    
    
    
</body>

</html>

