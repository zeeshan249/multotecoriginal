@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<style type="text/css">
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

.main-container {
      /* display: flex; */
      justify-content: space-between;
      align-items: center; /* Optional: Center the content vertically */
    }

    /* Container for the label and select box */
    .select-container {
    display:inline-block;
 
    }

    /* Optional: Additional styles for the select box */
    select {
      width: 150px !important;
      padding: 5px;
    }

.picboxsection{padding:35px 0;}
.picinner {
    /* padding: 15px;
    background: #f1f1f1;
	margin:0 0 30px;
    height: 468px; */
    padding: 15px;
  background: #f1f1f1;
  margin: 0 0 30px;
  min-height: 468px;
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
  .piccont a {
  color: #3b8d65 !important;
  border: 1px solid #3b8d65 !important;
  padding: 10px 0;
  margin: 20px auto;
  width: 200px;
  display: block;
  text-align: center;
  border-radius: 50px;
}
    .piccont p {
    font-size: 16px;
    line-height: 18px;
    padding: 8px 0;
   
    font-weight: bold;
}
 .link{
    font-size: 16px;
    line-height: 18px;
    padding: 8px 0;
    text-align: center;
    font-weight: bold;  
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



.picinner img {
width:100%;
}

select#year {
    display: inline-block;
}

.event-heading label {
    font-weight: 700;
}
body {
  padding : 10px ;
  
}

#exTab1 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

#exTab2 h3 {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

/* remove border radius for the tab */

#exTab1 .nav-pills > li > a {
  border-radius: 0;
}

/* change border radius for the tab , apply corners on top*/

#exTab3 .nav-pills > li > a {
  border-radius: 4px 4px 0 0 ;
}

#exTab3 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}


.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
    color: #fff;
    background-color: #3b8d65 !important;
}

.event-default
{
  color:#000;
}
.btn-bigger {
    font-size: 18px; /* Adjust the value as needed */
    padding: 10px 20px; /* Adjust padding as needed */
}
</style>
<link rel="stylesheet" href="{{ asset('public/front_end/css/jquery.tabs.css') }}">
@endpush

 
@section('page_content')

{{-- @if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif --}}
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/event_banner_image.jpg') }}" >
</section>
<section class="container" >

<div class="breadcrumb">
    <ul>
        <li><a href="{{ url('/') }}">Home</a></li>
        <li>Events</li>
    </ul>
</div>


<h1>{{$webinarContent->heading??''}}</h1>

<br>

<p>
{!! html_entity_decode($webinarContent->description??'' ) !!}
</p>
<div class="main-container">
   <div class="row">
    <div>
      <div class="col-md-4">
    <div id="exTab2">	
        <ul class="nav nav-pills">
                    <li class="active">
                <a  href="#1a" class="event-default" data-toggle="tab">Upcoming Events</a>
                    </li>
                    <li><a class="event-default"  href="#2a" data-toggle="tab">Past Events</a>
                    </li>
               
                </ul>
        
                   
          </div>
    </div>
  
    <div class="col-md-3">
          <div class="select-container event-heading">
      <label for="year">Select Year</label>
      <form action="{{ route('eventListsen') }}" method="GET">
      <select name="year" id="year" class="form-control">
        <option value="">Select Year</option>  
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
      </select>
    </div>
  </div>
    @php
    $regionname=DB::table('event_management_continents')->select('id','continent_name')->get();
      @endphp
      @if(!empty($regionname))
      <div class="col-md-4">
      <div class="select-container ">
          <label for="year">Select Region</label>
          <select name="continent_id" id="continent_id" class="form-control">
            <option value="">Select Region</option>  
            @foreach ( $regionname as $regionname )
            <option value="{{$regionname->id}}" 
              @if(isset($continent_id) && $continent_id == $regionname->id) selected @endif
              >{{$regionname->continent_name}}</option>  
            
            @endforeach
        
          </select>
        </div>
      
        @else
        @endif
    
          <input class="btn btn-primary pl-5" type="submit" name="Submit" value="Filter">
        </div>
      </form>

    </div>
      
  </div>

 <br>
 <div class="tab-content ">
    <div class="tab-pane active" id="1a">
        <h3>Recent & Upcoming Events</h3>
<div class="picboxsection">
<div class="row" id="listWebinars">
  
    @if( isset($eventData) )
    @forelse( $eventData as $v )
<div class="col-sm-4 col-md-4">
<div class="picinner">    
        <h4 style="background: #3b8d65;" >{{ $v->name??'' }}</h4>   
<a href="" > 
    @php
    $imageURL='';                                    
        if(isset($v->image) && $v->image!=''){
            $imageURL = asset('public/uploads/event_images/original/'.$v->image); 
        }
        @endphp
        @if(isset($imageURL) && !empty($imageURL))
        <a href="{{ route('eventDetails', array( 'id' => $v->slug)) }}">
            <img src="{{$imageURL}}"  >
        </a>
        @else
<img src="{{ asset('public/images/default_multotec.jpg') }}" >
        @endif
 
</a>
<div class="piccont">
<ul>
<li style="display:none"><a href="{{ route('eventDetails', array('id' => $v->slug)) }}" >Event Details</a></li> 
</ul>
@php
  $regionname=DB::table('event_management_continents')->where('id',$v->region_id)->select('continent_name')->first();
  
  $countryname=DB::table('event_management_countries')->where('id',$v->country_id)->select('country_name')->first();
@endphp


<p class="font-size:12px; text-align:center;">
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
</p>




@if(isset($v->event_start_date)  )
@php
$event_start_date = date('d M', strtotime($v->event_start_date));
$event_end_date = date('d M Y', strtotime($v->event_end_date));  

@endphp
@if(empty($v->event_end_date))

<p >{{ date('d M Y', strtotime($v->event_start_date))}}</p>
<a href="{{ route('eventDetails', array( 'id' => $v->slug)) }}" >View Event</a>
@else

<p >{{ $event_start_date.' - '.$event_end_date  }}</p>
<a href="{{ route('eventDetails', array( 'id' => $v->slug)) }}" >View Event</a>
@endif


@endif
</div>
</div>
</div> 
@empty
<h3>No Record Found</h3>
@endforelse   
@endif
</div>
    <div class="prev_next_btn">
        <div class="prev_next_btn">
            <a href="{{route('currentEvents',['year'=>date('Y')])}}"> View {{date('Y')}} Events  </a>  
        </div>
    </div>
</div>
    </div>

    <div class="tab-pane" id="2a">
@if(count($queryPast)>0)
<h3>Past Events</h3>
 
<div class="picboxsection">
<div class="row" id="listWebinars">
  
    @if( isset($queryPast) )
    @forelse( $queryPast as $v )
<div class="col-sm-4 col-md-4">
<div class="picinner">    
        <h4 style="background: #3b8d65;" >{{ $v->name??'' }}</h4>   
<a href="" > 
    @php
    $imageURL='';                                    
        if(isset($v->image) && $v->image!=''){
            $imageURL = asset('public/uploads/event_images/original/'.$v->image); 
        }

      
  $regionname=DB::table('event_management_continents')->where('id',$v->region_id)->select('continent_name')->first();
  
  $countryname=DB::table('event_management_countries')->where('id',$v->country_id)->select('country_name')->first();

        @endphp
        @if(isset($imageURL) && !empty($imageURL))
        <a href="{{ route('eventDetails', array( 'id' => $v->slug)) }}">
            <img src="{{$imageURL}}"  >
        </a>
   
        @else
<img src="{{ asset('public/images/default_multotec.jpg') }}" >
        @endif
 
</a>
<div class="piccont">
<ul>
<li style="display:none"><a href="{{ route('eventDetails', array( 'id' => $v->slug)) }}" >Event Details</a></li> 
</ul>
<p class="font-size:12px; text-align:center;">
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
  
</p>

@if(empty($v->event_end_date))
    @if($v->event_start_date > now())
    <p >{{ date('d M Y', strtotime($v->event_start_date))}}</p>
        <a href="{{$v->event_url ?? ''}}">Register</a>
    @endif
    @if($v->event_start_date < now())
    <p >{{ date('d M Y', strtotime($v->event_start_date))}}</p>
@endif
@else
    @if($v->event_start_date >= now() && $v->event_end_date > now())
    <p >{{ date('d M Y', strtotime($v->event_start_date))}} - {{date('d M Y', strtotime($v->event_end_date))}} </p>
        <a href="{{$v->event_url ?? ''}}">Register</a>
    @endif
    @if($v->event_start_date <= now() && $v->event_end_date > now())
    <p >{{ date('d M Y', strtotime($v->event_start_date))}} - {{date('d M Y', strtotime($v->event_end_date))}} </p>
    <a href="{{$v->event_url ?? ''}}">Register</a>
    @endif
    @if($v->event_start_date <= now() && $v->event_end_date < now())
    <p >{{ date('d M Y', strtotime($v->event_start_date))}} - {{date('d M Y', strtotime($v->event_end_date))}} </p>
    @endif
@endif
</div>
</div>
</div> 
@empty
<h3>No Record Found</h3>
@endforelse   
@endif
</div>
    <div class="prev_next_btn" >
     
        <a href="{{route('currentEvents',['year'=>date('Y')])}}"> View {{date('Y')}} Events  </a>  
    </div>
</div>
@else

@endif
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

<script>
     $(document).ready(function() {
        var currentYear = new Date().getFullYear();

// Set the selected option based on the current year
// $("#year option[value='" + currentYear + "']").prop("selected", true);
//$("#continent option[value='" + currentYear + "']").prop("selected", true);
      $("#year").on("change", function() {
        // Get the selected year value
        var selectedYear = $(this).val();

        // Redirect to the corresponding page based on the selected year
        if (selectedYear === "2022") {
          window.location.href = '{{ route('currentEvents', ['year' => '2022']) }}';
        } else if (selectedYear === "2023") {
          window.location.href = '{{ route('currentEvents', ['year' => '2023']) }}';
        } else if (selectedYear === "2024") {
          window.location.href = '{{ route('currentEvents', ['year' => '2024']) }}';
        } else if (selectedYear === "2025") {
          window.location.href = '{{ route('currentEvents', ['year' => '2025']) }}';
        } else if (selectedYear === "2026") {
          window.location.href = '{{ route('currentEvents', ['year' => '2026']) }}';
        }
        // Add more conditions for other years if needed
      });

      

  //     $("#continent").on("change", function() {
  //       // Get the selected year value
  //       var selectedContinent = $(this).val();
  //       var selectedYear=$("#year").val();
  //       // Redirect to the corresponding page based on the selected year
  //       if (selectedContinent === "1") {
     
  //         var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 1]) }}';
  //         window.location.href = redirectUrl.replace(":year", selectedYear);
  //       } 
  //       if (selectedContinent === "2") {
     
  //    var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 2]) }}';
  //    window.location.href = redirectUrl.replace(":year", selectedYear);
  //  } 
  //  if (selectedContinent === "3") {
     
  //    var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 3]) }}';
  //    window.location.href = redirectUrl.replace(":year", selectedYear);
  //  } 
  //  if (selectedContinent === "4") {
     
  //    var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 4]) }}';
  //    window.location.href = redirectUrl.replace(":year", selectedYear);
  //  } 
  //  if (selectedContinent === "5") {
     
  //    var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 5]) }}';
  //    window.location.href = redirectUrl.replace(":year", selectedYear);
  //  } 
  //  if (selectedContinent === "6") {
     
  //    var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 6]) }}';
  //    window.location.href = redirectUrl.replace(":year", selectedYear);
  //  } 
  //  if (selectedContinent === "7") {
     
  //    var redirectUrl = '{{ route("currentEvents", ["year" => ":year", "region_id" => 7]) }}';
  //    window.location.href = redirectUrl.replace(":year", selectedYear);
  //  } 
    
  //     });
    });
</script>

@endpush

    