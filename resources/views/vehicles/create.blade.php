@extends('layouts.app')
@section('extra_css')
    <style type="text/css">
        .nav-tabs-custom>.nav-tabs>li.active {
            border-top-color: #00a65a !important;
        }

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

        .custom .nav-link.active {

            background-color: #21bc6c !important;

        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">@lang('fleet.vehicles')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.addVehicle'):</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.addVehicle'):</h3>
                </div>

                <div class="card-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-pills custom">
                            <li class="nav-item"><a class="nav-link active" href="#info-tab" data-toggle="tab">
                                    @lang('fleet.general_info') <i class="fa"></i></a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="info-tab">
                            {!! Form::open([
                                'route' => 'vehicles.store',
                                'files' => true,
                                'method' => 'post',
                                'class' => 'form-horizontal',
                                'id' => 'accountForm',
                            ]) !!}
                            {!! Form::hidden('user_id', Auth::user()->id) !!}

                            <div class="row card-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('class_id', __('fleet.select_class'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::select('class_id', [''=>'select option','external_vehicle' => 'External Vehicle ', 'internal_vehicle' => 'Internal Vehicle'], null, [
                                                'class' => 'form-control',
                                                'required',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('category_id', __('fleet.select_category'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::select(
                                                'category_id',
                                                ['1' => 'Inside City', '2' => 'Oustide Damascus'],
                                                null,
                                                [
                                                    'class' => 'form-control',
                                                    'required',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('type_id', __('fleet.type'), ['class' => 'col-xs-5 control-label']) !!}

                                        <div class="col-xs-6">
                                            <select name="type_id" class="form-control" required id="type_id">
                                                <option></option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->displayname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group">
                                        {!! Form::label('category_id', __('fleet.category'), ['class' => 'col-xs-5 control-label']) !!}

                                        <div class="col-xs-6">
                                            <select name="category_id" class="form-control" required id="category_id">
                                                <option></option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="form-group">
                                        {!! Form::label('color', __('fleet.color'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::text('color', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div>
                                    {{-- <div class="form-group">
                                        {!! Form::label('type', __('fleet.type'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::text('type', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div> --}}





                                    <div class="form-group">
                                        {!! Form::label('year', __('fleet.year'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::number('year', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div>
                                    <!--zena-->
                                    <div class="form-group ">
                                        {!! Form::label('bags', __('fleet.bags'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                        {!! Form::number('bags', null, ['class' => 'form-control', 'required', 'min' => 1]) !!}
                                        </div>
                                        
                                    </div>
                                    <!--zena-->
                                    <div class="form-group">
                                        {!! Form::label('vehicle_image', __('fleet.vehicleImage'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::file('vehicle_image', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>



                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('driver_id', __('fleet.assign_drivers'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                        <select id="driver_id" name="driver_id[]" class="form-control" multiple="true"
                                                required>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}">
                                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    {{-- <div class="form-group">
                                        {!! Form::label('driver_id', __('fleet.assign_drivers'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            <select id="driver_id" name="driver_id[]" class="form-control" multiple="true"
                                                required>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}">
                                                        {{ $driver->first_name }}
                                                        {{ $driver->last_name }}{{ $driver->user_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}


                                    <div class="form-group">
                                        {!! Form::label('category_id', __('fleet.select_subcategory'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            <select name="subcategory_id" class="form-control" id="subcategory_id">
                                                <option value="0" selected>كل المناطق</option>
                                                @foreach ($subcategories as $sub)
                                                    <option value="{{ $sub->id }}">
                                                        {{ $sub->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group">
                                        {!! Form::label('engine_type', __('fleet.engine'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::select('engine_type', ['Petrol' => 'Petrol', 'Diesel' => 'Diesel'], null, [
                                                'class' => 'form-control',
                                                'required',
                                            ]) !!}
                                        </div>
                                    </div> --}}
                                    <div class="form-group">
                                        {!! Form::label('car_number', __('fleet.car_number'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::text('car_number', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('car_model', __('fleet.car_model'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::text('car_model', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('device_number', __('fleet.device_number'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::text('device_number', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div>
                                    <!--zena-->
                                    <div class="form-group ">
                                        {!! Form::label('seats', __('fleet.seats'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                        {!! Form::number('seats', null, ['class' => 'form-control', 'required', 'min' => 1]) !!}
                                        </div>
                                    </div>
                                    
                                    <!--zena-->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('in_service', __('fleet.service'), ['class' => 'col-xs-5 control-label']) !!}
                                            </div>
                                            <div class="col-ms-6" style="margin-left: -140px">
                                                <label class="switch">
                                                    <input type="checkbox" name="in_service" value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- make --}}
                                    {{-- <div class="form-group">
                                        {!! Form::label('make_id', __('fleet.SelectVehicleMake'), ['class' => 'col-xs-5 control-label']) !!}

                                        <div class="col-xs-6">
                                            <select name="make_id" class="form-control" required id="make_id">
                                                <option></option>
                                                @foreach ($makes as $make)
                                                    <option value="{{ $make->id }}">{{ $make->make }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- model --}}
                                    {{-- <div class="form-group">
                                        {!! Form::label('model_id', __('fleet.SelectVehicleModel'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            <select name="model_id" class="form-control" required id="model_id">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- color --}}
                                    {{-- <div class="form-group">
                                        {!! Form::label('color_id', __('fleet.SelectVehicleColor'), ['class' => 'col-xs-5 control-label']) !!}

                                        <div class="col-xs-6">
                                            <select name="color_id" class="form-control" required id="color_id">
                                                <option></option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}">{{ $color->color }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    {{-- lic --}}
                                    {{-- <div class="form-group">
                                        {!! Form::label('lic_exp_date', __('fleet.lic_exp_date'), [
                                            'class' => 'col-xs-5 control-label
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    required',
                                        ]) !!}
                                        <div class="col-xs-6">
                                            <div class="input-group date">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                            class="fa fa-calendar"></i></span></div>
                                                {!! Form::text('lic_exp_date', null, ['class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- reg --}}
                                    {{-- <div class="form-group">
                                        {!! Form::label('reg_exp_date', __('fleet.reg_exp_date'), [
                                            'class' => 'col-xs-5 control-label
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                required',
                                        ]) !!}
                                        <div class="col-xs-6">
                                            <div class="input-group date">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                            class="fa fa-calendar"></i></span></div>
                                                {!! Form::text('reg_exp_date', null, ['class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- license --}}
                                    {{-- <div class="form-group">
                                        {!! Form::label('license_plate', __('fleet.licensePlate'), ['class' => 'col-xs-5 control-label']) !!}
                                        <div class="col-xs-6">
                                            {!! Form::text('license_plate', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div> --}}
                                    {{-- int --}}
                                    {{-- <div class="form-group">
                                        @if (Hyvikk::get('dis_format') == 'km')
                                            {!! Form::label('int_mileage', __('fleet.intMileage') . '(' . __('fleet.km') . ')', [
                                                'class' => 'col-xs-5
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            control-label',
                                            ]) !!}
                                        @else
                                            {!! Form::label('int_mileage', __('fleet.intMileage') . '(' . __('fleet.miles') . ')', [
                                                'class' => 'col-xs-5
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            control-label',
                                            ]) !!}
                                        @endif
                                        <div class="col-xs-6">
                                            {!! Form::number('int_mileage', null, ['class' => 'form-control', 'required']) !!}
                                        </div>
                                    </div> --}}
                                    <div class="blank"></div>
                                </div>
                            </div>
                            <div style=" margin-bottom: 20px;">
                                <div class="form-group" style="margin-top: 15px;">
                                    <div class="col-xs-6 col-xs-offset-3">
                                        {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']) !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
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
        $(".add_udf").click(function() {
            // alert($('#udf').val());
            var field = $('#udf1').val();
            if (field == "" || field == null) {
                alert('Enter field name');
            } else {
                $(".blank").append(
                    '<div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label">' +
                    field.toUpperCase() + '</label> <input type="text" name="udf[' + field +
                    ']" class="form-control" placeholder="Enter ' + field +
                    '" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>'
                );
                $('#udf1').val("");
            }
        });
        $(function() {
            var urlLike = '{{ url('admin/dropdown') }}';
            $('#category_id').change(function() {
                var up = $('#subcategory_id').empty();
                var cat_id = $(this).val();
                
                if (cat_id) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: urlLike,
                        data: {
                            catId: cat_id
                        },
                        success: function(data) {
                            console.log(data);
                            up.append('<option value="0">كل المناطق</option>');
                            $.each(data, function(id, title) {
                                up.append($('<option>', {
                                    value: id,
                                    text: title
                                }));
                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(XMLHttpRequest);
                        }
                    });
                }
            });
        });
        $(function() {
            var urlLike = '{{ url('admin/dropdown1') }}';
            $('#class_id').change(function() {
                var up = $('#driver_id').empty();
                var cat_id = $(this).val();
                if (cat_id) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: urlLike,
                        data: {
                            catId: cat_id
                        },
                        success: function(data) {
                            console.log(data);
                            // up.append('<option value="0">Please Select</option>');
                            $.each(data, function(i, currUser) {
                                up.append($(
                                    `<option value=${currUser.id}>${currUser.name}</option>`
                                ))
                                console.log(currUser.id);


                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(XMLHttpRequest);
                        }
                    });
                }
            });
        });


        $(document).ready(function() {
            $('#group_id').select2({
                placeholder: "@lang('fleet.selectGroup')"
            });
            $('#type_id').select2({
                placeholder: "@lang('fleet.type')"
            });
            $('#class_id').select2({
                placeholder: "@lang('fleet.type')"
            });
            $('#category_id').select2({
                placeholder: "@lang('fleet.category')"
            });
            $('#make_id').select2({
                placeholder: "@lang('fleet.SelectVehicleMake')"
            });
            $('#color_id').select2({
                placeholder: "@lang('fleet.SelectVehicleColor')"
            });
            $('#make_id').on('change', function() {
                // alert($(this).val());
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-models') }}/" + $(this).val(),
                    success: function(data) {
                        var models = $.parseJSON(data);
                        $('#model_id').empty();
                        $('#model_id').append('<option value=""></option>');
                        $.each(models, function(key, value) {
                            $('#model_id').append('<option value=' + value.id + '>' +
                                value.text + '</option>');
                            $('#model_id').select2({
                                placeholder: "@lang('fleet.SelectVehicleModel')"
                            });
                        });
                    },
                    dataType: "html"
                });
            });
            $('#driver_id').select2({
                placeholder: "@lang('fleet.Selectdriver')"
            });
            $('#exp_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#lic_exp_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    </script>
@endsection
