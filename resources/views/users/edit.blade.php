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

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}"> @lang('fleet.users') </a></li>
    <li class="breadcrumb-item active">@lang('fleet.editUser')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title" style="color:#fbfbfb;">@lang('fleet.editUser')</h3>
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

                    {{-- @php($names = explode(' ', $user->name)) --}}

                    {!! Form::open(['route' => ['users.update', $user->id], 'files' => true, 'method' => 'PATCH']) !!}
                    {!! Form::hidden('id', $user->id) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']) !!}
                                {!! Form::text('first_name', $user->first_name, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('mother_name', __('fleet.mother_name'), ['class' => 'form-label']) !!}
                                {!! Form::text('mother_name', $user->mother_name, ['class' => 'form-control']) !!}
                            </div>


                            <div class="form-group">
                                {!! Form::label('email', __('fleet.email'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    {!! Form::email('email', $user->email, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <!--<div class="form-group">-->
                            <!--    {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label']) !!}-->
                            <!--    {!! Form::text('phone', $user->phone, ['class' => 'form-control']) !!}-->
                            <!--</div>-->
                            <div class="form-group">
                                {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']) !!}


                                <div class="input-group-prepend">
                                    {!! Form::text('phone_code', '+963', [
                                        'class' => 'form-control,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              code',
                                        'disabled' => 'disabled',
                                        'style' => 'width:40px',
                                    ]) !!}
                                    {!! Form::text('phone', $user->phone, ['class' => 'form-control']) !!}
                                </div>


                            </div>



                            <div class="form-group">
                                {!! Form::label('role_id', __('fleet.role'), ['class' => 'form-label']) !!}
                                <select id="role_id" name="role_id" class="form-control">
                                    <option value="">@lang('fleet.role')</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            @if ($user->roles->first()->id == $role->id) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div class="form-group">
                                {!! Form::label('group_id', __('fleet.selectGroup'), ['class' => 'form-label']) !!}

                                <select id="group_id" name="group_id" class="form-control">
                                    <option value="">@lang('fleet.vehicleGroup')</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}"
                                            @if ($group->id == $user->group_id) selected @endif>{{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                {!! Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label']) !!}
                                {!! Form::text('last_name', $user->last_name, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('father_name', __('fleet.father_name'), ['class' => 'form-label']) !!}
                                {!! Form::text('father_name', $user->father_name, ['class' => 'form-control']) !!}
                            </div>
                            {{-- <div class="form-group">
                                {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    {!! Form::password('password', ['class' => 'form-control']) !!}
                                </div>
                            </div> --}}

                            <div class="form-group">
                                {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
                                    <div class="invalid-feedback" id="password-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('place_of_birth', __('fleet.place_of_birth'), ['class' => 'form-label']) !!}
                                {!! Form::text('place_of_birth', $user->place_of_birth, ['class' => 'form-control']) !!}
                            </div>



                            <div class="form-group">
                                {!! Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']) !!}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    {!! Form::date('date_of_birth', $user->date_of_birth, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group mt-3">
                                    {!! Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']) !!}
                                    <img class='img'
                                        width='60px'src={{ $user->profile_image != null ? asset('uploads/' . $user->profile_image) : asset('assets/images/no-user.jpg') }}>

                                    {!! Form::file('profile_image', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('address', __('fleet.address'), ['class' => 'form-label']) !!}
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                                        </div>
                                        {!! Form::textarea('address', $user->address, ['class' => 'form-control', 'size' => '30x2']) !!}
                                    </div>
                                </div>

                            </div> --}}




                            {{-- <div class="form-group">
              {!! Form::label('module',__('fleet.select_modules'), ['class' => 'form-label']) !!} <br>
              @php($modules=unserialize($user->getMeta('module')))
              <div class="row">
                <div class="col-md-4" style="padding: 0px;">
                  <input type="checkbox" name="module[]" value="0" class="flat-red form-control"
                    @if (in_array(0, $modules)) checked @endif>&nbsp; @lang('menu.users')<br>
                  <input type="checkbox" name="module[]" value="1" class="flat-red form-control"
                    @if (in_array(1, $modules)) checked @endif>&nbsp; @lang('fleet.vehicles')<br>
                  <input type="checkbox" name="module[]" value="2" class="flat-red form-control"
                    @if (in_array(2, $modules)) checked @endif>&nbsp; @lang('menu.transactions')<br>
                  <input type="checkbox" name="module[]" value="3" class="flat-red form-control"
                    @if (in_array(3, $modules)) checked @endif>&nbsp; @lang('fleet.bookings')<br>
                  <input type="checkbox" name="module[]" value="13" class="flat-red form-control"
                    @if (in_array(13, $modules)) checked @endif>&nbsp; @lang('fleet.helpus')
                </div>
                <div class="col-md-4" style="padding: 0px;">
                  <input type="checkbox" name="module[]" value="4" class="flat-red form-control"
                    @if (in_array(4, $modules)) checked @endif>&nbsp; @lang('menu.reports')<br>
                  <input type="checkbox" name="module[]" value="5" class="flat-red form-control"
                    @if (in_array(5, $modules)) checked @endif>&nbsp; @lang('fleet.fuel')<br>
                  <input type="checkbox" name="module[]" value="6" class="flat-red form-control"
                    @if (in_array(6, $modules)) checked @endif>&nbsp; @lang('fleet.vendors')<br>
                  <input type="checkbox" name="module[]" value="7" class="flat-red form-control"
                    @if (in_array(7, $modules)) checked @endif>&nbsp; @lang('fleet.work_orders')<br>
                  <input type="checkbox" name="module[]" value="14" class="flat-red form-control"
                    @if (in_array(14, $modules)) checked @endif>&nbsp; @lang('fleet.parts')
                </div>
                <div class="col-md-4" style="padding: 0px;">
                  <input type="checkbox" name="module[]" value="8" class="flat-red form-control"
                    @if (in_array(8, $modules)) checked @endif>&nbsp; @lang('fleet.notes')<br>
                  <input type="checkbox" name="module[]" value="9" class="flat-red form-control"
                    @if (in_array(9, $modules)) checked @endif>&nbsp; @lang('fleet.serviceReminders')<br>
                  <input type="checkbox" name="module[]" value="10" class="flat-red form-control"
                    @if (in_array(10, $modules)) checked @endif>&nbsp; @lang('fleet.reviews')<br>
                  <input type="checkbox" name="module[]" value="12" class="flat-red form-control"
                    @if (in_array(12, $modules)) checked @endif>&nbsp; @lang('fleet.maps')<br>
                  <input type="checkbox" name="module[]" value="15" class="flat-red form-control"
                    @if (in_array(15, $modules)) checked @endif>&nbsp; @lang('fleet.testimonials')
                </div>
              </div>
            </div> --}}
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#group_id').select2({
                placeholder: "@lang('fleet.selectGroup')"
            });
            $('#role_id').select2({
                placeholder: "@lang('fleet.role')"
            });
            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    </script>



    {{-- check the password validate --}}
    <script>
        var passwordInput = document.getElementById('password');
        var passwordFeedback = document.getElementById('password-feedback');

        passwordInput.addEventListener('input', function(event) {
            var password = passwordInput.value;
            var passwordStrength = getPasswordStrength(password);

            if (passwordStrength < 4) {
                passwordInput.classList.add('is-invalid');
                passwordFeedback.textContent =
                    'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordFeedback.textContent = '';
            }
        });

        function getPasswordStrength(password) {
            var strength = 0;
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (password.match(regex)) {
                strength += 1;
            }
            if (password.length >= 8) {
                strength += 1;
            }
            if (password.length >= 12) {
                strength += 1;
            }
            if (password.match(/[~`!#$%^&*()-_=+[\]{}\\|;:'",.<>/?]+/)) {
                strength += 1;
            }

            return strength;
        }
    </script>
@endsection
