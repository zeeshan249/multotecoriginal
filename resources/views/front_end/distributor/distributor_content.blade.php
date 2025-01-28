@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@section('page_content')

@if( isset($allData) && !empty($allData) )



@php $distInfo = getDistInfo($allData->distributor_id); @endphp

<section class="container">
    <div class="breadcrumb" style="margin-top: 20px;"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('front.distrbMap', array('lng' => $lng)) }}">Distributor</a></li>
            
            @if( isset($allData->distributorInfo) && isset($allData->distributorInfo->distrCategorytOne) && isset($allData->distributorInfo->distrCategorytOne->catInfo) )
            <li>
              <a href="{{ route('front.distrbCat', array('lng' => $lng, 'catslug' => $allData->distributorInfo->distrCategorytOne->catInfo->slug)) }}">
                {{ $allData->distributorInfo->distrCategorytOne->catInfo->name }}
              </a>
            </li>
            <li>
              <a href="{{ route('front.distrb', array('lng' => $lng, 'catslug' => $allData->distributorInfo->distrCategorytOne->catInfo->slug, 'distrbslug' => $allData->distributorInfo->slug)) }}">
                {{ $allData->distributorInfo->name }}
              </a>
            </li>
            @endif
            
            <li class="active">{{$allData->name}}</li>
        </ul>
    </div>
</section>

<!--- FIRST BLOCK ---> 
<!--
    Here Title, Main Content, Buttons, Eform Fix
-->
<section class="container">
<!-- Title -->
<h1>@if( isset($allData->name) ){{ $allData->name }}@endif</h1>
<div class="row">
    <div class="col-sm-8">
        <div class="midblock" id="firstBlock">
            
            <!-- Main Page Content -->
            @if( isset($allData->page_content) )
            <p>{!! trim( html_entity_decode( $allData->page_content, ENT_QUOTES ) ) !!}</p>
            @endif

            <!-- Loop -->
            @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                @foreach( $allData->pageBuilderContent as $pgd )
                    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                        <!-- Buttons-->
                        @if( $pgd->builder_type == 'BROCHURE_BUTT' && $pgd->position == 'BODY' )
                            <div class="buttom-row dwn-btn">
                                <a class="squre-btn" href="{{ route('front_fileSubCat', array('lng' => $lng,'cat' => $pgd->main_content, 'subcat' => $pgd->sub_content)) }}"> <i class="fa fa-angle-down" aria-hidden="true"></i> <span>Download Brochure</span></a>
                            </div>
                            <div class="buttom-row dwn-btn">
                                <a class="squre-btn" href="{{ route('viewTechResLst', array('lng' => $lng)) }}"> <i class="fa fa-angle-down" aria-hidden="true"></i> <span>Technical Resources</span></a>
                            </div>
                        @endif

                        @if( $pgd->builder_type == 'IMAGEGAL_BUTT' && $pgd->position == 'BODY' )
                            <div class="buttom-row dwn-btn">
                                <a class="squre-btn" href="{{ route('front_galSubCat', array('lng' => $lng,'cat' => $pgd->main_content, 'subcat' => $pgd->sub_content)) }}"> <i class="fa fa-angle-down" aria-hidden="true"></i> <span>View Gallery</span></a>
                            </div>
                        @endif
                        <!-- End Buttons -->

                    @endif <!-- End Device Checking -->
                @endforeach
            @endif
            
            @if( isset($allData) && $allData->latitude != '' && $allData->longitude != '' )
            <div class="gmap-block">
              @if( $allData->map_heading != '' )<h3>{{ $allData->map_heading }}</h3>@endif
              <div id="map" style="height: 460px; width: 100%;"></div>
              <input type="hidden" id="mLat" value="@if(isset($allData)){{ $allData->latitude }}@endif">
              <input type="hidden" id="mLng" value="@if(isset($allData)){{ $allData->longitude }}@endif">
              <input type="hidden" id="mAddr" value="@if(isset($allData)){{ $allData->address }}@endif">
              <input type="hidden" id="mType" value="@if(isset($allData)){{ $allData->branch_type }}@endif">
              <input type="hidden" id="mBname" value="@if(isset($allData)){{ $allData->name }}@endif">
            </div>
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <div class="rightpanel">
            <!-- Loop -->
            @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                @foreach( $allData->pageBuilderContent as $pgd )
                    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                        <!-- Eform -->
                        @if( $pgd->builder_type == 'EFORM' && $pgd->position == 'RIGHT' )
                            <div class="sidebar_block form" id="EFORM">
                                <h1>{!! trim( html_entity_decode( $pgd->main_title, ENT_QUOTES ) ) !!}{{-- $pgd->main_title --}}<span>{!! trim( html_entity_decode( $pgd->sub_title, ENT_QUOTES ) ) !!}{{-- $pgd->sub_title --}}</span></h1>
                                {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                            </div>
                            <a href="javascript:void(0);" class="mob-frm-sbt" data-toggle="modal" data-target="#eform_modal">Submit & Enquiry</a>
                            <div class="modal fade" id="eform_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-body mobile_form">
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <h1>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h1>
                                   {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                                  </div>
                                </div>
                              </div>
                            </div>
                        @endif
                        <!-- End Eform -->
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
</section>
<!--- END FIRST BLOCK --->
<!--
-------- -------------- -------------- -------------- -------------- --------------- -------------
-->

<!-- MID BLOCK --> <!-- PRODUCT BOX FULL CONTAINER -->
<section class="container">
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

            @if( $pgd->builder_type == 'PRODUCT_BOX' && $pgd->position == 'BODY' )
                <div class="midblock_subblock">
                    <h2>{{ $pgd->main_title }}</h2>
                    @if( isset($pgd->links) )
                     <div class="row">
                        @foreach($pgd->links as $lnk)
                            @php
                                $linkData = linkSlugToContent( $lnk->slug );
                                $proImgArr = getProductImage($linkData->id);
                            @endphp
                            @if( !empty( $linkData ) )
                            <div class="col-sm-3">
                                <h4>{{ $linkData->name }}</h4>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px; width: 100%;">
                                </div>
                                @endif
                                <p>{{ str_limit( $linkData->description, 140 ) }}</p>
                                <div class="text-center ar-rmdiv"><a href="{{ url( $lng.'/'.$lnk->slug ) }}" class="btn1 btn2-default">Read More</a></div>
                            </div>
                            @endif
                        @endforeach
                        {!! genPBOXreusContent($pgd->link_text) !!}
                     </div>
                    @endif
                </div>
            @endif


            @if( $pgd->builder_type == 'PRODUCT_CAT_BOX' && $pgd->position == 'BODY' )
                <div class="midblock_subblock">
                    <h2>{{ $pgd->main_title }}</h2>
                    @if( isset($pgd->links) )
                     <div class="row">
                        @foreach($pgd->links as $lnk)
                            @php
                                $linkData = linkSlugToContent( $lnk->slug );
                                $proImgArr = getProductCatImage($linkData->id);
                            @endphp
                            @if( !empty( $linkData ) )
                            <div class="col-sm-3">
                                <h4>{{ $linkData->name }}</h4>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px; width: 100%;">
                                </div>
                                @endif
                                <p>{{ str_limit( $linkData->description, 140 ) }}</p>
                                <div class="text-center ar-rmdiv">
                                    <a href="{{ url( $lng.'/'.$lnk->slug ) }}" class="btn1 btn2-default">Read More</a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        {!! genPBOXreusContent($pgd->link_text) !!}
                     </div>
                    @endif
                </div>
            @endif

        @endif <!-- End Device Checking -->
    @endforeach
@endif
</section>
<!-- End Mid Block --> <!-- END PRODUCT BOX FULL CONTAINER -->



<!-- CTA BLOCK --> <!-- CTA FULL PAGE -->
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->
            @if( $pgd->builder_type == 'CTA' && $pgd->position == 'BODY' )
                <section class="green_strip">
                    <div class="container">
                        <div class="text-center">
                            <h6>{{ $pgd->main_title }}<a href="{{$pgd->link_url}}">{{ $pgd->link_text }}</a></h6>
                        </div>
                    </div>
                </section>
            @endif
        @endif
    @endforeach
@endif
<!-- END CTA BLOCK--> <!-- END CTA FULL PAGE -->


<!-- LOOP BLOK -->
<section class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="midblock">
                @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                    @foreach( $allData->pageBuilderContent as $pgd )
                        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                            <!-- Extra SEO -->
                            @if( $pgd->builder_type == 'EXTRA_SEO' && $pgd->position == 'BODY' )
                                <p>{!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}</p>
                            @endif


                            <!-- Image Carousel -->
                            @if( $pgd->builder_type == 'IMAGE_CAROUSEL' && $pgd->position == 'BODY' )
                            <div class="slider_block">
                                <div class="owl-carousel1">
                                    @if( isset($pgd->images) && !empty($pgd->images) && count($pgd->images) != 0 )
                                        @foreach( $pgd->images as $caraImgs )
                                            @if( isset($caraImgs->masterImageInfo) && !empty($caraImgs->masterImageInfo) )
                                                <div class="item">
                                                    <div class="innerslide">
                                                        <img src="{{ asset('public/uploads/files/media_images/'. $caraImgs->masterImageInfo->image) }}"  alt="{{ $caraImgs->img_alt }}" title="{{ $caraImgs->img_title }}" caption="{{ $caraImgs->img_caption }}"/>   
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif          
                                </div>
                            </div> 
                            @endif


                            <!-- Video Gallery -->
                            @if( $pgd->builder_type == 'VIDEO_GALLERY' && $pgd->position == 'BODY' )
                            <div class="slider_block">
                                <div class="owl-carousel2">
                                    @if( isset($pgd->videos) && !empty($pgd->videos) && count($pgd->videos) != 0 )
                                        @foreach( $pgd->videos as $vidGal )

                                            @if( isset($vidGal->masterVideoInfo) && !empty($vidGal->masterVideoInfo) )
                                                <div class="item">
                                                    <div class="innerslide">
                                                        <div class="video_block" style="width: 75%;">
                                                            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $vidGal->masterVideoInfo->video_link }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>   
                                                        </div>
                                                        <div class="caption" style="height: 315px; width: 25%;">
                                                            <p>{{ $vidGal->caption }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif          
                                </div>
                            </div> 
                            @endif

                            <!-- Extra Content -->
                            @if( $pgd->builder_type == 'EXTRA_CONT' && $pgd->position == 'BODY' )
                                <p>{!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}</p>
                            @endif


                            <!-- Container Width Hero Statement -->
                            @if( $pgd->builder_type == 'HERO_SCW' && $pgd->position == 'BODY' )
                                <h6 class="midbody_subheading">{{ $pgd->main_content }}</h6>
                            @endif

                            <!-- Quick Body LINKS -->
                            @if( ($pgd->builder_type == 'PRODUCT_LINKS' || $pgd->builder_type == 'DISTRIBUTOR' || $pgd->builder_type == 'DISTRIBUTOR_PAGE' || $pgd->builder_type == 'PRODUCT_CAT_LINKS' || $pgd->builder_type == 'PEOPLE_LINKS' || $pgd->builder_type == 'NEWS_LINKS' || $pgd->builder_type == 'CUSTOM_LINKS' || strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) && $pgd->position == 'BODY' )

                                <div class="midbody_newsblock">
                                    <h3>{{ $pgd->main_title }}</h3>
                                    @if( isset($pgd->links) )
                                        <div class="news_list">
                                        <ul class="greendot">
                                            @foreach($pgd->links as $lnk)
                                                @php
                                                    $linkData = linkSlugToContent( $lnk->slug );
                                                @endphp
                                                @if( !empty( $linkData ) )
                                                <li>
                                                    <a href="{{ url( $lng.'/'.$lnk->slug ) }}">{{ $linkData->name }}</a>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Accordion -->
                            @if( $pgd->builder_type == 'ACCORDION' && $pgd->position == 'BODY' )
                            <div class="midblock_subblock countries">
                                @if( isset($pgd->accordion) )
                                    @foreach($pgd->accordion as $accr)
                                    <div class="outeraccor">
                                        <div class="accor_heading open_arrow">{{ $accr->heading }}</div>
                                        <div class="accor_body">
                                            <div class="row"><div class="col-md-12"><div class="info">
                                             {!! html_entity_decode( $accr->content ) !!}
                                            </div></div></div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            @endif

                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="rightpanel">
                @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                    @foreach( $allData->pageBuilderContent as $pgd )
                        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                            <!-- Quick Body LINKS -->
                            @if( ($pgd->builder_type == 'PRODUCT_LINKS' || $pgd->builder_type == 'DISTRIBUTOR' || $pgd->builder_type == 'DISTRIBUTOR_PAGE' || $pgd->builder_type == 'PRODUCT_CAT_LINKS' || $pgd->builder_type == 'PEOPLE_LINKS' || $pgd->builder_type == 'NEWS_LINKS' || $pgd->builder_type == 'CUSTOM_LINKS' || strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) && $pgd->position == 'RIGHT' )

                                <div class="midbody_newsblock">
                                    <h3>{{ $pgd->main_title }}</h3>
                                    @if( isset($pgd->links) )
                                        <div class="news_list">
                                        <ul class="arrow-list">
                                            @foreach($pgd->links as $lnk)
                                                @php
                                                    $linkData = linkSlugToContent( $lnk->slug );
                                                @endphp
                                                @if( !empty( $linkData ) )
                                                <li>
                                                    <a href="{{ url( $lng.'/'.$lnk->slug ) }}">{{ $linkData->name }}</a>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif


                            <!-- STICKY BUTTON -->
                            @if( $pgd->builder_type == 'STICKY_BUTT' && $pgd->position == 'RIGHT' )
                                <div class="quote_block" id="sidebar">
                                    <h2>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                    <div class="buttom-row">
                                        <a href="javascript:void(0);" class="submit-btn scroll-btn">{{ $pgd->link_text }}</a>
                                    </div>
                                </div>
                            @endif


                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
<!-- END LOOP BLOCK -->

<!-- LAST BLOCK -->
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )

    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

        <!-- METRIC -->
        @if( $pgd->builder_type == 'METRIC' && $pgd->position == 'BODY' )
            <section class="container">
            <div class="row">
                <div class="col-sm-8"> 
                @if( $pgd->sub_content == 'METRIC_LEFT' )
                <div class="strip_1">
                    <div class="bg_green" style="background-color: {{ $pgd->link_text }}; color: {{ $pgd->link_url }};">
                         <div class="inner-dv">
                            <span class="number">{{ $pgd->main_title }}</span> <span class="text">{{ $pgd->sub_title }}</span>
                        </div>
                    </div>
                    <p>{{ $pgd->main_content }}</p>
                </div>
                @endif
                @if( $pgd->sub_content == 'METRIC_RIGHT' )
                <div class="strip_2">
                    <p>{{ $pgd->main_content }}</p>
                    <div class="bg_blue" style="background-color: {{ $pgd->link_text }}; color: {{ $pgd->link_url }};">
                        <div class="inner-dv">
                            <span class="number">{{ $pgd->main_title }}</span> <span class="text">{{ $pgd->sub_title }}</span>
                        </div>
                    </div>
                    
                </div>
                @endif
                </div>
            </div>
            </section>
        @endif

        <!-- PAGE WIDTH HERO STATEMENT -->
        @if( $pgd->builder_type == 'HERO_SPW' && $pgd->position == 'BODY' )
            <p>&nbsp;</p>
            <div class="padtop"><div class="container">{{ $pgd->main_content }}</div></div>
        @endif
    
    @endif <!-- End Device Checking -->

    @endforeach
@endif
<!-- END LAST BLOCK -->

<!-- Reusable -->
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

            <!-- Eform -->
            @if( $pgd->builder_type == 'REUSE' && $pgd->position == 'BODY' )
                {!! getHtmlReuseBySCODE( $pgd->main_content ) !!}
            @endif
            <!-- End Eform -->
        @endif
    @endforeach
@endif
<!-- End Reusable -->

@endif
@endsection




@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
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
</script>

@if( isset($allData) && $allData->latitude != '' && $allData->longitude != '')
<script type="text/javascript">
function initMap() {

    var getMLat = document.getElementById('mLat').value;
    var getMLng = document.getElementById('mLng').value;
    var getMAddr = document.getElementById('mAddr').value;
    var getMType = document.getElementById('mType').value;
    var getMBname = document.getElementById('mBname').value;

    var gmLink = '<br/><br/><a href="https://maps.google.com/?q='+ getMLat +','+ getMLng +'" target="_blank"><strong>Google View</strong></a>';

    var oneMarker = {
        info: '<strong>'+ getMBname + '</strong> ('+ getMType +')<br/>' + getMAddr + gmLink,
        lat: getMLat,
        long: getMLng
    };

    var locations = [
      [oneMarker.info, oneMarker.lat, oneMarker.long, 0]
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(getMLat, getMLng),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        minZoom: 8,
        maxZoom: 14,
    });

    var infowindow = new google.maps.InfoWindow({});

    var marker, i;
    
    //var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
        //bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    //map.fitBounds(bounds);
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ctyLhYi1UVzqbsc1fLA6evrrdGWeoWs&callback=initMap&sensor=false"></script>
@endif

@endpush

    