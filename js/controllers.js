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
  
snzengControllers.controller('projectsListCtrl', ['$scope', 'projects',
  function($scope, projects) {
    changeNav("projects");
    $scope.pageClass = 'projectsList';
    $scope.projects = projects.query();
    $scope.orderProp = 'order';
  }]);

snzengControllers.controller('projectDetailCtrl', ['$scope', '$routeParams', 'projects',
  function($scope, $routeParams, projects) {
    changeNav();      
    $scope.pageClass = 'projectDetail';
    $scope.project = projects.get({projectId: $routeParams.projectId}, function(project) {
      $scope.mainImageUrl = project.images[0];
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