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

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="card-title"> @lang('fleet.fare_manage_inside') : <strong><span id="total_today"> <a
                                            href="{{ route('farecreate') }}" class="btn btn-success"
                                            title="@lang('fleet.addDriver')"> <i class="fa fa-plus"></i> </a></span> </strong>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive" id="expenses">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <table class="table" id="data_table">
                        <thead class="thead-inverse">
                            <tr>
                                <th>
                                    <input type="checkbox" id="chk_all">
                                </th>

                                <th>@lang('fleet.id')</th>
                                <th>@lang('fleet.type')</th>
                                <th>@lang('fleet.user_type')</th>
                                <th>@lang('fleet.price_per_km')</th>
                                <th>@lang('fleet.price_per_minute')</th>
                                <th>@lang('fleet.limit_distance')</th>
                                <th>@lang('fleet.cost')</th>

                                <th>@lang('fleet.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $x = 1;
                            ?>
                            @foreach ($settings as $row)
                                <tr>

                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox"
                                            id="chk{{ $row->id }}" onclick='checkcheckbox();'>
                                    </td>
                                    <td>{{ $x++ }}</td>

                                    <td>{{ $row->vehicletype }}</td>
                                    <td>{{ $row->user_type }}</td>
                                    <td>{{ $row->base_km }} Sy</td>
                                    <td>{{ $row->base_time }} Sy</td>
                                    @if (isset($row->limit_distance))
                                        <td>{{ $row->limit_distance }} km</td>
                                    @else
                                        <td>---</td>
                                    @endif

                                    @if (isset($row->cost))
                                        <td>{{ $row->cost }} Sy</td>
                                    @else
                                        <td>---</td>
                                    @endif
                                    <td>

                                        <div class="btn-group" style="background:#075296;">
                                            <button type="button" style="color:white;" class="btn  dropdown-toggle"
                                                data-toggle="dropdown">
                                                <span class="fa fa-gear"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu custom" role="menu">

                                                <a class="dropdown-item" href="{{ url('admin/getfare1/' . $row->id) }}">
                                                    <span aria-hidden="true" class="fa fa-edit"
                                                        style="color: #075296"></span> @lang('fleet.edit')</a>

                                                {!! Form::open([
                                                    'url' => 'admin/delete-fare1/' . $row->id,
                                                    'method' => 'DELETE',
                                                    'class' => 'form-horizontal del_form',
                                                    'id' => 'form_' . $row->id,
                                                ]) !!}
                                                {!! Form::hidden('id', $row->id) !!}
                                                @can('Transactions delete')
                                                    <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal"
                                                        data-target="#myModal"><span aria-hidden="true" class="fa fa-trash"
                                                            style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                                                @endcan
                                            </div>
                                            {!! Form::open([
                                                'url' => 'admin/maintenance/' . $row->id,
                                                'method' => 'DELETE',
                                                'class' => 'form-horizontal del_form',
                                                'id' => 'form_' . $row->id,
                                            ]) !!}
                                            {!! Form::hidden('id', $row->id) !!}
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

                                <th>@lang('fleet.id')</th>
                                <th>@lang('fleet.type')</th>
                                <th>@lang('fleet.user_type')</th>
                                <th>@lang('fleet.base_km')</th>
                                <th>@lang('fleet.price_per_minute')</th>
                                <th>@lang('fleet.limit_distance')</th>
                                <th>@lang('fleet.cost')</th>

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
                    {!! Form::open(['url' => 'admin/fare_bulkdelete1', 'method' => 'POST', 'id' => 'form_delete']) !!}
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
                    <button id="del_btn" class="btn btn-danger" type="button" data-submit="">@lang('fleet.delete')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('fleet.vehicle')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        @lang('fleet.close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // $('#vehicle_id').select2({
            //     placeholder: "@lang('fleet.selectVehicle')"
            // });
            $('#vendor_id').select2({
                placeholder: "@lang('fleet.select_vendor')"
            });
            $('#vechicle_id').select2({
                placeholder: "@lang('fleet.selectVehicle')"
            });
            $('#type_id').select2({
                placeholder: "@lang('fleet.select_type')"
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
        $(document).on('click', '.openBtn', function() {
            // alert($(this).data("id"));
            var id = $(this).attr("data-id");
            $('#myModal2 .modal-body').load('{{ url('admin/getfare') }}/' + id, function(result) {
                $('#myModal2').modal({
                    show: true
                });
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
