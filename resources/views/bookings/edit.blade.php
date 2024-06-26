@extends('layouts.app')
@section('extra_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endsection
@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">@lang('menu.bookings')</a></li>
    <li class="breadcrumb-item active">@lang('fleet.edit_booking')</li> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.view_trip'):
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
                    <div class="alert alert-info hide fade in alert-dismissable" id="msg_driver" style="display: none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        Your current driver is not available in the chosen times. Available driver has been selected.
                    </div>
                    <div class="alert alert-info hide fade in alert-dismissable" id="msg_vehicle" style="display: none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        Your current vehicle is not available in the chosen times. Available vehicle has been selected.
                    </div>
                    {{-- 
                    {!! Form::open(['route' => ['bookings.update', $data->id], 'method' => 'PATCH']) !!}
                    {!! Form::hidden('user_id', Auth::user()->id) !!}
                    {!! Form::hidden('status', 0) !!}
                    {!! Form::hidden('id', $data->id) !!} --}}

                    <div class="row">
                        <div class="col-md-4">
                            <p><b>@lang('fleet.trip_details')</b>:</p>
                          <div class="col-md-6">
                                <p><b>@lang('fleet.driver_name')</b>:
                                  @if(isset( $user))
                                  {{ $user->first_name }} {{ $user->last_name }}
                                  @else
                                  No driver selected

                                  @endif
                            </p>
                            </div>
                            
                            <div class="col-md-6">
                                <p><b>@lang('fleet.date')</b>: {{ $booking->date }}</p>
                            </div>


                            <div class="col-md-6">
                                <p><b>@lang('fleet.time')</b>: {{ $booking->time }}</p>
                            </div>

                            <div class="col-md-6">
                                <p><b>@lang('fleet.distance')</b>: {{ round($booking->km) }}km</p>
                            </div>
                            <div class="col-md-6">
                                <p><b>@lang('fleet.minutes')</b>: {{ round($booking->minutes) }}min</p>
                            </div>
                            <div class="col-md-6">
                                <p><b>@lang('fleet.trip_cost')</b>: {{ $booking->cost }} SY</p>
                            </div>
                          @if($booking->status=='ended')
                          <div class="col-md-6">
                            @if($user->user_type=='driver')
                                <p><b>@lang('fleet.admin_earning')</b>: {{ intval(($booking->cost * $internal->value)/100) }} SY</p>
                            @elseif($user->user_type=='external_driver')
                             <p><b>@lang('fleet.admin_earning')</b>: {{ intval(($booking->cost * $external->value)/100)}} SY</p>
                            @endif
                           </div>
                          @endif
                            
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-6">
                                @if ($tour !== null)
                                    <p><b>@lang('fleet.tour_details')</b>:</p>
                                    {{-- <p>There is no tour
                                    </p>

                                    <!--<div class="col-md-6">-->
                                        <p><b>@lang('fleet.total_cost')</b>: {{ $booking->cost }}Sy</p>
                                    <!--</div> --}}-->

                                    <!--<div class="col-md-6">-->
                                        <p><b>@lang('fleet.start_time')</b>: {{ $tour->start_time }}</p>
                                    <!--</div>-->


                                    <!--<div class="col-md-6">-->
                                        <p><b>@lang('fleet.end_time')</b>: {{ $tour->end_time }}</p>
                                    <!--</div>-->

                                    <!--<div class="col-md-6">-->
                                        <p><b>@lang('fleet.tour_cost')</b>:{{ $tour->cost }}SY </p>
                                    <!--</div>-->


                                    <!--<div class="col-md-12">-->
                                        <p><b>@lang('fleet.total_cost')</b>: {{ $booking->cost + $tour->cost }}SY</p>
                                    <!--</div>-->
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!--<div class="col-md-6">-->
                                @if ($user_details !== null)
                                    <p><b>@lang('fleet.user_details')</b>:</p>
                                    

                                   

                                    <!--<div class="col-md-6">-->
                                         <p><b>@lang('fleet.user_name')</b>: {{ $user_details->first_name }} {{ $user_details->last_name }}</p>
                                    <!--</div>-->


                                    <!--<div class="col-md-6">-->
                                        <p><b>@lang('fleet.phone')</b>: {{ $user_details->phone }}</p>
                                    <!--</div>-->

                                    <!--<div class="col-md-6">-->
                                        <p><b>@lang('fleet.email')</b>:{{ $user_details->email }} </p>
                                    <!--</div>-->


                                @endif
                            <!--</div>-->
                        </div>
                    </div>
                    @if ($expense != null)
                        <table class="table table-striped  table-bordered ">
                            <thead>
                                <tr>
                                    <th>@lang('fleet.id')</th>
                                    <th>@lang('fleet.expense_type')</th>
                                    <th>@lang('fleet.price')</th>

                                </tr>
                            </thead>
                            @php
                                $types = json_decode($expense->type);
                                $prices = json_decode($expense->price);
                                $i = 1;
                                
                            @endphp
                            <tbody>
                                @foreach ($types as $key => $p)
                                    <tr>
                                        <td>
                                            {{ $i++ }}
                                        </td>
                                        <td>
                                            {{ $p }}
                                        </td>
                                        <td>
                                            {{ $prices[$key] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        

                        </table>
                    @endif

                </div>

            </div>


            {{-- <div class="row">
                            <div class="col-md-6">
                                <p><b>@lang('fleet.start_time')</b>: {{ $tour->start_time }}</p>
                            </div>
                        </div> --}}


            {{-- <div class="row">
                        <div class="col-md-6">
                            <p><b>@lang('fleet.start_time')</b>: {{ $tour->start_time }}</p>
                        </div>
                    </div> --}}
            {{-- <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('vehicle_id', __('fleet.selectVehicle'), ['class' => 'form-label']) !!}
                                <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                                    <option value="">-</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle['id'] }}"
                                            @if ($vehicle['id'] == $data->vehicle_id) selected @endif>
                                            {{ $vehicle->maker->make }} - {{ $vehicle->vehiclemodel->model }} -
                                            {{ $vehicle->license_plate }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('vehicle_id', __('fleet.selectDriver'), ['class' => 'form-label']) !!}
                                <select id="driver_id" name="driver_id" class="form-control" required>
                                    <option value="">-</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            @if ($driver->id == $data->driver_id) selected @endif>{{ $driver->name }}
                                            @if ($driver->getMeta('is_active') != 1)
                                                (@lang('fleet.in_active'))
                                            @endif
                                        </option>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('travellers', __('fleet.no_travellers'), ['class' => 'form-label']) !!}
                                {!! Form::number('travellers', $data->travellers, ['class' => 'form-control', 'min' => 1]) !!}
                            </div>
                        </div>
                    </div> --}}
            {{-- <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('pickup_addr', __('fleet.pickup_addr'), ['class' => 'form-label']) !!}
                                {!! Form::text('pickup_addr', $data->pickup_addr, [
                                    'class' => 'form-control',
                                    'required',
                                    'style' => 'height:100px',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('dest_addr', __('fleet.dropoff_addr'), ['class' => 'form-label']) !!}
                                {!! Form::text('dest_addr', $data->dest_addr, [
                                    'class' => 'form-control',
                                    'required',
                                    'style' => 'height:100px',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('note', __('fleet.note'), ['class' => 'form-label']) !!}
                                {!! Form::textarea('note', $data->note, [
                                    'class' => 'form-control',
                                    'placeholder' => __('fleet.book_note'),
                                    'style' => 'height:100px',
                                ]) !!}
                            </div>
                        </div>
                    </div> --}}
            <hr>
            {{-- <div class="row">
                        <div class="form-group col-md-6">
                            {!! Form::label('udf1', __('fleet.add_udf'), ['class' => 'col-xs-5 control-label']) !!}
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::text('udf1', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-info add_udf"> @lang('fleet.add')</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
            {{-- @if ($udfs != null)
                        @foreach ($udfs as $key => $value)
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group"> <label
                                            class="form-label text-uppercase">{{ $key }}</label> <input
                                            type="text" name="udf[{{ $key }}]" class="form-control" required
                                            value="{{ $value }}"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger"
                                            type="button"
                                            onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif --}}
            <div class="blank"></div>
            {{-- <div class="col-md-12">
                {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
            </div> --}}
            {{-- {!! Form::close() !!} --}}
        </div>
    </div>
    </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/datetimepicker.js') }}"></script>
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
    </script>
    <script type="text/javascript">
        $('#customer_id').select2({
            placeholder: "@lang('fleet.selectCustomer')"
        });
        $('#driver_id').select2({
            placeholder: "@lang('fleet.selectDriver')"
        });
        $('#vehicle_id').select2({
            placeholder: "@lang('fleet.selectVehicle')"
        });

        function get_driver(from_date, to_date) {
            var id = $("input:hidden[name=id]").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ url('admin/get_driver') }}",
                data: "req=edit&id=" + id + "&from_date=" + from_date + "&to_date=" + to_date,
                success: function(data2) {
                    $("#driver_id").empty();
                    $("#driver_id").select2({
                        placeholder: "@lang('fleet.selectDriver')",
                        data: data2.data
                    });
                    // if(data2.show_error=="yes"){
                    //   // alert("test");
                    // $("#msg_driver").removeClass("hide").fadeIn(1000);
                    // } else {
                    // $("#msg_driver").addClass("hide").fadeIn(1000);
                    // }
                },
                error: function(data) {
                    var errors = $.parseJSON(data.responseText);

                    $(".print-error-msg").find("ul").html('');
                    $(".print-error-msg").css('display', 'block');
                    $.each(errors, function(key, value) {
                        $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                    });

                },
                dataType: "json"
            });
        }

        function get_vehicle(from_date, to_date) {
            var id = $("input:hidden[name=id]").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ url('admin/get_vehicle') }}",
                data: "req=edit&id=" + id + "&from_date=" + from_date + "&to_date=" + to_date,
                success: function(data2) {
                    $("#vehicle_id").empty();
                    $("#vehicle_id").select2({
                        placeholder: "@lang('fleet.selectVehicle')",
                        data: data2.data
                    });
                    // if(data2.show_error=="yes"){

                    // $("#msg_vehicle").removeClass("hide").fadeIn(1000);
                    // } else {
                    // $("#msg_vehicle").addClass("hide").fadeIn(1000);
                    // }
                },
                error: function(data) {
                    var errors = $.parseJSON(data.responseText);
                    $(".print-error-msg").find("ul").html('');
                    $(".print-error-msg").css('display', 'block');
                    $.each(errors, function(key, value) {
                        $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                    });
                },
                dataType: "json"
            });
        }

        $(document).ready(function() {
            $('#pickup').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                sideBySide: true,
                icons: {
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $('#dropoff').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                sideBySide: true,
                icons: {
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $("#pickup").on("dp.change", function(e) {
                if ($('#dropoff').val() == null || $('#dropoff').val() == "") {
                    var to_date = e.date.format("YYYY-MM-DD HH:mm:ss");
                } else {
                    var to_date = $('#dropoff').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
                }
                var from_date = e.date.format("YYYY-MM-DD HH:mm:ss");

                get_driver(from_date, to_date);
                get_vehicle(from_date, to_date);

                $('#dropoff').data("DateTimePicker").minDate(e.date);
            });

            $("#dropoff").on("dp.change", function(e) {
                $('#pickup').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss")
                var from_date = $('#pickup').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
                var to_date = e.date.format("YYYY-MM-DD HH:mm:ss");
                get_driver(from_date, to_date);
                get_vehicle(from_date, to_date);
            });
        });
    </script>
    @if (Hyvikk::api('google_api') == '1')
        <script>
            function initMap() {
                $('#pickup_addr').attr("placeholder", "");
                $('#dest_addr').attr("placeholder", "");
                // var input = document.getElementById('searchMapInput');
                var pickup_addr = document.getElementById('pickup_addr');
                new google.maps.places.Autocomplete(pickup_addr);

                var dest_addr = document.getElementById('dest_addr');
                new google.maps.places.Autocomplete(dest_addr);

                // autocomplete.addListener('place_changed', function() {
                //     var place = autocomplete.getPlace();
                //     document.getElementById('pickup_addr').innerHTML = place.formatted_address;
                // });
            }
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::api('api_key') }}&libraries=places&callback=initMap"
            async defer></script>
    @endif
@endsection
