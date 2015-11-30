
  /**
   *  App runtime
   *
   */
   
  angular.module( 'runs' ).run( ['$rootScope',  '$location', '$state', 'loginFactory',
        function( $rootScope,  $location, $state, loginFactory ) {

            $rootScope.$on( "$stateChangeStart", function( event, next, current ) {
                // redirect to /login if is not logged  
                if (!loginFactory.isLogged() && $state.is('login') === false ) {
                     loginFactory.logout(function(user){
                          $state.go( 'login' );
                      });   
                // also redirect when user is logged in and hits /login route      
                } else if ( loginFactory.isLogged() && $state.is('login') ) {
                    $state.go( 'app.home' );
                }
            } );


}] );