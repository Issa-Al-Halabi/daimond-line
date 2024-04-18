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
    <!--<li class="breadcrumb-item active">@lang('fleet.expense')</li>-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.addoption')
                    </h3>
                </div>

                <div class="card-body">
                    {{-- <div class="row"> --}}
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
                        'route' => 'car_option.store',
                        'method' => 'post',
                        'class' => 'form-inline',
                        'id' => 'exp_form',
                    ]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('role_id', __('fleet.select_type'), ['class' => 'form-label']) !!}
                                <select id="role_id" name="type_id" class="form-control" required>
                                    <option value="">@lang('fleet.type')</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->vehicletype }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('option', __('fleet.option'), ['class' => 'form-label']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('price', __('fleet.price'), ['class' => 'form-label']) !!}
                                {!! Form::text('price', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div style="margin-top: 5px">
                            <h6 style="font-weight: bold">@lang('fleet.isenable')</h6>
                        </div>
                        <div class="col-md-3" style="margin-top: 5px">
                            <label class="switch">
                                <input type="checkbox" name="isenable" value="1">
                                <span class="slider round"></span>
                            </label>
                        </div>

                    </div>
                    <div class=" ml-7" style="margin-left: 70px;margin-top: 3px">
                        {{-- <div class=" mt-8"> --}}
                        {!! Form::submit(__('fleet.add'), ['class' => 'btn btn-success']) !!}
                        {{-- </div> --}}
                    </div>


                    {!! Form::close() !!}
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="card-title"> @lang('fleet.manage_option') :
                            </h3>
                           
                        </div>
                        {{-- <div class="col-md-8 pull-right">
                            {!! Form::open(['url' => 'admin/expense_records', 'class' => 'form-inline']) !!}
    
                            <div class="form-group">
                                {!! Form::label('date1', 'From', ['class' => 'control-label']) !!}
                                <div class="input-group date">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fa fa-calendar"></i></span></div>
                                    {!! Form::text('date1', $date1, [
                                        'class' => 'form-control',
                                        'placeholder' => __('fleet.start_date'),
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="form-group" style="margin-right: 10px">
                                {!! Form::label('date2', 'To') !!}
                                <div class="input-group date">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fa fa-calendar"></i></span></div>
                                    {!! Form::text('date2', $date2, ['class' => 'form-control', 'placeholder' => __('fleet.end_date'), 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group ">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div> --}}
                    </div>
                </div>

                <div class="card-body table-responsive" id="expenses">
                    <table class="table" id="data_table">
                        <thead class="thead-inverse">
                            <tr>
                                <th>

                                    <input type="checkbox" id="chk_all">

                                </th>

                                <th>@lang('fleet.type')</th>
                                <th>@lang('fleet.option')</th>
                                <th>@lang('fleet.price')</th>
                                <th>@lang('fleet.is_enable')</th>
                                <th>@lang('fleet.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($price_types as $type)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $type->id }}" class="checkbox"
                                            id="chk{{ $type->id }}" onclick='checkcheckbox();'>
                                    </td>
                                    <td>{{ $type->vehicletype }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->price }}</td>
                                    @if ($type->is_enable == 1)
                                        <td> @lang('fleet.Active') </td>
                                    @else
                                        <td> @lang('fleet.InActive')</td>
                                    @endif

                                    <td>
                                        {{-- {!! Form::open([
                                            'url' => 'admin/expense/' . $ex->id,
                                            'method' => 'DELETE',
                                            'class' => 'form-horizontal del_form',
                                            'id' => 'form_' . $ex->id,
                                        ]) !!}
                                        {!! Form::hidden('id', $ex->id) !!}
                                        @can('Transactions delete')
                                            <button type="button" class="btn btn-danger delete" id="btn_delete"
                                                data-id="{{ $ex->id }}" title="@lang('fleet.delete')">
                                                <span class="fa fa-times" aria-hidden="true"></span>
                                            </button>
                                        @endcan
                                        {!! Form::close() !!} --}}
                                        <div class="btn-group" style="background:#075296;">
                                            <button type="button" class="btn  dropdown-toggle" style="color:white;"
                                                data-toggle="dropdown">
                                                <span class="fa fa-gear"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu custom" role="menu">
                                                {{-- <a class="dropdown-item" href="{{ url('admin/car_option/' . $type->id) }}">
                                                    <span aria-hidden="true" class="fa fa-edit"
                                                        style="color: #75296;"></span>
                                                    @lang('fleet.edit')</a> --}}

                                                {!! Form::hidden('id', $type->id) !!}
                                                <a class="dropdown-item openBtn" data-id="{{ $type->id }}"
                                                    data-toggle="modal" data-target="#myModal2" id="openBtn">
                                                    <span class="fa fa-edit" aria-hidden="true"
                                                        style="color: #075296;"></span> @lang('fleet.edit')
                                                </a>
                                                <a class="dropdown-item" data-id="{{ $type->id }}" data-toggle="modal"
                                                    data-target="#myModal"><span aria-hidden="true" class="fa fa-trash"
                                                        style="color: #dd4b39""></span>
                                                    @lang('fleet.delete')</a>


                                            </div>
                                            {!! Form::open([
                                                'url' => 'admin/car_option/' . $type->id,
                                                'method' => 'DELETE',
                                                'class' => 'form-horizontal del_form',
                                                'id' => 'form_' . $type->id,
                                            ]) !!}
                                            {!! Form::hidden('id', $type->id) !!}
                                            {{-- @can('Transactions delete')
                                                <button type="button" class="btn btn-danger delete" id="btn_delete"
                                                    data-id="{{ $ex->id }}" title="@lang('fleet.delete')">
                                                    <span class="fa fa-times" aria-hidden="true"></span>
                                                </button>
                                            @endcan --}}
                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>

                                    <button class="btn btn-danger" id="bulk_delete" data-toggle="modal"
                                        data-target="#bulkModal" disabled title="@lang('fleet.delete')"><i
                                            class="fa fa-trash"></i></button>

                                </th>
                                <th>@lang('fleet.type')</th>
                                <th>@lang('fleet.option')</th>
                                <th>@lang('fleet.price')</th>
                                <th>@lang('fleet.is_enable')</th>
                                <th>@lang('fleet.action')</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                    {!! Form::open(['url' => 'admin/delete-option', 'method' => 'POST', 'id' => 'form_delete']) !!}
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
    <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('fleet.editoption')</h4>
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
        $(document).on('click', '.openBtn', function() {
            // alert($(this).data("id"));
            var id = $(this).attr("data-id");
            $('#myModal2 .modal-body').load('{{ url('admin/car_option') }}/' + id, function(result) {
                $('#myModal2').modal({
                    show: true
                });
            });
        });

        $(document).ready(function() {
            // $('#vehicle_id').select2({
            //     placeholder: "@lang('fleet.selectVehicle')"
            // });
            $('#vendor_id').select2({
                placeholder: "@lang('fleet.select_vendor')"
            });
            $('#driver_id').select2({
                placeholder: "@lang('fleet.Selectdriver')"
            });
            $('#expense_type').select2({
                placeholder: "@lang('fleet.expenseType')"
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
