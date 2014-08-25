<?php 
//$contacts_info = get_contacts_info();
?>
<span id="popup" class="right"></span>
<h2><?= $pg['name'] ?></h2>
<table id="project">
    <thead>
        <tr>
            <td class="span4">id</td>
            <td class="span4">name</td>
            <td class="span4"><span title="Remove ALL" class='fa fa-minus-circle fa-2x button right error' onclick='delProject("all")'></span><span title="Add project" class='fa fa-plus-circle fa-2x right add button' onclick='showEdit("new")'></span>Thumbnail</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    function getProject(){
        $("#popup").empty();
        var tbody = $("table#project tbody");
        tbody.empty();
         $.post( "ajax.php", {"inq":"projects"}, 
            function(project){
                if(project.length !== 0){
                    $.each(project, function(i, d){
                        tbody.append("<tr id='"+d.id+"'><td class='span4'>"+d.id+"</td><td class='span4'>"+d.name+"</td><td class='span4'><span title='Remove' class='fa fa-minus-circle right error button' onclick='delProject("+d.id+")'></span><span title='Edit' class='fa fa-edit button right edit' onclick='showEdit("+d.id+")'></span><img src='img/projects/project"+d.id+".thumb.jpg' height='30' alt='"+d.name+" thumbnail'/></td>\n\
                                                        <td class='span12 edit-form'><form enctype='multipart/form-data'><input type='text' name='id' value='"+d.id+"' ></input><input type='text' name='name' value='"+d.name+"' ></input><span title='Save' class='fa fa-check-circle right add button' onclick='editProject("+d.id+")'></span><input type='file' name='thumb' ></input></td></tr>");
                    });
                }else{
                    tbody.append("<tr><td class='span12'>No projects to show.</td></tr>");
                }
            }, "json" )
            .fail(function() {
              tbody.append("<tr class='error'><td class='span12'>Something went wrong!</td></tr>");
            });
    }
    function delProject(id){
        popup("Are you sure you want to delete "+ (id === "all" ? "all the users?" : "this user?"), function(){

                $.post( "ajax.php", {"inq":"delProject", "id": id})
                    .success(function(data){
                        if(!data){
                            say("The project is not deleted!");
                        }else{
                            say("");
                        }
                    })
                    .fail(function() {
                      say("Cannot connect to database. The project is not deleted!");
                    })
                    .always(function(){
                        getProject();
                    });

        });
    }
//    function editProject(id){
//        var tr = $("tr#"+id);
//        var user ={
//            "id"        : id,
//            "name"      : tr.find("input[name='name']").val(),
//            "password"  : 'temp',   //temporary value in order that password can stay empty
//            "email"     : tr.find("input[name='email']").val()
//        };
//        if(checkInput(user)){
//            user.password = tr.find("input[name='password']").val();//real value of the password
//            $.post( "ajax.php", {"inq":"editUsr", "user":user})
//                                .success(function(data){
//                                    if(!data){
//                                        say("The user is not edited!");
//                                    }else{
//                                        say("");
//                                    }
//                                })
//                                .fail(function() {
//                                  say("Cannot connect to database. The user is not edited!");
//                                })
//                                .always(function(){
//                                    getUsers();
//                                });
//        }
//    }
//    function addUsr(t){
//         var tr = $(t).closest("tr");
//         var user ={
//            "id"        : "new",
//            "name"      : tr.find("input[name='name']").val(),
//            "password"  : tr.find("input[name='password']").val(),
//            "email"     : tr.find("input[name='email']").val(),
//            "admin"     : 'temp'//inorder to pass the next if
//        };
//        if(checkInput(user)){
//            user.admin = tr.find("input[name='admin']").prop("checked");// actual value
//            $.post( "ajax.php", {"inq":"editUsr", "user":user})
//                                .success(function(data){
//                                    if(!data){
//                                        say("The user is not added!");
//                                    }else{
//                                        say("");
//                                    }
//                                })
//                                .fail(function() {
//                                  say("Cannot connect to database. The user is not added!");
//                                })
//                                .always(function(){
//                                    getUsers();
//                                });            
//        }
//    }
    $(document).ready(function(){
        getProject();
    });
</script>