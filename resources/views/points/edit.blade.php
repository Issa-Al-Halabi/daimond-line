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
    <li class="breadcrumb-item"><a href="{{ route('points') }}">@lang('fleet.points')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.edit_point')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.edit_point')</h3>
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

                    {!! Form::open(['route' => ['points.update', $point->id], 'files' => true, 'method' => 'PATCH']) !!}
                    {!! Form::hidden('id', $point->id) !!}
                    {!! Form::hidden('edit', '1') !!}


                    <div class="row">
                        <div class="col-md-6">
                           
                            <div class="form-group">
                                {!! Form::label('trip_type', __('fleet.trip_type'), ['class' => 'form-label']) !!}
                                <select name="trip_type" id="trip_type" class="form-control" required>
                                    <option @if($point->trip_type == 'Out city trips') {{ 'selected' }} @endif value="Out city trips">Out city trips</option>
                                    <option @if($point->trip_type == 'instant trips') {{ 'selected' }} @endif value="instant trips">instant trips</option>
                                    <option @if($point->trip_type == 'delayed trips') {{ 'selected' }} @endif value="delayed trips">delayed trips</option>
                                </select>
                            </div>
                            <div class="form-group">
                                {!! Form::label('point', __('fleet.point'), ['class' => 'form-label']) !!}
                                {!! Form::number('point',$point->points, null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('qty', __('fleet.qty'), ['class' => 'form-label']) !!}
                                {!! Form::number('qty',$point->qty, null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
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
