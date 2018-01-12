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
    
    $redirect_uri = (!empty($_GET['redirect_uri']))?$_GET['redirect_uri']:$adminbasepath.'single-listing.php';
    
    if(isset($_POST['btnLogin'])){
        $username = (!empty($_POST['username']))?$_POST['username']:'';
        $password = (!empty($_POST['password']))?$_POST['password']:'';
        
        if(empty($username) || empty($password)){
            if(empty($username)){
                echo "<script>alert('Email can\'t be blank.');$('#username').focus();</script>";
            }else{
                echo "<script>alert('Password can\'t be blank.');$('#password').focus();</script>";
            }            
        }else{        
            $adminuser = $function->getAdminUser(NULL, $username, NULL, $password, 1);
            //print_r($adminuser);
            if(!empty($adminuser['count']) && $adminuser['isactive'][0]=='1'){
                $_SESSION['admin_id'] = $adminuser['id'][0];
                $_SESSION['admin_email'] = $adminuser['email'][0];

                header("Location: $redirect_uri");
            }else{
                echo "<script>alert('Sorry! Invalid login credentials. Please try again later.');$('#username').focus();</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>XIOSHOP | Admin Login</title>

    <link href="_assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="_assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="_assets/css/animate.css" rel="stylesheet">
    <link href="_assets/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center m-b-sm">
                <a href="#">
                    <img src="_assets/img/xioshop-logo-color-min.png">
                </a>
            </div>
            <div class="col-md-6 col-md-offset-3">
                <div class="ibox-content">
                    <form class="m-t" role="form" action="" method="POST">
                        <div class="form-group">
                            <input id="username" name="username" type="text" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button id="btnLogin" name="btnLogin" type="submit" class="btn btn-info block full-width m-b">Login</button>

                        <a href="#">
                            <small>Forgot password?</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <small>&nbsp;</small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                © 2015-2016 XioShop (www.xioshop.com). All rights reserved.
            </div>
            <div class="col-md-6 text-right">
                <small>Developed &amp; Managed by <a href="http://www.abworks.net" target="_blank">AB Works</a></small>
            </div>
        </div>
    </div>

</body>
</html>
