'use strict';
var changeNav = function(currentClass){
    if(currentClass===undefined){
        currentClass = 'No Class';
    }
    $(".navigation .hexagon").removeClass("lighter");
    $(".navigation .hexagon."+currentClass).toggleClass("lighter");
};
/* Controllers */

var snzengControllers = angular.module('snzengControllers', []);

snzengControllers.controller('homepageCtrl', ['$scope', 'office',
  function($scope, office) {
    changeNav("home");
    $scope.pageClass = 'home';
    $scope.office = office.query();
  }]);
  
snzengControllers.controller('projectsListCtrl', ['$scope', '$http',
  function($scope, $http) {
    changeNav("projects");
    $scope.pageClass = 'projectsList';
    $http.get('/ajax.php?projects=true').success(function(data){
        $scope.projects= jQuery.grep(data, function(d) {
              return d.status !== "0";
            });
    });
    $scope.orderProp = 'order';
  }]);

snzengControllers.controller('projectDetailCtrl', ['$scope', '$routeParams', '$http',
  function($scope, $routeParams, $http) {
    changeNav();      
    $scope.pageClass = 'projectDetail';
    var id = $routeParams.projectId;
    $http.get('/ajax.php?projects=true').success(function(data_raw){
        var data = jQuery.grep(data_raw, function(d) {
              return d.status !== "0";
            });
        $.each(data, function(i,d){
            if(d.id == id){
               $scope.project= d;
               $scope.mainImageUrl = d.img[0];
            }
        });
    });
    $scope.setImage = function(imageUrl) {
        $scope.mainImageUrl = imageUrl;
    };
  }]);
  
snzengControllers.controller('contactCtrl', ['$scope', 'office', '$http',
  function($scope, office, $http) {
    changeNav('contact');      
    $scope.pageClass = 'contact';
    $scope.office = office.query();
    $scope.submit = function(user){
                        if(user === undefined){
                            $scope.result ={ 'val':'Pleaes fill out the form!',
                                             'class':'error',
                                             'icon':'fa fa-exclamation fa-2x' 
                                         };
                            return false;
                        }
                        if(user.name === undefined || user.name == ''){
                            $scope.result ={ 'val':'What do we call you?',
                                             'class':'error',
                                             'icon':'fa fa-exclamation fa-2x' 
                                         };
                            return false;
                        }
                        if(user.email === undefined || user.email == ''){
                            $scope.result ={ 'val':'How do we contact you back?',
                                             'class':'error',
                                             'icon':'fa fa-exclamation fa-2x' 
                                         };
                            return false; 
                        }
                        if(user.message === undefined || user.message == ''){
                            $scope.result ={ 'val':'Don\'t you have a message for us?',
                                             'class':'error',
                                             'icon':'fa fa-exclamation fa-2x' 
                                         };
                            return false;
                        }
                        $scope.result ={ 'val':'Thanks! It is sending...',
                                             'class':'working',
                                             'icon':'fa fa-cog fa-spin fa-2x'
                                         };
                                         
                        $http.get('/ajax.php?from=contact_us&name='+user.name+'&email='+user.email+'&message='+user.message ).success(function(data){
                                                                    
                                                                    if(data == 1){
                                                                        $scope.result ={    
                                                                                        'val':'Cool! Get back to you soon.',
                                                                                        'class':'success',
                                                                                        'icon':'fa fa-check fa-2x'
                                                                                       };
                                                                    }else{
                                                                        $scope.result ={    'val':'Sorry! Cannot send it.',
                                                                                            'class':'error',
                                                                                            'icon':'fa fa-exclamation fa-2x'
                                                                                        };                                                                        
                                                                    }
                                                        })
                                                       .error(function(){
                                                                    $scope.result ={    'val':'Sorry! Cannot send it.',
                                                                                        'class':'error',
                                                                                        'icon':'fa fa-exclamation fa-2x'
                                                                                    };
                        });
                        return true;
                   };                      
  }]);
  
snzengControllers.controller('aboutCtrl', ['$scope', 'office',
  function($scope, office) {
    changeNav('about');      
    $scope.pageClass = 'about';
    $scope.office = office.query();
  }]);
  
snzengControllers.controller('jobsCtrl', ['$scope', 'files',
  function($scope, files) {
    var path = 'office/job.pdf';
    $scope.path = path;  
    changeNav('jobs');      
    $scope.pageClass = 'jobs';
    $scope.fileFlag = files.exist(path);
  }]);
  
snzengControllers.controller('mapCtrl', ['$scope','uiGmapGoogleMapApi', '$http', 'mapShwo', '$routeParams',
  function($scope, uiGmapGoogleMapApi, $http, mapShwo, $routeParams) {
    changeNav('map');      
    $scope.pageClass = 'map';
    $scope.markers = new Array;
    $scope.windowMarkers = new Array;
    $scope.noWindowMarkers = new Array;
    var id = $routeParams.projectId;
    var mob = $("body").width() < 350 ? true : false;
    $http.get('/ajax.php?markers=true').success(function(data){
        var projects= jQuery.grep(data, function(d) {
            return d.status !== "0";
        });
        $.each(projects,function(i,project){
                    var temp = {
                            "id": project.id,
                            "coords": {latitude: project.lat, longitude: project.lng },
                            "icon": "img/marker_inactive.png",
                            "show": false,
                            "window": true,
                            "options": {
                                "clickable": false
                            },
                            "content": {
                                "title": "Project #"+project.id,
                                "snippet": "This image is from Google Street View",
                                "img": "https://maps.googleapis.com/maps/api/streetview?size=100x66&location="+project.lat+","+project.lng+"&key=AIzaSyACYkJtaPFR-UcR2ci-Xic7myJAWW977j0"
                            }
                    };
                    temp.onClick = function(e){
                        return true;
                    };
                    if(project.marker_only === "0"){
                        temp.options.animation = id === project.id ? false : google.maps.Animation.DROP;;
                        temp.options.clickable = true;
                        temp.show = (id && id===project.id) ? true : false;
                        temp.content ={
                                    "title": project.name,
                                    "snippet": project.snippet,
                                    "link": { "url": "#/projects/"+project.id,
                                              "text": "more..."
                                    },
                                    "img": "img/projects/project"+project.id+".thumb.jpg"
                        };
                        temp.icon = "img/marker.png";
                        temp.closeClick = function(){
                            temp.show = false;
                        };
                        temp.onClick = function(){
                            temp.show = false;
                        };

                        $scope.windowMarkers.push(temp); 
                    }else{

                        $scope.noWindowMarkers.push(temp);
                    }
                    if(id && id === project.id){
                        $scope.map.zoom = 19;
                        $scope.map.center.latitude = project.lat;
                        $scope.map.center.longitude = project.lng;
                    }
                    $scope.markers.push(temp);
                    
        });
    });
    
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = {
            doCluster: true,
            center: {
              latitude: mob ? 35.5 : 36,
              longitude: mob ? -120 : -118
            },
            zoom: 6,
            options: {
                maxZoom: 19,
                minZoom: 4,
                mapTypeControl: false,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.VERTICAL_BAR,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                panControl: false,
                zoomControl: !mob,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                scaleControlOptions : {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                streetViewControl: false,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            }
        };
    });
  }]);