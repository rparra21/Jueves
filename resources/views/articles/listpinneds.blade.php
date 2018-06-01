<?php 
      $varCategoriesP =  "";
      $numComasP = 0;
?> 
      @foreach($pinned->categories as $categorys)    
                  <?php 
                        if($numComasP == 0){
                              $varCategoriesP = $varCategoriesP . "$categorys->category";
                        }
                        else{
                              $varCategoriesP = $varCategoriesP. "," . "$categorys->category";
                        }
                        $numComasP++; 
                  ?> 
                                                                  
      @endforeach
                
      <div class="row putBorder rowpinneds" data-id="{{$pinned->id}}" data-pos="{{$pinned->order}}" data-categories="{{$varCategoriesP}}" data-image="{{$pinned->image->id}}" data-imagename="{{$pinned->image->name}}">             
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                  <div style="float:left; margin-right: 4%;">
                        <div style="background-image: url(http://localhost:7070/Jueves/public/getImage/{{ $pinned->id }}/listpinneds);" class="imageStyle"></div>
                  </div>
                              
                  <b id="pinned_title">{{ $pinned->title }}</b> <small>From </small> <small id="pinned_startdate">{{ $pinned->start_date }}</small> <small> to </small> <small id="pinned_expiredate">{{ $pinned->expire_date }}</small>
                                                                  <p id="pinned_snippet">{{ $pinned->snippet }} </p>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 btnOptions text-right">
                  <div class="btn-group" style="margin-right: 5%;">
                        <button type="button" class="btn btn-default btn-sm unPinned"><span class="icon-pushpin"></span></i> Unpin</button>         
                        <button type="button" data-id="{{$posPinned}}" class="btn btn-default btn-sm editPinned"><i class="fa fa-fw fa-pencil"></i>Edit</button>
                        <button type="button" data-id="{{$posPinned}}" class="btn btn-default btn-sm deletePinned"><i class="fa fa-fw fa-times-circle"></i>Delete</button>
                  </div>                    
                  
                  <div class="btn-group-vertical" style="margin-right: 5%;">
                        <button type="button" class="btn upArticlePinned"><i class="fa fa-fw fa-caret-up"></i></button>
                        <button type="button" class="btn downArticlePinned"><i class="fa fa-fw fa-caret-down"></i></button>
                  </div>
            </div>                                 
      </div>                               
