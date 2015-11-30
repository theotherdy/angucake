/**
 * HttpProvider push interceptor
 */
angular.module("configs").config(function($httpProvider){
    $httpProvider.interceptors.push('authInterceptor');
});