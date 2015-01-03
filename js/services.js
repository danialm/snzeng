'use strict';

/* Services */

var snzengServices = angular.module('snzengServices', ['ngResource']);
    
snzengServices.service('mapShwo', 
    function() {
        var setup = {
            "lat" : 36,
            "lng" : -118,
            "zoom": 6
        };

        var setSetup = function(obj) {
            setup.lat  = obj.lat;
            setup.lng  = obj.lng;
            setup.zoom = obj.zoom;
        };

        var getSetup = function(){
            return setup;
        };

        return {
          setSetup: setSetup,
          getSetup: getSetup
        };
    });
  
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
