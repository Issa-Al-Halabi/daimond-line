<div class="btn-group" style="background:#075296;">
    <button type="button" class="btn  dropdown-toggle" style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>

    <div class="dropdown-menu custom" role="menu">
        @if ($row->status == 0 && $row->ride_status != 'Cancelled')
            @can('Bookings edit')
           
                <a class="dropdown-item" href="{{ url('admin/bookings/' . $row->id . '/edit') }}"> <span aria-hidden="true"
                        class="fa fa-eye" style="color: #075296;"></span> @lang('fleet.view')</a>
                        
            @endcan
          @php
      
         $expenses= App\Model\Expense::where('trip_id',$row->id)->where('expense_type','constant')->get();
 
         @endphp

            @can('Bookings edit')
               
                @if($row->category_id=='2'&& $row->status=="pending" )
      @if($expenses->isEmpty())
                <a class="dropdown-item" href="{{ url('admin/bookings/expense/' . $row->id) }}"> <span aria-hidden="true"
                        class="fa fa-plus" style="color: #075296;"></span> @lang('fleet.add_expense')</a>
      @else
      
      <a class="dropdown-item" href="{{ url('admin/bookings/edit_expense/' . $row->id) }}"> <span aria-hidden="true"
                        class="fa fa-edit" style="color: #075296;"></span> @lang('fleet.edit_expense')</a>
               @endif
               
                @endif
            @endcan
             @if($row->category_id=='2' && $row->status=="pending")
            <a class="dropdown-item openBtn" data-id="{{ $row->id }}"
                                                    data-toggle="modal" data-target="#myModal2" id="openBtn">
                                                    <span class="fa fa-plus" aria-hidden="true"
                                                        style="color: #075296;"></span> @lang('fleet.assign_driver')
                                                </a>
               @endif
            @if ($row->receipt != 1)
                <!--<a class="dropdown-item vtype" data-id="{{ $row->id }}" data-toggle="modal"-->
                <!--    data-target="#cancelBooking"> <span class="fa fa-times" aria-hidden="true"-->
                <!--        style="color: #dd4b39"></span> @lang('fleet.cancel_booking')</a>-->
            @endif
        @endif
      
         
                      <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal"><span
                    aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
        {{-- @if ($row->vehicle_id != null)
            @if ($row->status == 0 && $row->receipt != 1)
                @if (Auth::user()->user_type != 'C' && $row->ride_status != 'Cancelled')
                    <a data-toggle="modal" data-target="#receiptModal" class="open-AddBookDialog dropdown-item"
                        data-booking-id="{{ $row->id }}" data-user-id="{{ $row->user_id }}"
                        data-customer-id="{{ $row->customer_id }}" data-vehicle-id="{{ $row->vehicle_id }}"
                        data-vehicle-type="{{ strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) }}"
                        data-base-mileage="{{ $row->total_kms ? $row->total_kms : Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_base_km') }}"
                        data-base-fare="{{ $row->total ? $row->total : Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_base_fare') }}"
                        data-base_km_1="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_base_km') }}"
                        data-base_fare_1="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_base_fare') }}"
                        data-wait_time_1="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_base_time') }}"
                        data-std_fare_1="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_std_fare') }}"
                        data-base_km_2="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_weekend_base_km') }}"
                        data-base_fare_2="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_weekend_base_fare') }}"
                        data-wait_time_2="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_weekend_wait_time') }}"
                        data-std_fare_2="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_weekend_std_fare') }}"
                        data-base_km_3="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_night_base_km') }}"
                        data-base_fare_3="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_night_base_fare') }}"
                        data-wait_time_3="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_night_wait_time') }}"
                        data-std_fare_3="{{ Hyvikk::fare(strtolower(str_replace(' ', '', $row->vehicle->types->vehicletype)) . '_night_std_fare') }}"><span
                            aria-hidden="true" class="fa fa-file" style="color: #5cb85c;">

                        </span> @lang('fleet.invoice')
                    </a>
                @endif
            @elseif($row->receipt == 1)
                <a class="dropdown-item" href="{{ url('admin/bookings/receipt/' . $row->id) }}"><span aria-hidden="true"
                        class="fa fa-list" style="color: #31b0d5;"></span> @lang('fleet.receipt')
                </a>
                @if ($row->receipt == 1 && $row->status == 0 && Auth::user()->user_type != 'C')
                    <a class="dropdown-item" href="{{ url('admin/bookings/complete/' . $row->id) }}"
                        data-id="{{ $row->id }}" data-toggle="modal" data-target="#journeyModal"><span
                            aria-hidden="true" class="fa fa-check" style="color: #5cb85c;"></span> @lang('fleet.complete')
                    </a>
                @endif
            @endif
        @endif --}}

        {{-- @if ($row->status == 1)
            @if ($row->payment == 0 && Auth::user()->user_type != 'C')
                <a class="dropdown-item" href="{{ url('admin/bookings/payment/' . $row->id) }}"><span aria-hidden="true"
                        class="fa fa-credit-card" style="color: #5cb85c;"></span> @lang('fleet.make_payment')
                </a>
            @elseif($row->payment == 1)
                <a class="dropdown-item text-muted" class="disabled"><span aria-hidden="true" class="fa fa-credit-card"
                        style="color: #5cb85c;"></span> @lang('fleet.paid')
                </a>
            @endif
        @endif --}}
    </div>
    <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('fleet.assign_driver')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {{-- body --}}
                <div class="modal-body">

                </div>
                {{-- footer --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        @lang('fleet.close')
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
{!! Form::open([
    'url' => 'admin/bookings/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'book_' . $row->id,
]) !!}
{!! Form::hidden('id', $row->id) !!}
{!! Form::close() !!}
<!--@section('script')-->
<!--    <script src="{{ asset('assets/js/moment.js') }}"></script>-->
    
<!--    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>-->
<!--    <script type="text/javascript">-->
<!--        $(document).on('click', '.openBtn', function() {-->
           
<!--            var id = $(this).attr("data-id");-->
<!--            $('#myModal2 .modal-body').load('{{ url('admin/bookings/assign') }}/' + id, function(result) {-->
<!--                $('#myModal2').modal({-->
<!--                    show: true-->
<!--                });-->
<!--            });-->
<!--        });-->
<!--    </script>-->
<!--@endsection-->
