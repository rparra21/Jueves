@extends('layouts.master')
@section('estilos')
<link href="{{ asset('css/articles.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('jQuery-tagEditor-master/jquery.tag-editor.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@endsection
@section('title-content','Articles')
@section('breadcrumb','Articles')
@section('content')
@include('articles.search')
@include('articles.create')
@include('articles.update')

<div class="container">

      <div class="row mb-12">
            <div class="col-sm-7">
                  <h5 id="headingPinneds">@if(count($pinneds) > 0)
                          Pinned Articles
                      @else
                          There are no articles pinned    
                      @endif
                  </h5>
            </div>
      </div>
      <?php $posPinned=0 ?>
            <div id="divpinneds">  
                  <?php $posPinned = 1 ?>
                  @foreach($pinneds as $pinned)                           
                        @include('articles.listpinneds')
                  <?php $posPinned++ ?>
                  @endforeach
            </div>

      </br>      
</br>

      <div class="row mb-12">
            <div class="col-sm-7">
                  <h5 id="headingAvailables">@if(count($availables) > 0)
                          Available Articles
                      @else
                          There are no articles available    
                      @endif
                  </h5>
            </div>
            <div class="col-sm-2">
                  <button type="button" class="btn" onclick="openModal()">Add New Article</button>
            </div>
            <div class="col-sm-3">
                  <button type="button" class="btn" data-toggle="modal" data-target="#modalSearch">Search Previous Article</button>
            </div>
      </div>
</br>

            <div id="divavailables">
                  <?php $posAvailables = 1 ?>
                  @foreach($availables as $available)

                        @include('articles.listavailables')
                  <?php $posAvailables++ ?>
                  @endforeach
            </div>
</div>      

@endsection

@section('script')


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="{{asset('jQuery-tagEditor-master/jquery.tag-editor.min.js')}}"></script>

<script src="{{asset('jQuery-tagEditor-master/jquery.caret.min.js')}}"></script>

<!-- 
<script src="{{asset('js/validator.min.js')}}"></script>

<script src="http://malsup.github.com/jquery.form.js"></script> -->
 
<script>
/******* OPEN DOCUMENT READY *********/
  $(document).ready(function(){   
     
     idRemove = "";
     divRemove = "";

     //variables to update article
     id_article_update = 0;
     id_image_update = 0;
     image_name_update = ""; 
     //***************/

     //variables to update article
     id_article_delete = 0;

      //change image when update
      $('#image_update').change(function() {
            $("#previewImage").hide();
      });

      $("#errorCategories").hide();

//Update article
$("#formUpdate").on('submit',function(e) {
      
            var id = $("#update_id").val();
            resultupdate = [];
            console.log("update");
            e.preventDefault();
            $.ajax({

                        dataType: 'json',
                        type:'POST',
                        url: "updatePost/"+id_article_update+"/"+id_image_update+"/"+image_name_update,
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                  success: function(data){
                        console.log(data);
                                          for(var a in data){               
                                                resultupdate.push(data[a]);                                              
                                          }     
                                          console.log(resultupdate[0]);
                                          
                                                if(divRemove == "rowavailables")
                                                {
                                                      $(".rowavailables").each(function() {
                                                            var mydata = $(this).data('id');
                                                                  if(mydata == id_article_update){
                                                                        console.log("entra eliminar");
                                                                        $(this).remove();
                                                                  }                                                                  
                                                      }); 
                                                }
                                                else
                                                {
                                                      $(".rowpinneds").each(function() {
                                                            var mydata = $(this).data('id');
                                                                  if(mydata == id_article_update){
                                                                        console.log("entra eliminar");
                                                                        $(this).remove();
                                                                  }                                                                  
                                                      });  
                                                      
                                                }
                                    
                                                if(resultupdate[1] == 1)
                                                {                     
                                                      $("#divpinneds").prepend(resultupdate[0]);    
                                                      $('#headingPinneds').text("Pinned Articles"); 
                                                }
                                                else{                                                
                                                      $("#divavailables").prepend(resultupdate[0]);
                                                      $('#headingAvailables').text("Available Articles"); 
                                                }
                                          
                                          swal(
                                                'Good job!',
                                                'Article Updated!',
                                                'success'
                                                )  
                                                
                                          $('#formUpdate')[0].reset();
                                          $('#modalUpdate').modal('hide');

                        },
                        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                              console.log("error");  
                              //var messageError = jqXHR["responseJSON"].errors.image[0].toString();  
                              swal({
                                    type: 'error',
                                    title: 'The given data was invalid.',
                                    text: 'Use .jpg, .jpeg, .png extensions. The dimensions should be less than 3000 x 3000. The image may not be greater than 2 MB',
                                    })               
                              //console.log(json[message]);                      
                              console.log(JSON.stringify(jqXHR));
                              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
      });
});

//Add article
$("#formAdd").on('submit',function(e) {
      
      var bootstrapValidator = $("#formAdd").data('bootstrapValidator');
      bootstrapValidator.validate();

   if(bootstrapValidator.isValid())
     {
      var tags = $('#categories').tagEditor('getTags')[0].tags;
      
           if(tags.length > 0){

                  $("#errorCategories").hide();
                  resulthtml = [];
                  console.log("entra");
                  e.preventDefault();
                        $.ajax({
                                    dataType: 'json',
                                    type:'POST',
                                    url: "article",
                                    data: new FormData(this),
                                    contentType: false,
                                    processData: false,
                              success: function(data){
                                          console.log(data);
                                                for(var a in data){               
                                                      resulthtml.push(data[a]);                                              
                                                }     
                                          
                                                $('#formAdd')[0].reset();
                                                //$('#modalAdd').modal('show');
                                                
                                                var tags = $('#categories').tagEditor('getTags')[0].tags;
                                                for (i = 0; i < tags.length; i++) { $('#categories').tagEditor('removeTag', tags[i]); }
                                                $('#categories').tagEditor('destroy');
                                                tagsInputCreate();
                                                console.log(resulthtml[0]);
                                                
                                                if(resulthtml[1] == 1)
                                                {                     
                                                      $("#divpinneds").prepend(resulthtml[0]);    
                                                      $('#headingPinneds').text("Pinned Articles");                                  
                                                }
                                                else{
                                                      $("#divavailables").prepend(resulthtml[0]);
                                                      $('#headingAvailables').text("Available Articles"); 
                                                }

                                                swal(
                                                      'Good job!',
                                                      'Article Created!',
                                                      'success'
                                                      )                             
                              },
                              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                    console.log("error");  
                                    //var messageError = jqXHR["responseJSON"].errors.image[0].toString();  
                                    swal({
                                          type: 'error',
                                          title: 'The given data was invalid.',
                                          text: 'Use .jpg, .jpeg, .png extensions. The dimensions should be less than 3000 x 3000. The image may not be greater than 2 MB',
                                          })               
                                    //console.log(json[message]);                      
                                    console.log(JSON.stringify(jqXHR));
                                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              }
                  });
            }
            else{
                  $("#errorCategories").show();
            }
      }
     else 
     {
          console.log("validator deteniendo");
          return; 
     }
      
});

});

/******* CLOSE DOCUMENT READY *********/

$('#formAdd').bootstrapValidator({
 
            fields: {
            url: {
                  validators: {
                        notEmpty: {
                              message: 'The url address is required and cannot be empty'
                        },
                        uri: {
                              message: 'The url address is not valid'
                        }
                  }
            },
            title: {
                  validators: {
                        notEmpty: {
                              message: 'The title is required and cannot be empty'
                        }
                  }
            },
            snippet: {
                  validators: {
                        notEmpty: {
                              message: 'The snippet is required and cannot be empty'
                        }
                  }
            },
            image: {
                  validators: {
                        notEmpty: {
                              message: 'The image is required and cannot be empty'
                        }
                  }
            }
                     
     }
 })

/******* ACTIONS MODAL CLOSE *********/
$("#modalUpdate").on('hidden.bs.modal', function () {
      var tags = $('#categories_update').tagEditor('getTags')[0].tags;
      for (i = 0; i < tags.length; i++) { $('#categories_update').tagEditor('removeTag', tags[i]); }
            $('#categories_update').tagEditor('destroy');
            console.log("Esta accion se ejecuta al cerrar el modal");
            $('#formUpdate')[0].reset();
    });        

$("#modalAdd").on('hidden.bs.modal', function () {
      var tags = $('#categories').tagEditor('getTags')[0].tags;
      for (i = 0; i < tags.length; i++) { $('#categories').tagEditor('removeTag', tags[i]); }
            $('#categories').tagEditor('destroy');
      
      $("#errorCategories").hide();
            //console.log("Esta accion se ejecuta al cerrar el modal");
});
/******* ACTIONS MODAL CLOSE *********/

/******* CREATE TAGS INPUT *********/
function tagsInputCreate(){
         getAll();
      $('#categories').tagEditor({      
      
      autocomplete: { 
        delay: 0, // show suggestions immediately
        position: { collision: 'flip' }, // automatic menu position up/down
        source: resultado
      }   
});   
}

function tagsInputUpdate(){
          getAll();
      $('#categories_update').tagEditor({      
      
      autocomplete: { 
        delay: 0, // show suggestions immediately
        position: { collision: 'flip' }, // automatic menu position up/down
        source: resultado
      }   
});
}
/******* CREATE TAGS INPUT *********/

/************* DISABLED *************/
function editModal(position, url ,title, snippet, pinnedcheck, pinned_id, startdate, expiredate, image_id, image_name, category){
      var categories = "";
      var numComas = 0;
      $("#url_update").val(url);
      $("#title_update").val(title);
      $("#snippet_update").val(snippet);
      $("#title_update").val(title);
      $("#pinned_update").prop("checked", pinnedcheck);
      $("#update_id").val(pinned_id);
      $("#update_image_id").val(image_id);
      $("#update_image_name").val(image_name);

      //console.log(category);
      tagsInputUpdate();
      $('#categories_update').tagEditor('addTag', category);
      
      $('#modalUpdate').modal('show');      
      
}
/************* DISABLED *************/

//Update article
$("#divpinneds").on("click", ".editPinned", function() {         
      //console.log($(".rowpinneds:nth-child($(this).attr('value')) #pinnedtitle").text());

      idRemove = $(this).parent().parent().parent().data('id');            
      divRemove = "rowpinneds";

      id_article_update = $(this).parent().parent().parent().data('id');
      id_image_update = $(this).parent().parent().parent().data('image');
      image_name_update = $(this).parent().parent().parent().data('imagename'); 
      var categories = $(this).parent().parent().parent().data('categories');

      var date = $(this).parent().parent().parent().data('pos');
      var timestamp = new Date(date).getTime();


      console.log(id_article_update);
                        
                  $.ajax({
                              headers: {
                                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),                                             
                                          },
                              dataType: 'json',
                              type:'GET',
                              url: "article/"+id_article_update,
                        
                              success: function(data){
                                          console.log(data);
                                          console.log(data.url)
                                          $("#url_update").val(data.url);
                                          $("#title_update").val(data.title);
                                          $("#snippet_update").val(data.snippet);
                                          $("#pinned_update").prop("checked", 1);
                                          $("#startdate_update").val(data.start_date);
                                          $("#expiredate_update").val(data.expire_date);
                                          $("#previewImage").show();
                                          $("#previewImage").css('background-image', 'url(/getImage/'+id_article_update+'/'+timestamp+')');
                                          tagsInputUpdate();
                                          $('#categories_update').tagEditor('addTag', categories);
                                          $('#modalUpdate').modal('show');
                                    },
                              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                          console.log("error");                  
                                          console.log(JSON.stringify(jqXHR));
                                          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                    }
                        });  
  
/*
      var url = $("#divpinneds .rowpinneds:nth-child("+position+")  #pinned_url").val();     
      var title = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_title").text(); 
      var snippet = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_snippet").text();
      var pinnedcheck = 1;            
      var startdate = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_startdate").text();
      var expiredate = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_expiredate").text();      
      var pinned_id = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_id").val();
      var pinned_image_id = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_image_id").val();
      var pinned_image_name = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_image_name").val();
      var pinned_category = $("#divpinneds .rowpinneds:nth-child("+position+") #pinned_category").val();

      //var categories = $(".rowpinneds:nth-child("+position+") #pinned_category").val();
     
      //console.log(url + " " + title + " " + pinned_id + " " +startdate + " " + expiredate + " " + snippet);
      editModal(position, url, title, snippet, pinnedcheck, pinned_id, startdate, expiredate, pinned_image_id, pinned_image_name, pinned_category);
   */   

});

//Update article
$("#divavailables").on("click", ".editAvailable", function() {        
      //console.log($(".rowpinneds:nth-child($(this).attr('value')) #pinnedtitle").text());

      idRemove = $(this).parent().parent().parent().data('id');
      divRemove = "rowavailables";            
 
      id_article_update = $(this).parent().parent().parent().data('id');
      id_image_update = $(this).parent().parent().parent().data('image');
      image_name_update = $(this).parent().parent().parent().data('imagename'); 
      var categories = $(this).parent().parent().parent().data('categories');

      var date = $(this).parent().parent().parent().data('pos');
      var timestamp = new Date(date).getTime();

      console.log(id_article_update);
                         
                  $.ajax({
                              headers: {
                                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),                                             
                                          },
                              dataType: 'json',
                              type:'GET',
                              url: "article/"+id_article_update,
                        
                        success: function(data){
                                    console.log(data);
                                    console.log(data.url)
                                    $("#url_update").val(data.url);
                                    $("#title_update").val(data.title);
                                    $("#snippet_update").val(data.snippet);
                                    $("#pinned_update").prop("checked", 0);
                                    $("#startdate_update").val(data.start_date);
                                    $("#expiredate_update").val(data.expire_date);
                                    $("#previewImage").show();
                                    $("#previewImage").css('background-image', 'url(/getImage/'+id_article_update+'/'+timestamp+')');
                                    tagsInputUpdate();
                                    $('#categories_update').tagEditor('addTag', categories);
                                    $('#modalUpdate').modal('show');
                              },
                        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                    console.log("error");                  
                                    console.log(JSON.stringify(jqXHR));
                                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              }
                  });  

/*
      var url = $("#divavailables .rowavailables:nth-child("+position+") #available_url").val();     
      var title = $("#divavailables .rowavailables:nth-child("+position+") #available_title").text(); 
      var snippet = $("#divavailables .rowavailables:nth-child("+position+") #available_snippet ").text();
      var pinnedcheck = 0;            
      var startdate = $("#divavailables .rowavailables:nth-child("+position+") #available_startdate").text();
      var expiredate = $("#divavailables .rowavailables:nth-child("+position+") #available_expiredate").text();      
      var available_id = $("#divavailables .rowavailables:nth-child("+position+") #available_id").val();
      var available_image_id = $("#divavailables .rowavailables:nth-child("+position+") #available_image_id").val();
      var available_image_name = $("#divavailables .rowavailables:nth-child("+position+") #available_image_name").val();
      var available_category = $("#divavailables .rowavailables:nth-child("+position+") #available_category").val();
     

      console.log(url + " " + title + " " + available_id + " " +startdate + " " + expiredate + " " + snippet);
      editModal(position, url, title, snippet, pinnedcheck, available_id, startdate, expiredate, available_image_id, available_image_name, available_category);
*/
});

//Delete article
$("#divpinneds").on("click", ".deletePinned", function(e) {  
      
      id_article_delete = $(this).parent().parent().parent().data('id');
      divRemove = "rowpinneds";
     
      console.log(id_article_delete);

      swal({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                  if (result.value) {
                    
                        e.preventDefault();
                                    $.ajax({
                                                headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),                                             
                                                         },
                                                dataType: 'json',
                                                type:'DELETE',
                                                url: "article/"+id_article_delete,
                                          
                                          success: function(data){
                                                                  if(divRemove == "rowavailables")
                                                                  {
                                                                        $(".rowavailables").each(function() {
                                                                              var mydata = $(this).data('id');
                                                                              if(mydata == id_article_delete){
                                                                                          console.log("entra eliminar");
                                                                                          $(this).remove();
                                                                              }                                                                  
                                                                        }); 
                                                                  }
                                                                  else
                                                                  {
                                                                        $(".rowpinneds").each(function() {
                                                                              var mydata = $(this).data('id');
                                                                              if(mydata == id_article_delete){
                                                                                    console.log("entra eliminar");
                                                                                    $(this).remove();
                                                                              }                                                                  
                                                                        }); 
                                                                  }
                                                            swal(
                                                            'Deleted!',
                                                            'Your file has been deleted.',
                                                            'success'
                                                            )

                                                },
                                                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                                      console.log("error");                  
                                                      console.log(JSON.stringify(jqXHR));
                                                      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                                }
                                          });      

                  }
      })
      console.log("delete p");
});

//Delete article
$("#divavailables").on("click", ".deleteAvailable", function(e) {  

      id_article_delete = $(this).parent().parent().parent().data('id');
      divRemove = "rowavailables";
     
      console.log(id_article_delete);

      swal({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                  if (result.value) {

                        e.preventDefault();
                                    $.ajax({
                                                headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),                                             
                                                         },
                                                dataType: 'json',
                                                type:'DELETE',
                                                url: "article/"+id_article_delete,
                                          
                                          success: function(data){
                                                                  if(divRemove == "rowavailables")
                                                                  {
                                                                        $(".rowavailables").each(function() {
                                                                              var mydata = $(this).data('id');
                                                                              if(mydata == id_article_delete){
                                                                                          console.log("entra eliminar");
                                                                                          $(this).remove();
                                                                              }                                                                  
                                                                        }); 
                                                                  }
                                                                  else
                                                                  {
                                                                        $(".rowpinneds").each(function() {
                                                                              var mydata = $(this).data('id');
                                                                              if(mydata == id_article_delete){
                                                                                    console.log("entra eliminar");
                                                                                    $(this).remove();
                                                                              }                                                                  
                                                                        }); 
                                                                  }
                                                            swal(
                                                            'Deleted!',
                                                            'Your file has been deleted.',
                                                            'success'
                                                            )

                                                },
                                                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                                      console.log("error");                  
                                                      console.log(JSON.stringify(jqXHR));
                                                      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                                }
                                          });  
                  }
      })
      console.log("delete a");
});

$("#divpinneds").on("click", ".unPinned", function(e) {  

      id_div_move = $(this).parent().parent().parent().data('id');

            $(".rowpinneds").each(function() {
                  var mydata = $(this).data('id');
                  if(mydata == id_div_move){
                        console.log("entra eliminar");
                        $(this).remove();
                        }                                                                  
            }); 

      console.log("unpin");
});  

$("#divavailables").on("click", ".pinAvailable", function(e) {  
      id_div_move = $(this).parent().parent().parent().data('id');

            $(".rowavailables").each(function() {
                  var mydata = $(this).data('id');
                  if(mydata == id_div_move){
                              console.log("entra eliminar");
                              $(this).remove();
                        }                                                                  
            }); 
      console.log("pinnear");
}); 

$("#divpinneds").on("click", ".upArticlePinned", function(e) {  
console.log("upPin");
}); 
$("#divpinneds").on("click", ".downArticlePinned", function(e) {  
      console.log("downPin");
}); 
$("#divavailables").on("click", ".upArticleAvailable", function(e) {  
      console.log("upAva");
}); 
$("#divavailables").on("click", ".downArticleAvailable", function(e) {  
      console.log("downAva");
}); 

function getAll(){

      resultado = [];

      $.ajax({
            dataType: 'json',
            url: 'getall',
      }).done(function(data) {
           
                  for(var a in data){
                  
                  resultado.push(data[a].category);                
            }     
           
      });
}


function openModal(){

      tagsInputCreate();
      $('#modalAdd').modal('show');

}

</script>

@endsection
