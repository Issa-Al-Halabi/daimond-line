@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item active">@lang('fleet.roles')</li>
@endsection
@section('extra_css')
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
                    <h3 class="card-title" style="color:#fbfbfb;">@lang('fleet.roles') &nbsp;
                        <a href="{{ route('roles.create') }}" class="btn btn-success" title="@lang('fleet.addNew')"><i
                                class="fa fa-plus"></i></a>
                    </h3>
                </div>

                <div class="card-body table-responsive">
                    <table class="table" id="data_table">
                        <thead class="thead-inverse">
                            <tr>

                                <th>@lang('fleet.role')</th>
                                {{-- <th>@lang('fleet.permission')</th> --}}
                                <th>@lang('fleet.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>

                                    <td>
                                        {{ $row->name }}
                                    </td>
                                    {{-- <td>
                @foreach ($row->getAllPermissions() as $perm)
                  {{ $perm->name }} <br>
                @endforeach
              </td> --}}
                                    <td>
                                        <div class="btn-group ">
                                            <button type="button" class="btn btn-info dropdown-toggle"
                                                style="background:#075296;" data-toggle="dropdown">
                                                <span class="fa fa-gear"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu custom" role="menu">
                                                <a class="dropdown-item"
                                                    href="{{ url('admin/roles/' . $row->id . '/edit') }}">
                                                    <span aria-hidden="true" class="fa fa-edit"
                                                        style="color: #075296;"></span> @lang('fleet.edit')</a>
                                                {!! Form::hidden('id', $row->id) !!}

                                                <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal"
                                                    data-target="#myModal"> <span aria-hidden="true" class="fa fa-trash"
                                                        style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                                            </div>
                                        </div>
                                        {!! Form::open([
                                            'url' => 'admin/roles/' . $row->id,
                                            'method' => 'DELETE',
                                            'class' => 'form-horizontal',
                                            'id' => 'form_' . $row->id,
                                        ]) !!}

                                        {!! Form::hidden('id', $row->id) !!}

                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

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
                    {!! Form::open(['url' => 'admin/delete-city', 'method' => 'POST', 'id' => 'form_delete']) !!}
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
                    <h4 class="modal-title">@lang('fleet.delete')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
@endsection

@section('script')
    <script type="text/javascript">
        $("#del_btn").on("click", function() {
            var id = $(this).data("submit");
            $("#form_" + id).submit();
        });

        $('#myModal').on('show.bs.modal', function(e) {
            var id = e.relatedTarget.dataset.id;
            $("#del_btn").attr("data-submit", id);
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
