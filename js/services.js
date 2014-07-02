'use strict';

/* Services */

var phonecatServices = angular.module('phonecatServices', ['ngResource']);

phonecatServices.factory('projects', ['$resource',
  function($resource){
    return $resource('projects/:projectId.json', {}, {
      query: {method:'GET', params:{projectId:'projects'}, isArray:true}
    });
  }]);
  
phonecatServices.factory('office', ['$resource',
  function($resource){
    return $resource('office/office.json', {}, {
      query: {method:'GET', params:{}}
    });
  }]);
