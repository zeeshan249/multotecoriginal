@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    All Events
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">All Events</li>
  </ol>
</section>
@endsection

@section('content')
<style>
  .mask {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
    /*required for z-index*/
    z-index: 1000;
    /*puts on top of everything*/
    background-image: url(../../../public/loading.gif);
  }

  #loader {
    width: 60px;
    height: 60px;
    border: 10px solid #f3f3f3;
    border-top: 10px solid #3c8dbc;
    border-radius: 50%;
    animation: spin 2s linear infinite, heart-beat 2s linear infinite;
    background-color: #fff;
    text-align: center;
    line-height: 60px;
    z-index: 9999;
  }


  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes heart-beat {
    55% {
      background-color: #fff;
    }

    60% {
      background-color: #3c8dbc;
    }

    65% {
      background-color: #fff;
    }

    70% {
      background-color: #3c8dbc;
    }

    100% {
      background-color: #fff;
    }
  }

  #loader-block {
    position: absolute;
    background-color: rgba(0, 0, 0, .2);
    width: 100%;
    height: 100%;
    display: flex;
    z-index: 999;
    align-items: center;
    justify-content: center;
  }
</style>
<section class="content">


  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('addEventManagement') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create New
        Event</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>


  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif


  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div id="loader-block">
      <div id="loader"></div>
    </div>
    <form name="frmx" action="{{ route('downloadEventUsers') }}" method="post">
      {{ csrf_field() }}
      <div class="box-header with-border">
        <h3 class="box-title">All Events</h3>
        <!-- Start -->

        <!-- End -->
        <div class="box-tools pull-right">
          <div class="row">
            <div class="col-md-3 pull-right" style="text-align:right;padding-left: 0px;">
              {{-- <button type="button" name="filter" id="filter" class="btn btn-info btn-sm">Filter</button>
              <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">Refresh</button> --}}
              {{-- <a href="{{ route('webiDWN') }}" class="btn btn-default btn-sm">Download Excel</a> --}}
              <button name="action_btn" value="download" type="submit" class="btn btn-default btn-sm">Download Excel</button>

            </div>
            <div class="col-md-6 pull-right" style="text-align:right; padding:0 0 20px 0;">
              <div class="input-group input-daterange">
                <input type="text" name="from_date" id="from_date" readonly class="form-control" />
                <div class="input-group-addon">to</div>
                <input type="text" name="to_date" id="to_date" readonly class="form-control" />
              </div>
            </div>


          </div>
          <!-- <button type="submit" name="action_btn" class="btn btn-success btn-sm" value="activate">Activate</button>
        <button type="submit" name="action_btn" class="btn btn-warning btn-sm" value="deactivate">Deactivate</button>
        <button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete" onclick="return confirm('Are You Sure You Want To Delete Selected ?');">Delete</button> -->
        </div>
      </div>

      <div class="box-body" style="position:relative;">

        <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%; position:relative;">
          <thead>
            <tr>
              <th><input type="checkbox" id="ckAll"></th>
              <th>Action</th>
              <th>Event Name</th>
              <th>Event Type</th>
              <th>Event Start Date</th>
              <th>Event End Date</th>
              <th>Event Location</th>
              <th>Event URL</th>
            </tr>
          </thead>
          <tbody id="webinar_body">
            @if(isset($eventsAll))
            @php $sl = 1; @endphp
            @forelse($eventsAll as $pc)
            @php
            // $hit_count = getHits($pc->url);
            

            // $lngcode = getLngCode($pc->language_id);
            @endphp
            <tr>
              <td>
                {{ $sl }}
                <input type="checkbox" name="ids[]" class="ckbs" value="{{ $pc->id }}">
              </td>
              <td>

                @php $url=route('editEvent', array('id' => $pc->id)) @endphp

                <a href="{{ route('editEvent', array('id' => $pc->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
                <a href="{{ route('deleteEvent', array('id' => $pc->id)) }}" onclick="return confirm('Sure To Delete This Event ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
               

              </td>
          
            


              <!-- <td></td>
            <td></td> -->
              <td>{{ ucfirst($pc->name??'') }}</td>
              
              <td>{{$pc->event_type??''}}</td>

              <td> {{ date('d-M-Y', strtotime($pc->event_start_date)) }} </td>
              @if(empty($pc->event_end_date))
              <td> N.A </td>
              @else
              <td> {{ date('d-M-Y', strtotime($pc->event_end_date)) }} </td>
              @endif
          
              <td> {{$pc->event_location??''}} </td>
              <td> 
                <a href="{{ $pc->event_url??'' }}" target="_blank"><i class="fa fa-eye fa-2x base-green"></i></a>
            </td>
            </tr>
            @php $sl++; @endphp
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
    </form>
  </div>
  <!-- /.box -->

</section>
@endsection

@push('page_js')
<script type="text/javascript">
  $(function() {



    var table = $('.ar-datatable').DataTable({
      "scrollX": true,
      // buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],


      "columnDefs": [{
        "targets": [0, 1, 2],
        "orderable": false
      }]
    });

  });
  $(function() {
    $("#ckAll").on('click', function() {
      var isCK = $(this).is(':checked');
      if (isCK == true) {
        $('.ckbs').prop('checked', true);
        $('#delAll').removeAttr('disabled');
      }
      if (isCK == false) {
        $('.ckbs').prop('checked', false);
        $('#delAll').attr('disabled', 'disabled');
      }
      colMark();
      $('#delAll').val('Delete Selected');
    });
    $(".ckbs").on('click', function() {
      var c = 0;
      $(".ckbs").each(function() {
        colMark();
        if ($(this).is(':checked')) {
          c++;
        }
      });
      if (c == 0) {
        $("#ckAll").prop('checked', false);
        $('#delAll').attr('disabled', 'disabled');
      }
      if (c > 0) {
        $("#ckAll").prop('checked', true);
        $('#delAll').removeAttr('disabled');
      }
      $('#delAll').val('Delete Selected (' + c + ')');
    });
  });

  function colMark() {
    $('.ckbs').each(function() {
      if ($(this).is(':checked')) {
        $(this).parents('tr').css('background-color', '#ffe6e6');
      } else {
        $(this).parents('tr').removeAttr('style');
      }
    });
  }
</script>


<script type='text/javascript'>
  function action(url, id) {

    var language_id = $("#language_id" + id).val();
    var csrf = $("#csrf" + id).val();

    var form = document.createElement("form");
    element1 = document.createElement("input");
    element2 = document.createElement("input");
    form.action = url;
    form.method = "post";
    element1.name = "language_id";
    element1.value = language_id;
    element2.name = "_token";
    element2.value = csrf;
    form.appendChild(element1);
    form.appendChild(element2);
    document.body.appendChild(form);
    form.submit();
  }
</script>
<script src="{{ asset('public/assets/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('public/assets/bootstrap-datetimepicker.min.js') }}"></script>

<script type="text/javascript">
  jQuery("#from_date").datepicker({
    format: 'mm-dd-yyyy'
  });
  jQuery("#to_date").datepicker({
    format: 'mm-dd-yyyy'
  });

  $(document).ready(function() {
    $("#loader-block").hide();

    $("#filter").on('click', function() {
      $("#loader-block").show();
      let from_date = $("#from_date").val();
      let to_date = $("#to_date").val();
      let url = "{{route('ajaxEvents')}}";
      let data = {
        from_date: from_date,
        to_date: to_date,
      };
      $("#content").html('<img src="ajax-icon-from-www.ajaxload.info">');

      $.get(url, data, function(res) {
        $("#loader-block").hide();

        if (res.success == true) {
          $('.ar-datatable').DataTable().destroy();
          $("#webinar_body").html(res.html);
          $('.ar-datatable').DataTable().draw();
        }
      })

    })
    $("#refresh").on('click', function() {
      $("#loader-block").show();

      let url = "{{route('ajaxRefreshEvents')}}";
      $("#from_date").val('');
      $("#to_date").val('');
      $.get(url, function(res) {
        $("#loader-block").hide();

        if (res.success == true) {
          $('.ar-datatable').DataTable().destroy();
          $("#webinar_body").html(res.html);
          $('.ar-datatable').DataTable().draw();
        }
      })

    })


  });
</script>
@endpush