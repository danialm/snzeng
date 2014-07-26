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
  
phonecatServices.service('files',
  function(){
    this.exist = function(path){
                    var flag = false;
                    $.ajax({
                        url:path,
                        type:'HEAD',
                        async: false,
                        success: function()
                        {
                            flag = true;
                        }
                    });
                    return flag;
                };
                //return this.exist;
  });
