@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        Home Page Banner Management
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
      <a href="{{ route('addBann') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Banner</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  
  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">Banners List</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
            <th>SL</th>
            <th>Action</th>
            <th>Status</th>
            <th>Image</th>
            <th>Name</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($banners))
          @php $sl = 1; @endphp
          @forelse($banners as $banr)
          @if(isset($banr->BannerImages))
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('media_img_detl', array('id' => $banr->image_id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>

              <a href="{{ route('delBann', array('imgid' => $banr->image_id)) }}" onclick="return confirm('Sure To Delete This Banner ?');"><i class="fa fa-trash-o base-red fa-2x"></i></a>
            </td>
            <td>
              @if($banr->BannerImages->status == '1')
                <a href="{{ route('acInac') }}?id={{ $banr->image_id }}&val=2&tab=image"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($banr->BannerImages->status == '2')
                <a href="{{ route('acInac') }}?id={{ $banr->image_id }}&val=1&tab=image"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>
              <img src="{{ asset('public/uploads/files/media_images/thumb/'. $banr->BannerImages->image) }}" class="img-thumbnail"> 
            </td>
            <td>{{ $banr->BannerImages->name }}</td>
            <td>
              {{ date('m-d-Y', strtotime($banr->BannerImages->created_at)) }}
            </td>
          </tr>
          @php $sl++; @endphp
          @endif
          @empty
          @endforelse
        @endif
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script type="text/javascript">
$(function() {
  $('.ar-datatable').DataTable({
    "columnDefs": [ {
      "targets": [ 0, 5 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush