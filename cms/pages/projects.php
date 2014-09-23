<?php 
//$contacts_info = get_contacts_info();
?>
<span id="popup" class="right"></span>
<h2><?= $pg['name'] ?></h2>
<table id="project">
    <thead>
        <tr>
            <td class="span2">status</td>
            <td class="span2">id</td>
            <td class="span4">name</td>
            <td class="span4"><span title="Add project" class='fa fa-plus-circle fa-2x right add button' onclick='showAdd()'></span>Thumbnail</td>
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
                        var statusClass;
                        var checked;
                        var statusVal;
                        if(d.status === "1"){
                            statusClass = 'circle add';
                            checked = "";
                        }else{
                            statusClass = 'ban error' ;
                            checked = "checked";
                        }
                        tbody.append("<tr id='"+d.id+"'><td class='span2 fa fa-"+statusClass+"'></td><td class='span2'>"+d.id+"</td><td class='span4'>"+d.name+"</td><td class='span4'><span title='Remove' class='fa fa-minus-circle right error button' onclick='delProject("+d.id+")'></span><span title='Edit' class='fa fa-edit button right edit' onclick='showEdit("+d.id+")'></span><img src='"+d.thumb+"' height='30' alt='projects "+d.name+" thumbnail'/></td>\n\
                                                        <td class='edit-form'><form id='editPrj' enctype='multipart/form-data'><span class='span2'>status: <input title='Change status' type='checkbox' name='status' value='1' "+checked+" /><lable class='fa fa-circle'></lable></span><span class='span2'>id: "+d.id+"<input type='hidden' name='id' value='"+d.id+"' /></span><span class='span4'>name:<input type='text' name='name' value='"+d.name+"' /></span><span  class='span4'><span title='Save' class='fa fa-check-circle fa-2x right add button spin' onclick='editProject("+d.id+")'></span>thumb:<input type='file' name='img/projects/project"+d.id+".thumb' /></span>\n\
                                                                                                                  <span class='span3'>year:<input type='text' name='year' value='"+d.year+"' /></span><span class='span3'>order:<input type='text' name='order' value='"+parseInt(d.order)+"' /></span><span class='span4'>address:<input type='text' name='address' value='"+d.address+"' /></span><span class='span2'><select name='type'><option value='mov'>movie</option><option value='res'>residential</option><option value='com'>comercial</option><option value='apt'>apt/condo</option><option value='fac'>facade</option><option value='nbu'>non-building</option></select></span>\n\
                                                                                                                  <span class='span6'>snippet:<textarea name='snippet'>"+d.snippet+"</textarea></span><span class='span6'>description:<textarea name='description'>"+d.description+"</textarea></span>\n\
                                                                                                                  <span class='span12'>images:</span><span class='span6' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[0]' value='1' /><lable class='fa fa-circle'></lable></span>1. <input type='file' name='img/projects/project"+d.id+".0' /><img alt='No Img'src='"+d.img[0]+"' height='30' width='40' alt='project "+d.name+" image'/></span>\n\
                                                                                                                                                     <span class='span6' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[1]' value='1' /><lable class='fa fa-circle'></lable></span>2. <input type='file' name='img/projects/project"+d.id+".1' /><img alt='No Img'src='"+d.img[1]+"' height='30' width='40' alt='project "+d.name+" image'/></span>\n\
                                                                                                                                                     <span class='span6' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[2]' value='1' /><lable class='fa fa-circle'></lable></span>3. <input type='file' name='img/projects/project"+d.id+".2' /><img alt='No Img'src='"+d.img[2]+"' height='30' width='40' alt='project "+d.name+" image'/></span>\n\
                                                                                                                                                     <span class='span6' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[3]' value='1' /><lable class='fa fa-circle'></lable></span>4. <input type='file' name='img/projects/project"+d.id+".3' /><img alt='No Img'src='"+d.img[3]+"' height='30' width='40' alt='project "+d.name+" image'/></span>\n\
                                                                                                                                                     <span class='span6' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[4]' value='1' /><lable class='fa fa-circle'></lable></span>5. <input type='file' name='img/projects/project"+d.id+".4' /><img alt='No Img'src='"+d.img[4]+"' height='30' width='40' alt='project "+d.name+" image'/></span>\n\
                                                                                                                                                     <span class='span6' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[5]' value='1' /><lable class='fa fa-circle'></lable></span>6. <input type='file' name='img/projects/project"+d.id+".5' /><img alt='No Img'src='"+d.img[5]+"' height='30' width='40' alt='project "+d.name+" image'/></span>\n\
                                                                                                                  <span class='span12'>drawing(pdf):</span><span class='span8' ><span class='right'><input title='Remove Image' type='checkbox' name='remove[draw]' value='1' /><lable class='fa fa-circle'></lable></span>1. <input type='file' name='img/projects/project"+d.id+".draw' /><a target='blank' href='"+d.draw+"'>Current file</a></span></span>\n\
                                                                                                                  <span class='span12'><span title='Add Specification' class='fa fa-plus-circle right add button' onclick='addSpec("+d.id+")'></span>specifications:</span><span id='spec"+d.id+"' class='spec'></span>\n\
                                                                              </form></td></tr>");
                        $('tr#'+d.id+' select[name="type"] option[value="'+d.type+'"]').attr("selected","selected");                                                                              
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
        $("thead").append("<tr><td class='span2'><input type='text' name='id' /></td><td class='span4'><input type='text' name='name' /></td><td class='span6'><span title='Close' class='fa fa-times right error button' onclick='$(this).closest(\"tr\").slideUp().remove(); $(\"#popup\").empty();'></span><span title='Save' class='fa fa-check-circle right add button' onclick='addProject(this)'></span></td></tr>").slideDown();
    }
    function delProject(id){
        popup("Are you sure you want to delete this project?", function(){

                $.post( "ajax.php", {"inq":"delPrj", "id": id})
                    .success(function(data){
                        if(data === "1"){
                            say("");
                            getProjects();
                        }else{
                            say("ERROR: "+data);
                        }
                    })
                    .fail(function() {
                      say("ERROR: Cannot connect to server!");
                    });

        });
    }
    function editProject(id){
        var tr = $("tr#"+id);
        var formData = new FormData(tr.find("form")[0]); 
        $.ajax({
            url: 'ajax.php?inq=editPrj',  //Server script to process data
            type: 'POST',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // Check if upload property exists
                    myXhr.upload.addEventListener('progress',function(){// For handling the progress of the upload
                        tr.find("span.spin").removeClass("fa-check-circle button").addClass("fa-cog fa-lg fa-spin buttonish");
                    }, false); 
                }
                return myXhr;
            },
            success: function(data){
                                if(data === "1"){
                                    say("");
                                    getProjects();
                                }else{
                                    say("ERROR: "+data);
                                    tr.find("span.spin").addClass("fa-check-circle button").removeClass("fa-cog fa-lg fa-spin buttonish");
                                }
            },
            error: function(){
                say("ERROR: Cannot connect to server!");
                tr.find("span.spin").addClass("fa-check-circle button").removeClass("fa-cog fa-lg fa-spin buttonish");
            },
            // Form data
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    }
    function addProject(t){
        var tr = $(t).closest("tr");
        var prj ={
           "id"        : tr.find("input[name='id']").val(),
           "name"      : tr.find("input[name='name']").val()
        };
        if(checkInput(prj)){
            $.post( "ajax.php", {"inq":"addPrj", "prj":prj})
                                .success(function(data){
                                    if(data === "1"){
                                        say("");
                                        getProjects();
                                    }else{
                                        say("ERROR: "+data);
                                    }
                                })
                                .fail(function() {
                                  say("ERROR: Cannot connect to server!");
                                });            
        }
    }
    $(document).ready(function(){
        getProjects();
    });
</script>