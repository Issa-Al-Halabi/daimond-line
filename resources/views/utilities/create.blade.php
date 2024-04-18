@extends('layouts.app')
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
@endsection
@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{ route('users.index') }}"> @lang('fleet.users') </a></li> --}}
    <li class="breadcrumb-item active">@lang('fleet.add_fare')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.add_fare')</h3>
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

                    {!! Form::open(['route' => 'farestore', 'files' => true, 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('category_id', __('fleet.select_category'), ['class' => 'col-xs-5 control-label']) !!}
                                <div class="input-group mb-3">
                                    {!! Form::select('category_id', ['1' => 'Inside City', '2' => 'Oustide Damascus'], null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('category_id', __('fleet.selectUser'), ['class' => 'col-xs-5 control-label']) !!}
                                <div class="input-group mb-3">
                                    {!! Form::select('user_type', ['Organizations' => ' Organizations', 'User' => ' User'], null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('base_km', __('fleet.base_km'), ['class' => 'form-label']) !!}
                                {!! Form::text('base_km', null, ['class' => 'form-control', 'required']) !!}
                            </div>

                          <div class="form-group">
                                {!! Form::label('cost', __('fleet.cost'), ['class' => 'form-label']) !!}
                                {!! Form::text('cost', null, ['class' => 'form-control']) !!}
                            </div>
                          
                          
                          
                            {{-- <div class="form-group">
                                {!! Form::label('weekend_base_km', __('fleet.weekend_base_km'), ['class' => 'form-label']) !!}
                                {!! Form::text('weekend_base_km', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}

                            {{-- <div class="form-group">
                                {!! Form::label('night_base_km', __('fleet.night_base_km'), ['class' => 'form-label']) !!}
                                {!! Form::text('night_base_km', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}




                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('type_id', __('fleet.select_type'), ['class' => 'form-label']) !!}
                                <select id="type_id" name="type_id" class="form-control" required>
                                    <option value="">@lang('fleet.role')</option>
                                    @foreach ($vehicle_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->vehicletype }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                {!! Form::label('base_time', __('fleet.base_time'), ['class' => 'form-label']) !!}
                                {!! Form::text('base_time', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                          
                          <div class="form-group">
                                {!! Form::label('limit_distance', __('fleet.Limit_distance'), ['class' => 'form-label']) !!}
                                {!! Form::text('limit_distance', null, ['class' => 'form-control']) !!}
                            </div>

                            {{-- <div class="form-group">
                                {!! Form::label('weekend_wait_km', __('fleet.weekend_wait_km'), ['class' => 'form-label']) !!}
                                {!! Form::text('weekend_wait_km', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}
                            {{-- <div class="form-group">
                                {!! Form::label('night_wait_time', __('fleet.night_wait_time'), ['class' => 'form-label']) !!}
                                {!! Form::text('night_wait_time', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}

                        </div>
                    </div>

                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.save'), ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#group_id').select2({
                placeholder: "@lang('fleet.selectGroup')"
            });
            $('#type_id').select2({
                placeholder: "@lang('fleet.select_type')"
            });
            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    </script>
@endsection
