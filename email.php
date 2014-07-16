<?php
define('SNZENG_EMAIL', 'shawnk@snzeng.com');
/*
 * COontact Us page form
 * 
 */
if($_GET['from']){
    $out = false;
    if(isset($_GET['name']) && trim($_GET['name']) !== '' && isset($_GET['email']) && trim($_GET['email']) !== '' && isset($_GET['message']) && trim($_GET['message']) !== '' ){
        $user = array(
                        'name'      =>  $_GET['name'],
                        'email'     =>  $_GET['email'],
                        'message'   =>  $_GET['message']
                    );
        $out = send_email($user, '[SNZENG]contac_us');
        save_to_db($user, 'users');
    }
    print_r($out);
}

function send_email($obj, $sub){    
    $subject = $sub;
    $message = 'From: ' . $obj['name'] . ' ' . $obj['email'] . "\r\n\r\n"
               .'Message: ' . $obj['message'] ;
    $message = wordwrap($message, 70);
    
    return mail(SNZENG_EMAIL , $subject, $message);
}
function save_to_db($obj , $tbl){
    return true;
}

