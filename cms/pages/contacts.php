<span id="popup" class="right"></span>
<h2><?= $pg['name'] ?></h2>
<table id="contacts">
    <thead>
        <tr>
            <td class="span2">date</td>
            <td class="span4">email</td>
            <td class="span2">name</td>
            <td class="span4"><span title="Remove ALL" class='fa fa-minus-circle fa-2x right error button' onclick='delMsg("all")'></span>message</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    function getMessages(){
        var tbody = $("table#contacts tbody");
        tbody.empty();
         $.post( "ajax.php", {"inq":"contacts"}, 
            function(messages){
                if(messages.length !== 0){
                    $.each(messages, function(i, d){
                        tbody.append("<tr><td class='span2'>"+d.date+"</td><td class='span4'>"+d.email+"</td><td class='span2'>"+d.name+"</td><td class='span4'><span title='Remove' class='fa fa-minus-circle right error button' onclick='delMsg("+d.id+")'></span>"+d.message+"</td></tr>");
                    });
                }else{
                    tbody.append("<tr><td class='span12'>No message to show.</td></tr>");
                }
            }, "json" )
            .fail(function() {
              tbody.append("<tr class='error'><td class='span12'>Something went wrong!</td></tr>");
            });
    }
    function delMsg(id){
        popup("Are you sure you want to delete "+ (id === "all" ? "all the messages?" : "this message?"), function(){

                $.post( "ajax.php", {"inq":"delMsg", "id": id})
                    .success(function(data){
                        if(!data){
                            say("The message is not deleted!");
                        }else{
                            say("");
                        }
                    })
                    .fail(function() {
                      say("Cannot connect to database. The message is not deleted!");
                    })
                    .always(function(){
                        getMessages();
                    });

        });
    }
    $(document).ready(function(){
        getMessages();
    });
</script>