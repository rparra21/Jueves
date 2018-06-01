<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Image as Imagen;
use App\Models\Category;
use App\Models\Article_Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\DB;
use DateTime;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index()
    {
        $pinneds = Article::with('categories')
                            ->where('pinned',1)
                            ->orderBy('order', 'desc')
                            ->take(10)
                            ->get();
        $availables = Article::with('categories')->where('pinned',0)
                            ->orderBy('order', 'desc')
                            ->take(10)
                            ->get();

        return view("articles/articles")
                        ->with('pinneds', $pinneds)
                        ->with('availables', $availables);
    }

    public function getall()
    {
        $list =  Category::all();

        return $list;
    }

    public function getImage($id)
    {   
        header("Access-Control-Allow-Origin: *");
        $imagen = Imagen::find($id);
        $article = Article::find($id);

        $pic = Image::make($imagen->image);
        $response = Response::make($pic->encode('jpeg'));
        $response->header('Content-Type','image/jpeg');
        return $response;      
    }

    public function getCategories($article_id)
    {
       // $article = Article::
    }

    /**
     * Store a newly created resource in storage.  /* 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $validatedData = $request->validate([
            'image' => 'required|mimes:jpg,png,jpeg|dimensions:max_width=3000,max_height=3000|max:2000'
        ]);

        if ($request->hasFile('image')){
        
        //Add Article    
        $article = new Article();   
        $article->url = $request->url;
        $article->title = $request->title;
        $article->snippet = $request->snippet;     
        $claseDiv = "";
        $claseEdit = "";
        $claseDelete = "";
        $clasePinnear = "";
        $claseUp = "";
        $claseDown = "";
        $count = 0; 
        $constante = "";    
          
            if ($request->has("pinned")){
                $article->pinned = 1;
                $claseDiv = "rowpinneds";
                $claseEdit = "editPinned";
                $claseDelete = "deletePinned";
                $clasePinnear = "unPinned";
                $claseUp = "upArticlePinned";
                $claseDown = "downArticlePinned";
                $count = Article::where('pinned',1)->count() + 1;    
                $constante = "pinned";                    
            }  
            else{
                $article->pinned = 0;
                $claseDiv = "rowavailables";
                $claseEdit = "editAvailable";
                $claseDelete = "deleteAvailable";
                $clasePinnear = "pinAvailable";
                $claseUp = "upArticleAvailable";
                $claseDown = "downArticleAvailable";
                $count = Article::where('pinned',0)->count() + 1;  
                $constante = "available";              
            }    

        $article->start_date = $request->startdate;
        $article->expire_date = $request->expiredate;
        $now = new DateTime();
        $article->order = $now;
        $article->save();
        $position = $article->id;
        $updated_timestamp = $article->updated_at->getTimestamp();

        //Add Image
        $file = $request->file('image');
        $img = Image::make($file);
        Response::make($img->encode('jpeg'));
        
        $imagenSave = new Imagen();  
        $imagenSave->article_id = $article->id;  
        $imagenSave->name = $file->getClientOriginalName();
        $imagenSave->unique_identifier = uniqid();
        $imagenSave->image = $img;
        $imagenSave->save();              


        $categories = explode(",", $request->categories);
            for($i = 0; $i < count($categories); ++$i)
            {
                        //Add Category
                        $category = new Category();
                        $category = Category::firstOrCreate(
                            ['category' => $categories[$i]], ['description' => $categories[$i]]); 
                        $category->save();
                        
                        //Add Article Category
                        $articlecategory = new Article_Category();
                        $articlecategory->article_id = $article->id;
                        $articlecategory->category_id = $category->id;
                        $articlecategory->save();
            }
        }
         

        $html = "<div class='row putBorder $claseDiv' data-id='$article->id' data-pos='$updated_timestamp' data-categories='$request->categories' data-image='$imagenSave->id' data-imagename='$imagenSave->name'>                                                                                   
                     <div class='col-xs-7 col-sm-7 col-md-7 col-lg-7'>
                        <div style='float:left; margin-right: 4%;'>
                                <div style='background-image: url(getImage/$imagenSave->id/$updated_timestamp);' class='imageStyle'></div>
                        </div>                          
                        <b id='$constante"."_title'>$article->title</b> <small>From </small> <small id='$constante"."_startdate'>$article->start_date</small> <small> to </small> <small id='$constante"."_expiredate'>$article->expire_date</small>
                                                      <p id='$constante"."_snippet'>$article->snippet</p>
                     </div>
                    <div class='col-xs-5 col-sm-5 col-md-5 col-lg-5 btnOptions text-right'>
                        <div class='btn-group' style='margin-right: 5%;'>
                                <button type='button' class='btn btn-default btn-sm $clasePinnear'><span class='icon-pushpin'></span></i>Unpin</button>         
                                <button type='button' data-id=1 class='btn btn-default btn-sm $claseEdit'><i class='fa fa-fw fa-pencil'></i>Edit</button>
                                <button type='button' data-id=1 class='btn btn-default btn-sm $claseDelete'><i class='fa fa-fw fa-times-circle'></i>Delete</button>
                        </div>                    
      
                        <div class='btn-group-vertical' style='margin-right: 5%;'>
                                <button type='button' class='btn $claseUp'><i class='fa fa-fw fa-caret-up'></i></button>
                                <button type='button' class='btn $claseDown'><i class='fa fa-fw fa-caret-down'></i></button>
                        </div>
                    </div>                                 
        </div>";

        return Response::json(array('html' => $html, 'type' => $article->pinned));              
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {    
        $article = Article::find($id);      
        return $article;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePost(Request $request, $id, $id_image, $name_image)
    {
           if ($request->hasFile('image_update')){

                $validatedData = $request->validate([
                    'image_update' => 'required|mimes:jpg,png,jpeg|dimensions:max_width=3000,max_height=3000|max:2000'
                ]);
            }
     
                //update
                //$article = Article::find($request->update_id); 
                $article = Article::find($id); 
   
                $article->url = $request->url_update;
                $article->title = $request->title_update;
                $article->snippet = $request->snippet_update;     
                $claseDiv = "";
                $claseEdit = "";
                $claseDelete = "";
                $clasePinnear = "";
                $claseUp = "";
                $claseDown = "";
                $count = 0; 
                $constante = "";  

                    if ($request->has("pinned_update")){
                        $article->pinned = 1;
                        $claseDiv = "rowpinneds";
                        $claseEdit = "editPinned"; 
                        $claseDelete = "deletePinned";  
                        $clasePinnear = "unPinned";
                        $claseUp = "upArticlePinned";
                        $claseDown = "downArticlePinned";
                        $count = Article::where('pinned',1)->count() + 1;    
                        $constante = "pinned";   
                    }  
                    else{
                        $article->pinned = 0;
                        $claseDiv = "rowavailables";
                        $claseEdit = "editAvailable";
                        $claseDelete = "deleteAvailable";
                        $clasePinnear = "pinAvailable";
                        $claseUp = "upArticleAvailable";
                        $claseDown = "downArticleAvailable";
                        $count = Article::where('pinned',0)->count() + 1;  
                        $constante = "available";  
                    }    
                $article->start_date = $request->startdate_update;
                $article->expire_date = $request->expiredate_update;
                $now = new DateTime();
                $article->order = $now;
                $article->save();
                
                $updated_timestamp = $article->updated_at->getTimestamp();

                //search Image and update Image
                $image_id_return = 0;
                $image_name_return = "";

                if ($request->hasFile('image_update')){
                    $file = $request->file('image_update');
                    $img = Image::make($file);
                    Response::make($img->encode('jpeg'));

                        //$imageSearch = Imagen::find($request->update_image_id);
                        $imageSearch = Imagen::find($id_image);
                        //if($request->update_image_name != $file->getClientOriginalName())
                        if($name_image != $file->getClientOriginalName())
                        {
                            
                            $imageSearch->article_id = $article->id;
                            $imageSearch->name = $file->getClientOriginalName();
                            $imageSearch->unique_identifier = uniqid();
                            $imageSearch->image = $img;
                            $imageSearch->save(); 
                            $image_id_return = $imageSearch->id; 
                            $image_name_return = $imageSearch->name;                      
                        }       
                        else
                        {
                            //$image_id_return = $request->update_image_id; 
                            //$image_name_return = $request->update_image_name; 
                            $image_id_return = $id_image; 
                            $image_name_return = $name_image; 
                        }         
                    }
                    else{
                            $image_id_return = $id_image; 
                            $image_name_return = $name_image; 
                    }
                $parametro = $article->updated_at->getTimestamp();
                //update or create categories  
                //aqui es donde tengo que bretear  
                $list = Article_Category::where('article_id' , '=', $article->id)->get();
                //aqui esta un error
                    foreach($list as $item){
                        $eliminar = Article_Category::find($item->id);
                        $eliminar->delete();
                    }     

                $categories = explode(",", $request->categories_update);
                for($i = 0; $i < count($categories); ++$i)
                {
                            //Add Category
                            $category = new Category();
                            $category = Category::firstOrCreate(
                                ['category' => $categories[$i]], ['description' => $categories[$i]]); 
                            $category->save();
                            
              
                            //Add Article Category
                            $articlecategory = new Article_Category();
                            //$articlecategory = Article_Category::firstOrCreate(
                               // ['article_id' => $article->id], ['category_id' => $category->id ]); 
                            $articlecategory->article_id = $article->id;
                            $articlecategory->category_id = $category->id;
                            $articlecategory->save();
                }
                
            
            
            $html = "<div class='row putBorder $claseDiv' data-id='$article->id' data-pos='$updated_timestamp' data-categories='$request->categories_update' data-image='$image_id_return' data-imagename='$image_name_return'>                                                            
                            <input type='hidden' id='$constante"."_category' value='$request->categories_update'>   
                            <div class='col-xs-7 col-sm-7 col-md-7 col-lg-7'>
                                    <div style='float:left; margin-right: 4%;'>
                                        <div style='background-image: url(getImage/$image_id_return/$updated_timestamp);' class='imageStyle'></div>
                                        
                                    </div>                          
                                    <b id='$constante"."_title'>$article->title</b> <small>From </small> <small id='$constante"."_startdate'>$article->start_date</small> <small> to </small> <small id='$constante"."_expiredate'>$article->expire_date</small>
                                                                <p id='$constante"."_snippet'>$article->snippet</p>
                            </div>
                            <div class='col-xs-5 col-sm-5 col-md-5 col-lg-5 btnOptions text-right'>
                                    <div class='btn-group' style='margin-right: 5%;'>
                                            <button type='button' class='btn btn-default btn-sm $clasePinnear'><span class='icon-pushpin'></span></i> Unpin</button>         
                                            <button type='button' data-id=1 class='btn btn-default btn-sm $claseEdit'><i class='fa fa-fw fa-pencil'></i>Edit</button>
                                            <button type='button' class='btn btn-default btn-sm $claseDelete'><i class='fa fa-fw fa-times-circle'></i>Delete</button>
                                    </div>                    

                                    <div class='btn-group-vertical' style='margin-right: 5%;'>
                                            <button type='button' class='btn $claseUp'><i class='fa fa-fw fa-caret-up'></i></button>
                                            <button type='button' class='btn $claseDown'><i class='fa fa-fw fa-caret-down'></i></button>
                                    </div>
                            </div>                                 
                    </div>";
        return Response::json(array('html' => $html, 'type' => $article->pinned));     
    }


    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    
    public function destroy($id)
    {
        $listCategories = Article_Category::where('article_id' , '=', $id)->delete();
        $listImages = Imagen::where('article_id' , '=', $id)->delete();
                            

        $article = Article::destroy($id); 
        return Response::json(array('html' => 'si', 'type' => 'no', 'article' => $article)); 
    }
}
