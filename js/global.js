$(document).ready(function(){
    $('.navigation .ham').click(function(){
       if($(window).width() < 1070){
            $('.navigation .honeycomb').slideToggle();
        }
    });
    $('.navigation .honeycomb a').click(function(){
        if($(window).width() < 1070){
            $('.navigation .honeycomb').slideUp(0);
        }
    });
});