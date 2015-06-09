<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Reg extends Controller_Common
{
   public function action_sigin()
   {
       if(Auth::instance()->logged_in())//если пользователь уже авторизирован
       {
           HTTP::redirect('profile/overview');
       }
       $content =  View::factory('page/sigin')
                  ->bind('message', $message)
                  ->bind('user',$user);
       
        $message="До проверки";
        
        $this->template->content=$content;
       
      
       		if (HTTP_Request::POST == $this->request->method()) 
		{
                      $post = $this->request->post();
                      
                     
			// Attempt to login user
			$remember = array_key_exists('remember', $post)?(bool)$post['remember']:FALSE;
                       
			$success = Auth::instance()->login($post['username'], $post['password'], $remember);
			
                                      

                    if ($success)
                    {
                        
                        HTTP::redirect('profile/overview');
                    }
                    else
                    {
                       
                        HTTP::redirect('member/sigin');
                    }
                    
		}
     
   }

   public function action_register()
    {
        
        //echo Debug::vars($content);
        
        $captcha = Captcha::instance();
                                
        $content =  View::factory('page/registrn')
                    ->bind('errors', $errors);
        
        $content->captcha = $captcha->render(); 
        
         $content->message = '';
        
        $this->template->content=$content;
        
        if(HTTP_Request::POST == $this->request->method())
        {
           
            
            $_POST = Arr::map('trim',$_POST);
            
             $post = Validation::factory($_POST);
             
             $post->rule('captcha', 'not_empty')
                  ->rule('captcha','Tools_Rulevalidationmy::valid_captcha');
             
            
             
             if($post->check())
            {
                $data=Arr::extract($_POST, array('username','password','password_confirm','email'));
                        //->rule('captcha','Tools_Rulevalidationmy::valid_captcha');
                
                 
                 
                try 
                {

                    $user = ORM::factory('user')                        
                            ->create_user($data, array('username','password','email'))                     
                            ->add('roles',ORM::factory('role', array('name'=>'login')));

                    
                    // Reset values so form is not sticky
		    $_POST = array();
                                                            
             
             Session::instance()->set('user_name_reg', $user->username);
                
             HTTP::redirect('member/regend');
                   
                   

                }
                catch (ORM_Validation_Exception $e)
                {
                   $content->message = 'There were errors, please see form below.';

                   $errors = $e->errors('models');

                   if(isset($errors['_external'])) 
                   {
                       $errors = Arr::merge($errors, $errors['_external']);
                   }
                   unset($errors['_external']);

                  //print_r($errors);


                }
            }
            else
                {
                
                $errors=$post->errors('validation');
            }
            
            
        }
    }   
    
    public function action_regend()
    {
       
     $content =  View::factory('page/regend');
      
     
     $content->message = Session::instance()->get_once('user_name_reg');
     
     
     $this->template->content=$content;
        
    }
    
     public function action_logout()
    {
        Auth::instance()->logout(TRUE);
        
        HTTP::redirect('member/sigin');
        
    }
}
