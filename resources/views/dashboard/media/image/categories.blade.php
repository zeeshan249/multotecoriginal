@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Image Groups
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Image Groups</li>
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
      <a href="{{ route('media_img_cats_crte') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> 
      Create New Category/Subcategory</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Image Categories & Subcategories</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable display nowrap" style="width: 100%;">
        <thead>
          <tr>
            <th>SL</th>
            <th>Action</th>
            <th>Status</th>
            <th>Name</th>
            <th>URL</th>
            <th>Parent</th>
            <th>Created</th>
            <th>Modified</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($allCats))
          @php $sl = 1; @endphp
          @forelse($allCats as $cat)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('media_img_cats_edt', array('id' => $cat->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
              <a href="{{ route('media_img_cats_del', array('id' => $cat->id)) }}" onclick="return confirm('Sure To Delete This Group ?');"><i class="fa fa-trash-o fa-2x base-red"></i></a>
              <a href="{{ url($cat->slug) }}" target="_blank">
              <i class="fa fa-2x fa-eye" aria-hidden="true"></i></a>
            </td>
            <td>
              @if($cat->status == '1') <span class="base-green">Active</span> @endif
              @if($cat->status == '2') <span class="base-red">Inctive</span> @endif
            </td>
            <td>{{ $cat->name }}</td>
            <td>{{ url($cat->slug) }}</td>
            <td>
              @if( isset($cat->parent) && !empty($cat->parent) )
              {{ $cat->parent->name }}
              @endif
            </td>
            <td>{{ date('d-m-Y', strtotime($cat->created_at)) }}</td>
            <td>{{ date('d-m-Y', strtotime($cat->updated_at)) }}</td>
          </tr>
          @php $sl++; @endphp
          @empty
          <tr>
            <td colspan="6">No Records Found!</td>
          </tr>
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
    "scrollX": true,
    "columnDefs": [ {
      "targets": [  0,1,2 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush