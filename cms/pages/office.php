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
                        tbody.append("<tr id='"+i+"'><td class='span4'>"+d.key+"</td><td class='span8'><span title='Edit' class='fa fa-edit button right edit' onclick=\"showEdit('"+i+"')\"></span>"+d.value+"</td>\n\
                                                         <td class='span4 edit-form key'>"+d.key+"</td><td class='span8 edit-form'><span title='Save' class='fa fa-check-circle right add button' onclick='editOfficeInfo("+i+")'></span><input style='width: 90%' type='text' name='value' value='"+d.value+"' ></input></td></tr>");
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
        var pair ={
            "key"        : tr.find("td.key").text(),
            "value"      : tr.find("input[name='value']").val()
        };
        $.post( "ajax.php", {"inq":"editOfficeInfo", "pair":pair})
                            .success(function(data){
                                if(!data){
                                    say("The user is not edited!");
                                }
                            })
                            .fail(function() {
                              say("Cannot connect to database. The user is not edited!");
                            })
                            .always(function(){
                                getOfficeInfo();
                            });
    }
    $(document).ready(function(){
        getOfficeInfo();
    });
</script>