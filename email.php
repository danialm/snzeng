<?php
define('TEST_ENV_EMAIL', 'gdanialq@gmail.com');
define('DOMAIN', 'localhost');
define('EMAIL', defined('TEST_ENV_EMAIL')? TEST_ENV_EMAIL : 'shawnk@snzeng.com');
define('DB_NAME', 'snzeng');
define('DB_UNAME', 'data');
define('DB_PASS', 'D231564d');
/*
 * COontact Us page form
 * 
 */
if($_GET['from']){
    $e_flag = $m_flag = $u_flag = false;
    if(isset($_GET['name']) && trim($_GET['name']) !== '' && isset($_GET['email']) && trim($_GET['email']) !== '' && isset($_GET['message']) && trim($_GET['message']) !== '' ){
        $user = array(
                        'name'      =>  $_GET['name'],
                        'email'     =>  $_GET['email'],
                        'message'   =>  $_GET['message']
                    );
        $e_flag = send_email($user, '[SNZENG]contac_us');
        $m_flag = save_message($user['email'], $user['message']);
        $u_flag = save_user($user['email'], $user['name']);
    }
    print_r($m_flag && $e_flag && u_flag);
}

function send_email($obj, $sub){    
    $subject = $sub;
    $message = 'From: ' . $obj['name'] . ' ' . $obj['email'] . "\r\n\r\n"
               .'Message: ' . $obj['message'] ;
    $message = wordwrap($message, 70);
    
    return mail(EMAIL , $subject, $message);
}
function save_message($email, $message=''){
    $con = connect_db();
    if($con){
        $q = "INSERT INTO messages (email, message)
                              VALUES ('" . $email . "', '" . $message . "')";
        $res = mysqli_query($con,$q);
        if($res){
            return true;
        }
    }
    return false;
}
function save_user($email, $name, $pass=''){
    $con = connect_db();
    if($con){
        $q = "SELECT * FROM users where email ='" . $email . "'";
        $res = mysqli_query($con,$q);
        $row = mysqli_fetch_array($res);
        if($row === null){
            $q = "INSERT INTO users (email, name, password)
                              VALUES ('" . $email . "', '" . $name . "', '" . $pass . "')";
            $res = mysqli_query($con,$q);
            if($res){
                return true;
            }
        }
        return true;
    }
    return false;
}
function connect_db(){
    $con = mysqli_connect(DOMAIN,DB_UNAME,DB_PASS,DB_NAME);
    // Check connection
    if (mysqli_connect_errno()){
        //var_dump(mysqli_connect_error());
      return false;
    }
    return $con;
}
