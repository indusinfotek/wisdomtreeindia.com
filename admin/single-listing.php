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
    
    $redirect_uri = $function->getpageurl(); //echo 'session: '.$_SESSION['admin_id'];
    if(empty($_SESSION['admin_id'])){
        header("Location: login.php?redirect_uri=$redirect_uri");
        //header("Location: login.php");
    }
    
    $pcat = $function->getcategory(0,NULL,NULL,1);
    
    global $isUpdated, $isedit;
    if(isset($_POST['btnAddProduct'])){
        $pid = strtoupper(uniqid('PID',false));
        $sku = !empty($_POST['sku'])?strtoupper($_POST['sku']):'';
        $category = !empty($_POST['category'])?$_POST['category']:'';
        $title = !empty($_POST['title'])?strtoupper($_POST['title']):'';
        $title_hin = !empty($_POST['title_hin'])?$_POST['title_hin']:'';        
        $product_weight = !empty($_POST['product_weight'])?json_encode(explode(",", str_replace(" ", "", $_POST['product_weight']))):'';        
        $mrp = !empty($_POST['mrp'])?json_encode(explode(",", str_replace(" ", "", $_POST['mrp']))):'';
        $sellingprice = !empty($_POST['sellingprice'])?json_encode(explode(",", str_replace(" ", "", $_POST['sellingprice']))):'';
        $keywords = !empty($_POST['keywords'])?strtolower($_POST['keywords']):'';
        $description = !empty($_POST['description'])?mysql_real_escape_string($_POST['description']):'';
        $ipaddress = $function->getClientIP();
        $isactive = (!empty($_POST['isactive']))?1:0;
        //Uploading Product Image and retrieving path
        if($_FILES["product_main_image"]["name"]){
            $foldername = "_uploads/products/";            
            $product_main_image = $function->uploadFile($pid,$pid,$foldername, 'product_main_image');
        }
        
        $sql = "INSERT INTO `product` (pid, sku, category, title, title_hin, tags, description, product_weight, mrp, selling_price, image, isactive, ipaddress) "
                . "VALUES ('$pid', '$sku', '$category', '$title', '$title_hin', '$keywords', '$description', '$product_weight', '$mrp', '$sellingprice', '$product_main_image[2]', '$isactive', '$ipaddress')";
        
        //echo $sql;
        $db = new db();
        $db->connect();
        $dbname = MAINDB;
        $db->select_db($dbname);
        
        $result = $db->query($sql);
        
        if(!empty($result)){
            $isUpdated=1;
        }else{
            $isUpdated=2;
        }
    }
    
    
    if(($_GET['action']=='edit') && !empty($_GET['pid'])){
        $pid = $_GET['pid'];
        $prod = $function->getProducts($pid);
        $isedit = 1;
    }
    
    if(isset($_POST['btnUpdate'])){
        $pid = $_GET['pid'];
        $sku = !empty($_POST['sku'])?strtoupper($_POST['sku']):'';
        $category = !empty($_POST['category'])?$_POST['category']:'';
        $title = !empty($_POST['title'])?strtoupper($_POST['title']):'';
        $title_hin = !empty($_POST['title_hin'])?$_POST['title_hin']:'';        
        $product_weight = !empty($_POST['product_weight'])?json_encode(explode(",", str_replace(" ", "", $_POST['product_weight']))):'';        
        $mrp = !empty($_POST['mrp'])?json_encode(explode(",", str_replace(" ", "", $_POST['mrp']))):'';
        $sellingprice = !empty($_POST['sellingprice'])?json_encode(explode(",", str_replace(" ", "", $_POST['sellingprice']))):'';
        $keywords = !empty($_POST['keywords'])?strtolower($_POST['keywords']):'';
        $description = !empty($_POST['description'])?$_POST['description']:'';
        $isactive = (!empty($_POST['isactive']))?1:0;
        $ipaddress = $function->getClientIP();
        //Uploading Product Image and retrieving path
        if($_FILES["product_main_image"]["name"]){
            $foldername = "_uploads/products/";            
            $product_main_image = $function->uploadFile($pid,$pid,$foldername, 'product_main_image');
            
            $sql = "UPDATE `product` SET "
                . "sku = '$sku', "
                . "category = '$category', "
                . "title = '$title', "
                . "title_hin = '$title_hin', "
                . "tags = '$keywords', "
                . "description = '$description', "
                . "product_weight = '$product_weight', "
                . "mrp = '$mrp', "
                . "selling_price = '$sellingprice', "
                . "image = '$product_main_image[2]', "
                . "isactive = '$isactive', "
                . "ipaddress = '$ipaddress' WHERE "
                . "pid = '$pid'";
        }else{
            $sql = "UPDATE `product` SET "
                . "sku = '$sku', "
                . "category = '$category', "
                . "title = '$title', "
                . "title_hin = '$title_hin', "
                . "tags = '$keywords', "
                . "description = '$description', "
                . "product_weight = '$product_weight', "
                . "mrp = '$mrp', "
                . "selling_price = '$sellingprice', "
                . "isactive = '$isactive', "
                . "ipaddress = '$ipaddress' WHERE "
                . "pid = '$pid'";
        }
        
        //echo $sql;
        $db = new db();
        $db->connect();
        $dbname = MAINDB;
        $db->select_db($dbname);
        
        $result = $db->query($sql);
        
        if(!empty($result)){
            $isUpdated=1;
        }else{
            $isUpdated=2;
        }
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
                            <strong>SINGLE LISTING</strong>
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
                        <h5>Fill in all the required details to list your product.</h5>
                        <div class="ibox-content">
                            <form method="POST" action="" role="form" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product SKU</label>
                                    <div class="col-sm-4">
                                        <input id="sku" name="sku" type="text" class="form-control text-uppercase" value="<?=($isedit==1)?$prod['sku'][0]:''?>" />
                                    </div>
                                    
                                    <label class="col-sm-2 control-label">Product Category</label>
                                    <div class="col-sm-4">                                        
                                        <select class="form-control m-b" name="category" id="category">
                                            <option value="0">Select Main Category</option>
                                            <?php
                                                if(!empty($pcat['count'])){
                                                    for($i=0;$i<$pcat['count'];$i++){
                                            ?>
                                            <option value="<?=$pcat['id'][$i];?>" disabled="disabled"><?=$pcat['name'][$i];?></option>
                                                <?php
                                                    $cat = $function->getcategory($pcat['id'][$i],NULL,NULL,1);
                                                    if(!empty($cat['count'])){
                                                        for($j=0;$j<$cat['count'];$j++){
                                                ?>
                                            <option value="<?=$cat['id'][$j];?>" <?=($isedit==1 && $cat['id'][$j]==$prod['category'][0])?'selected':''?>>&nbsp;&nbsp;&mdash;&nbsp;<?=$cat['name'][$j];?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>  
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product Title [English]</label>
                                    <div class="col-sm-4">
                                        <input id="title" name="title" type="text" class="form-control text-uppercase" value="<?=($isedit==1)?$prod['title'][0]:''?>" />
                                    </div>
                                    <label class="col-sm-2 control-label">Product Title [Hindi]</label>
                                    <div class="col-sm-4">
                                        <input id="title_hin" name="title_hin" type="text" class="form-control" value="<?=($isedit==1)?$prod['title_hin'][0]:''?>"/>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product Weight</label>
                                    <div class="col-sm-10 tagsinput">                                        
                                        <input id="product_weight" name="product_weight" type="text" class="form-control" value="<?=($isedit==1)?implode(', ', json_decode($prod['product_weight'][0])):''?>" />
                                        <small>For multiple {0.25, 0.5, 1, 1.5, 2, 2.5, 5, 10}</small>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label tagsinput">MRP (Rs.)</label>
                                    <div class="col-sm-10">
                                        <input id="mrp" name="mrp" type="text" class="form-control" value="<?=($isedit==1)?implode(', ', json_decode($prod['mrp'][0])):''?>" />
                                        <small>For multiple {0.25, 0.5, 1, 1.5, 2, 2.5, 5, 10}</small>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">                                    
                                    <label class="col-sm-2 control-label tagsinput">Selling Price (Rs.)</label>
                                    <div class="col-sm-10">
                                        <input id="sellingprice" name="sellingprice" type="text" class="form-control" value="<?=($isedit==1)?implode(', ', json_decode($prod['selling_price'][0])):''?>" />
                                        <small>For multiple {0.25, 0.5, 1, 1.5, 2, 2.5, 5, 10}</small>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div> 
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keywords</label>
                                    <div class="col-sm-10 tagsinput">
                                        <input placeholder="Enter keywords seperated with comma" id="keywords" name="keywords" type="text" class="form-control lowercase" data-role="tagsinput" style="width: 100%!important;" value="<?=($isedit==1)?$prod['tags'][0]:''?>" />
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product Description</label>
                                    <div class="col-sm-10">
                                        <textarea id="description" name="description" class="form-control" rows="6" cols="10"><?=($isedit==1)?$prod['description'][0]:''?></textarea>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product Image</label>
                                    <div class="btn-group1">
                                        <div class="col-sm-3" style="float: left; text-align: center;">
                                            <img src="<?=($isedit==1)?"_uploads/products/".$prod['image'][0]:"_assets/img/repeat-gray-bg.jpg"?>" name="img-product_main_image" id="img-product_main_image" class="col-sm-12" style="margin: 15px 0;"/>
                                            <label title="Upload image file" for="product_main_image" class="btn btn-danger col-sm-12">
                                                <input type="file" accept="image/*" name="product_main_image" id="product_main_image" class="hide file-img-changer">
                                                Upload main image
                                            </label> 
                                        </div>
                                    </div>
                                </div>                                
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">ISACTIVE</label>
                                    <div class="col-sm-4">
                                        <input id="isactive" name="isactive" type="checkbox" class="js-switch" <?=($isedit==1 && $prod['isactive'][0]=='0')?'':'checked';?> />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <?php if($isedit==1){ ?>
                                        <button id="btnUpdate" name="btnUpdate" class="btn btn-primary" type="submit">Update Details</button>
                                        <?php }else{ ?>
                                        <button id="btnAddProduct" name="btnAddProduct" class="btn btn-primary" type="submit">Add Product</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
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
        $(document).ready(function(){
            var elem = document.querySelector('#isactive.js-switch');
            var switchery = new Switchery(elem, { color: '#1AB394' });            
        });
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
        // Prepare the preview for profile picture
        $(".file-img-changer").change(function(){
            readURL(this);
        });
        
        //Function to show image before upload
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img-'+input.id).attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        //If UPDATED
        <?php 
            if($isUpdated==1){$isUpdated=0;
        ?>
                toastr.success('Product added successfully.', 'SUCCESS'); 
                window.location.href=window.location.href;
        <?php }elseif($isUpdated==2){$isUpdated=0; ?>
                window.location.href=window.location.href;
                toastr.error('Sorry! Due to some technical issues we are unable to process your request. Please try again after some time.', 'ERROR');
        <?php } ?>
        // END IF UPDATED
    </script>
    
    
</body>

</html>
