<?php

include 'functions.php';

/*
 * Contact Us page form
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

if(isset($_POST['inq'])){
    if($_POST['inq'] === 'contacts'){
        $contacts_info = get_contacts_info();
        echo json_encode($contacts_info);
    }

    if($_POST['inq'] === 'delMsg'){
        $res = delete_message($_POST['id']);
        echo $res;
    }
}