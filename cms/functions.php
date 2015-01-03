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
function get_projects_info($markerOnly = false){
    $con = connect_db();
    if($con){
        $out = array();
        $q = "SELECT * FROM projects";
        if(!$markerOnly)
            $q .= " WHERE marker_only='0'";
        $res = mysqli_query($con,$q);
        while($row = mysqli_fetch_array($res)){
            $specs = array();
            $qa = "SELECT name, value FROM project_spec WHERE project_id=" . $row['id'];
            $resa = mysqli_query($con,$qa);
            while($spec = mysqli_fetch_array($resa)){
                array_push($specs, $spec);
            }
            $row['spec'] = $specs;
            
            $row['img'] = array();
            $path = "img/projects/project" . $row['id']  . ".";
            for($i=0;$i<PROJECT_IMAGE_NUMBER;$i++){
                if(is_file($path.$i.".jpg"))
                    array_push ($row['img'], $path.$i.".jpg");
                else
                    array_push ($row['img'], DEFAULT_IMAGE);
            }
            $row['draw'] = is_file($path."draw.pdf") ? $path."draw.pdf" : false;
            $row['thumb'] = is_file($path."thumb.jpg") ? $path."thumb.jpg" : DEFAULT_IMAGE;
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
        if(!$res)
            return "Project ID already exists!";
        return true;
    }else{
        return "Cannot connect to database. Try again!";
    }
}
/*
 * add a projects to the table projects with default values from an uploaded excel file.
 * 
 */
function add_marker_project($prj){
    $con = connect_db();
    if($con){
            $q = "INSERT INTO projects (id, name, address, status, marker_only, lat, lng)";
            $q.= " VALUES ('" . $prj['id'] . "', '" . $prj['name'] . "', '" . $prj['address'] . "', '1', '1', '" . $prj['lat'] . "', '" . $prj['lng'] . "')";
            $res = mysqli_query($con,$q);
            if(!$res)
                return "Project ID already exists!";
            return true;
    }else{
        return "Cannot connect to database. Try again!";
    }
}

/*
 * returns the project ids that do not exist.
 * 
 */
function get_new_projects( $ids ) {
    $con = connect_db();
    if($con){
        $return = array();
        foreach($ids as $id){
            $q = "SELECT id from projects WHERE id='" . $id['id'] . "'";
            $res = mysqli_query($con,$q);
            if(!mysqli_fetch_array($res)){
                    array_push($return, $id['rowNumber']);
            }      
        }
        return $return;
    }else{
        return "Cannot connect to database. Try again!";
    }
}
/*
 * delete all the marker_only projects.
 * 
 */
function delete_marker_projects(){
    $con = connect_db();
    if($con){
        $q = "DELETE from projects WHERE marker_only= '1'";
        $res = mysqli_query($con,$q);
        if(!$res)
            return "Delete all marker projects failed!";
        
        return true;
    }else{
        return "Cannot connect to database. Try again!";
    }
}

/*
 * remove a project from table projects and its spec from table project_spec.
 * 
 */
function delete_project($id){
    $con = connect_db();
    $path = "img/projects/project" . $id  . ".";
    $des = "img/projects/old";
    for($i=0;$i<6;$i++){
        if(is_file($path.$i.".jpg")){
            rename($path.$i.".jpg", $des.".jpg");
        }
    }
    rename($path."thumb.jpg", $des.".jpg");
    rename($path."draw.pdf", $des.".pdf");
    
    if($con){
        $q = "DELETE from projects WHERE id= " . $id; 
        $res1 = mysqli_query($con,$q);
        if(!$res1)
            return "The project is not deleted!";
        $q = "DELETE from project_spec WHERE project_id= " . $id;
        $res2 = mysqli_query($con,$q);
        if(!$res2)
            return "The spesificatins are not deleted!";
        return true;
    }else{
        return "Cannot connect to database. Try again!";
    }
}

/*
 * converts the image from $pair[vakue] to png and save it to $pair[key].
 * 
 */
function save_file($des, $file){
    if($file["size"] !== 0){
//        var_dump($des);
//        var_dump($file);
        $allowedExts = array("jpg", "jpeg", "png");
        $allowedType = array("image/jpeg", "image/jpg", "image/pjpeg" , "image/png", "image/x-png");
        $temp = explode(".", $file["name"]);
        $path_jpg = str_replace("_", ".", $des).".jpg";
        $path_pdf = str_replace("_", ".", $des).".pdf";
        $extension = strtolower ( end($temp) );
        if (  in_array($file["type"], $allowedType) && in_array($extension, $allowedExts) ){
            if($file["size"] < 2000000){
                if( $file["error"] === 0 ) {
                    if (!file_exists($path_jpg)) {
                        file_put_contents($path_jpg, file_get_contents("img/def.jpg"));
                    }
                    move_uploaded_file($file["tmp_name"], $path_jpg);
                    return true;
                }else{
                    return "One of images: ". $file["error"];
                }
            }else{
               return "Image size should be less than 2M."; 
            }

        } else if ( in_array($file["type"], array("application/pdf") ) && in_array($extension, array("pdf"))  ){
            if($file["size"] < 1000000){
                if( $file["error"] === 0 ) {
                    if (!file_exists($path_pdf)) {
                        // var_dump($path);
                        file_put_contents($path_pdf, file_get_contents("image/def.pdf"));
                    }
                    move_uploaded_file($file["tmp_name"], $path_pdf);
                    return true;
                }else{
                    return "PFD: ". $file["error"];
                }                
            }else{
               return "PDF size should be less than 10M."; 
            }
        } else {
            return ".jpg, .png, .pdf ONLY!";
        }
    }else{
        return true;
    }    
}

/*
 * removes the selected files and edits the project information.
 * 
 */
function edit_project($data){
    $project_id = $data['id'];
    if(isset($data["remove"])){
        foreach($data["remove"] as $key => $value){
            $ext = ".jpg";
            $path = "img/projects/project" . $project_id . "." . $key;
            $des = "img/projects/old";
            if($key === "draw")
                $ext = ".pdf";
            if(!rename($path.$ext, $des.$ext))
                return $path.$ext." is not removed!";
        }
    }
    $con = connect_db();
    if($con){
        if(isset($data["spec"])){
            $q = "DELETE FROM project_spec WHERE project_id= " . $project_id;
            $res1 = mysqli_query($con,$q);
            if (!$res1)
                return "Specifications are not removed!";
            foreach($data["spec"] as $spec){
                if(trim($spec['name']) != '' && trim($spec['value']) != ''){
                    $q = "  INSERT INTO project_spec (project_id, name, value)
                            VALUES ('" . $project_id . "', '" . $spec['name'] ."', '" . $spec['value'] . "')";
                    $res2 = mysqli_query($con,$q);
                    if(!$res2)
                        return "Specifications are not added!";
                }
            }
        }
        if(!isset($data['status'])){
            $data['status'] = 1;
        }else{
            $data['status'] = 0;
        }
        $order = str_pad((int) $data['order'], 4, '0', STR_PAD_LEFT);
        $q = "UPDATE snzeng.projects"
           . " SET name= '" . $data['name'] . "', status= '" . $data['status'] . "', type= '" . $data['type'] . "', year= '" . $data['year'] . "', snippet= '" . $data['snippet'] . "', description= '" . $data['description'] . "', address= '" . $data['address'] . "', projects.order= '" . $order . "', lat= '" . $data['lat'] . "', lng= '" . $data['lng'] . "'"
           . " WHERE id= " . $project_id ;
        mysqli_query($con,$q);
        return true;
    }else{
        return "Cannot connect to database. Try again!";
    }
    
}