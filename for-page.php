<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Forpage extends Controller_Common {
 
    public function action_index()
    {
        
       $captcha = Captcha::instance();
       
       $content = View::factory('/page/give_announcement')
                 ->bind('errors', $errors);
                 
       $content->captcha = $captcha->render(); 
     
       $content->message = '';
       
        if(HTTP_Request::POST == $this->request->method())
        {
          
            
            $_POST = Arr::map('trim', $_POST);
            
            $post = Validation::factory($_POST);
            
            $post ->rule('heading', 'not_empty')
                  ->rule('subheading', 'not_empty')
                  ->rule('nameOds', 'not_empty')
                  ->rule('nameOds', 'min_length',array(':value',2))
                  ->rule('nameOds', 'max_length',array(':value',83))
                  ->rule('message', 'not_empty')
                  ->rule('message', 'min_length',array(':value',2))
                  ->rule('message', 'max_length',array(':value',2000))
                  ->rule('telefon', 'not_empty')
                  ->rule('telefon', 'phone', array(':value',array(2,7,8,10,11)))                 
                  ->rule('email', 'not_empty')
                  ->rule('email', 'email')
                  ->rule('captcha','Tools_Rulevalidationmy::valid_captcha');

            if($post->check())
            {
                
                $image_validate_file = Validation::factory($_FILES)
                                ->rule('uploadImage', 'Upload::valid')
                                ->rule('uploadImage', 'Upload::type', array(':value', array('jpg','jpeg', 'png', 'gif')))
                                ->rule('uploadImage', 'Upload::size',array(':value','2M'));
                                //->rule('photo', 'Upload::image', array(640, 480));
                
                if($image_validate_file->check())
                {
                    $name_img_db='notimage.jpg';
                    
                    if(!empty($_FILES["uploadImage"]["name"]))
                     {
                        
                     $new_name_image = md5($_FILES["uploadImage"]["name"]);
                     
                     $ext_image = explode(".",$_FILES["uploadImage"]["name"]);
                     
                     $ext_image = end($ext_image);
                     
                     $name_img_db = $new_name_image.".".$ext_image;
                     
                     $filename = Upload::save($_FILES['uploadImage'],$name_img_db,'./public/images/upload',0777);
                     
                     $img = Image::factory($filename)
                             ->resize(200,160, Image::HEIGHT)
                             ->save();
                     
                     
                    Image::factory($filename)
                             ->resize(122,87, Image::AUTO)
                             ->save("./public/images/upload/small_img/$name_img_db",0777);
                    
                    }

                    if(Auth::instance()->get_user())
                    {
                       $name_user = Auth::instance()->get_user()->username;
                    }
                    else
                    {
                     $name_user = 'guest';
                    }

                     Model::factory('Ads')->add_ad($name_user,
                                                   $_POST['heading'],                                              
                                                   $_POST['subheading'],
                                                   $_POST['nameOds'], 
                                                   nl2br($_POST['message']),                                              
                                                   $_POST['telefon'],
                                                   $_POST['email'],
                                                   $name_img_db,
                                                   '');
                    
                     $_POST = array();
                     
                }
                else
                    {
                    
                     $errors = $image_validate_file->errors('upload'); 
                    
                    }
            }
            else
                {
                
                $errors=$post->errors('validation');
            }
        }
       
        $this->template->content = $content;
              
        
    }

}
