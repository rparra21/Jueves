<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Support\Facades\Response;
use DateTime;

class WidgetController extends Controller
{
    //
    public function index(){
        
        $categories = Category::all();
        return view("widget/setup")
                    ->with('categories', $categories);
    }

    public function getWidget($snippets, $quantity, $categories = ""){

        header("Access-Control-Allow-Origin: *");
        $host = gethostname();
        $server = $_SERVER['SERVER_NAME'];
        
        $categoriesSelected[] = "";

        $now = new DateTime();
      
        //if categories parameter is null should show allcategories, less filter
        if($categories == ""){
            $first = Article::where([['start_date', '<=' , $now], ['expire_date', '>=', $now]])->orWhere(function ($q) {
                                        $q->whereNull('start_date')->whereNull('expire_date');
                                        });
  

            $second = Article::where([['start_date', '<=' , $now], ['expire_date', '>=', $now]])->orWhere(function ($q) use($now){
                                        $q->whereNull('start_date')->where('expire_date', '>=', $now); 
                                        }); 

            $third = Article::where([['start_date', '<=' , $now], ['expire_date', '>=', $now]])->orWhere(function ($q) use($now){
                                            $q->whereNull('expire_date')->where('start_date', '<=', $now);
                                            }); 
          
            $articles = $first->union($second)->union($third)->orderBy('pinned', 'desc')
                                        ->orderBy('order', 'desc')
                                        ->take($quantity)->get(); 
                                          
        }
        //else categories parameter has some category should show the articles belongs that categories 
        else{
            $categorys = explode(",", $categories);
                for($i = 0; $i < count($categorys); ++$i){
                    $categoriesSelected[$i] = $categorys[$i];
                }
                               
                $first = Article::whereHas('categories', function($q) use ($categoriesSelected)
                        {
                                $q->whereIn('category', $categoriesSelected);
                        })->where([['start_date', '<=' , $now], ['expire_date', '>=', $now]]);
                 
                                         
                $second = Article::whereHas('categories', function($q) use ($categoriesSelected)
                        {
                            $q->whereIn('category', $categoriesSelected);
                        })->whereNull('start_date')->whereNull('expire_date');    
                
                $third = Article::whereHas('categories', function($q) use ($categoriesSelected)
                        {
                            $q->whereIn('category', $categoriesSelected);
                        })->whereNull('start_date')->where('expire_date', '>=', $now); 
                
                $fourth = Article::whereHas('categories', function($q) use ($categoriesSelected)
                        {
                            $q->whereIn('category', $categoriesSelected);
                        })->whereNull('expire_date')->where('start_date', '<=', $now);  

                $articles = $first->union($second)->union($third)->union($fourth)->orderBy('pinned', 'desc')
                                          ->orderBy('order', 'desc')
                                          ->take($quantity)->get(); 
            }
        


                
            
            
            
            
            
            
            
            
            
            
            
            
            //->where('start_date', '<=' , $now)

                 //->where([['start_date', '<=' , $now], ['expire_date', '>', $now]])

               //  where([ ['name' , '=', $request->update_image_name],
                 //                              ['article_id', '=', $request->update_id ] 
                   //                         ])
        //$articles = Article::with('categories');
        
        /*********************************/
        /*header("Access-Control-Allow-Origin: *");
        $articles = Article::with('categories')
                            ->orderBy('order', 'desc')
                            ->take($quantity)
                            ->get();
        
        $host = gethostname();
        $server = $_SERVER['SERVER_NAME'];*/
         /*********************************/

        //$posts = Article::has('categories')->get();          
        /*$posts = Article::whereHas('categories', function($q)
        {
            $q->where('category', '=', 'sports');

        })->get();*/

        /*if($categories == ""){
            $articles = Article::where([['start_date', '<=' , $now], ['expire_date', '>=', $now]])
                                ->orderBy('pinned', 'desc')
                                ->orderBy('order', 'desc')
                                ->take($quantity)->get();
        }*/

        return view("widget.widget")
                    ->with('articles', $articles)
                    ->with('snippets', $snippets)
                    ->with('server', $server)
                    ->with('now', $now);
    }


    public function widgetAll(){
        $html = "<div> <h1>HOla mundo </h1> </div>";
        header("Access-Control-Allow-Origin: *");
        return $html;
    }
}
