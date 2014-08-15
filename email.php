<?php
define('TEST_ENV_EMAIL', 'gdanialq@gmail.com');
define('DOMAIN', 'localhost');
define('EMAIL', defined('TEST_ENV_EMAIL')? TEST_ENV_EMAIL : 'shawnk@snzeng.com');
define('DB_NAME', 'snzeng');
define('DB_UNAME', 'data');
define('DB_PASS', 'D231564d');
define('THANK_YOU', 'Dear Custommr,'. "\r\n\r\n" .
                    'We at S$Z Engineering do appretiate your contact. We will get back to you as soon as possible.'. "\r\n\r\n" .
                    'Best regards,'. "\r\n" .
                    'SNZENG'. "\r\n" .
                     EMAIL);
/*
 * COontact Us page form
 * 
 */
if($_GET['from']){
    $thank_you = THANK_YOU;
    $result = false;
    if(isset($_GET['name']) && trim($_GET['name']) !== '' && isset($_GET['email']) && trim($_GET['email']) !== '' && isset($_GET['message']) && trim($_GET['message']) !== '' ){
        $user = array(
                        'name'      =>  $_GET['name'],
                        'email'     =>  $_GET['email'],
                        'message'   =>  $_GET['message']
                    );
        if(filter_var($user['email'], FILTER_VALIDATE_EMAIL)){
            $result =   send_email(EMAIL, '[SNZENG]contac_us', get_message($user), $user['email'] ) && 
                        send_email($user['email'], '[SNZENG] Thank You!', $thank_you, EMAIL) && 
                        save_message($user['email'], $user['message']) && 
                        save_user($user['email'], $user['name']);
        }
    }
    echo $result;
}

function send_email($to, $sub, $msg, $from){    
    $subject = $sub;
    $message = wordwrap($msg, 70);
    return mail($to , $subject, $message, "From: $from\n");
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
function get_message($obj){
        return 'From: ' . $obj['name'] . ' ' . $obj['email'] . "\r\n\r\n"
               .'Message: ' . $obj['message'] ;
}