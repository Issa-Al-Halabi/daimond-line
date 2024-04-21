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
    <li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">@lang('fleet.drivers')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.edit_driver')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.edit_driver')</h3>
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

                    {!! Form::open(['route' => ['drivers.update', $driver->id], 'files' => true, 'method' => 'PATCH']) !!}
                    {!! Form::hidden('id', $driver->id) !!}
                    {!! Form::hidden('edit', '1') !!}
                    {!! Form::hidden('detail_id', $driver->getMeta('id')) !!}
                    {!! Form::hidden('user_id', Auth::user()->id) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']) !!}
                                {!! Form::text('first_name', $driver->first_name, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label required']) !!}
                                {!! Form::text('last_name', $driver->last_name, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('email', __('fleet.email'), ['class' => 'form-label required']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    {!! Form::email('email', $driver->email, ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']) !!}


                                <div class="input-group-prepend">
                                    {!! Form::text('phone_code', '+963', [
                                        'class' => 'form-control,
                                                                                                                                                                                                                                                                                   code',
                                        'disabled' => 'disabled',
                                        'style' => 'width:40px',
                                    ]) !!}
                                    {!! Form::text('phone', $driver->phone, ['class' => 'form-control', 'required']) !!}
                                </div>



                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('place_of_birth', __('fleet.place_of_birth'), ['class' => 'form-label']) !!}
                                {!! Form::text('place_of_birth', $driver->place_of_birth, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    {!! Form::date('date_of_birth', $driver->date_of_birth, ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('start_date', __('fleet.join_date'), ['class' => 'form-label']) !!}
                                <div class="input-group date">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                    {!! Form::text('start_date', $driver->getMeta('start_date'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>

                                    </div>
                                    {!! Form::password('password', ['class' => 'form-control']) !!}
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('personal_identity', __('fleet.personal_identity'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $driver->personal_identity != null ? asset('uploads/' . $driver->personal_identity) : asset('assets/images/no-user.jpg') }}>
                                {!! Form::file('personal_identity', null, ['class' => 'form-control', 'required']) !!}

                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('driving_certificate', __('fleet.driving_certificate'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $driver->driving_certificate != null ? asset('uploads/' . $driver->driving_certificate) : asset('assets/images/no-user.jpg') }}>
                                {!! Form::file('driving_certificate', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('car_mechanic', __('fleet.car_mechanic'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $driver->car_mechanic != null ? asset('uploads/' . $driver->car_mechanic) : asset('assets/images/no-user.jpg') }}>
                                {!! Form::file('car_mechanic', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('car_insurance', __('fleet.car_insurance'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $driver->car_insurance != null ? asset('uploads/' . $driver->car_insurance) : asset('assets/images/no-user.jpg') }}>
                                {!! Form::file('car_insurance', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('car_image', __('fleet.car_image'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $driver->car_image != null ? asset('uploads/' . $driver->car_image) : asset('assets/images/no-user.jpg') }}>

                                {!! Form::file('car_image', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                {!! Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $driver->profile_image != null ? asset('uploads/' . $driver->profile_image) : asset('assets/images/no-user.jpg') }}>

                                {!! Form::file('profile_image', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-success']) !!}
                            {{-- <a href="{{ route('drivers.index') }}" class="btn btn-danger">@lang('fleet.back')</a> --}}
                        </div>

                    </div>
                    {{-- <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('vehicle_id', __('fleet.assign_vehicle'), ['class' => 'form-label']) !!}
                                <select id="vehicle_id" name="vehicle_id[]" class="form-control" multiple="true" required>
                                    <option value="">@lang('fleet.selectVehicle')</option>
                                    @foreach ($vehicles as $vehicle)
                                        @php
                                            $selected = in_array($vehicle->id, $driver->vehicles->pluck('id')->toArray()) ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $vehicle->id }}" {{ $selected }}>
                                            {{ $vehicle->maker->make }}-{{ $vehicle->vehiclemodel->model }}-{{ $vehicle->license_plate }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}


                </div>
                <div class="row">
                    {{-- <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']) !!}
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        {!! Form::select('phone_code', $phone_code, $driver->getMeta('phone_code'), [
                                            'class' => 'form-control
                                                                                                                                                                                                                                                                                                                                                                                          code',
                                            'required',
                                            'style' => 'width:80px;',
                                        ]) !!}
                                    </div>
                                    {!! Form::number('phone', $driver->getMeta('phone'), ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div> --}}
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('emp_id', __('fleet.employee_id'), ['class' => 'form-label']) !!}
                            {!! Form::text('emp_id', $driver->getMeta('emp_id'), ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div> --}}


                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('contract_number', __('fleet.contract'), ['class' => 'form-label']) !!}
                            {!! Form::text('contract_number', $driver->getMeta('contract_number'), ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('license_number', __('fleet.licenseNumber'), ['class' => 'form-label required']) !!}
                            {!! Form::text('license_number', $driver->getMeta('license_number'), ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div> --}}

                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('issue_date', __('fleet.issueDate'), ['class' => 'form-label']) !!}
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-calendar"></i></span>
                                </div>
                                {!! Form::text('issue_date', $driver->getMeta('issue_date'), ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('exp_date', __('fleet.expirationDate'), ['class' => 'form-label required']) !!}
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-calendar"></i></span>
                                </div>
                                {!! Form::text('exp_date', $driver->getMeta('exp_date'), ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="row">

                    {{-- <div class="form-group">
                            {!! Form::label('role_id', __('fleet.role'), ['class' => 'form-label']) !!}
                            <select id="role_id" name="role_id" class="form-control" required>
                                <option value="">@lang('fleet.role')</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $roles->id }}" @if ($driver->roles->first()->id == $roles->id) selected @endif>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('end_date', __('fleet.leave_date'), ['class' => 'form-label']) !!}
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-calendar"></i></span>
                                </div>
                                {!! Form::text('end_date', $driver->getMeta('end_date'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('driver_commision_type', __('fleet.driver_commision_type'), ['class' => 'form-label']) !!}

                            {!! Form::select(
                                'driver_commision_type',
                                ['amount' => __('fleet.amount'), 'percent' => __('fleet.percent')],
                                $driver->getMeta('driver_commision_type'),
                                ['class' => 'form-control', 'placeholder' => __('fleet.select'), 'required'],
                            ) !!}
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-4" id="driver_commision_container" style="display: none;">
                        <div class="form-group">
                            {!! Form::label('driver_commision', __('fleet.driver_commision'), ['class' => 'form-label']) !!}
                            {!! Form::number('driver_commision', $driver->getMeta('driver_commision'), ['class' => 'form-control']) !!}
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {{-- <div class="form-group">
                            {!! Form::label('gender', __('fleet.gender'), ['class' => 'form-label']) !!}<br>
                            <input type="radio" name="gender" class="flat-red gender" value="1"
                                @if ($driver->getMeta('gender') == 1) checked @endif> @lang('fleet.male')<br>
                            <input type="radio" name="gender" class="flat-red gender" value="0"
                                @if ($driver->getMeta('gender') == 0) checked @endif> @lang('fleet.female')
                        </div> --}}
                        {{-- <div class="form-group">
                                {!! Form::label('driver_image', __('fleet.driverImage'), ['class' => 'form-label']) !!}
                                @if ($driver->getMeta('driver_image') != null)
                                    <a href="{{ asset('uploads/' . $driver->getMeta('driver_image')) }}"
                                        target="_blank">View</a>
                                @endif
                                {!! Form::file('driver_image', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}
                        {{-- <div class="form-group">
                                {!! Form::label('documents', __('fleet.documents'), ['class' => 'form-label']) !!}
                                @if ($driver->getMeta('documents') != null)
                                    <a href="{{ asset('uploads/' . $driver->getMeta('documents')) }}"
                                        target="_blank">View</a>
                                @endif
                                {!! Form::file('documents', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}
                        {{-- <div class="form-group">
                                {!! Form::label('license_image', __('fleet.licenseImage'), ['class' => 'form-label']) !!}
                                @if ($driver->getMeta('license_image') != null)
                                    <a href="{{ asset('uploads/' . $driver->getMeta('license_image')) }}"
                                        target="_blank">View</a>
                                @endif
                                {!! Form::file('license_image', null, ['class' => 'form-control', 'required']) !!}
                            </div> --}}

                    </div>
                    {{-- <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('econtact', __('fleet.emergency_details'), ['class' => 'form-label']) !!}
                                {!! Form::textarea('econtact', $driver->getMeta('econtact'), ['class' => 'form-control']) !!}
                            </div>
                        </div> --}}

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
            $('#driver_commision_type').on('change', function() {
                var val = $(this).val();
                if (val == '') {
                    $('#driver_commision_container').hide();
                } else {
                    if (val == 'amount') {
                        $('#driver_commision').attr('placeholder', "@lang('fleet.enter_amount')");
                    } else {
                        $('#driver_commision').attr('placeholder', "@lang('fleet.enter_percent')")
                    }
                    $('#driver_commision_container').show();
                }
            });
            $('#driver_commision_type').trigger('change');
            $('.code').select2();
            $('#vehicle_id').select2({
                placeholder: "@lang('fleet.selectVehicle')"
            });
            $('#end_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#exp_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#issue_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#start_date').datepicker({
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
