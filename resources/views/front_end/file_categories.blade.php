@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<link href="{{ asset('public/front_end/css/jquery.tabs.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('page_content')

<section class="innerpage-banner">

    @if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
        <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
    @else
        <img src="{{ asset('public/front_end/images/innerpage_banner6.jpg') }}" alt="Multotec-image-video-gallery">
    @endif
    
</section>

<section class="container">

    <div class="breadcrumb"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Product Brochures & Files</li>
        </ul>
    </div>

    <div class="midblock">
        <h1>@if(isset($extraContent)){{ $extraContent->title }}@endif</h1>
        <p>@if(isset($extraContent)){!! html_entity_decode($extraContent->page_content, ENT_QUOTES) !!}@endif</p>
        <div class="tabblock">
            <div class="jq-tab-wrapper" id="horizontalTab">
                <div class="jq-tab-menu">
                    <div class="jq-tab-title active" data-tab="1">Products</div>
                    <div class="jq-tab-title" data-tab="2">Multotec Group</div>
                </div>
                <div class="jq-tab-content-wrapper">
                    <div class="jq-tab-content active" data-tab="1">
                    <div class="tab_info_list">
                        <div class="row">
                            @if( isset($fileCategories) && count($fileCategories) > 0 )
                                @foreach($fileCategories as $pd)
                                  @if($pd->tab_section == 'PRODUCT')
                                    <div class="col-sm-4">
                                    <ul><li>
                                        <a href="{{ route('front_fileSubCat', array('lng' => $lng, 'category' => $pd->slug)) }}">{{ ucfirst($pd->name) }}</a>
                                    </li></ul>    
                                    </div>
                                  @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    </div>    
                    <div class="jq-tab-content" data-tab="2">
                    <div class="tab_info_list">
                        <div class="row">
                            @if( isset($fileCategories) && count($fileCategories) > 0 )
                                @foreach($fileCategories as $pd)
                                  @if($pd->tab_section == 'MULTOTEC_GROUP')
                                    <div class="col-sm-4">
                                    <ul><li>
                                        <a href="{{ route('front_fileSubCat', array('lng' => $lng, 'category' => $pd->slug)) }}">{{ ucfirst($pd->name) }}</a>
                                    </li></ul>    
                                    </div>
                                  @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
    <div class="col-sm-4">
    <!--nav aria-label="Page navigation">
      <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#">Prev</a></li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">4</a></li>
        <li class="page-item"><a class="page-link" href="#">5</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav-->
    </div>
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
    <!--div class="search"><input type="text" class="form-control" placeholder="Search"><button type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button></div-->
    </div>
    </div>

    </div>
</section>

@endsection






@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script src="{{ asset('public/front_end/js/jquery.tabs.min.js') }}"></script>
<script type="text/javascript">
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
  togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "],  //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
  animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
  oninit:function(expandedindices){ //custom code to run when headers have initalized
    //do nothing
  },
  onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
    //do nothing
  }
});

$(function () {
   // $('#verticalTab').jqTabs();
    $('#horizontalTab').jqTabs({direction: 'horizontal', duration: 200});
});

</script>
@endpush

    