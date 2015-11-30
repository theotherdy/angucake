/*
 *
 * Auth interceptor factory
 *
 */


'user strict';
(function() {

    angular.module( 'factories' )
            .factory( 'authInterceptor', authInterceptor );

    authInterceptor.$inject = ['$q', '$location', '$timeout', '$injector'];

    function authInterceptor( $q, $location,   $timeout, $injector) {
        var $state;
        var factory = {
            request : request,
            responseError : responseError,
            response : response

        };

        return factory;

        //////////////////////////////

        /**
         * Request
         * @param object config
         * @returns object
         */
        function request( config ) {

            config.headers = config.headers || { }
            var token;        

            if ( localStorage.token ) {
                token = localStorage.token;
            }

            if ( token ) {
                config.headers.Authorization = 'Bearer ' + token;
            }

            return config;

        };
        
        /**
         * Response Error
         * @param object response
         * @returns object
         */
        function responseError( response ) {
            
            // to avoid circular dependency we inject $state in $timeout function
             $timeout(function () {         
              $state = $injector.get('$state');
             });
             
            if ( response.status === 403 ) {                
                $timeout(function () {  
                    $state.go('login');
                })
            }

            if ( response.status === 401 ) {
                 $timeout(function () {  
                    $state.go('login');
                })
            }

            return $q.reject( response );
        };
        
        /**
         * Response
         * @param object response
         * @returns object
         */
        function response( response ) {         

            return response;
        }
    }


})();