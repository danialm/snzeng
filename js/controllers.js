'use strict';
var changeNav = function(currentClass){
    if(currentClass===undefined){
        currentClass = 'No Class';
    }
    $(".navigation .hexagon").removeClass("lighter");
    $(".navigation .hexagon."+currentClass).toggleClass("lighter");
};
/* Controllers */

var phonecatControllers = angular.module('phonecatControllers', []);

phonecatControllers.controller('homepageCtrl', ['$scope', 'office',
  function($scope, office) {
    changeNav("home");
    $scope.pageClass = 'home';
    $scope.office = office.query();
  }]);
  
phonecatControllers.controller('projectsListCtrl', ['$scope', 'projects',
  function($scope, projects) {
    changeNav("projects");
    $scope.pageClass = 'projectsList';
    $scope.projects = projects.query();
    $scope.orderProp = 'age';
  }]);

phonecatControllers.controller('projectDetailCtrl', ['$scope', '$routeParams', 'projects',
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
  
phonecatControllers.controller('contactCtrl', ['$scope', 'office', '$http',
  function($scope, office, $http) {
    changeNav('contact');      
    $scope.pageClass = 'contact';
    $scope.office = office.query();
    
    $scope.submit = function(user){
                        if(user === undefined){// || user.name === '' || user.email === '' || user.message ===''){
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
                                         
                        $http.get('/email.php?from=contact_us&name='+user.name+'&email='+user.email+'&message='+user.message ).success(function(data){
                                                                    
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
  
phonecatControllers.controller('aboutCtrl', ['$scope',
  function($scope) {
    changeNav('about');      
    $scope.pageClass = 'about';
  }]);
  
phonecatControllers.controller('jobsCtrl', ['$scope', 'files',
  function($scope, files) {
    var path = 'office/jobs.pdf';
    $scope.path = path;  
    changeNav('jobs');      
    $scope.pageClass = 'jobs';
    $scope.fileFlag = files.exist(path);
  }]);