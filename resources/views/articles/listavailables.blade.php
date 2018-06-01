<?php 
      $varCategoriesA =  "";
      $numComasA = 0;
?> 
      @foreach($available->categories as $categorys)            
                  <?php 
                        if($numComasA == 0){
                              $varCategoriesA = $varCategoriesA . "$categorys->category";
                        }
                        else{
                              $varCategoriesA = $varCategoriesA. "," . "$categorys->category";
                        }
                        $numComasA++; 
                  ?>   
                                                                  
      @endforeach
                   
                    
      <div class="row putBorder rowavailables" data-id="{{$available->id}}" data-pos="{{$available->order}}" data-categories="{{$varCategoriesA}}" data-image="{{$available->image->id}}" data-imagename="{{$available->image->name}}">                                                                                                               
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                  <div style="float:left; margin-right: 4%;">
                        <div style="background-image: url(http://localhost:7070/Jueves/public/getImage/{{$available->id}}/listavailables);" class="imageStyle"></div>
                  </div>
                        
                  <b id="available_title">{{ $available->title }}</b> <small>From </small> <small id="available_startdate">{{ $available->start_date }}</small> <small> to </small> <small id="available_expiredate"> {{ $available->expire_date }}</small>
                                                                  <p id="available_snippet">{{ $available->snippet }} </p>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 btnOptions text-right">
                  <div class="btn-group" style="margin-right: 5%;">
                        <button type="button" class="btn btn-default btn-sm pinAvailable"><span class="icon-pushpin"></span></i>Pin</button>         
                        <button type="button" data-id="{{$posAvailables}}" class="btn btn-default btn-sm editAvailable"><i class="fa fa-fw fa-pencil"></i>Edit</button>
                        <button type="button" data-id="{{$posAvailables}}" class="btn btn-default btn-sm deleteAvailable"><i class="fa fa-fw fa-times-circle"></i>Delete</button>
                  </div>                    
                  
                  <div class="btn-group-vertical" style="margin-right: 5%;">
                        <button type="submit" class="btn upArticleAvailable"><i class="fa fa-fw fa-caret-up"></i></button>
                        <button type="button" class="btn downArticleAvailable"><i class="fa fa-fw fa-caret-down"></i></button>
                  </div>
            </div>                                 
      </div>  
                                                                                                             