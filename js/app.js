'use strict';

/* App Module */

var phonecatApp = angular.module('phonecatApp', [
  'ngRoute',
  'phonecatAnimations',
  'phonecatDirectives',
  'phonecatControllers',
  'phonecatFilters',
  'phonecatServices'
]);

phonecatApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'partials/homepage.html',
        controller: 'homepageCtrl'
      }).
      when('/projects', {
        templateUrl: 'partials/projects-list.html',
        controller: 'projectsListCtrl'
      }).
      when('/projects/:projectId', {
        templateUrl: 'partials/project-detail.html',
        controller: 'projectDetailCtrl'
      }).
      when('/about-us', {
        templateUrl: 'partials/about.html',
        controller: 'aboutCtrl'
      }).
      when('/contact-us', {
        templateUrl: 'partials/contact.html',
        controller: 'contactCtrl'
      }).
      when('/jobs', {
        templateUrl: 'partials/jobs.html',
        controller: 'jobsCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);
