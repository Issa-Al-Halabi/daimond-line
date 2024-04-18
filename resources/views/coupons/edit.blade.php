@extends('layouts.app')
@section('extra_css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">

    <style type="text/css">
        .select2-selection:not(.select2-selection--multiple) {
            height: 38px !important;
        }

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('points') }}">@lang('fleet.coupon')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.edit_coupon')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.edit_coupon')</h3>
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

                    {!! Form::open(['route' => ['coupons.update', $coupon->id], 'files' => true, 'method' => 'PATCH']) !!}
                    {!! Form::hidden('id', $coupon->id) !!}
                    {!! Form::hidden('edit', '1') !!}


                    <div class="row">
                        <div class="col-md-6">
                           
                            
                            <div class="form-group">
                                {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
                                {!! Form::text('title', $coupon->title, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('code', __('fleet.code'), ['class' => 'form-label']) !!}
                                {!! Form::text('code', $coupon->code, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('discount', __('fleet.discount'), ['class' => 'form-label']) !!}
                                {!! Form::number('discount', $coupon->discount, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('limit', __('fleet.limit'), ['class' => 'form-label']) !!}
                                {!! Form::number('limit', $coupon->limit, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('start_date', __('fleet.start_date'), ['class' => 'form-label']) !!}
                                {!! Form::date('start_date', $coupon->start_date, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('expire_date', __('fleet.expire_date'), ['class' => 'form-label']) !!}
                                {!! Form::date('expire_date', $coupon->expire_date, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-success']) !!}
                    </div>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {




            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

        });
    </script>
@endsection
