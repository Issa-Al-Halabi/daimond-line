@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('points') }}">@lang('fleet.customers')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.add_new_point')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.add_new_point')
                    </h3>
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
                    {!! Form::open(['route' => 'points.store', 'files' => true, 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-md-6">
                           
                            <div class="form-group">
                                {!! Form::label('trip_type', __('fleet.trip_type'), ['class' => 'form-label']) !!}
                                <select name="trip_type" id="trip_type" class="form-control" required>
                                    <option selected value="Out city trips">Out city trips</option>
                                    <option value="instant trips">instant trips</option>
                                    <option value="delayed trips">delayed trips</option>
                                </select>
                            </div>
                            <div class="form-group">
                                {!! Form::label('point', __('fleet.point'), ['class' => 'form-label']) !!}
                                {!! Form::number('point', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('qty', __('fleet.qty'), ['class' => 'form-label']) !!}
                                {!! Form::number('qty', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.add_new_point'), ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
