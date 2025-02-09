@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($product))
    Edit Product
    @else
    Add New Product
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allProds') }}">All Products</a></li>
    @if(isset($product))
    <li class="active">Edit Product</li>
    @else
    <li class="active">Add Product</li>
    @endif
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
      <a href="{{ route('allProds') }}" class="btn btn-primary"> All Products</a>
      @if(isset($product) && $product->slug != '')
      <a href="{{ url($product->slug) }}" target="_blank" class="btn btn-primary"> View Page</a>
      @endif
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($product)) Edit Product @else Add Product @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($product) ){{ route('updateProd', array('id' => $product->id)) }}@else{{ route('saveProd') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Default Language :</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    @if( isset($languages) && !empty($languages) )
                      @foreach( $languages as $lng )
                        @if( $lng->is_default == '1' )
                          <img src="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}" style="height: 20px;" id="flag">
                        @endif
                      @endforeach
                    @endif
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                  @if( isset($languages) && !empty($languages) )
                    @foreach( $languages as $lng )
                      @if( $lng->is_default == '1' )
                        <option value="{{ $lng->id }}">{{ $lng->name }}</option>
                      @endif
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-9" style="text-align: right;">
              @if( isset($product) && !empty($product) )
                @if( isset($product->ChildLanguages) && count($product->ChildLanguages) < count($languages) - 1 )
                <a href="{{ route('prod.adedlng', array('pid' => $product->id)) }}"><i class="fa fa-plus"></i> Add Language ?</a>
                @endif
                @if( isset($product->ChildLanguages) )
                  @foreach( $product->ChildLanguages as $chl )
                    @if( isset($chl->Language) )
                      @php $Lng = $chl->Language; @endphp
                      <a href="{{ route('prod.adedlng', array('pid' => $product->id, 'cid' => $chl->id)) }}" class="lngLink btn btn-xs">
                        <img src="{{ asset('public/uploads/flags/thumb/'. $Lng->flag) }}"> {{ $Lng->name }}
                      </a>
                    @endif
                  @endforeach
                @endif
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Product Name (H1): <em>*</em></label>
                <input type="text" name="name" id="prodName" class="form-control" placeholder="Enter Product Name" value="@if( isset($product) ){{ $product->name }}@endif">
              </div>
              <div class="form-group">
                <label>Link or Url : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter Link or Page URL" value="@if( isset($product) ){{ $product->slug }}@endif" @if( isset($product) && $product->is_duplicate == '0') readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Short Description : <em>*</em></label>
                <textarea name="description" id="description" class="form-control" placeholder="Short Description">@if( isset($product) ){{ $product->description }}@endif</textarea>
              </div>

              <div class="form-group">
                @php
                if( isset($product) && isset($product->ProductCategoryIds) && count($product->ProductCategoryIds) > 0 ) {
                  $seleArr = array();
                  foreach( $product->ProductCategoryIds as $v ) {
                    array_push( $seleArr, $v->product_category_id );
                  }
                }
                @endphp
                <label>Select Categories :</label>
                <select name="category_id[]" class="form-control">
                  <option value="">-Select Product Category-</option>
                  @if( isset($allCats) && !empty($allCats) )
                    @foreach( $allCats as $pc )
                    <option value="{{ $pc->id }}" @if( isset($product) && isset($seleArr) && !empty($seleArr) && in_array($pc->id, $seleArr) ) selected="selected" @endif>{{ ucfirst( $pc->name ) }}</option>
                    @endforeach
                  @endif
                </select>
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
                    <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($product) ){{ $product->meta_title }}@endif">
                  </div>
                  <div class="form-group">
                    <label>Meta Keywords:</label>
                    <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($product) ){{ $product->meta_keyword }}@endif">
                  </div>
                  <div class="form-group">
                    <label>Meta Description:</label>
                    <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($product) ){{ $product->meta_desc }}@endif</textarea>
                  </div>
                  <div class="form-group">
                    <label>Canonical Url:</label>
                    <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($product) ){{ $product->canonical_url }}@endif">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Language Tag:</label>
                    <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($product) ){{ $product->lng_tag }}@endif">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Add No Follow Tag :</label>
                    <select name="follow" class="form-control">
                      <option value="1" @if(isset($product) && $product->follow == '1') selected="selected" @endif>FOLLOW</option>
                      <option value="0" @if(isset($product) && $product->follow == '0') selected="selected" @endif>NO FOLLOW</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Add No Index Tag :</label>
                    <select name="index_tag" class="form-control">
                      <option value="1" @if(isset($product) && $product->index_tag == '1') selected="selected" @endif>INDEX</option>
                      <option value="0" @if(isset($product) && $product->index_tag == '0') selected="selected" @endif>NO INDEX</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-10">
                  <div class="form-group">
                    <label> Add Structured data mark-up (Json-LD) :</label>
                    <textarea name="json_markup" class="form-control" rows="6">@if( isset($product) ){!! html_entity_decode($product->json_markup, ENT_QUOTES) !!}@endif</textarea>
                  </div>
                </div>
              </div>
              <!-- END META INFO -->
              <!------------------------------------------------------------------------------------------------------->

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Product Image :</label><br/>
                <!-- CALL SCRIPT -->
                <button type="button" class="btn btn-default addMedImgBtn" title="Product Main Image" data="imgIds_Box"><i class="fa fa-picture-o" aria-hidden="true"></i> Add Product Main Image</button>
                
                <input type="hidden" id="imgIds_Box-idholder" name="main_image_ids">

                <input type="hidden" id="imgIds_Box-infoholder" name="main_image_infos">
                
                <div id="imgIds_Box-dispDiv"></div>
                <!-- END -->

              </div>
            </div>
            <div class="form-group">
                <label>Page Banner Image</label><br/>
                <!-- CALL SCRIPT -->
                <button type="button" class="btn btn-default addMedImgBtn" title="Add Page Banner Image" data="catPageimgIds_Box"><i class="fa fa-picture-o" aria-hidden="true"></i> Add Page Banner Image</button>
                
                <input type="hidden" id="catPageimgIds_Box-idholder" name="banner_image_ids">

                <input type="hidden" id="catPageimgIds_Box-infoholder" name="banner_image_infos">
                
                <div id="catPageimgIds_Box-dispDiv"></div>
                <!-- END -->

              </div>
          </div>

          <div class="row">
            <div class="col-md-6">
            @if( isset($product) && isset($product->ProductImages) && count($product->ProductImages) > 0 )
              @foreach( $product->ProductImages as $imgs )
                @if( isset($imgs->imageInfo) )
                <div class="col-md-3">
                <div class="thumbnail">
                  <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                  <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                  <a href="javascript:void(0);" class="idel ifdel" data="products_images_map" id="{{ $imgs->id }}">
                    <i class="fa fa-times base-red" aria-hidden="true"></i>
                  </a>
                </div>
                </div>
                @endif
              @endforeach
            @endif
            </div>
            <div class="col-md-6">
            @if( isset($product) && isset($product->allImgIds) && count($product->allImgIds) > 0 )
              @foreach( $product->allImgIds as $imgs )
              @if( $imgs->image_type == 'BANNER_IMAGE' )
                @if( isset($imgs->imageInfo) )
                <div class="col-md-3">
                <div class="thumbnail">
                  <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                  <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                  <a href="javascript:void(0);" class="idel ifdel" data="products_images_map" id="{{ $imgs->id }}">
                    <i class="fa fa-times base-red" aria-hidden="true"></i>
                  </a>
                </div>
                </div>
                @endif
              @endif
              @endforeach
            @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : <small>(Above the fold content)</small></label> 
                <textarea name="page_content" class="form-control" id="pg_cont" data-error-container="#pg_cont_error">@if( isset($product) ){{ html_entity_decode($product->page_content, ENT_QUOTES) }}@endif</textarea>
                <div id="pg_cont_error"></div>
              </div>
            </div>
          </div>

          <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
              @if( isset($product) )
              <!--input type="submit" class="btn btn-primary" value="Save Changes"-->
              <input type="hidden" id="table_id" value="{{ $product->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $product->insert_id }}">
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

<!-- End Page Builder -->

{{--
@include('dashboard.page_builder.tech_res.html_layout') <!-- Technical Resource -->
@include('dashboard.page_builder.image_gal_btn.html_layout') <!-- Image Gallery -->
@include('dashboard.modals.editor_element_modal')
--}}

@include('dashboard.modals.editor_imgmedia_modal')

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
  $('#category_id').multiselect({
    buttonWidth : '100%',
    includeSelectAllOption : true,
    nonSelectedText: 'Select Categories',
    enableFiltering: true,
    filterPlaceholder: 'Search Categories..',
    enableCaseInsensitiveFiltering: true,
    //enableClickableOptGroups: true,
    //enableCollapsibleOptGroups: true,
    selectAllText: 'All Categories',
    maxHeight: 300
  });
});

var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );



var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.pg_cont.updateElement();
});
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
    slug: {
      required: true,
      nowhitespace: true,
      pattern: /^[A-Za-z\d-.]+$/,
      remote:{
        url: "{{ route('checkSlugUrl') }}",
        type: "post",
        data: {
          "slug_url": function() {
            return $( "#pgSlug" ).val();
          },
          "_token": function() {
            return "{{ csrf_token() }}";
          },
          "id": function() {
            return $( "#table_id" ).val();
          }
        }
      }
    },
    language_id: {
      required: true
    },
    page_content: {
      required: true
    },
    insert_id: {
      required: true
    },
    description: {
      required: true
    },
    canonical_url: {
      url: true
    },
    "category_id[]": {
      required: true
    },
    meta_title: {
      required: true
    },
    meta_keyword: {
      required: true
    },
    meta_desc: {
      required: true
    },
    lng_tag: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Product Name.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    language_id: {
      required: 'Please Select Language'
    },
    page_content: {
      required: 'Please Enter Page Contents'
    },
    insert_id: {
      required: 'SERVER ERROR:: InsertID Not Created!'
    },
    description: {
      required: 'Enter Short Description'
    },
    canonical_url: {
      required: 'Please Enter Valid URL.'
    },
    "category_id[]": {
      required: "Please select product category."
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
  }
});
$( function() {
  <?php if( !isset($product) || (isset($product) && $product->is_duplicate == '1') ) { ?>
  $('#prodName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }
  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text
  return str;
}
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

@include('dashboard.page_builder.script')

{{--
@include('dashboard.page_builder.tech_res.script')
@include('dashboard.page_builder.image_gal_btn.script')
@include('dashboard.modals.editor_element_modal_script')
--}}

@include('dashboard.modals.editor_imgmedia_modal_script')

@endpush