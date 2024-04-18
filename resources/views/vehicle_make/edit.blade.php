@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">{{ link_to_route('vehicle-make.index', __('fleet.make'))}}</li>
<li class="breadcrumb-item active">@lang('fleet.edit_vehicle_make')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_vehicle_make')</h3>
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

      {!! Form::open(['route' => ['vehicle-make.update',$vehicle_make->id],'method'=>'PATCH','files'=>true]) !!}
      {!! Form::hidden('id',$vehicle_make->id) !!}
      {!! Form::hidden('edit',1) !!}

      <div class="row">
        <div class="form-group col-md-6">
          {!! Form::label('make', __('fleet.make'), ['class' => 'form-label']) !!}
          {!! Form::text('make', $vehicle_make->make,['class' => 'form-control','required']) !!}
        </div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('image', __('fleet.picture'), ['class' => 'form-label']) !!}
            @if($vehicle_make->image) <a href="{{url('uploads/'.$vehicle_make->image)}}" target="blank">View</a> @endif
            <br>
            {!! Form::file('image',null,['class' => 'form-control']) !!}
          </div>
        </div>
     </div>
      <div class="form-group">
        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
      </div>
      {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection