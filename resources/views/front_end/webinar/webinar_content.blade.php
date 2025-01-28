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
        <li><a href="{{ url('/en/webinar') }}">Webinar</a></li>
        <li>{{$allData->name}}</li>
    </ul>
</div>

 
<div class="picboxsection">
<div class="row">

<div class="col-md-8">
 
   
<div class="loopblock catagorydetails">
<h1 >{{$allData->name}}   </h1>



@if(isset($allData->sub_heading))

<h5 style="font-weight: 400;">{{$allData->sub_heading}}</h5>

<br>

@endif

<h5 style="font-weight: 400;font-size:18px;"><span style="font-weight: 500;">Date:</span> {{date('d M Y', strtotime($allData->webinar_start_date))}} - {{date('d M Y', strtotime($allData->webinar_end_date))}}</h5>

 
@if(isset($allData->speaker))

<h5 style="font-weight: 400;font-size:18px;"><span  style="font-weight: 500;">Presented by :</span> {{$allData->speaker}}</h5>
 
@endif

<?php $urlview=str_replace(" ","-",$allData->WebinarCategory->name); ?>

<h5 style="font-weight: 400;font-size:18px;"><span style="font-weight: 500;">Category:</span> {{$allData->WebinarCategory->name}}</h5>

@if(isset($allData->duration))

<h5 style="font-weight: 400;font-size:18px;"><span  style="font-weight: 500;">Duration :</span> {{$allData->duration}}</h5>
 
@endif

  
<p>{!! html_entity_decode( $allData->description ) !!}</p>


<h4>Complete the form to access the recording!</h4>

<div >

<style>

.containeri{position:relative;float:left;}
.overlay{top:0;left:0;width:100%;height:100%;position:absolute;background: rgb(0 0 0 / 16%);}

</style>

<div class="containeri">
<!-- <iframe  class="youtubeimg" style="width: 600px;height: 380px;" src="{{$allData->url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-kmt="1" id="widget2" data-gtm-yt-inspected-1_25="true" data-gtm-yt-inspected-11482088_5="true"></iframe> -->
<img src="{{asset('public/uploads/user_images/original/'.$allData->video_image)}}" class="youtubeimg" style="width: 600px;height: 380px;">
<div class="overlay" ></div>
</div>



<!-- <img src="{{ asset('public/front_end/images/play.jpeg') }}" style="height: 100%;"> -->


<!-- <img src="http://i1.ytimg.com/vi/{{$allData->url}}/[Thumnail-Size].jpg"> -->


</div>

<!-- <div class="youtubevideo">
<iframe width="560" height="315" src="{{$allData->url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-kmt="1" id="widget2" data-gtm-yt-inspected-1_25="true" data-gtm-yt-inspected-11482088_5="true"></iframe>
</div> -->

</div></div>

<div class="col-md-4">

<div class="rightpanel">
           
                        <div class="sidebar_block form" id="EFORM">
                            <h1>Watch this<span> Multotec Webinar</span></h1>
                            <div class="ar_frm_container" style="background-color:#eaeaea; color:#000000;">
                            <form id="form" name="general" action="https://www.multotec.com/en/saveWbUser" method="post" class="ar_vali_class " enctype="multipart/form-data" novalidate="novalidate" data-kmt="1">
                                {{ csrf_field() }}

                                <input type="hidden" name="webinar_url"  value="{{ route('front.webinarVideo', array('lng' => $lng, 'id' => $allData->id)) }}"   id="webinar_url"   class="form-control">
          <input type="hidden" name="webinar_id"  id="webinar_id" value="{{$allData->id}}"   class="form-control">


          <span id="alertmessage" style="color:red;display:none;font-size: 16px;font-weight: 400;">Complete form to access the recording!</span>
                                <div class="row fd_box" id="field_16"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Name :</label> <em>*</em> <input type="text" name="name" id="name" placeholder="Name" required="" class="form-control">
                                <div id="ed_action_box_16"></div></div></div></div><div class="row fd_box" id="field_3"><div class="col-md-12 col-sm-12">
                                <div class="form-group"><label>Your Email-id :</label> <em>*</em> <input type="email" name="email_id" placeholder="Email:" required="" class="form-control"><div id="ed_action_box_3"></div></div></div></div><div class="row fd_box" id="field_4"><div class="col-md-12 col-sm-12">
                                    <div class="form-group"><label>Phone Number :</label> <input type="text" name="contact_no" placeholder="Phone:"  class="form-control onlyNumber valid"><div id="ed_action_box_4"></div></div></div></div><div class="row fd_box" id="field_17"><div class="col-md-12 col-sm-12">
                                        
                                    <div class="form-group"><label>Company :</label> <em>*</em> <input type="text" name="company" placeholder="Company" class="form-control"><div id="ed_action_box_17"></div></div></div></div><div class="row fd_box" id="field_18"><div class="col-md-12 col-sm-12">
                                        
                                    <div class="form-group"><label>Country :</label> <em>*</em> <input type="text" name="country" placeholder="Country" required="" class="form-control"><div id="ed_action_box_18"></div></div></div></div><div class="row fd_box" id="field_5"><div class="col-md-12 col-sm-12">
                                        
                                      <div class="form-group"><label>Acceptance Info :</label> <br><p style="font-size:13px!important; "><input type="checkbox" name="terms" id="terms" class="ar-ckb" value="1"   required="" > I consent to receiving marketing communications from Multotec</p> <span id="termserror"  style="display: none;color: #cc0000;
    font-weight: normal;
    font-size: 12px;">This field is required.</span><div id="ed_action_box_19"></div></div></div></div>
                                        
                           
                                                    <div class="row fd_box btnf" id="field_1"><div class="col-md-12 col-sm-12"><div class="form-group"><input type="submit" name="submit" value="Watch Webinar" class="submit-btn" style=""><div id="ed_action_box_1"></div></div></div></div><input type="hidden" name="ar_frm_id" value="d5d8517aae26dc072e04284ffdd0d267">
                                                
                                                </form></div>
                        </div>
                        
                                      
                    
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

    