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
    {{-- <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('fleet.customers')</a></li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title w-100 d-flex justify-content-between align-items-center"> <span>@lang('fleet.driver')
                            @lang('fleet.details')</span>
                        {{-- <div class=" float-right">
                            <a href="{{ route('customers.edit', $customer->id) }}"
                                class="float-right btn btn-sm btn-warning" title="@lang('fleet.edit_driver')"><i
                                    class="fa fa-edit"></i></a>
                        </div> --}}
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>@lang('fleet.first_name')</b>: {{ $customer->first_name }}</p>
                            
                            <p><b>@lang('fleet.phone')</b>: {{ $customer->phone }}</p>
                            <p><b>@lang('fleet.date_of_birth')</b>: {{ $customer->date_of_birth }}</p>


                        </div>
                        <div class="col-md-6">
                            <p><b>@lang('fleet.last_name')</b>: {{ $customer->last_name }}</p>
                           
                            <p><b>@lang('fleet.email')</b>: {{ $customer->email }}</p>
                            <p><b>@lang('fleet.profile_photo')</b>:<img class='img'
                                    width='60px'src={{ $customer->profile_image != null ? asset('uploads/' . $customer->profile_image) : asset('assets/images/no-user.jpg') }}>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        'customer_id': "{{ $customer->id }}"
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
