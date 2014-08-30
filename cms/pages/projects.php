<?php 
//$contacts_info = get_contacts_info();
?>
<span id="popup" class="right"></span>
<h2><?= $pg['name'] ?></h2>
<table id="project">
    <thead>
        <tr>
            <td class="span2">id</td>
            <td class="span4">name</td>
            <td class="span6"><span title="Add project" class='fa fa-plus-circle fa-2x right add button' onclick='showAdd()'></span>Thumbnail</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    function getProjects(){
        $("#popup").empty();
        var tbody = $("table#project tbody");
        tbody.empty();
         $.post( "ajax.php", {"inq":"projects"}, 
            function(project){
                if(project.length !== 0){
                    $.each(project, function(i, d){
                        tbody.append("<tr id='"+d.id+"'><td class='span2'>"+d.id+"</td><td class='span6'>"+d.name+"</td><td class='span4'><span title='Remove' class='fa fa-minus-circle right error button' onclick='delProject("+d.id+")'></span><span title='Edit' class='fa fa-edit button right edit' onclick='showEdit("+d.id+")'></span><img src='img/projects/project"+d.id+".thumb.jpg' height='30' alt='"+d.name+" thumbnail'/></td>\n\
                                                        <td class='edit-form'><form id='editPrj' enctype='multipart/form-data'><span class='span2'>id:<input type='text' name='id' value='"+d.id+"' /></span><span class='span4'>name:<input type='text' name='name' value='"+d.name+"' /></span><span  class='span6'><span title='Save' class='fa fa-check-circle fa-2x right add button' onclick='editProject("+d.id+")'></span>thumb:<input type='file' name='project"+d.id+".thumb' /></span>\n\
                                                                                                                  <span class='span3'>year:<input type='text' name='year' value='"+d.year+"' /></span><span class='span3'>order:<input type='text' name='order' value='"+d.order+"' /></span><span class='span4'>address:<input type='text' name='city' value='"+d.address+"' /></span><span class='span2'><select name='type"+d.id+"'><option value='mov'>movie</option><option value='res'>residential</option><option value='com'>comercial</option><option value='apt'>apt/condo</option><option value='fac'>facade</option><option value='nbu'>non-building</option></select></span>\n\
                                                                                                                  <span class='span6'>snippet:<textarea name='snipt'>"+d.snippet+"</textarea></span><span class='span6'>description:<textarea name='description'>"+d.description+"</textarea></span>\n\
                                                                                                                  <span class='span12'>images:</span><span class='span4' >1. <input type='file' name='project"+d.id+".0' /></span><span class='span4' >2. <input type='file' name='project"+d.id+".1' /></span><span class='span4' >3. <input type='file' name='project"+d.id+".2' /></span>\n\
                                                                                                                                                     <span class='span4' >4. <input type='file' name='project"+d.id+".3' /></span><span class='span4' >5. <input type='file' name='project"+d.id+".4' /></span><span class='span4' >6. <input type='file' name='project"+d.id+".5' /></span>\n\
                                                                                                                  <span class='span12'><span title='Add Specification' class='fa fa-plus-circle right add button' onclick='addSpec("+d.id+")'></span>specifications:</span><span id='spec"+d.id+"' class='spec'></span>\n\
                                                                              </form></td></tr>");
                        $('select[name="type'+d.id+'"] option[value="'+d.type+'"]').attr("selected","selected");                                                                              
                        $.each(d.spec, function(j, s){
                            $("#spec"+d.id).append("<span class='span6'>"+(j+1)+". <input type='text' name='spec["+j+"][name]' value='"+s.name+"' />: <input type='text' name='spec["+j+"][value]' value='"+s.value+"' /></span>");
                        });                                                                                                                      
                    });
                    
                }else{
                    tbody.append("<tr><td class='span12'>No projects to show.</td></tr>");
                }
            }, "json" )
            .fail(function() {
              tbody.append("<tr class='error'><td class='span12'>Something went wrong!</td></tr>");
            });
    }
    function addSpec(id){
        var newId = $("#spec"+id+" > span").length;
        $("#spec"+id).append("<span class='span6'>"+(newId+1)+". <input type='text' name='spec["+newId+"][name]' />: <input type='text' name='spec["+newId+"][value]' /></span>");
    }
    function showAdd(){
        $("thead").append("<tr><td class='span2'><input type='text' name='id' /></td><td class='span4'><input type='text' name='name' /></td><td class='span6'><span title='Save' class='fa fa-times right error button' onclick='$(this).closest(\"tr\").slideUp().remove(); $(\"#popup\").empty();'></span><span title='Save' class='fa fa-check-circle right add button' onclick='addProject(this)'></span></td></tr>").slideDown();
    }
    function delProject(id){
        popup("Are you sure you want to delete this project?", function(){

                $.post( "ajax.php", {"inq":"delPrj", "id": id})
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
                        getProjects();
                    });

        });
    }
    function editProject(id){
        console.log(id);
        getProjects();
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
    }
    function addProject(t){
        var tr = $(t).closest("tr");
        var prj ={
           "id"        : tr.find("input[name='id']").val(),
           "name"      : tr.find("input[name='name']").val()
        };
        if(checkInput(prj)){
            console.log(prj);
            $.post( "ajax.php", {"inq":"addPrj", "prj":prj})
                                .success(function(data){
                                    if(!data){
                                        say("The project is not added!");
                                    }else{
                                        say("");
                                    }
                                })
                                .fail(function() {
                                  say("Cannot connect to database. The project is not added!");
                                })
                                .always(function(){
                                    getProjects();
                                });            
        }
    }
    $(document).ready(function(){
        getProjects();
    });
</script>