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
                        'name'      =>  strip_tags(htmlspecialchars($_GET['name'])),
                        'email'     =>  strip_tags(htmlspecialchars($_GET['email'])),
                        'message'   =>  strip_tags(htmlspecialchars($_GET['message']))
                    );
        if(filter_var($user['email'], FILTER_VALIDATE_EMAIL)){
            $result =   send_email(EMAIL, '[SNZENG] Contac Us', get_message($user), $user['email'] ) && 
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
    if($_POST['inq'] === 'users'){
        $users_info = get_users_info();
        echo json_encode($users_info);
    }
    if($_POST['inq'] === 'delUsr'){
        $res = delete_user($_POST['id']);
        echo $res;
    }
    if($_POST['inq'] === 'editUsr'){
        $res = edit_user($_POST['user']);
        echo $res;
    }
    if($_POST['inq'] === 'office'){
        $res = get_office_info();
        echo json_encode($res);
    }
    if($_POST['inq'] === 'editOfficeInfo'){
        $res = edit_office_info($_POST['pair']);
        echo $res;
    }
    if($_POST['inq'] === 'projects'){
        $res = get_projects_info();
        echo json_encode($res);
    }
}
if(isset($_FILES) && count($_FILES)>0){
    foreach($_FILES as $des => $file){
        $res = save_file($des, $file);
        echo $res;
    }
}