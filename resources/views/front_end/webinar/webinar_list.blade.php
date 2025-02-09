@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<style type="text/css">
.art-pagination {
    text-align: right;
}
.art-pagination .pagination {
    margin-top: 22px;
}


input.filtersearch{width: 100%;
height: 50px !important;
padding: 10px;
font-size: 14px;
border: none;
background: #ebebeb;
}
a.filterbut {
    float: right;
    padding: 13px 33px;
    background: #3b8d65;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 1px;
    border-radius: 4px;
    color: #fff;
}
.filterbox {
    padding: 26px;
    margin: 15px 0 ;
    background: #f5f5f5;
    border-radius: 0 0 5px 5px;
    border-bottom: 3px solid #3b8d65;
	box-shadow: 0px 8px 13px #ccc;
}
.filterbox select.form-control{
	height:45px !important;
	border:1px solid #ccc;
}

.picboxsection{padding:35px 0;}
.picinner {
    padding: 15px;
    background: #f1f1f1;
	margin:0 0 30px;
    height: 468px;
}
.picinner h4 {
    margin: 0;
    padding: 13px 10px;
    background: #3b8d65;
    color: #fff;
}

.piccont{
	font-size:14px;
	
	}
.piccont p {
    font-size: 16px;
    /*line-height: 23px;*/
    line-height: 18px;
	padding:8px 0;
}
	
.piccont ul {
    padding: 12px 0 0 0;
    margin: 0;
    text-align: center;
}
.piccont ul li {
    display: inline-block;
}
.piccont ul li a {
    padding: 2px 15px;
    display: inline-block;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    margin: 0 5px 5px 0;
    color: #3b8d65;
    border: 1px solid #3b8d65;
    border-radius: 23px;
}	
	
.piccont ul li a:hover{background:#3b8d65; color:#fff;}
.catagorydetails{padding:0 0 25px;}
.youtubevideo{padding:15px; background:#f6f6f6; margin:20px 5%;}
.youtubevideo iframe {
    width: 100%;
    height: 450px;
}
@media only screen and (max-width:767px){
	input.filtersearch{margin:35px 0 8px;}
	a.filterbut{display:block; text-align:center; float:none;}
	.filterbox select.form-control {margin: 9px 0;}
	.picinner img {
    width: 100%;
}
}
 
.filtersecrch {
    position: relative;
}
.filtersecrch button {
    position: absolute;
    right: 9px;
    top: 11px;
    background: none;
    border: none;
    color: #3b8d65;
    font-size: 21px;
}
.filtersecrch input.filtersearch {
    padding-right: 46px;
}

 
</style>
<link rel="stylesheet" href="{{ asset('public/front_end/css/jquery.tabs.css') }}">
@endpush

 
@section('page_content')

@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif

<section class="container" style="margin-top: 40px;">

<div class="breadcrumb">
    <ul>
        <li><a href="{{ url('/') }}">Home</a></li>
        <li>Webinar</li>
    </ul>
</div>


<h1>{{$webinarContent->heading}}</h1>

<br>
<p>
{!! html_entity_decode($webinarContent->description ) !!}
</p>

 <br>



<form name="frmsx" method="GET"  >
<div class="row" style="float:right;">
 

<div  style="display:flex;">

                   
<div class="filtersecrch">
        <button style="border: none;" type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>    
<input type="text" value="" class=" form-control filtersearch" name="search" placeholder="SEARCH">
</div>

 
<a href="#" class="filterbut" data-kmt="1" onclick="showhide();"><span id="plusminus">-</span> FILTER</a>
<div class="clearfix"></div> 
 
</div>

</div>

<br><br>

<div class="filterbox" >
<div class="row">
<div class="col-sm-4">
<label  style="font-weight: 300; font-size: 12px;">Product</label>
        <br>    
<select class="form-control" name="webinar_category" id="webinar_category" onchange="getWebinars()" >
    
    <option value="">Select Product</option>

    @foreach($listCats as $row) 
    <option value="{{$row->id}}">{{$row->name}}</option> 
    @endforeach
 
</select>
</div>

<div class="col-sm-4">
<label  style="font-weight: 300; font-size: 12px;">Topic</label>
<br>    
<select class="form-control" name="webinar_topic" id="webinar_topic" onchange="getWebinars()">
    
    <option value="">Select Topic</option> 

    @foreach($listTopic as $row)
    <option value="{{$row->id}}">{{$row->name}}</option> 
    @endforeach 

</select>
</div>

<div class="col-sm-4">
<label  style="font-weight: 300; font-size: 12px;">Industry</label>
<br>    
<select class="form-control" name="webinar_industry" id="webinar_industry" onchange="getWebinars()">
    
    <option value="">Select Industry</option> 

    @foreach($listIndustry as $row)
    <option value="{{$row->id}}">{{$row->name}}</option> 
    @endforeach 

</select>
</div>

</div> 
</div>

</form>

<div class="picboxsection">
<div class="row" id="listWebinars">
  
@if( isset($listData) )
 @forelse( $listData as $v )
<div class="col-sm-4 col-md-4">
<div class="picinner">
    
        <h4 @if($v->webinar_type == 1) style="background: #3b8d65;" @elseif($v->webinar_type == 2) style="background: #90c84c;" @endif>{{ $v->name }}</h4>
    
<a href="{{ route('front.webinarCont', array('lng' => $lng, 'id' => $v->slug)) }}" > 
@php
$imageURL='';
                                  
    if(isset($v->image) && $v->image!=''){
        $imageURL = asset('public/uploads/user_images/original/'.$v->image); 
    }
    @endphp
    @if(isset($imageURL) && !empty($imageURL))
    <img src="{{$imageURL}}" >
    @else
    <img src="{{ asset('public/images/default_multotec.jpg') }}" style="height: 100%;">
    @endif
</a>

 
<div class="piccont">
<ul>
<!-- <li><a >Product: <?php  echo rtrim( $v->webinarcat, ', ');?></a></li> -->
<!-- <li><a h onclick="setURL('{{ route('front.webinarCont', array('lng' => $lng, 'id' => $v->id)) }}', {{$v->id}})" data-kmt="1"  data-toggle="modal" data-target="#myModal">Watch</a></li> -->
<li><a href="{{ route('front.webinarCont', array('lng' => $lng, 'id' => $v->slug)) }}" >Watch Webinar</a></li> 

 
</ul>
<p>{!! html_entity_decode( $v->short_description ) !!}</p>
</div>
</div>
</div>

 
    @empty
        <h3>No Record Found</h3>
    @endforelse   
    @endif


</div>
    <div class="prev_next_btn" >
        @if( $listData->previousPageUrl() != '' ) <a href="{{ $listData->previousPageUrl() }}"> < Prev  </a> @endif
        @if( $listData->nextPageUrl() != '' ) <a href="{{ $listData->nextPageUrl() }}"> Next > </a> @endif 
    </div>
</div>
 
  
<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Please fill this form to continue</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <form action="https://www.multotec.com/en/saveWbUser" method="post">

        {{ csrf_field() }}
        <!-- Modal body -->
        <div class="modal-body">
         
          <div class="form-group">
          <input type="text" name="name" placeholder="Enter Name" class="form-control" required>
          <input type="hidden" name="webinar_url"  id="webinar_url"   class="form-control">
          <input type="hidden" name="webinar_id"  id="webinar_id"   class="form-control">
          
          </div>

          <div class="form-group">
          <input type="text" name="contact_no" placeholder="Enter Contact Number" class="form-control" required>
          </div>

          <div class="form-group">
          <input type="text" name="email_id" placeholder="Enter Email" class="form-control" required>
          </div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-submit" >Proceed</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </form >
        
      </div>
    </div>
  </div>


</section>
@endsection




@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script>
//Initialize 2nd demo:
ddaccordion.init({
    headerclass: "accor_heading", //Shared CSS class name of headers group
    contentclass: "accor_body", //Shared CSS class name of contents group
    revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
    mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
    collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
    defaultexpanded: [true], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
    onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
    animatedefault: false, //Should contents open by default be animated into view?
    scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
    persiststate: false, //persist state of opened contents within browser session?
    toggleclass: ["closed_arrow", "open_arrow"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
    togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
    animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
    oninit:function(expandedindices){ //custom code to run when headers have initalized
        //do nothing
    },
    onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
        //do nothing
    }
})

</script>

<script type="text/javascript">

 


function getWebinars() {
     
   var webinar_category  = $('#webinar_category').val();
   var webinar_topic  = $('#webinar_topic').val();
   var webinar_industry  = $('#webinar_industry').val();
   
 
    $.ajax({
    url: 'https://www.multotec.com/en/ajaxWebinar',
    type: "POST",
    datatype: "json", 
    data: {webinar_category: webinar_category, webinar_topic: webinar_topic, webinar_industry: webinar_industry, _token: '{{ csrf_token() }}'},

    success: function(data) { 
      $("#listWebinars").empty().html(data); 
    }, 

    error: function(jqXHR, ajaxOptions, thrownError) {
      alert('No response from server');
    }

  });


                // var url = "{{route('ajaxWebinar')}}";
                // var data = {
                //     webinar_category : $( "#webinar_category" ).val(),
                //     webinar_topic : $( "#webinar_topic" ).val(),
                //     _token: '{{ csrf_token() }}',
                // }
                // $.post(url, data).done(function(msg){

                //     alert(msg);
                    
                // }); 

}
 
var i=1;

function showhide(){
 
    $('.filterbox').toggle();
    if(i%2==1){
        $('#plusminus').html('+'); 
    }
    else{
        $('#plusminus').html('-'); 
    }
     
    i++;
}
 
function setURL(url,webinar_id){
    $('#webinar_url').val(url);
    $('#webinar_id').val(webinar_id);
 
}

// *********** tightpanr ********** //
$(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});
</script>
<script type="text/javascript">
$( function() {
    $('ul.pagination li:first').find('span').text('Prev');
    $('ul.pagination li:last').find('span').text('Next');

    $('ul.pagination li [rel=prev]').html('Prev');
    $('ul.pagination li [rel=next]').html('Next');

} );
 
</script>

@endpush

    