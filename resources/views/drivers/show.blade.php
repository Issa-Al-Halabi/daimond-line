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
            border-radius: 60px
        }
    </style>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">@lang('menu.drivers')</a></li>
    <li class="breadcrumb-item active">{{ $driver->name }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title w-100 d-flex justify-content-between align-items-center"> <span>@lang('fleet.driver')
                            @lang('fleet.details'):</span>
                        
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>@lang('fleet.firstname')</b>: {{ $driver->first_name }}</p>
                            <p><b>@lang('fleet.lastname')</b>: {{ $driver->last_name }}</p>
                            <p><b>@lang('fleet.email')</b>: {{ $driver->email }}</p>
                            <p><b>@lang('fleet.place_of_birth')</b>: {{ $driver->place_of_birth }}</p>
                            <p>
                                @if ($driver->profile_image)
                                    <span><b>@lang('fleet.profile_photo'):</b><a
                                            href="{{ asset('uploads/' . $driver->profile_image) }}" target="_blank">
                                            {{ $driver->profile_image }}</a>
                                    </span>
                                @else
                                    <span>__</span>
                                @endif
                            </p>
                            <p>
                                @if ($driver->personal_identity)
                                    <span><b>@lang('fleet.personal_identity'):</b><a
                                            href="{{ asset('uploads/' . $driver->personal_identity) }}" target="_blank">
                                            {{ $driver->personal_identity }}</a>
                                    </span>
                                @else
                                    <span>__</span>
                                @endif
                            </p>
                            <p>
                                @if ($driver->driving_certificate)
                                    <span><b>@lang('fleet.driving_certificate'):</b><a
                                            href="{{ asset('uploads/' . $driver->driving_certificate) }}" target="_blank">
                                            {{ $driver->driving_certificate }}</a>
                                    </span>
                                @else
                                    <span>__</span>
                                @endif
                            </p>


                        </div>

                        <div class="col-md-6">
                            <p><b>@lang('fleet.phone')</b>: {{ $driver->phone }}</p>
                            <p><b>@lang('fleet.start_date')</b>: {{ $driver->created_at }}</p>

                            <p><b>@lang('fleet.date_of_birth')</b>: {{ $driver->date_of_birth }}</p>
                            <p><b>@lang('fleet.status')</b>: {{ $driver->is_active }}</p>
                            </p>
                            <p>
                                @if ($driver->car_insurance)
                                    <span><b>@lang('fleet.car_insurance'):</b><a
                                            href="{{ asset('uploads/' . $driver->car_insurance) }}" target="_blank">
                                            {{ $driver->car_insurance }}</a>
                                    </span>
                                @else
                                    <span>__</span>
                                @endif
                            </p>
                            <p>
                                @if ($driver->car_image)
                                    <span><b>@lang('fleet.car_image'):</b><a href="{{ asset('uploads/' . $driver->car_image) }}"
                                            target="_blank">
                                            {{ $driver->car_image }}</a>
                                    </span>
                                @else
                                    <span>__</span>
                                @endif
                            </p>
                            <p>
                                @if ($driver->car_mechanic)
                                    <span><b>@lang('fleet.car_mechanic'):</b><a
                                            href="{{ asset('uploads/' . $driver->car_mechanic) }}" target="_blank">
                                            {{ $driver->car_mechanic }}</a>
                                    </span>
                                @else
                                    <span>__</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title"> @lang('fleet.driver') @lang('fleet.bookings') &nbsp;
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive display" id="ajax_data_table"
                            style="padding-bottom: 35px; width: 100%">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="chk_all">
                                    </th>
                                    <th style="width: 10% !important">@lang('fleet.customer')</th>
                                    <th style="width: 10% !important">@lang('fleet.vehicle')</th>
                                    <th style="width: 10% !important">@lang('fleet.pickup_addr')</th>
                                    <th style="width: 10% !important">@lang('fleet.dropoff_addr')</th>
                                    <th style="width: 10% !important">@lang('fleet.pickup')</th>
                                    <th style="width: 10% !important">@lang('fleet.dropoff')</th>
                                    <th style="width: 10% !important">@lang('fleet.passengers')</th>
                                    <th style="width: 10% !important">@lang('fleet.payment_status')</th>
                                    <th>@lang('fleet.booking_status')</th>
                                    <th style="width: 10% !important">@lang('fleet.amount')</th>
                                  
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        @can('Bookings delete')
                                            <button class="btn btn-danger" id="bulk_delete" data-toggle="modal"
                                                data-target="#bulkModal" disabled title="@lang('fleet.delete')"><i
                                                    class="fa fa-trash"></i></button>
                                        @endcan
                                    </th>
                                    <th>@lang('fleet.customer')</th>
                                    <th>@lang('fleet.vehicle')</th>
                                    <th>@lang('fleet.pickup_addr')</th>
                                    <th>@lang('fleet.dropoff_addr')</th>
                                    <th>@lang('fleet.pickup')</th>
                                    <th>@lang('fleet.dropoff')</th>
                                    <th>@lang('fleet.passengers')</th>
                                    <th>@lang('fleet.payment_status')</th>
                                    <th>@lang('fleet.booking_status')</th>
                                    <th>@lang('fleet.amount')</th>
                                 
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('script')
    <script>
        $(function() {

            var table = $('#ajax_data_table').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{ __('fleet.print') }}',

                    exportOptions: {
                        columns: ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<h3>{{ __('fleet.bookings') }}</h3>'
                            );
                        $(win.document.body).find('table')
                            .addClass('table-bordered');
                        // $(win.document.body).find( 'td' ).css( 'font-size', '10pt' );

                    }
                }],
                "language": {
                    "url": '{{ asset('assets/datatables/') . '/' . __('fleet.datatable_lang') }}',
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/driver-bookings-fetch') }}",
                    type: 'POST',
                    data: {
                        'driver_id': "{{ $driver->id }}"
                    }
                },
                columns: [{
                        data: 'check',
                        name: 'check',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'customer',
                        name: 'customer.name'
                    },
                    {
                        data: 'vehicle',
                        name: 'vehicle'
                    },
                    {
                        data: 'pickup_addr',
                        name: 'pickup_addr'
                    },
                    {
                        data: 'dest_addr',
                        name: 'dest_addr'
                    },
                    {
                        name: 'pickup',
                        data: {
                            _: 'pickup.display',
                            sort: 'pickup.timestamp'
                        }
                    },
                    {
                        name: 'dropoff',
                        data: {
                            _: 'dropoff.display',
                            sort: 'dropoff.timestamp'
                        }
                    },
                    {
                        data: 'travellers',
                        name: 'travellers'
                    },
                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'ride_status',
                        name: 'ride_status'
                    },
                    {
                        data: 'tax_total',
                        name: 'tax_total'
                    },
                    // {data: 'action',  name: 'action', searchable:false, orderable:false}
                ],
                order: [
                    [1, 'desc']
                ],
                "initComplete": function() {
                    table.columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change', function() {
                            // console.log($(this).parent().index());
                            that.search(this.value).draw();
                        });
                    });
                }
            });
        });

        $('#bulk_delete').on('click', function() {
            // console.log($( "input[name='ids[]']:checked" ).length);
            if ($("input[name='ids[]']:checked").length == 0) {
                $('#bulk_delete').prop('type', 'button');
                new PNotify({
                    title: 'Failed!',
                    text: "@lang('fleet.delete_error')",
                    type: 'error'
                });
                $('#bulk_delete').attr('disabled', true);
            }
            if ($("input[name='ids[]']:checked").length > 0) {
                // var favorite = [];
                $.each($("input[name='ids[]']:checked"), function() {
                    // favorite.push($(this).val());
                    $("#bulk_hidden").append('<input type=hidden name=ids[] value=' + $(this).val() + '>');
                });
                // console.log(favorite);
            }
        });


        $('#chk_all').on('click', function() {
            if (this.checked) {
                $('.checkbox').each(function() {
                    $('.checkbox').prop("checked", true);
                });
            } else {
                $('.checkbox').each(function() {
                    $('.checkbox').prop("checked", false);
                });
                $('#bulk_delete').prop('disabled', true);
            }
        });

        // Checkbox checked
        function checkcheckbox() {
            // Total checkboxes
            var length = $('.checkbox').length;
            // Total checked checkboxes
            var totalchecked = 0;
            $('.checkbox').each(function() {
                if ($(this).is(':checked')) {
                    totalchecked += 1;
                }
            });
            // console.log(length+" "+totalchecked);
            // Checked unchecked checkbox
            if (totalchecked == length) {
                $("#chk_all").prop('checked', true);
            } else {
                $('#chk_all').prop('checked', false);
            }
        }
    </script>
@endsection
