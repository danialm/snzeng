var phonecatAnimations = angular.module('phonecatAnimations', ['ngAnimate']);

phonecatAnimations.animation('.project', function() {

  var animateUp = function(element, className, done) {
    if(className != 'active') {
      return;
    }
    element.css({
      top: "100%"
    });

    jQuery(element).animate({
      top: 30
    }, done);

    return function(cancel) {
      if(cancel) {
        element.stop();
      }
    };
  };

  var animateDown = function(element, className, done) {
    if(className != 'active') {
      return;
    }
    element.css({
      top: 30
    });

    jQuery(element).animate({
      top: "-100%"
    }, done);

    return function(cancel) {
      if(cancel) {
        element.stop();
      }
    };
  }

  return {
    addClass: animateUp,
    removeClass: animateDown
  };
});
