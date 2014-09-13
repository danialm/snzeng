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

/*
 * this function get the information from /office/office.json.
 * 
 */
function get_office_info(){
    $out = array();
    $office_file = str_replace("cms", "office/office.json", dirname(__FILE__));
    $office = json_decode(file_get_contents($office_file));
    foreach($office as $k => $v){
        $temp = array(
            'key' => $k,
            'value' => $v
        );
        array_push($out, $temp);
    }
    return $out;
}

/*
 * this function edit the information on /office/office.json.
 * 
 */
function edit_office_info($pair){
    $office_file = str_replace("cms", "office/office.json", dirname(__FILE__));
    $office = (array) json_decode(file_get_contents($office_file));
    $office[$pair['key']] = $pair['value'];
    $data = json_encode($office);
    if(file_put_contents($office_file, $data)){
        return true;
    }else{
        return false;
    }
}

/*
 * gets the projects information from tabels projects and projec_spec.
 * 
 */
function get_projects_info(){
    $con = connect_db();
    if($con){
        $out = array();
        $q = "SELECT * FROM projects";
        $res = mysqli_query($con,$q);
        while($row = mysqli_fetch_array($res)){
            $specs = array();
            $qa = "SELECT name, value FROM project_spec WHERE project_id=" . $row['id'];
            $resa = mysqli_query($con,$qa);
            while($spec = mysqli_fetch_array($resa)){
                array_push($specs, $spec);
            }
            $row['spec'] = $specs;
            array_push($out, $row);
        }
        return $out;
    }else{
        return false;
    }
}

/*
 * add a project to the table projects with default values.
 * 
 */
function add_project($prj){
    $con = connect_db();
    if($con){
        $q = "INSERT INTO projects (id, name)"
               . " VALUES ('" . $prj['id'] . "', '" . $prj['name'] . "')";
        $res = mysqli_query($con,$q);
        return $res;
    }else{
        return false;
    }
}

/*
 * remove a project from table projects and its spec from table project_spec.
 * 
 */
function delete_project($id){
    $con = connect_db();
    if($con){
        $q = "DELETE from projects WHERE id= " . $id; 
        $res1 = mysqli_query($con,$q);
        $q = "DELETE from project_spec WHERE project_id= " . $id;
        $res2 = mysqli_query($con,$q);
        return $res1 && $res2;
    }else{
        return false;
    }
}

/*
 * converts the image from $pair[vakue] to png and save it to $pair[key].
 * 
 */
function save_file($des, $file){
    $allowedExts = array("jpg", "jpeg", "png", "pdf");
    $allowedType = array("image/jpeg", "image/jpg", "image/pjpeg" , "image/png", "image/x-png", "text/pdf");
    $temp = explode(".", $file["name"]);
    $path = str_replace("_", ".", $des).".jpg";
    $extension = strtolower ( end($temp) );
    if (   in_array($file["type"], $allowedType) 
        && $file["size"] < 1500000
        && in_array($extension, $allowedExts)
        && $file["error"] === 0 ) {
        if (!file_exists($path)) {
           // var_dump($path);
            file_put_contents($path, file_get_contents("img/def.jpg"));
        }
        move_uploaded_file($file["tmp_name"], $path);
        return true;
    } else {
        return false;
    }
}
function save_file_test($des, $file){
    var_dump($des);
    var_dump($file);
//    $allowedExts = array("png");
//    $temp = explode(".", $file["name"]);
//    $extension = end($temp);
//    if (($file["type"] == "image/png")
//        && ($file["size"] < 1000000)
//        && in_array($extension, $allowedExts)
//        && $file["error"] === 0 ) {
//            move_uploaded_file($file["tmp_name"], str_replace("_", ".", $des));
//            return true;
//    } else {
//        return false;
//    }
}