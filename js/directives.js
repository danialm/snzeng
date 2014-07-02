'use strict';

/* Directives */

var phonecatDirectives = angular.module('phonecatDirectives', []);

phonecatDirectives.directive('navPtr',
  function(currentClass) {
    $(".navigation .hexagon").css("background", "hsl(257, 22%, 42%)");
    $(".navigation .hexagon."+currentClass).css("background","rgb(157, 144, 187");
  });