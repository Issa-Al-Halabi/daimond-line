@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">{{link_to_route('vehicle-model.index', __('fleet.vehicle_models')) }}</li>
<li class="breadcrumb-item active">@lang('fleet.add_vehicle_model')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.add_vehicle_model')</h3>
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

      {!! Form::open(['route' => 'vehicle-model.store','method'=>'post']) !!}
      <div class="row">
        <div class="form-group col-md-6">
          {!! Form::label('make_id',__('fleet.SelectVehicleMake'), ['class' => 'form-label']) !!}
          <select id="make_id" name="make_id" class="form-control" required>
            <option value="">@lang('fleet.SelectVehicleMake')</option>
            @foreach($vehicle_makes as $vehicle_make)
            <option value="{{$vehicle_make->id}}" >{{$vehicle_make->make}}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-6">
          {!! Form::label('model', __('fleet.model'), ['class' => 'form-label']) !!}
          {!! Form::text('model', null,['class' => 'form-control','required']) !!}
        </div>
      </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="form-group col-md-4">
            {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-success']) !!}
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection

@section("script")
<script type="text/javascript">
  $('#make_id').select2({placeholder: "@lang('fleet.SelectVehicleMake')"});
</script>
@endsection