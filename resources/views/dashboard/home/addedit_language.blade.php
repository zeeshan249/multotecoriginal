@extends('dashboard.layouts.app')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')

@if( isset($parentLngCont) )
<section class="content-header">
  <h1>
    Home Content
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
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
      
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Home Page Content</h3>

          
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($home) ){{ route('home.adedlngPst', array('pid' => $parentLngCont->id, 'cid' => $home->id)) }}@else{{ route('home.adedlngPst', array('pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            @if(isset($home) && isset($home->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $home->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $home->language_id }}">{{ $home->Language->name }}</option>
                  </select>
                </div>
              </div>
            </div>
            @else
            <div class="col-md-3">
              <div class="form-group">
                <label>Select Language :</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                      <img src="" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  @php
                    $usedLng = array();
                    if( isset($parentLngCont->ChildLanguages) ) {
                      foreach( $parentLngCont->ChildLanguages as $x ) {
                        array_push($usedLng, $x->language_id);
                      }
                    }
                  @endphp
                  <select name="language_id" id="language_id" class="form-control">
                  @if( isset($languages) && !empty($languages) )
                    <option data-image="" value="">-Select Language-</option>
                    @foreach( $languages as $lng )
                      @if( $lng->is_default != '1' && !in_array($lng->id, $usedLng) )
                        <option data-image="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}" value="{{ $lng->id }}">{{ $lng->name }}</option>
                      @endif
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
            </div>
            @endif
            <div class="col-md-9" style="text-align: right;">
            @if(isset($home))
            <a href="{{ route('home.adedlngDel', array('pid' => $parentLngCont->id, 'cid' => $home->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Home Page Heading (H1): <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter home page heading" value="@if( isset($home) ){{ $home->name }}@endif">
              </div>
              <div class="form-group">
                <label>URL/Slug: <em>*</em></label>
                <input type="text" name="slug" class="form-control" @if( isset($home) ) value="{{ url('/'.getLngCode($home->language_id) ) }}" @else value="{{ url('/') }}" @endif disabled="disabled">
              </div>
            </div>
          </div>



          <!------------------------------------------------------------------------------------------------------->
          <!-- META INFO -->
          <div class="row">
            <div class="col-md-10">
              <h3>Page Meta Information</h3>
              <hr/>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Title:</label>
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($home) ){{ $home->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($home) ){{ $home->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($home) ){{ $home->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($home) ){{ $home->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($home) ){{ $home->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="0" @if(isset($home) && $home->follow == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($home) && $home->follow == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="0" @if(isset($home) && $home->index_tag == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($home) && $home->index_tag == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->




          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : (Above the fold content)</label>
                <textarea name="page_content" class="form-control" id="pg_cont">@if( isset($home) ){{ html_entity_decode($home->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>
          
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
              @if( isset($home) )
              <!--input type="submit" class="btn btn-primary" value="Save Changes"-->
              <input type="hidden" id="table_id" value="{{ $home->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $home->insert_id }}">
              @else
              <!--input type="submit" class="btn btn-primary" value="Save All"-->
              <!--a href="{{ route('addProd') }}" class="btn btn-danger">Cancel</a-->
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
            </div>
            <div class="col-md-6"></div>
          </div>
          <div class="row" id="shared-lists">
           @include('dashboard.page_builder.edit_box')
          </div>
          </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>

<!-- Page Builder -->
@include('dashboard.page_builder.fix_design') <!-- Page Builder Buttons -->

@include('dashboard.page_builder.seo.html_layout') <!-- Extra Seo -->

@include('dashboard.page_builder.content.html_layout') <!-- Content -->

@include('dashboard.page_builder.cta.html_layout') <!-- CTA -->

@include('dashboard.page_builder.sticky_button.html_layout') <!-- Sticky Button -->

@include('dashboard.page_builder.hero_pw.html_layout') <!-- Hero Statement PAGE WIDTH -->

@include('dashboard.page_builder.hero_cw.html_layout') <!-- Hero Statement CONTAINER WIDTH -->

@include('dashboard.page_builder.form.html_layout') <!-- E-Form -->

@include('dashboard.page_builder.image_carousel.html_layout') <!-- Image Carousel -->

@include('dashboard.page_builder.brochure.html_layout') <!-- Brochure -->

@include('dashboard.page_builder.video.html_layout') <!-- Video -->

@include('dashboard.page_builder.links.html_layout') <!-- links -->

@include('dashboard.page_builder.product_box.html_layout') <!-- product box -->

@include('dashboard.page_builder.custom_links.html_layout') <!-- custom links -->

@include('dashboard.page_builder.metric.html_layout') <!-- metric -->

@include('dashboard.page_builder.accordion.html_layout') <!-- accordion -->

@include('dashboard.page_builder.image_gal_btn.html_layout') <!-- Image Gallery -->

<!-- End Page Builder -->


{{--

@include('dashboard.modals.editor_element_modal')
@include('dashboard.modals.editor_imgmedia_modal')

--}}

@endif

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">
$( function() {
  $('.pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
});
</script>


<script type="text/javascript">
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    name: {
      required: true,
      minlength: 3
    },
    language_id: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Home Page Heading.'
    },
    language_id: {
      required: 'Please Select Language.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
</script>

@include('dashboard.page_builder.seo.script')
@include('dashboard.page_builder.content.script')
@include('dashboard.page_builder.cta.script')
@include('dashboard.page_builder.sticky_button.script')
@include('dashboard.page_builder.hero_pw.script')
@include('dashboard.page_builder.hero_cw.script')
@include('dashboard.page_builder.form.script')
@include('dashboard.page_builder.image_carousel.script')
@include('dashboard.page_builder.brochure.script')
@include('dashboard.page_builder.video.script')
@include('dashboard.page_builder.links.script')
@include('dashboard.page_builder.product_box.script')
@include('dashboard.page_builder.custom_links.script')
@include('dashboard.page_builder.metric.script')
@include('dashboard.page_builder.accordion.script')
@include('dashboard.page_builder.image_gal_btn.script')

@include('dashboard.page_builder.script')

{{--

@include('dashboard.modals.editor_element_modal_script')
@include('dashboard.modals.editor_imgmedia_modal_script')

--}}




@endpush