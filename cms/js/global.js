function popup(data, callback){
    $('#popup').empty();
    $('#popup').append("<span class='error'>"+data+"</span>");
    $('#popup').append("<span id='confirm' class='button'> Yes</span><span id='notConfirm' class='button'> No</span>");
    $('#confirm').on("click", function(){
        $('#popup').empty();
        callback();
    });
    $('#notConfirm').on("click", function(){
        $('#popup').empty();
        return false;
    });
}
function say(data){
    $('#popup').empty();
    $('#popup').append("<span class='error'>"+data+"</span>");
}
function checkInput(obj){
    var arr = $.map( obj, function(val, key){
        return val;
    });
    flag = true;
    $.each( arr, function(i,d){
       if(d == ''){
           say("Pleas fill out the form completly!");
           flag = false;
       }
    });
    return flag;
}
function showEdit(id){
    if(id === 'new'){
        $("thead").append("<tr><td class='span4'><label for='admin' class='right'>Admin</label><input type='checkbox' id='admin' name='admin' class='right'></input><input type='text' name='name' ></input></td><td class='span4'><input type='email' name='email' ></input></td><td class='span4'><span title='Save' class='fa fa-times right error button' onclick='$(this).closest(\"tr\").slideUp().remove(); $(\"#popup\").empty();'></span><span title='Save' class='fa fa-check-circle right add button' onclick='addUsr(this)'></span><input type='text' name='password' ></input></td></tr>").slideDown();
    }else{
        $("tr#"+id+" td").hide();
        $("tr#"+id+" td.edit-form").show();
    }
}
