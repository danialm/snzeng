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

if(isset($_GET['markers'])&&$_GET['markers']==='true'){
        $res = get_projects_info(true);
        echo json_encode($res); 
}
if(isset($_GET['projects'])&&$_GET['projects']=='true'){
        $res = get_projects_info();
        echo json_encode($res);    
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
    if($_POST['inq'] === 'addPrj'){
        $res = add_project($_POST['prj']);
        echo $res;
    }
    if($_POST['inq'] === 'addMarkerPrj'){
        $res = add_marker_project($_POST['prj']);
        echo $res;
    }
    if($_POST['inq'] === 'delPrj'){
        $res = delete_project($_POST['id']);
        echo $res;
    }
    if($_POST['inq'] === 'delAllMarkerPrj'){
        $res = delete_marker_projects();
        echo $res;
    }
    if($_POST['inq'] === 'getNewProjects'){
        $res = get_new_projects($_POST['ids']);
        echo json_encode($res);
    }
}
if(isset($_GET['inq'])){
    $res1 = true;
    $res2 = true;
    if(isset($_FILES) && count($_FILES)>0){
        foreach($_FILES as $des => $file){
            $res2 = save_file($des, $file);
            //echo $res2;
            if($res2 !== true){
                echo $res2;
                break;
            }
        }
    }
    if($_GET['inq'] === 'editPrj'){
        $res1 = edit_project($_POST);
        if($res1 !== true){
            echo $res1;
        }
    }
    if($res1 === true && $res2 === true)
        echo true;
}