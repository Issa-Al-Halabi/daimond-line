@extends('layouts.app')
@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('fleet.customers')</a></li> --}}
    <li class="breadcrumb-item active"> @lang('fleet.edit_driver')</li>
@endsection
@section('extra_css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">

    <style type="text/css">
        /* .select2-selection:not(.select2-selection--multiple) {
                                                            height: 38px !important;
                                                        } */

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('fleet.edit_driver')
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

                    {!! Form::open(['route' => ['customers.update', $data->id], 'files' => true, 'method' => 'PATCH']) !!}
                    {!! Form::hidden('id', $data->id) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', __('fleet.first_name'), ['class' => 'form-label']) !!}
                                {!! Form::text('first_name', $data['first_name'], ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name', __('fleet.last_name'), ['class' => 'form-label']) !!}
                                {!! Form::text('last_name', $data['last_name'], ['class' => 'form-control']) !!}
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
                                    {!! Form::text('phone', $data['phone'], ['class' => 'form-control', 'required']) !!}
                                </div>


                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('email', __('fleet.email'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    {!! Form::email('email', $data['email'], ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        {{-- <span class="input-group-text"><i class="fa fa-envelope"></i></span> --}}
                                    </div>
                                    {!! Form::password('password', [
                                        'class' => 'form-control',
                                        'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                                        'title' =>
                                            'Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, and one digit.',
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('role_id', __('fleet.role'), ['class' => 'form-label']) !!}
                                <select id="role_id" name="role_id" class="form-control" required>
                                    <option value="">@lang('fleet.role')</option>
                                    @foreach ($roles as $role)
                                        <option
                                            value="{{ $role->id }}"@if ($data->roles->first()->id == $role->id) selected @endif>
                                            {{ $role['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"></span>
                                    </div>
                                    {!! Form::date('date_of_birth', $data['date_of_birth'], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group mt-3">
                                {!! Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']) !!}
                                <img class='img'
                                    width='60px'src={{ $data['profile_image'] != null ? asset('uploads/' . $data['profile_image']) : asset('assets/images/no-user.jpg') }}>

                                {!! Form::file('profile_image', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('gender', __('fleet.gender'), ['class' => 'form-label']) !!}<br>
                                <input type="radio" name="gender" class="flat-red gender" value="1"
                                    @if ($data->gender == 1) checked @endif required> @lang('fleet.male')<br>
                                <input type="radio" name="gender" class="flat-red gender" value="0"
                                    @if ($data->gender == 0) checked @endif required> @lang('fleet.female')
                            </div>
                        </div> --}}

                    </div>
                    {{-- <div class="form-group">
                        {!! Form::label('address', __('fleet.address'), ['class' => 'form-label']) !!}
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                            </div>
                            {!! Form::textarea('address', $data['address'], ['class' => 'form-control', 'size' => '30x2']) !!}
                        </div>
                    </div> --}}

                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('gender', __('fleet.gender'), ['class' => 'form-label']) !!}<br>
                            <input type="radio" name="gender" class="flat-red gender" value="1" checked>
                            @lang('fleet.male')<br>

                            <input type="radio" name="gender" class="flat-red gender" value="0">
                            @lang('fleet.female')
                        </div>
                    </div> --}}
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
    <script type="text/javascript">
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        })
    </script>
@endsection
