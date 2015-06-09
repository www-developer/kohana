<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {
    
    public function action_checkpassword()
    {
    if($this->request->is_ajax())
      {        
        $objt = Validation::factory($this->request->post());
        
                    $objt->rule(TRUE, 'not_empty')
                    ->rule(TRUE, 'min_length', array(':value', '2'))
                    ->rule(TRUE, 'max_length', array(':value', '16'))         
                    ->rule('newp2', 'matches', array(':validation', 'newp2', 'newp'));
                    
            if($objt->check())  
            {
                $oldpassdb= Auth::instance()->get_user()->password;
                
           
                $oldpassuser = Auth::instance()->hash_password($this->request->post('old'));
                
                if($oldpassdb===$oldpassuser)
                {
                    try 
                    {

                        $date = array('password'=>$this->request->post('newp'));

                        $u = ORM::factory('user')->where('id', '=', Auth::instance()->get_user()->id)
                                ->find()
                                ->values($date)
                                ->save();


                        echo 'Пароль успешно изменен';
                       }
                      catch (ORM_Validation_Exception $e)
                      {
                          echo 'Ошибка при изменении пароля';
                      }
                }
                else 
                {
                    echo 'Ошибка при изменении пароля. Неправильно введен старый пароль';
                }
                
            }
            else
            {
                echo 'Ошибка при изменении пароля.Новый пароль не соотвествует требованиям';
                
            }
                        
      }
    
    }
    
    public function action_oldcheckpassword(){
        
        if($this->request->is_ajax())
        {
             $objt = Validation::factory($this->request->post());
             
              $objt->rule(TRUE, 'not_empty')
                    ->rule(TRUE, 'min_length', array(':value', '2'))
                    ->rule(TRUE, 'max_length', array(':value', '16'));        
                    
                    
      
    
            if($objt->check())  
            {
                 $oldpassdb= Auth::instance()->get_user()->password;
                
           
                $oldpassuser = Auth::instance()->hash_password($this->request->post('old'));
                
                if($oldpassdb===$oldpassuser)
                {
                    echo "true";
                }
                else 
                {
                   echo "false";
                }
            }
            else
            {
                echo 'Error!';
            }
             
        }
        
    }
    
    
   
    public function action_checkemail(){
        
        if($this->request->is_ajax())
        {
            $objt = Validation::factory($this->request->post());
            
            $objt->rule(TRUE, 'not_empty')
                    ->rule(TRUE, 'min_length', array(':value', '5'))
                    ->rule(TRUE, 'max_length', array(':value', '40'))
                    ->rule('newemail2', 'matches', array(':validation','newemail2','newemail'));
            
            if($objt->check())
            {
                $old_email_db = Auth::instance()->get_user()->email;
                
                $old_email_user = $this->request->post('old');
                
                if($old_email_db==$old_email_user)
                {
                    
                    try {

                      $date = array('email'=>$this->request->post('newemail'));
                      
                      $u = ORM::factory('user')->where('id', '=', Auth::instance()->get_user()->id)
                                ->find()
                                ->values($date)
                                ->save();


                        echo 'Email успешно изменен';

                    } 
                    catch (ORM_Validation_Exception $e) 
                    {

                        echo 'Ошибка при изменении email';;

                    }
                }
                else
                {
                    echo 'Ошибка при изменении email. Неправильно введен старый email';
                }
            }
            else
            {
              echo 'Ошибка при изменении email.Новый email не соотвествует требованиям для email';
            }
            
            
        }
        
    }
    
    
        public function action_oldcheckemail(){
        
        if($this->request->is_ajax())
        {
            $objt = Validation::factory($this->request->post());
            
             $objt->rule(TRUE, 'not_empty')
                    ->rule(TRUE, 'min_length', array(':value', '5'))
                    ->rule(TRUE, 'max_length', array(':value', '40'));
             
             if($objt->check())
             {
                 
                 $old_email_db = Auth::instance()->get_user()->email;
                 
                 $old_email_user = $this->request->post('old');
                 
                 
                 if($old_email_db ==$old_email_user)
                 {
                     echo "true";
                 }
                else 
                {
                    echo "false";
                    
                }
                     
                 
             }
            else 
            {
                 echo 'Error!';
            }
             
        }
        
        
    }

}
