$(document).ready(function(){
    if($(window).width() < 1070){
        $('.navigation .ham').click(function(){
           $('.navigation .honeycomb').slideToggle(); 
        });
        $('.navigation .honeycomb a').click(function(){
            $('.navigation .honeycomb').slideUp(0);
        });
    }
});