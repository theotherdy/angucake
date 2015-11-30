/**
    Login Controller
**/
(function() {
    'user strict';
    angular.module('controllers').controller('loginCtrl', loginCtrl);
    
    loginCtrl.$inject = ['$scope', '$injector'];
    
    function loginCtrl($scope, $injector) {
        var $this = this;  
        
        //define scope variables
        this.formData = {}      
        this.errors = false;
        
        // inejct some dependencies
        this.loginFactory= $injector.get('loginFactory');
        this.state = $injector.get('$state');
        
         // Login action    
         this.login = function(){
            var $this = this;
            this.loginFactory.login(this.formData, function(success) {
                // after success login navigate to app.home state
                $this.state.go('app.home');
             }, function(error) {
                $this.errors = error.data.message;               
             });
        }
        
       };
        
       
 
            
})();