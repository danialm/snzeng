<?php
        session_start();
define("USER_NAME", "admin");
define("PASSWORD", "admin");

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SNZENG | CMS</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/cms/css/global.css" />
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
            if( strip_tags(trim($_POST['username'])) == USER_NAME && strip_tags(trim($_POST['password'])) == PASSWORD ){
                    $access = true;
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['access'] = true;
                    $_SESSION['start_time'] = time();
            }else{
                $error = "ACCESS DENIED!";
            }
        }
        if(isset($_GET['cmd'])){
            $cmd = strip_tags(trim($_GET['cmd']));
            if($cmd === 'projects' ){
                $pg = 'projects';
            }else if($cmd === 'store-info' ){
                $pg = 'store-info';
            }
        }else{
            $pg = 'home';
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
                <form id="sign-in" method="post" action="">
                    <p>Please sign in to continue:<span style="color:red; float:right"><?= $error ?></span></p>
                    <input type="hidden" name="sign-in" value="true" />
                    <input type="text" name="username" placeholder="User Name:" />
                    <input type="password" name="password" placeholder="Password:" />
                    <input type="submit" name="submit"  value="SUBMIT" />
                </form>
            <?php }else{ ?>
            <p>Hi <?= $_SESSION['username']?>,</p>
                <?php if($pg === 'home'){ ?>
                    <div class="span12 nav-container">
                        <div class="span6">
                            <div class="nav">
                                <a href="?cmd=projects" class="span12"><span class="fa fa-folder-o fa-4x"></span></a>  
                                <a href="?cmd=projects" class="span12">Projects</a>  
                            </div>
                        </div>
                        <div class="span6">
                            <div class="nav">
                                <a href="?cmd=store-info" class="span12"><span class="fa fa-briefcase fa-4x"></span></a>  
                                <a href="?cmd=store-info" class="span12">Office info</a>  
                            </div>
                        </div>
                    </div>
                <?php }else if($pg === 'projects'){ ?>
                        projects
                <?php }else if($pg === 'store-info'){ ?>
                        office
                <?php }?>
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
