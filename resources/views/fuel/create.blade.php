@extends('layouts.app')

@section("breadcrumb")

@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.Terms_and_Conditions')</h3>
      </div>

      <div class="card-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        
           <?php
  
                $fuel=DB::table('fuel')->where('reference','!=','null')->first();

               ?>
       @if($fuel==null)
        {!! Form::open(['route' => 'fuel.store','method'=>'post','files'=>true]) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" id="editor">
              {!! Form::textarea('reference',null,['class'=>'form-control ckeditor ']) !!}
            </div>
          </div>
          
        </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.add'), ['class' => 'btn btn-success']) !!}
         
          </div>
        </div>
      </div>
      
       <div class="card-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        
           <?php
 
                $fuel=DB::table('fuel')->where('reference','!=','null')->first();
               ?>
        @elseif($fuel!==null)
        {!! Form::open(['route' => ['fuel.update', $fuel->id],'method'=>'PATCH','files'=>true]) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        {!! Form::hidden('id', $fuel->id) !!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" id="editor">
              {!! Form::textarea('reference',$fuel->reference,['class'=>'form-control ckeditor ']) !!}
            </div>
          </div>
          
        </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.add'), ['class' => 'btn btn-success']) !!}
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('.ckeditor').ckeditor(function() {
        // Configuration options for CKEditor
        this.config.extraPlugins = 'justify'; // Add the 'justify' plugin for alignment options
        this.config.toolbar = [
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'align', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'RemoveFormat'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            //{ name: 'insert', items: ['Image', 'Table', 'SpecialChar'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'tools', items: ['Undo', 'Redo'] },
            { name: 'document', items: ['Source'] },
           { name: 'justify', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        ];
    });
});



quill.on('text-change', function(delta, oldDelta, source) {
    document.getElementById('content').value = quill.root.innerHTML;
});
  $(document).ready(function() {
  $("#vehicle_id").select2({placeholder: "@lang('fleet.selectVehicle')"});
  $("#vendor_name").select2({placeholder: "@lang('fleet.select_fuel_vendor')"});

  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $("#date").on("dp.change", function (e) {
    var date=e.date.format("YYYY-MM-DD");
  });

    //Flat green color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

  $(".fuel_from").change(function () {
    if ($("#r1").attr("checked")) {
      $('#vendor_name').show();
    }
    else {
      $('#vendor_name').hide();
    }
  });
});
</script>
@endsection
@section('extra_css')
<style type="text/css">
  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  /* Hide default HTML checkbox */
  .switch input {
    display: none;
  }

  /* The slider */
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection