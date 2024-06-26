@extends('layouts.app')
@php($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y')
@php($currency = Hyvikk::get('currency'))
@section('extra_css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <style type="text/css">
        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }
    </style>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">@lang('fleet.expense')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.addRecord')
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {!! Form::open([
                            'route' => ['maintenance.update', $maint->id],
                        
                            'method' => 'PATCH',
                            'class' => 'form-inline',
                            'id' => 'exp_form',
                        ]) !!}
                        {!! Form::hidden('id', $maint->id) !!}

                        {{-- <div class="col-md-4 col-sm-6">
                            <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" style="width: 100%"
                                required>
                                <option value="">@lang('fleet.selectVehicle')</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" @if ($maint->vehicle_id == $vehicle->id) selected @endif>
                                        {{ $vehicle->maker->make }}-{{ $vehicle->vehiclemodel->model }}-{{ $vehicle->license_plate }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-md-4 ">
                            <select id="vechicle_id" name="vehicle_id" class="form-control vehicles" style="width: 100%"
                                required>
                                <option value="">@lang('fleet.selectVehicle')</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" @if ($maint->vehicle_id == $vehicle->id) selected @endif>
                                        {{ $vehicle->types->vehicletype }}:{{ $vehicle->car_model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5" style="margin-top: 5px;">
                            <select id="type_id" name="type_id[]" class="form-control vehicles" style="width: 100%"
                                multiple="true" required>
                                <option value="">@lang('fleet.select_type')</option>
                                @foreach ($types as $type)
                                    <?php
                                    
                                    $x = explode(',', $maint->type_id);
                                    
                                    $selected = in_array($type->id, $x) ? 'selected' : '';
                                    
                                    ?>
                                    <option value="{{ $type->id }}"{{ $selected }}>
                                        {{ $type->type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-md-5"style="margin-top: 5px;">
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-calendar"></i></span></div>
                                {!! Form::text('date', $maint->date, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-2"style="margin-top: 5px;">
                            <div class="input-group ">
                                <label class="switch">
                                    <input type="checkbox" class="input-group-prepend" name="status" value="1"
                                        @if ($maint->status == '1') Checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            </label>
                        </div>


                        <div class="col-md-4" style="margin-top: 10px;">
                            @can('Transactions add')
                                <button type="submit" class="btn btn-success">@lang('fleet.update')</button>
                            @endcan
                        </div>

                        {{-- <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('status', __('fleet.service'), ['class' => 'col-xs-5 control-label']) !!}
                                </div>
                                <div class="col-ms-6" style="margin-left: -140px">
                                    <label class="switch">
                                        <input type="checkbox" name="status" value="1"
                                            @if ($maint->status == '1') checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div id="bulkModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">@lang('fleet.delete')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            {!! Form::open(['url' => 'admin/delete-expense', 'method' => 'POST', 'id' => 'form_delete']) !!}
                            <div id="bulk_hidden"></div>
                            <p>@lang('fleet.confirm_bulk_delete')</p>
                        </div>
                        <div class="modal-footer">
                            <button id="bulk_action" class="btn btn-danger" type="submit"
                                data-submit="">@lang('fleet.delete')</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">@lang('fleet.delete')</h4>
                        </div>
                        <div class="modal-body">
                            <p>@lang('fleet.confirm_delete')</p>
                        </div>
                        <div class="modal-footer">
                            <button id="del_btn" class="btn btn-danger" type="button"
                                data-submit="">@lang('fleet.delete')</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
        @endsection


        @section('script')
            <script src="{{ asset('assets/js/moment.js') }}"></script>
            <!-- bootstrap datepicker -->
            <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#vehicle_id').select2({
                        placeholder: "@lang('fleet.selectVehicle')"
                    });
                    $('#vendor_id').select2({
                        placeholder: "@lang('fleet.select_vendor')"
                    });
                    $('#expense_type').select2({
                        placeholder: "@lang('fleet.expenseType')"
                    });
                    $('#type_id').select2({
                        placeholder: "@lang('fleet.select_type')"
                    });

                    $('#date').datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    });

                    $('#date1').datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    });

                    $('#date2').datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    });

                    $("#del_btn").on("click", function() {
                        var id = $(this).data("submit");
                        $("#form_" + id).submit();
                    });

                    $('#myModal').on('show.bs.modal', function(e) {
                        var id = e.relatedTarget.dataset.id;
                        $("#del_btn").attr("data-submit", id);
                    });


                    $(document).on("click", ".delete", function(e) {
                        var hvk = confirm("Are you sure?");
                        if (hvk == true) {
                            var id = $(this).data("id");
                            var action = "{{ url('admin/expense') }}" + "/" + id;
                            $.ajax({
                                type: "POST",
                                url: action,
                                data: "_method=DELETE&_token=" + window.Laravel.csrfToken + "&id=" + id,
                                success: function(data) {
                                    $("#expenses").empty();
                                    $("#expenses").html(data);
                                    new PNotify({
                                        title: 'Deleted!',
                                        text: '@lang('fleet.deleted')',
                                        type: 'wanring'
                                    })
                                },
                                dataType: "HTML",
                            });
                        }
                    });
                });

                $('input[type="checkbox"]').on('click', function() {
                    $('#bulk_delete').removeAttr('disabled');
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
