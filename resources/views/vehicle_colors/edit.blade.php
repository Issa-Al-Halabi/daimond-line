@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" href="{{ asset('assets/colorpicker/la_color_picker.css') }}">
<style>
  .inp {
    border: 1px solid #949494;
    border-radius: 3px;
    padding: 10px;
    font-size: 110%;
  }
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item">{{ link_to_route('vehicle-color.index', __('fleet.vehicle_colors'))}}</li>
<li class="breadcrumb-item active">@lang('fleet.edit_vehicle_color')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_vehicle_color')</h3>
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

      {!! Form::open(['route' => ['vehicle-color.update',$color->id],'method'=>'PATCH']) !!}
      {!! Form::hidden('id',$color->id) !!}

      <div class="row">
        <div class="form-group col-md-6">
          {!! Form::label('color', __('fleet.color'), ['class' => 'form-label']) !!}
          {!! Form::text('color', $color->color,['class' => 'form-control','required']) !!}
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
@section('script')
<script src="{{ asset('assets/colorpicker/la_color_picker.js') }}"></script>
@endsection