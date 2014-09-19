<span id="popup" class="right"></span>
<h2><?= $pg['name'] ?></h2>
<table id="office">
    <thead>
        <tr>
            <td class="span4">name</td>
            <td class="span8">value</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    function getOfficeInfo(){
        var tbody = $("table#office tbody");
        tbody.empty();
         $.post( "ajax.php", {"inq":"office"}, 
            function(officeInfo){
                if(officeInfo.length !== 0){
                    $.each(officeInfo, function(i, d){
                        if(d.key.indexOf("Img")>=0){
                            tbody.append("<tr id='"+i+"' data-src='"+d.value+"'><td class='span4'>"+d.key+"</td><td class='span8'><span title='Edit' class='fa fa-edit button right edit' onclick=\"showEdit('"+i+"')\"></span><img height='30' src='"+d.value+".jpg' /></td>\n\
                                                         <td class='span4 edit-form key'>"+d.key+"</td><td class='span8 edit-form'><span title='Save' class='fa fa-check-circle right add button' onclick='editOfficeInfo("+i+")'></span><form enctype='multipart/form-data'><input style='width: 90%' type='file' name='"+d.value+"'></input></form></td></tr>");
                        }else{
                         tbody.append("<tr id='"+i+"'><td class='span4'>"+d.key+"</td><td class='span8'><span title='Edit' class='fa fa-edit button right edit' onclick=\"showEdit('"+i+"')\"></span>"+d.value+"</td>\n\
                                                      <td class='span4 edit-form key'>"+d.key+"</td><td class='span8 edit-form'><span title='Save' class='fa fa-check-circle right add button' onclick='editOfficeInfo("+i+")'></span><input style='width: 90%' type='text' name='value' value='"+d.value+"' ></input></td></tr>");   
                        }                 
                    });
                }else{
                    tbody.append("<tr><td class='span12'>No information to show.</td></tr>");
                }
            }, "json" )
            .fail(function() {
              tbody.append("<tr class='error'><td class='span12'>Something went wrong!</td></tr>");
            });
    }
    function editOfficeInfo(id){
        var tr = $("tr#"+id);
        if(tr.attr("data-src")){//this statement takes care of images.
            var formData = new FormData(tr.find("form")[0]);
            
            $.ajax({
                url: 'ajax.php?inq=office',  //Server script to process data
                type: 'POST',
                xhr: function() {  // Custom XMLHttpRequest
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){ // Check if upload property exists
                        myXhr.upload.addEventListener('progress',function(){// For handling the progress of the upload
                            tr.find("span.fa").removeClass("fa-check-circle button").addClass("fa-cog fa-lg fa-spin");
                        }, false); 
                    }
                    return myXhr;
                },
                success: function(data){
                                    if(data === "1"){
                                        say("");
                                        getOfficeInfo();
                                    }else{
                                        say("ERROR: "+data);
                                        tr.find("span.fa").addClass("fa-check-circle button").removeClass("fa-cog fa-lg fa-spin");
                                    }
                },
                error: function(){
                    say("ERROR: Cannot connect to server!");
                    tr.find("span.fa").addClass("fa-check-circle button").removeClass("fa-cog fa-lg fa-spin");
                },
                // Form data
                data: formData,
                //Options to tell jQuery not to process data or worry about content-type.
                cache: false,
                contentType: false,
                processData: false
            });
        }else{
            var pair ={
                "key"        : tr.find("td.key").text(),
                "value"      : tr.find("input[name='value']").val()
            };
            $.post( "ajax.php", {"inq":"editOfficeInfo", "pair":pair})
                                .success(function(data){
                                    if(!data){
                                        say("The information is not edited!");
                                    }else{
                                        say("");
                                    }
                                })
                                .fail(function() {
                                  say("ERROR: Cannot connect to server!");
                                })
                                .always(function(){
                                    getOfficeInfo();
                                });
        }
    }
    $(document).ready(function(){
        getOfficeInfo();
    });
</script>