<?php

/**************************************/
/*contac us form functions*/
/**************************************/
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
function get_message($obj){
        return 'From: ' . $obj['name'] . ' ' . $obj['email'] . "\r\n\r\n"
               .'Message: ' . $obj['message'] ;
}



/*
 * Conects to internal data base
 * 
 */
function connect_db(){
    $con = mysqli_connect(DOMAIN,DB_UNAME,DB_PASS,DB_NAME);
    // Check connection
    if (mysqli_connect_errno()){
      return false;
    }
    return $con;
}

/*
 * this function gets all the information needed for contacs page
 * 
 */
function get_contacts_info(){
    $con = connect_db();
    if($con){
        $out = array();
        $q = "SELECT messages.date, messages.message, messages.id,
                     users.name, users.email 
                FROM messages
                JOIN users
                ON messages.email = users.email";
        $res = mysqli_query($con,$q);
        while($row = mysqli_fetch_array($res)){
            array_push($out, $row);
        }
        return $out;
    }else{
        return false;
    }
}

/*
 * this function remove the message whose id is provided.
 * if id is all all the messages are going to be deleted.
 * 
 */
function delete_message($id){
    $con = connect_db();
    if($con){
        $q = $id === "all" ? "DELETE from messages" : "DELETE from messages WHERE id= " . $id ;
        $res = mysqli_query($con,$q);
        return $res;
    }else{
        return false;
    }
}