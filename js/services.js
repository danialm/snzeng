'use strict';

/* Services */

var snzengServices = angular.module('snzengServices', ['ngResource']);
    
snzengServices.factory('office', ['$resource',
  function($resource){
    return $resource('office/office.json', {}, {
      query: {method:'GET', params:{}}
    });
  }]);
  
snzengServices.service('files',
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
  });
