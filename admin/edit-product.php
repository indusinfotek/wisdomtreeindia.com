<?php
    include '_includes/_settings/constant.php';
    include '_includes/_settings/db.php';
    include '_includes/_modules/functions.php';
    
    $function = new FUNCTIONS();
    $pcat = $function->getcategory(0,NULL,NULL,1);
    
    global $isUpdated;
    
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
                    <h2>PRODUCT MANAGEMENT</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">PRODUCT MANAGEMENT</a>
                        </li>
                        <li>
                            <a>LISTING</a>
                        </li>
                        <li class="active">
                            <strong>EDIT PRODUCT</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <!--End Breadcrumb-->
            
            <!--Content Wrapper-->
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox-heading">
                            <div class="ibox-title">SEARCH PRODUCT</div>
                        </div>
                        <div class="ibox-content">
                            <form method="POST" action="" role="form" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Search by PID</label>
                                    <div class="col-sm-4">
                                        <input id="search_pid" name="search_pid" type="text" class="form-control text-uppercase" />
                                    </div> 
                                    
                                    <label class="col-sm-2 control-label">Search by SKU</label>
                                    <div class="col-sm-4">
                                        <input id="search_sku" name="search_sku" type="text" class="form-control text-uppercase" />
                                    </div> 
                                </div>
                                                                
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button id="btnSearch" name="btnSearch" class="btn btn-primary" type="button">SEARCH PRODUCT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="col-lg-12 m-t-lg">
                        <div class="ibox-heading">
                            <div class="ibox-title">SEARCH RESULT</div>
                        </div>
                        <div class="ibox-content">
                            <div class="row search-data hidden">
                                <div class="col-sm-12">
                                <div class="col-sm-3">
                                    <img class="prod-image" src="" style="width: 100%;" />
                                </div>
                                <div class="col-sm-4">
                                    <div>
                                        <h3 class="prod-title">Product Title [ <span class="prod-title_hin">हिंदी नाम</span> ]</h3>
                                        <div><small>PID: <span class="prod-pid">PID000000001</span></small> | <small>SKU: <span class="prod-sku">SKU000000001</span></small></div>
                                        <div class="m-t-md">
                                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                                <thead>
                                                    <tr>
                                                        <th>WEIGHT</th>
                                                        <th>MRP</th>
                                                        <th>SELLING PRICE</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="prod-details">
                                                    <tr>
                                                        <td>250gms.</td>
                                                        <td>12</td>
                                                        <td>10</td>
                                                    </tr>
                                                    <tr>
                                                        <td>500gms.</td>
                                                        <td>24</td>
                                                        <td>20</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div>TAGS: <span class="prod-tags"></span></div>
                                    <div>CATEGORY: <span class="prod-category"></span></div>
                                    <div>DESCRIPTION: <span class="prod-desc"></span></div>
                                    <div class="m-t-lg">
                                        <a href="#" class="btn btn-sm btn-primary btnEdit" target="_blank">EDIT DETAILS</a>
                                    </div>
                                </div>
                            </div>
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
    <script src="_assets/js/bootstrap.min.js"></script>
    <script src="_assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="_assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    
    <!-- Switchery -->
   <script src="_assets/js/plugins/switchery/switchery.js"></script>
    
    <!--Tags Input-->
    <script src="_assets/js/bootstrap-tagsinput.js"></script>
    
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
    
    <!--Google Transliteration-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Google Transliterate API
      google.load("elements", "1", {
            packages: "transliteration"
          });

      function onLoad() {
        var options = {
            sourceLanguage:
                google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage:
                [google.elements.transliteration.LanguageCode.HINDI],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        // Create an instance on TransliterationControl with the required
        // options.
        var control =
            new google.elements.transliteration.TransliterationControl(options);

        // Enable transliteration in the textbox with id
        // 'transliterateTextarea'.
        control.makeTransliteratable(['title_hin']);
      }
      google.setOnLoadCallback(onLoad);
    </script>
    <!--End Google Transliteration-->
    
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
        
        
        $("#btnSearch").click(function(){
           var pid = $("#search_pid").val();
           var sku = $("#search_sku").val();
           var prod = "";
           if(pid!=="" || sku!==""){
                $.ajax({
                    url: "<?=$adminbasepath;?>_includes/_modules/masterajax.php", 
                    type: "POST", //can be post or get
                    data: {pid: pid, sku: sku, action: 'getProduct'}, 
                    success: function(data){                        
                        prod = JSON.parse(data);
                        console.log(prod);
                        $(".search-data .prod-title").html(prod.title+" [ "+prod.title_hin+" ]");
                        $(".search-data .prod-pid").html(prod.pid);
                        $(".search-data .prod-sku").html(prod.sku);
                        $(".search-data .prod-image").attr("src", "<?=$adminbasepath;?>_uploads/products/"+prod.image);
                        
                        product_weight = JSON.parse(prod.product_weight);
                        product_mrp = JSON.parse(prod.mrp);
                        product_selling_price = JSON.parse(prod.selling_price);
                        
                        var len = Object.keys(product_weight).length;
                        var d = "";
                        for(i=0;i<len;i++){
                            pw = product_weight[i]
                            //if(product_weight[i]<1){
                                //pw = product_weight[i]*1000+" Gms.";
                            //}else{
                                //pw = product_weight[i]+" Kg.";
                            //}
                            d += "<tr><td>"+pw+"</td><td>"+product_mrp[i]+"</td><td>"+product_selling_price[i]+"</td></tr>";
                        }
                        $(".search-data .prod-details").html(d);
                        
                        $(".search-data .prod-tags").html(prod.tags);
                        $(".search-data .prod-category").html(prod.category);
                        $(".search-data .prod-description").html(prod.description);
                        
                        $(".search-data a.btnEdit").attr("href", "single-listing.php?action=edit&pid="+prod.pid);
                        
                        $(".search-data").removeClass('hidden');
                    }
                });
            }else{
                alert('Please enter PID or SKU to search');
                $("#search_pid").focus();
            }
        });
    </script>
    
    
</body>

</html>
