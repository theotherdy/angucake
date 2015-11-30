angular.module('configs').config(['$stateProvider', '$urlRouterProvider',

    function($stateProvider, $urlRouterProvider) {
       
        $stateProvider.state('login', {
            url: "/login",
            controller: 'loginCtrl',
            controllerAs: 'ctrl',
            templateUrl: "partials/login.html",
        }).
        state('app', {
            abstract: true,
            templateUrl : "partials/app.html"
        }).
        state('app.home', {
            url: '/app/home',
            templateUrl : "partials/home.html"
        });
        
       $urlRouterProvider.otherwise("/login");
    }
]);