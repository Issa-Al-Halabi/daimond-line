@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('coupons') }}">@lang('fleet.customers')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.add_new_point')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.add_new_coupon')
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
                    {!! Form::open(['route' => 'coupons.store', 'files' => true, 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-md-6">
                           
                            
                            <div class="form-group">
                                {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('code', __('fleet.code'), ['class' => 'form-label']) !!}
                                {!! Form::text('code', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('discount', __('fleet.discount'), ['class' => 'form-label']) !!}
                                {!! Form::number('discount', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('limit', __('fleet.limit'), ['class' => 'form-label']) !!}
                                {!! Form::number('limit', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('start_date', __('fleet.start_date'), ['class' => 'form-label']) !!}
                                {!! Form::date('start_date', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('expire_date', __('fleet.expire_date'), ['class' => 'form-label']) !!}
                                {!! Form::date('expire_date', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.add_new_coupon'), ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
