<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Articles extends Controller_Common
{
   
    public function action_review()
     {
        
        $section = $this->request->param('section');
        
        $count = $this->request->param('count');
                       
        $page = $this->request->param('page');
  
        $ads = array();
        
        $content =  View::factory('page/review')
                ->bind('ads', $ads)
                ->bind('pagination', $pagination);
                //->bind('count', $count);

         $total_items = Model::factory('Ads')->get_count($section);//общее количество элементов

         if($_POST)
         {
            $_POST = Arr::map('trim', $_POST);
            $count = $_POST['count_articles']; 
            
         }
         
        $strt_page = ($page-1)*$count;
        
        if($strt_page>$total_items)
             $strt_page = 0;
        
        
        $pagination = Pagination::factory(array('total_items'=>$total_items,'items_per_page' =>$count))
                ->route_params(array(
                'controller' => Request::current()->controller(),
                'action' => Request::current()->action(),
                'section'=>$this->request->param('section'),
                'count'=>$count,  
                ));
     
        $ads =  Model::factory('Ads')->get_arcticles($section,$strt_page,$count);
 
        $this->template->content=$content;

    }

    public function action_fullarticle()
    {
        
        $id = $this->request->param('id');
        
        $section = $this->request->param('section');
        
        $ads = array();
        
        $content =  View::factory('page/fullarcticle')
                ->bind('ads', $ads);
        
        
          $ads =  Model::factory('Ads')->get_arcticle($id,$section);
        
        
        $this->template->content=$content;
    }

    
}
