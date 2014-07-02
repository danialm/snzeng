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
    console.log($scope.office);
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
  
phonecatControllers.controller('contactCtrl', ['$scope', 'office',
  function($scope, office) {
    changeNav('contact');      
    $scope.pageClass = 'contact';
    $scope.office = office.query();
  }]);
  
phonecatControllers.controller('aboutCtrl', ['$scope',
  function($scope) {
    changeNav('about');      
    $scope.pageClass = 'about';

  }]);
phonecatControllers.controller('jobsCtrl', ['$scope',
  function($scope) {
    changeNav('jobs');      
    $scope.pageClass = 'jobs';

  }]);