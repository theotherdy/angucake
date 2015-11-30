<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;
//use Cake\Log\Log;
 
/**
 * Login Controller
 *
 * @property \App\Model\Table\LoginTable $Login
 */
class LoginController extends AppController
{
    
    /**
     *  Initialize Controller
     */
     
    public function initialize()
    {          
        $this->loadModel('User');
        parent::initialize();       
    }
    
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'logout']);
    }
 
    /**
     * Index Login method  API URL  /api/login method: POST
     * @return json response
     */
    public function index()
    {   
        try {                                    
            if(!isset($this->request->data['username'])){
                throw new UnauthorizedException("Please enter your username");                
            }
             if(!isset($this->request->data['password'])){
                throw new UnauthorizedException("Please enter your password");                
            }
            $username  = $this->request->data['username'];
            $password  = $this->request->data['password'];
            
            // Check for user credentials 
            
            
            $users = TableRegistry::get('Users');
            $user =  $users
                        ->find()
                        ->where(['username'=>$username, 'password'=>$password])
                        ->first();
            //$this->User->find('login', ['username'=>$username, 'password'=>$password]);
            if(!$user) {
               throw new UnauthorizedException("Invalid login");     
            }
              
              // if everything is OK set Auth session with user data
              
                //debug($token);
                //but first generate and insert token into user before put into Auth
              $token =  Security::hash($user->id.$user->username, 'sha1', true);  //TODO - maybe need tmestamp on this so saving token doesn't work outside current session
              //debug($user);
              $user['token']=$token;

              $this->Auth->setUser($user->toArray());
              
              // Generate user Auth token
              // $token =  Security::hash($user->id.$user->username, 'sha1', true);  //TODO - maybe need tmestamp on this so saving token doesn't work outside current session

              

              // Add user token into Auth session
              $this->request->session()->write('Auth.User.token', $token);

              //add token into 
           
              // return Auth token
              $this->response->header('Authorization', 'Bearer ' . $token);
              
                            
                
        } catch (UnauthorizedException $e) {            
            throw new UnauthorizedException($e->getMessage(),401);   
        }           
        $this->set('user', $this->Auth->user());        
        $this->set('_serialize', ['user']);
    }
     /**
     * Logout user
     * API URL  /api/login method: DELETE
     * @return json response
     */
    public function logout()
    {        
        $this->Auth->logout();
        $this->set('message', 'You were logged out');
        $this->set('_serialize', ['message']);
    }
}
?>