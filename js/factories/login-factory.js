 /**
  Login Factory
**/

 (function() {
     'user strict';
     angular.module('factories').factory('loginFactory', loginFactory);

     loginFactory.$inject = ['$resource', '$q'];
     function loginFactory($resource, $q) {
        
         return {
             login: login,
             logout:logout,
             isLogged:isLogged,
             getUserData:getUserData,
           
         };
         ///////////////
         /**
          * Login user in
          * @param object $data
          * @return object promise
          */
         function login($data) {
             return getResource().save($data).$promise.then(function(user) {
                 setUserData(user.user);
                 return user;
             })
         }
         /**
          * Store user save data
          * @param object user data
          */
         function setUserData($data) {              
             localStorage.setItem('token', $data.token);
             localStorage.setItem('id', $data.id);
         }

          /**
          * Get user data  
          * @return object
          */
         function getUserData() {
             // perform  asynchronous operation
             var deferred = $q.defer();
             
             if(localStorage.token && localStorage.id) {
              
               deferred.resolve({
                       token:localStorage.token,
                       id:localStorage.id,
                 });      
             
             // if local storage fails, check with the server if user is still logged in 
             } else {
                getUserFromServer().$promise.then(function(user){
                 deferred.resolve(user)
                },function(error){
                  deferred.reject();
                })
             }
             // return promise
             return deferred.promise;
         }

         /**
          * Remove User data
          */
         function removeUserData() {
             delete localStorage.token;
             delete localStorage.id;
         }

         /**
          * Logout user
          * @return object promise
          */
         function logout() {
             return getResource().delete().$promise.then(function(message) {
                 removeUserData();		
             })
         }

         /**
          * Check if user is logged in
          * @return boolean
          */
         function isLogged() {
             return getUserData().then(function(user){
                return true;
             }, function(error){               
                return false;
             });
         }
         
         /** 
          * Get user data from server
          * call api/login GET method, returns JSON {token,id}
          * @return promise
           */
         function getUserFromServer() {
            return getResource().get();
         }
         
         /**
          * Get resource
          * @return  obj
          */
         function getResource() {
             return $resource('angucake-api/login');
         };
     }
 })();