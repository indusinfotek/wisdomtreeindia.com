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

    $redirect_uri = $function->getpageurl(); //echo 'session: '.$_SESSION['admin_id'];
    if(empty($_SESSION['admin_id'])){
        header("Location: login.php?redirect_uri=$redirect_uri");
        //header("Location: login.php");
    }else{
        header("Location: active-orders.php");
    }

?>
