@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('fleet.customers')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.addDriver')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.addDriver')
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

                    {!! Form::open(['route' => 'customers.store', 'files' => true, 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']) !!}
                                {!! Form::text('first_name', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label']) !!}
                                {!! Form::text('last_name', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        {{-- father name --}}
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('father_name', __('fleet.father_name'), ['class' => 'form-label']) !!}
                                {!! Form::text('father_name', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div> --}}
                        {{-- mother name --}}
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('mother_name', __('fleet.mother_name'), ['class' => 'form-label']) !!}
                                {!! Form::text('mother_name', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div> --}}
                        {{-- id entry --}}
                     
                        <div class="col-md-6">
                        <div class="form-group">
                                {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']) !!}
                            

                                   <div class="input-group-prepend">
                                        {!! Form::text('phone_code',  '+963', [
                                            'class' => 'form-control,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 code',
        'disabled'=>'disabled',
                                            'style' => 'width:40px',
                                        ]) !!}
                                         {!! Form::text('phone', null, ['class' => 'form-control', 'required']) !!}
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
                                    {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    {!! Form::password('password', ['class' => 'form-control', 'required', 'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}', 'title' => 'Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, and one digit.']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('role_id', __('fleet.role'), ['class' => 'form-label']) !!}
                                <select id="role_id" name="role_id" class="form-control" required>
                                    <option value="">@lang('fleet.role')</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('password', __('fleet.id_entry'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    {!! Form::text('id_entry', ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div> --}}



                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('address', __('fleet.address'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                                    </div>
                                    {!! Form::textarea('address', null, ['class' => 'form-control', 'size' => '30x2']) !!}
                                </div>
                            </div>

                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    {!! Form::date('date_of_birth', null, ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6  mt-3">
                            <div class="form-group mt-3">
                                {!! Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']) !!}

                                {!! Form::file('profile_image', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            {!! Form::label('is_active', __('fleet.is_active'), ['class' => 'form-label']) !!}
                            <div class="form-group ">
                                <label class="switch">
                                    <input type="checkbox" name="is_active" value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            {!! Form::label('role_id', __('fleet.role'), ['class' => 'form-label']) !!}
                            <select id="role_id" name="role_id" class="form-control" required>
                                <option value="">@lang('fleet.role')</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

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



                    </div>
                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.addDriver'), ['class' => 'btn btn-success']) !!}
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
