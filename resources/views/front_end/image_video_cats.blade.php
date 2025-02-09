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
            @if(isset($tab_tag) && $tab_tag == 'image_gallery')
            <li>Image Gallery</li>
            @endif
            @if(isset($tab_tag) && $tab_tag == 'video_gallery')
            <li>Video Gallery</li>
            @endif
        </ul>
    </div>

</section>


<section class="container">
    <div class="midblock">
        <div class="tabblock">
            <div>
                <div class="row artab">
                    <div class="col-sm-6 tab @if(isset($tab_tag) && $tab_tag == 'image_gallery') active @endif">
                        <a href="{{ route('img_gal_cats', array('lng' => $lng)) }}">Photo Gallery</a></div>
                    <div class="col-sm-6 tab @if(isset($tab_tag) && $tab_tag == 'video_gallery') active @endif">
                        <a href="{{ route('vid_gal_cats', array('lng' => $lng)) }}">Video Gallery</a></div>
                </div>
                <div class="jq-tab-content-wrapper">
                    <div class="jq-tab-content active">
                    @if(isset($extraContent) && $extraContent->page_content != '')
                    <div class="tab_info_content">
                        <p>{!! html_entity_decode($extraContent->page_content, ENT_QUOTES) !!}</p>
                    </div>
                    @endif
                        <div class="tab_info_list">
                            <div class="row">
                                @if(isset($tab_tag) && $tab_tag == 'image_gallery')
                                    @if( isset($allcats) )
                                        @forelse( $allcats as $ic )
                                        <div class="col-sm-4">
                                            <ul>
                                                <li>
                                                <a href="{{ route('front_galSubCat', array('lng' => $lng, 'category' => $ic->slug)) }}">{{ $ic->name }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @empty
                                        <div class="col-sm-4">
                                            <ul>
                                                <li>
                                                    <a href="#">No Record Found.</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @endforelse
                                    @endif
                                @endif
                                @if(isset($tab_tag) && $tab_tag == 'video_gallery')
                                    @if( isset($allcats) )
                                        @forelse( $allcats as $vc )
                                        <div class="col-sm-4">
                                            <ul>
                                                <li>
                                                <a href="{{ route('front_galVSubCat', array('lng' => $lng, 'category' => $vc->slug)) }}">{{ $vc->name }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @empty
                                        <div class="col-sm-4">
                                            <ul>
                                                <li>
                                                    <a href="#">No Record Found.</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @endforelse
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-sm-4">
            {{ $allcats->links() }}
        </div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <div class="search">
                <form name="frmsx" method="GET">
                    <input type="text" class="form-control" name="search" placeholder="Search" value="@if(isset($_GET['search'])){{ $_GET['search'] }}@endif">
                    <button type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>

    </div>
</section>

@endsection






@push('page_js')
<script type="text/javascript">
$( function() {
    $('ul.pagination li:first').find('span').text('Prev');
    $('ul.pagination li:last').find('span').text('Next');

    $('ul.pagination li [rel=prev]').html('Prev');
    $('ul.pagination li [rel=next]').html('Next');

} );
</script>
@endpush

    