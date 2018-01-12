<?php
    include '../_settings/constant.php';
    include '../_settings/db.php';
    include 'functions.php';
    
    $function = new FUNCTIONS();
    
    if(isset($_POST) && $_POST['action']=='getProduct'){
        $pid = $_POST['pid'];
        $sku = $_POST['sku'];
        
        global $prod;
        if(!empty($pid)){
            $prod = $function->getProducts($pid, NULL, NULL, NULL, NULL);
        }else{
            $prod = $function->getProducts(NULL, $sku, NULL, NULL, NULL);
        }
        
        print_r(json_encode($prod, JSON_PRETTY_PRINT));
    }
    
?>

