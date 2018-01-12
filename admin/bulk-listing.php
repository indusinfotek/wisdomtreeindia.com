<?php
    ini_set("display_errors",1);
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
    require_once '_includes/_modules/excel_reader.php';
    
    $function = new FUNCTIONS();
    
    $redirect_uri = $function->getpageurl(); //echo 'session: '.$_SESSION['admin_id'];
    if(empty($_SESSION['admin_id'])){
        header("Location: login.php?redirect_uri=$redirect_uri");
        //header("Location: login.php");
    }
    
    global $isUpdated;
    
    if(isset($_POST['btnUploadFile'])){
        $action = $_POST['action'];
        $new_file_name = strtoupper(uniqid('FILE',false));
        //echo "FILE NAME: ".$_FILES["file"]["name"];
        if($_FILES["file"]["name"]){
            $foldername = "_uploads/files/";            
            $file = $function->uploadExcelFile($new_file_name,$foldername, 'file');
        }
        //print_r($file);
        
        $data = new Spreadsheet_Excel_Reader($foldername.$file[2]);
        
        for($i=0;$i<count($data->sheets);$i++){
            if(count($data->sheets[$i][cells])>0){
                for($j=2;$j<=count(@$data->sheets[$i][cells]);$j++){
                    $pid = ($action==0)?strtoupper(uniqid('PID',false)):(@$data->sheets[$i][cells][$j][1]);
                    $sku = (strtoupper(@$data->sheets[$i][cells][$j][2]));
                    $category = (@$data->sheets[$i][cells][$j][3]);
                    $title = (strtoupper(@$data->sheets[$i][cells][$j][4]));
                    $title_hin = (@$data->sheets[$i][cells][$j][5]);
                    $keywords = (strtolower(@$data->sheets[$i][cells][$j][6]));
                    $description = (@$data->sheets[$i][cells][$j][7]);
                    $product_weight = (json_encode(explode(",", str_replace(" ", "", @$data->sheets[$i][cells][$j][8]))));
                    $mrp = (json_encode(explode(",", str_replace(" ", "", @$data->sheets[$i][cells][$j][9]))));
                    $sellingprice = (json_encode(explode(",", str_replace(" ", "", @$data->sheets[$i][cells][$j][10]))));
                    $image = ($action==0)?$pid.".jpg":(@$data->sheets[$i][cells][$j][11]);
                    $isactive = (@$data->sheets[$i][cells][$j][12]);
                    
                    $ipaddress = $function->getClientIP();
                    
                    if($action==0){                    
                        $sql = "INSERT INTO `product` (pid, sku, category, title, title_hin, tags, description, product_weight, mrp, selling_price, image, isactive, ipaddress) "
                                . "VALUES ('$pid', '$sku', '$category', '$title', '$title_hin', '$keywords', '$description', '$product_weight', '$mrp', '$sellingprice', '$image', '$isactive', '$ipaddress')";

                        
                        
                    }elseif($action==1){
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
                            . "image = '$image', "
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
                        $isUpdated = 1;
                    }else {
                        $isUpdated = 2;
                    }
                }
            }
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
                            <strong>BULK LISTING</strong>
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
                        <h5>Browse your product excel to upload the data.<span class="text-right" style="float:right;"><a href="_uploads/BULK_UPLOAD_SAMPLE.xls" target="_blank"><button class="btn btn-xs btn-warning"><i class="fa fa-download"></i> SAMPLE FILE</button></a></span></h5>
                        <div class="ibox-content">
                            <form method="POST" action="" role="form" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">ACTION</label>
                                    <div class="col-sm-10">
                                        <div class="i-checks">
                                            <label> 
                                                <input type="radio" checked="" value="0" name="action" /> 
                                                <i></i>&nbsp;&nbsp;New Listing 
                                            </label>
                                        </div>
                                        <div class="i-checks">
                                            <label> 
                                                <input type="radio" checked="" value="1" name="action" /> 
                                                <i></i>&nbsp;&nbsp;Update Listing 
                                            </label>
                                        </div>
                                    </div> 
                                </div>
                                
                                <div class="hr-line-dashed"></div>  
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">BROWSE EXCEL</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="file" class="btn btn-white"/>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>                                
                                
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button id="btnUploadFile" name="btnUploadFile" class="btn btn-primary" type="submit">Upload File</button>
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
                toastr.success('Product added/updated successfully.', 'SUCCESS'); 
                window.location.href=window.location.href;
        <?php }elseif($isUpdated==2){$isUpdated=0; ?>
                window.location.href=window.location.href;
                toastr.error('Sorry! Due to some technical issues we are unable to process your request. Please try again after some time.', 'ERROR');
        <?php } ?>
        // END IF UPDATED
    </script>
    
    
</body>

</html>
