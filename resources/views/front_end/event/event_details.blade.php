@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<style type="text/css">
.catagorydetails a {
  /* background-color: #95c856;
  color: #fff; */
  display: block;
  width: 120px;
  padding: 5px;
  text-align: center;
  margin: 20px 60px;
} 
.innerpage-banner img {
    width: 100% !important;
    height: 340px !important;
}
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
    line-height: 23px;
    padding:8px 0;
}
    
.piccont ul {
    padding: 12px 0 0 0;
    margin: 0;
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

 
</style>

@endpush
@section('page_content')

<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/event_banner_image.jpg') }}" >
</section>

<section class="container" style="margin-top: 40px;">

<div class="breadcrumb">
    <ul>
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/en/events') }}">Event</a></li>
        <li>{{$allData->name}}</li>
    </ul>
</div>

 
<div class="picboxsection">
<div class="row">

<div class="col-md-8">
 
   
<div class="loopblock catagorydetails">
<h1 >{{$allData->name??''}}   </h1>

<p>{!! html_entity_decode( $allData->description??'') !!}</p>


@if(isset($allData->sub_heading))

<h5 style="font-weight: 400;">{{$allData->sub_heading}}</h5>

<br>

@endif
@if(!empty($allData->event_end_date)  )
<h5 style="font-weight: 400;font-size:18px;"><span style="font-weight: 500;">Date:</span> {{date('d M Y', strtotime($allData->event_start_date))}} - {{date('d M Y', strtotime($allData->event_end_date))}}</h5>
@endif
@if(empty($allData->event_end_date))
<h5 style="font-weight: 400;font-size:18px;"><span style="font-weight: 500;">Date:</span> {{date('d M Y', strtotime($allData->event_start_date))}} </h5>

@endif
 


<h5 style="font-weight: 400;font-size:18px;"><span  style="font-weight: 500;">Event Type:</span> {{$allData->event_type??''}}</h5>
 

@php

  $regionname=DB::table('event_management_continents')->where('id',$allData->region_id)->select('continent_name')->first();
  
  $countryname=DB::table('event_management_countries')->where('id',$allData->country_id)->select('country_name')->first();
@endphp


<h5 style="font-weight: 400;font-size:18px;"><span style="font-weight: 500;">Event Location:</span>
    {{ empty($countryname->country_name) ? '' : $countryname->country_name . ', ' }}
    @if (!empty($regionname->continent_name))
    {{-- @if (str_contains(strtolower($regionname->continent_name), 'na'))
        NORTH AMERICA
    @elseif (str_contains(strtolower($regionname->continent_name), 'sa'))
        SOUTH AMERICA
    @elseif (str_contains(strtolower($regionname->continent_name), 'asia'))
        ASIA
    @elseif (str_contains(strtolower($regionname->continent_name), 'europe'))
        EUROPE
    @elseif (str_contains(strtolower($regionname->continent_name), 'australia'))
        AUSTRALIA
    @elseif (str_contains(strtolower($regionname->continent_name), 'africa'))
        AFRICA
    @else --}}
        {{ $regionname->continent_name }}
    {{-- @endif --}}
  @else
    <!-- Display a message or fallback content if $regionname->region_name is empty -->
  @endif
</h5>



@php
$event_start_date = date('d-M', strtotime($allData->event_start_date));
$event_end_date = date('d-M-Y', strtotime($allData->event_end_date));  

@endphp






@if(empty($allData->event_end_date))
    @if($allData->event_start_date >= date('Y-m-d'))
        <a style=" background-color: #95c856;
        color: #fff;
        display: block;
        width: 120px;
        padding: 5px;
        text-align: center;
        margin: 20px 60px;"href="{{$allData->event_url ?? ''}}">Register</a>
    @endif

@else
    @if($allData->event_end_date >= date('Y-m-d'))
        <a style=" background-color: #95c856;
        color: #fff;
        display: block;
        width: 120px;
        padding: 5px;
        text-align: center;
        margin: 20px 60px;" href="{{$allData->event_url ?? ''}}">Register</a>
        @else

    @endif

@endif
    {{-- @if($allData->event_start_date <= date('Y-m-d') && $allData->event_end_date > date('Y-m-d'))
    <a href="{{$allData->event_url ?? ''}}">Register</a>
    @endif --}}









 


  
<p></p>




<div >

<style>

.containeri{position:relative;float:left;}
.overlay{top:0;left:0;width:100%;height:100%;position:absolute;background: rgb(0 0 0 / 16%);}

</style>





<!-- <img src="{{ asset('public/front_end/images/play.jpeg') }}" style="height: 100%;"> -->





</div>


</div> 

</div>
<div class="col-md-4">
    <div class="container">
        @if(!empty($allData->image))
        <img src="{{asset('public/uploads/event_images/original/'.$allData->image)}}" class="youtubeimg" style="width: 350px ;height: 400px;">
        @else
        
        @endif
        
        </div>
</div>
</div>


</div>
 
</section>
@endsection

 

@push('page_js')

<script>


</script>


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

 
//   $(document).ready(function () {
//     var youtubeid = $(".youtubeimg").attr("src").match(/[\w\-]{11,}/)[0];
//        alert(youtubeid);
//      });
//   });
 
// *********** tightpanr ********** //
$(document).ready(function() {


    
$('#form').submit(function() {
    

    if($('#terms').is(":checked")){
        return true;
    }
    else{
         
        $('#termserror').show();
        return false;
    
    }
            
});


   
        // $('iframe_id').contents().find('video').each(function () 
        // {
        //     this.currentTime = 0;
        //     this.pause();
        // });

        $('.overlay').click(function(){
            
            $('#name').focus();

            $("#alertmessage").show();

setTimeout(function() { $("#alertmessage").hide(); }, 5000);


            // alert('Complete the form to access the recording!');
        
        
        });


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

    