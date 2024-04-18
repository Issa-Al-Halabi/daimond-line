@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">{{ link_to_route('vehicle-make.index', __('fleet.make'))}}</li>
<li class="breadcrumb-item active">@lang('fleet.add_vehicle_make')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.add_vehicle_make')</h3>
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

      {!! Form::open(['route' => 'vehicle-make.store','method'=>'post','files'=>true]) !!}
      <div class="row">
        <div class="form-group col-md-6">
          {!! Form::label('make', __('fleet.make'), ['class' => 'form-label']) !!}
          {!! Form::text('make', null,['class' => 'form-control','required']) !!}
        </div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('image', __('fleet.picture'), ['class' => 'form-label']) !!}
            <br>
            {!! Form::file('image',null,['class' => 'form-control']) !!}
          </div>
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