'use strict';

/* App Module */

var snzengApp = angular.module('snzengApp', [
  'ngRoute',
  'snzengAnimations',
  'snzengDirectives',
  'snzengControllers',
  'snzengFilters',
  'snzengServices',
  'uiGmapgoogle-maps'
]);

snzengApp.config(['$routeProvider',
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
      when('/map', {
        templateUrl: 'partials/map.html',
        controller: 'mapCtrl'
      }).
      when('/map/:projectId', {
        templateUrl: 'partials/map.html',
        controller: 'mapCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);
  
snzengApp.config(function(uiGmapGoogleMapApiProvider) {
    uiGmapGoogleMapApiProvider.configure({
        key: 'AIzaSyACYkJtaPFR-UcR2ci-Xic7myJAWW977j0',
        v: '3.17',
        libraries: 'weather,geometry,visualization'
    });
});
    