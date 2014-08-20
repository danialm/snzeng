<?php 
//$contacts_info = get_contacts_info();
?>
<span id="popup" class="right"></span>
<h2><?= $pg['name'] ?></h2>
<table id="users">
    <thead>
        <tr>
            <td class="span4">name</td>
            <td class="span4">email</td>
            <td class="span4"><span title="Remove ALL" class='fa fa-minus-circle fa-2x button right error' onclick='delUsr("all")'></span><span title="Add user" class='fa fa-plus-circle fa-2x right add button' onclick='showEdit("new")'></span>password</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    function getUsers(){
        $("#popup").empty();
        var tbody = $("table#users tbody");
        tbody.empty();
         $.post( "ajax.php", {"inq":"users"}, 
            function(users){
                if(users.length !== 0){
                    $.each(users, function(i, d){
                        tbody.append("<tr id='"+d.id+"'><td class='span4'><span style='margin-right: 3px' class='fa fa-user"+(d.admin == 1 ? "" : "s")+"' title="+(d.admin == 1 ? "admin" : "user")+"></span>"+d.name+"</td><td class='span4'>"+d.email+"</td><td class='span4'><span title='Remove' class='fa fa-minus-circle right error button' onclick='delUsr("+d.id+")'></span><span title='Edit' class='fa fa-edit button right edit' onclick='showEdit("+d.id+")'></span>"+d.password+"</td>\n\
                                <td class='span4 edit-form'><input type='text' name='name' value='"+d.name+"' ></input></td><td class='span4 edit-form'><input type='email' name='email' value='"+d.email+"' ></input></td><td class='span4 edit-form'><span title='Save' class='fa fa-check-circle right add button' onclick='editUsr("+d.id+")'></span><input type='text' name='password' value='"+d.password+"' ></input></td></tr>");
                    });
                }else{
                    tbody.append("<tr><td class='span12'>No users to show.</td></tr>");
                }
            }, "json" )
            .fail(function() {
              tbody.append("<tr class='error'><td class='span12'>Something went wrong!</td></tr>");
            });
    }
    function delUsr(id){
        popup("Are you sure you want to delete "+ (id === "all" ? "all the users?" : "this user?"), function(){

                $.post( "ajax.php", {"inq":"delUsr", "id": id})
                    .success(function(data){
                        if(!data){
                            say("The user is not deleted!");
                        }
                    })
                    .fail(function() {
                      say("Cannot connect to database. The user is not deleted!");
                    })
                    .always(function(){
                        getUsers();
                    });

        });
    }
    function editUsr(id){
        var tr = $("tr#"+id);
        var user ={
            "id"        : id,
            "name"      : tr.find("input[name='name']").val(),
            "password"  : 'temp',   //temporary value in order that password can stay empty
            "email"     : tr.find("input[name='email']").val()
        };
        if(checkInput(user)){
            user.password = tr.find("input[name='password']").val();//real value of the password
            $.post( "ajax.php", {"inq":"editUsr", "user":user})
                                .success(function(data){
                                    if(!data){
                                        say("The user is not edited!");
                                    }
                                })
                                .fail(function() {
                                  say("Cannot connect to database. The user is not edited!");
                                })
                                .always(function(){
                                    getUsers();
                                });
        }
    }
    function addUsr(t){
         var tr = $(t).closest("tr");
         var user ={
            "id"        : "new",
            "name"      : tr.find("input[name='name']").val(),
            "password"  : tr.find("input[name='password']").val(),
            "email"     : tr.find("input[name='email']").val(),
            "admin"     : 'temp'//inorder to pass the next if
        };
        if(checkInput(user)){
            user.admin = tr.find("input[name='admin']").prop("checked");// actual value
            $.post( "ajax.php", {"inq":"editUsr", "user":user})
                                .success(function(data){
                                    if(!data){
                                        say("The user is not added!");
                                    }
                                })
                                .fail(function() {
                                  say("Cannot connect to database. The user is not added!");
                                })
                                .always(function(){
                                    getUsers();
                                });            
        }
    }
    $(document).ready(function(){
        getUsers();
    });
</script>