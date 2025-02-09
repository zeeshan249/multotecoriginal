@extends('dashboard.layouts.app')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
<style type="text/css">
.fs-marker-outer {
  position: relative;
  width: 1000px;
  height: auto;
}
img.fsimg {
  width: 100%;
  display: block;
  height: auto;
}
img.fsmark:hover {
  cursor: pointer;
}
</style>
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    Add Edit Flowsheet Markers
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allFSs') }}">All Flowsheets</a></li>
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allFSs') }}" class="btn btn-primary"> All Flowsheets</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <span><code>Click on this image, you can create Marker and after that you can drag-drop markers if needed.</code></span>
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <div class="box-header with-border">
          <div class="box-body">
            <div class="fs-marker-outer">
              @if(isset($fsmarkers) && !empty($fsmarkers) && count($fsmarkers) > 0)
                @foreach($fsmarkers as $mrk)

                @if( $mrk->pin_image==1)
                <img src="{{ asset('public/pin.png') }}" style="position: absolute; width: 32px; height: 32px; z-index: 99; left: {{ $mrk->left_pos }}px; top: {{ $mrk->top_pos }}px;" class="fsmark draggable" id="{{ $mrk->id }}" /> 
               @elseif( $mrk->pin_image==2)
               <img src="{{ asset('public/blue_pin.png') }}" style="position: absolute; width: 32px; height: 32px; z-index: 99; left: {{ $mrk->left_pos }}px; top: {{ $mrk->top_pos }}px;" class="fsmark draggable" id="{{ $mrk->id }}" /> 
               @else
               <img src="{{ asset('public/yellow_pin.png') }}" style="position: absolute; width: 32px; height: 32px; z-index: 99; left: {{ $mrk->left_pos }}px; top: {{ $mrk->top_pos }}px;" class="fsmark draggable" id="{{ $mrk->id }}" /> 
                
                @endif


                @endforeach
              @endif
              
              @if( isset($flowsheet) && isset($flowsheet->ImageIds) && count($flowsheet->ImageIds) > 0 )
                @php $i = 0; @endphp
                @foreach( $flowsheet->ImageIds as $imgs )
                @if( $imgs->image_type == 'FS_IMAGE' )
                  @if( isset($imgs->imageInfo) && $i == 0 )
                  <img src="{{ asset('public/uploads/files/media_images/'.$imgs->imageInfo->image) }}" class="fsimg" id="droppable">
                  @php $i++; @endphp
                  @endif
                @endif
                @endforeach
              @endif
            </div>
          </div>
      </div>
    </div>
  </div>

</section>


<!-- Modal -->
<div id="fsMarkModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <form name="fsinfoFRM" id="fsinfoFRM" action="@if(isset($flowsheet)){{ route('adedMarkSV', array('id' => $flowsheet->id)) }}@endif" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><img src="{{ asset('public/pin.png') }}" style="width: 30px; height: 30px;"> Marker Informations</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">


           
              <!--
                 <div class="form-group">
                <label>Default Language :</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    @if( isset($language_id) && isset($languages) && !empty($languages) )
                      @foreach( $languages as $lng )
                        @if( $lng->id ==  $language_id )
                          <img src="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}" style="height: 20px;" id="flag">
                        @endif
                      @endforeach
                    @endif
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                  @if(isset($language_id) && isset($languages) && !empty($languages) )
                    @foreach( $languages as $lng )
                      @if( $lng->id ==  $language_id )
                        <option value="{{ $lng->id }}">{{ $lng->name }}</option>
                      @endif
                    @endforeach
                  @endif
                  </select>
                </div>
              </div> -->


              <div class="form-group">
                <label>Pin Image : <em>*</em></label>
                <input type="radio" id="red_image" name="pin_image" value="1" checked="checked">  <img src="{{ asset('public/pin.png') }}" style="width: 32px; height: 32px;" /> 
               
                <input type="radio" id="blue_image" name="pin_image" value="2">  <img src="{{ asset('public/blue_pin.png') }}" style="width: 32px; height: 32px;" /> 
             
                <input type="radio" id="yellow_image" name="pin_image" value="3">  <img src="{{ asset('public/yellow_pin.png') }}" style="width: 32px; height: 32px;" /> 
             
              </div>
            


            <div class="form-group">
              <label>Title: <em>*</em></label>
              <input type="text" name="name" id="name" class="form-control"> 
            </div>
            <div class="form-group">
              <textarea name="page_content" id="pg_cont" class="form-control" data-error-container="#pgcont_error"></textarea> 
              <div id="pgcont_error"></div>
            </div>
            <div class="form-group">
              <input type="file" name="imgx" id="imgx" accept="image/*">
              <img id="dispImg" style="margin-top: 5px; width: 100px; height: 75px; display: none;">
              <a id="delMrkImg" href="javascript:void(0);" class="btn btn-danger btn-xs" style="display: none;">Delete Image</a>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-6"><div id="ari-mag" style="font-weight: 600;"></div></div>
          <div class="col-md-6">
            <input type="submit" class="btn btn-primary" value="Set Marker">
            <input type="button" id="delMarker" class="btn btn-danger" value="Delete Marker">
            <input type="hidden" name="left_pos" id="left_pos">
            <input type="hidden" name="top_pos" id="top_pos">
            <input type="hidden" name="marker_id" id="marker_id" value="0">
            <input type="hidden" name="flowsheet_id" id="flowsheet_id" value="{{ $flowsheet->id }}">
          </div>
        </div>
      </div>
      </form>
    </div>

  </div>
</div>

<div id="arinfo" style="position: fixed; top: 300px; right: 15px; font-weight: 700;"></div>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>


<script type="text/javascript">
$( function() { 

  $('.fsimg').on('click', function(e) { 
    
    var offset = $(this).offset();    
    var leftPosition = (e.pageX - offset.left) - 16;
    var topPosition = (e.pageY - offset.top) - 32;

    $('#left_pos').val(leftPosition);
    $('#top_pos').val(topPosition);
    CKEDITOR.instances[ 'pg_cont' ].setData( '' );
    $('#name').val('');
    $('#dispImg').hide();
    $('#delMrkImg').hide();

    $('#arinfo').html('Please wait, Popup Coming..');
    $('#ari-mag').html('');

    $('#fsMarkModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    
    $('#arinfo').html('');


  } );

  $('body').on('click', '.fsmark', function() { 
      var _getID = $(this).attr('id');
      $.ajax({
        type : "POST",
        url : "{{ route('getMkInfo') }}",
        data : {
          'id' : _getID,
          '_token' : "{{ csrf_token() }}"
        },
        cache: false,
        beforeSend: function() {
          $('#arinfo').html('Please wait, Popup Coming..');
        },
        success: function(rtnJsn) {
          var obj = JSON.parse( rtnJsn );
          var _name = obj.name;
          var _page_content = obj.page_content;
          var _marker_id = obj.id;
          var _left_pos = obj.left_pos;
          var _top_pos = obj.top_pos;
          var _dispImg = obj.imgx;
          var _pin_image = obj.pin_image;

          $('#name').val(_name);
          $('#marker_id').val(_marker_id);
          $('#left_pos').val(_left_pos);
          $('#top_pos').val(_top_pos);


      

          if(_pin_image==1){
              $('#red_image').attr('checked', 'checked');
            }
            else  if(_pin_image==2){
              $('#blue_image').attr('checked', 'checked');
            }
            else if(_pin_image==3){
              $('#yellow_image').attr('checked', 'checked');
            }



          if( _dispImg != '' ) {
            $('#dispImg').attr('src', _dispImg).show();
            $('#delMrkImg').removeAttr('disabled').show();
          }

          CKEDITOR.instances[ 'pg_cont' ].setData( _page_content );
          $('#arinfo').html('');
          $('#ari-mag').html('');

          $('#fsMarkModal').modal({
            backdrop: 'static',
            keyboard: false
          });
        }
      });
  } );

  $('#delMarker').on('click', function() { 
    if(confirm('Sure To Delete This Marker ? ')) {
      var marker_id = $('#marker_id').val();
      if(marker_id != '0' && marker_id != '' && marker_id != 'undefined') {
        $.ajax({
          type : "POST",
          url : "{{ route('delMkInfo') }}",
          data : {
            'id' : marker_id,
            '_token' : "{{ csrf_token() }}"
          },
          cache: false,
          beforeSend: function() {

          },
          success: function(sts) {
            if(sts == 'OK') {
              $('#fsMarkModal').modal('hide');
              $( 'img#' + marker_id ).remove();
            }
          }
        })
      } 
    }
  } );

  $('#delMrkImg').on('click', function() { 
    if(confirm('Sure To Delete This Image ?')) {
      var marker_id = $('#marker_id').val();
      if(marker_id != '0' && marker_id != '' && marker_id != 'undefined') {
        $.ajax({
          type : "POST",
          url : "{{ route('delMkImg') }}",
          data : {
            'id' : marker_id,
            '_token' : "{{ csrf_token() }}"
          },
          cache: false,
          beforeSend: function() {
            $('#delMrkImg').attr('disabled', 'disabled');
          },
          success: function(sts) {
            if(sts == 'OK') {
              $('#dispImg').attr('src', '').hide();
              $('#delMrkImg').removeAttr('disabled').hide();
            }
          }
        })
      } 
    }
  } );

} );
</script>

<script>
$( function() {
  $( ".draggable" ).draggable();
  $( "#droppable" ).droppable({
    drop: function( e, ui ) {
      var _dd_offset = $(this).offset();
      var _dd_leftPosition = (ui.offset.left - _dd_offset.left);
      var _dd_topPosition = (ui.offset.top - _dd_offset.top);
      var _dd_id = ui.draggable.attr('id'); 
      if( _dd_id != '' && _dd_id != 'undefined') {
        $.ajax({
          type : "POST",
          url : "{{ route('getMkInfo') }}",
          data : {
            'id' : _dd_id,
            '_token' : "{{ csrf_token() }}"
          },
          cache: false,
          beforeSend: function() {
            $('#arinfo').html('Please wait, Popup Coming..');
          },
          success: function(rtnJsn) {
            var obj = JSON.parse( rtnJsn );
            var _name = obj.name;
            var _page_content = obj.page_content;
            var _marker_id = obj.id;
            var _dispImg = obj.imgx;

            var _pin_image = obj.pin_image;

            $('#name').val(_name);
            $('#marker_id').val(_marker_id);
            $('#left_pos').val(_dd_leftPosition);
            $('#top_pos').val(_dd_topPosition);

           

            if(_pin_image==1){
              $('#red_image').attr('checked', 'checked');
            }
            else  if(_pin_image==2){
              $('#blue_image').attr('checked', 'checked');
            }
            else if(_pin_image==3){
              $('#yellow_image').attr('checked', 'checked');
            }

           
 

            if( _dispImg != '' ) {
              $('#dispImg').attr('src', _dispImg).show();
              $('#delMrkImg').removeAttr('disabled').show();
            }

            CKEDITOR.instances[ 'pg_cont' ].setData( _page_content );
            
            $('#arinfo').html('');
            $('#ari-mag').html('<span><code>Please Save New Position, Thanks.</code></span>');

            $('#fsMarkModal').modal({
              backdrop: 'static',
              keyboard: false
            });
          }
        });
      }
    }
  });
} );
</script>

<script type="text/javascript">
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/mini_config.js') }}",
} );

var fm = $('#fsinfoFRM');
fm.on('submit', function() {
  CKEDITOR.instances['pg_cont'].updateElement();
});
fm.validate({
    errorElement: 'span',
    errorClass : 'roy-vali-error',
    ignore: [],
    normalizer: function( value ) {
      return $.trim( value );
    },
    rules: {
      left_pos: {
        required: true
      },
      top_pos: {
        required: true
      },
      page_content: {
        required: true
      },
      name: {
        required: true
      }
  },
  messages: {
      left_pos: {
        required: 'ERROR'
      },
      top_pos: {
        required: 'ERROR'
      },
      page_content: {
        required: 'Enter content.'
      },
      name: {
        required: 'Enter heading.'
      }
  },
  errorPlacement: function(error, element) {
      //element.parent('.form-group').addClass('has-error');
      if (element.attr("data-error-container")) { 
        error.appendTo(element.attr("data-error-container"));
      } else if(element.attr('id') == 'language_id') {
        error.insertAfter(element.parent('div'));
      } else {
        error.insertAfter(element); 
      }
  },
  success: function(label) {
      //label.closest('.form-group').removeClass('has-error');
  },
  submitHandler : function(form) { 
      $.ajax({
        type: form.method,
        url: form.action,
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          
        },
        success: function(rtnJson) {
          var obj = JSON.parse( rtnJson );
          var msg = obj.status;
          var left = obj.left_pos;
          var top = obj.top_pos;

          var pin_image = obj.pin_image;

           
          if(msg == 'OK') {
            form.reset();
            CKEDITOR.instances[ 'pg_cont' ].setData( '' );
            $('#fsMarkModal').modal('hide');

            if(pin_image==1){
              var mkr = '<img src="'+ obj.marker_url +'" class="fsmark draggable" id="'+ obj.marker_id +'" style="width: 32px; height: 32px; position: absolute; z-index: 99; left:'+ left +'px; top:'+ top +'px;"/>';
           
            }
            else  if(pin_image==2) {
              var mkr = '<img src="{{asset("public/blue_pin.png")}}" class="fsmark draggable" id="'+ obj.marker_id +'" style="width: 32px; height: 32px; position: absolute; z-index: 99; left:'+ left +'px; top:'+ top +'px;"/>';
           
            }

            else  if(pin_image==3) {
              var mkr = '<img src="{{asset("public/yellow_pin.png")}}" class="fsmark draggable" id="'+ obj.marker_id +'" style="width: 32px; height: 32px; position: absolute; z-index: 99; left:'+ left +'px; top:'+ top +'px;"/>';
           
            }
           
           
           
            $('div.fs-marker-outer').append(mkr);
          }

          if(msg == 'DONE') {
            form.reset();
            CKEDITOR.instances[ 'pg_cont' ].setData( '' );
            $('#fsMarkModal').modal('hide');
          }

          $( ".draggable" ).draggable();
        },
        error: function(ajx_err) {
          
        }
      });
      //return false;
  }
});
</script>


@endpush