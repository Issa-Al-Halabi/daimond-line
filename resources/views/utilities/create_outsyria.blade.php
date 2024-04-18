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

                    {!! Form::open(['route' => 'store_outside', 'files' => true, 'method' => 'post']) !!}
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('category_id', __('fleet.Select_direction'), ['class' => 'col-xs-5 control-label']) !!}
                                <div class="input-group mb-3">
                                    {!! Form::select('direction', ['1' => 'One Way Trip', '2' => 'Round Trip'], null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('subcategory_id', __('fleet.select_type'), ['class' => 'form-label']) !!}
                                <select id="subcategory_id" name="subcategory_id" class="form-control" required>
                                    <option value="">@lang('fleet.select_type')</option>
                                    @foreach ($subcategory as $type)
                                        <option value="{{ $type->id }}">{{ $type->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                {!! Form::label('price', __('fleet.price'), ['class' => 'form-label']) !!}
                                {!! Form::text('price', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
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
