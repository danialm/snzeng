'use strict';

/* Filters */

angular.module('snzengFilters', []).filter('checkmark', function() {
  return function(input) {
    return input ? '\u2713' : '\u2718';
  };
});
