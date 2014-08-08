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

