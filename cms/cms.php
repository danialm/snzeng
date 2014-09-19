<?php
session_start();
define("USER_NAME", "admin");
define("PASSWORD", "admin");
include 'functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SNZENG | CMS</title>
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <META HTTP-EQUIV="Expires" CONTENT="-1">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/cms/css/global.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="/cms/js/global.js"></script>
    </head>
    <body>
        <?php;
        $error = "";
        $timeout = 1800;
        $access = isset($_SESSION['access']) ? $_SESSION['access'] : false;
        if(isset($_SESSION['start_time'])){ 
            if( $_SESSION['start_time'] + $timeout < time() ){
                $error = "TIME OUT!";
                $access = false;
            }else{
                $_SESSION['start_time'] = time();
            }
        }
        if(isset($_POST['sign-in'])){
            $usrn = strip_tags(trim($_POST['username']));
            $psw = strip_tags(trim($_POST['password']));
            if( $display_name = sign_in($usrn, $psw) ){
                    $access = true;
                    $_SESSION['username'] = $display_name;
                    $_SESSION['access'] = true;
                    $_SESSION['start_time'] = time();
            }else{
                $error = "ACCESS DENIED!";
            }
        }
        if(isset($_GET['cmd'])){
            $cmd = strip_tags(trim($_GET['cmd']));
            if($cmd === 'logout' ){
                session_destroy();
                $access = false;
            }else if($cmd === 'projects' ){
                $pg['name'] = 'Projects List';
            }else if($cmd === 'store-info' ){
                $pg['name'] = 'Office Info';
            }else if($cmd === 'contacts-manager' ){
                $pg['name'] = 'Contacts';
            }else if($cmd === 'job' ){
                $pg['name'] = 'Jobs';
            }else if($cmd === 'users' ){
                $pg['name'] = 'Users';
            }
        }else{
            $pg['name'] = 'Home';
        }
        ?>
        <div class="header">
            <div class="inner">
                <h1>haveawebsite.net</h1>
            </div>
        </div>
        <div class="main">
            <div class="inner">
            <?php if(!$access){?>
                <form id="sign-in" method="post" action="/cms.php">
                    <p>Please sign in to continue:<span class="right error"><?= $error ?></span></p>
                    <input type="hidden" name="sign-in" value="true" />
                    <input type="text" name="username" placeholder="User Name:" />
                    <input type="password" name="password" placeholder="Password:" />
                    <input type="submit" name="submit"  value="SUBMIT" />
                </form>
            <?php }else{ ?>
            <p class="right"><a href="?cmd=logout">Log Out <span class="fa fa-sign-out fa-lg"></span></a></p>
            <p>Hi <?= $_SESSION['username']?>,</p>
                <?php if($pg['name'] === 'Home'){
                        include 'pages/home.php';
                     }else{ ?>
                    <p class="breadcrumb"><a href="?"><span class="fa fa-home fa-lg"></span></a> &gt&gt <span><?= $pg['name'] ?></span></p>
                    <?php if($pg['name'] === 'Projects List'){
                            include 'pages/projects.php';
                        }else if($pg['name'] === 'Office Info'){
                            include 'pages/office.php';
                        }else if($pg['name'] === 'Contacts'){
                            include 'pages/contacts.php';
                        }else if($pg['name'] === 'Jobs'){
                            include 'pages/job.php';
                        }else if($pg['name'] === 'Users'){
                            include 'pages/users.php';
                        }?>
                <?php } ?>
            <?php } ?>
            </div>
        </div>
        <div class="footer">
            <div class="inner">
                <p><a href="http://www.haveawebsite.net">Have a website today.</a></p>
            </div>
        </div>
    </body>
</html>
