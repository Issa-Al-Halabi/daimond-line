@extends('layouts.app')
@php($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y')
@section('extra_css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <style type="text/css">
        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }

        .img {
            border-radius: 60px;
            height: 40px;
        }
    </style>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('fleet.all_user')</a></li>
    {{-- <li class="breadcrumb-item active">{{ $user->name }}</li> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title w-100 d-flex justify-content-between align-items-center"> <span>@lang('fleet.user_details'):
                        </span>
                        
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>@lang('fleet.full_name')</b>: {{ $user->first_name }} {{ $user->last_name }}</p>

                            <p><b>@lang('fleet.email')</b>: {{ $user->email }}</p>
                           

                        </div>
                        <div class="col-md-6">
                            <p><b>@lang('fleet.phone')</b>: {{ $user->phone }}</p>
                        
                            {{-- <p><b>@lang('fleet.address')</b>: {{ $user->address }}</p> --}}
                            <p><b>@lang('fleet.date_of_birth')</b>: {{ $user->date_of_birth }}</p>
                            <p><b>@lang('fleet.place_of_birth')</b>: {{ $user->place_of_birth }}</p>
                            <p><b>@lang('fleet.profile_photo')</b>:<img class='img'
                                    width='60px'src={{ $user->profile_image != null ? asset('uploads/' . $user->profile_image) : asset('assets/images/no-user.jpg') }}>
                            </p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

