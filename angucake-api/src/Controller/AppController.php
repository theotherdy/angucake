<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->template = 'ajax';
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        /* see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
        Send all unauthenticated users to unauthorized action which returns 401 so can be easily handled by Angular application */
        $this->loadComponent('Auth', [  
            'loginAction'=>['controller'=>'Pages', 'action'=>'unauthorized', '_ext'=>'json'],
            'authorize'=>['Controller'],
            'authError'=>"Error"//,
            //'storage' => 'Memory'
            ]); 
    }

    /**
     * Before filter logic - see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
     *
     */
    public function beforeFilter(Event $event)
    {
        $this->user_id = $this->Auth->user('id');
        
        // validate user token for logged user
        if($this->user_id) {
            if(!$this->checkUserToken()) {   //if token is invalid we log out user and respond with 403 error
            $this->Auth->logout(); // logout user
            throw new ForbiddenException("Invalid Token!");    // throw an 403 error
            }
        }        
    }

    /**
     * Check User Token - see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
     */
    public function checkUserToken() 
    {
            $request_token = $this->getRequestToken();
            
            if (!$request_token) {
               return false;
            }
            
            if ($request_token != $this->userToken()) {               
                return false;
            }
        return true;
    }

    /**
     * Get Request token - see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
     * Grab token sent along with every request to the application
     */
    public function getRequestToken() 
    {
        
        $headers = $this->getHeaders();
        if (!isset($headers['Authorization'])) return false;
        $token = explode(" ", $headers['Authorization']);       
        return $token[1];
    }

    /**
     * Get Request headers - see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
     */
    private function getHeaders() 
    {
        $headers = getallheaders();        
        return $headers;
    }
    
    /**
     * Get User token - see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
     *
     */
    public function userToken()
    {
        return $this->Auth->user('token');
    }
    
    /**
     * Authorization default true - see https://medium.com/@ranostaj/login-with-cakephp3-and-angularjs-b4b124708086#.cy375kudb
     */
    public function isAuthorized($user)
    {
        return false;
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
}
