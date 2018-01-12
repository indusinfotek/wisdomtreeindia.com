<?php
    ob_start();
    error_reporting(E_ALL ^ E_NOTICE);
    @session_start();
    ini_set('allow_url_include', 1);
    date_default_timezone_set("Asia/Kolkata");
    set_time_limit(600);
    ini_set('max_execution_time',600); 
    
    include '../_settings/constant.php';
    include '../_settings/db.php';
    include 'functions.php';
    
    $function = new FUNCTIONS();
    
    if(isset($_POST) && $_POST['action']='setOrderStatus'){
        $status = $_POST['status'];
        $orderid = $_POST['orderid'];
        $userid = $_POST['userid'];
        
        $condition = $_POST['condition'];
          
        $sql = "UPDATE `orders` SET status='$status' WHERE orderid='$orderid' AND userid='$userid'";
        //echo $sql;
        $db = new db();
        $db->connect();
        $dbname = MAINDB;
        $db->select_db($dbname);
        
        $result = $db->query($sql);
        
        if($result==1){
            echo "Status updated successfully.";
        }else{
            echo "Failed to update status.";
        }
    }
?>
