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
 * checking the username and password for signing in
 * 
 */
function sign_in($usrn, $psw){

    $con = connect_db();
    if($con){
        $q = "SELECT name FROM users WHERE admin= '1' AND email= '" . $usrn . "' AND password= '" . $psw . "'" ;
        $res = mysqli_query($con,$q);
        if($res){
            $row = mysqli_fetch_row($res);
            return $row[0];
        }
    }else{
        return false;
    }
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

/*
 * this function gets all the information needed for contacs page
 * 
 */
function get_users_info(){
    $con = connect_db();
    if($con){
        $out = array();
        $q = "SELECT * FROM users";
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
 * this function remove the user whose id is provided.
 * if id is all all the users are going to be deleted except for the admin.
 * 
 */
function delete_user($id){
    $con = connect_db();
    if($con){
        $q = $id === "all" ? "DELETE from users where admin=0" : "DELETE from users WHERE id= " . $id ;
        $res = mysqli_query($con,$q);
        return $res;
    }else{
        return false;
    }
}

/*
 * this function remove the message whose id is provided.
 * if id is all all the messages are going to be deleted.
 * 
 */
function edit_user($user){
    $con = connect_db();
    if($con){
        if($user["id"] === "new"){
            $q = "INSERT INTO users (name, email, password, admin)"
               . " VALUES ('" . $user['name'] . "', '" . $user['email'] . "', '" . $user['password'] . "', '" . ($user['admin'] === "true" ? "1" : "") . "')";
        }else{
            $q = "UPDATE users "
               . " SET name= '" . $user['name'] . "', email= '" . $user['email'] . "', password= '" . $user['password'] . "'"
               . " WHERE id= " . $user['id'] ;            
        }
        $res = mysqli_query($con,$q);
        return $res;
    }else{
        return false;
    }
}