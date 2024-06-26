@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">@lang('fleet.users')</li>
@endsection
@section('extra_css')
    <style type="text/css">
        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }

        td>img {
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.points') &nbsp;
                        {{-- @can('Users add')
                            <a href="{{ route('points.create') }}" class="btn btn-success" title="@lang('fleet.addPoint')"><i
                                    class="fa fa-plus"></i></a>
                        </h3>
                    @endcan --}}
                </div>

                <div class="card-body table-responsive">
                    <table class="table" id="ajax_data_table">
                        <thead class="thead-inverse">
                            <tr>
                                <th>

                                    <input type="checkbox" id="chk_all">

                                </th>
                                <th>#</th>
                                <th>@lang('fleet.user')</th>
                                <th>@lang('fleet.points')</th>
                               
                                <th>@lang('fleet.action')</th>
                            </tr>
                        </thead>
                        <tbody>

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
                {{-- <div class="modal-body">
                    {!! Form::open(['url' => 'admin/delete-points', 'method' => 'POST', 'id' => 'form_delete']) !!}
                    <div id="bulk_hidden"></div>
                    <p>@lFormang('fleet.confirm_bulk_delete')</p>
                </div> --}}
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

    <!-- Modal -->
    <div id="changepass" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('fleet.change_password')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => url('admin/change_password'), 'id' => 'changepass_form']) !!}
                    <form id="change" action="{{ url('admin/change_password') }}" method="POST">

                        {!! Form::hidden('driver_id', '', ['id' => 'driver_id']) !!}
                        <div class="form-group">
                            {!! Form::label('passwd', __('fleet.password'), ['class' => 'form-label']) !!}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                {!! Form::password('passwd', ['class' => 'form-control', 'id' => 'passwd', 'required']) !!}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="password" class="btn btn-info" type="submit">@lang('fleet.change_password')</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
                    </button>
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

        $('#changepass').on('show.bs.modal', function(e) {
            var id = e.relatedTarget.dataset.id;
            $("#driver_id").val(id);
        });

        $("#changepass_form").on("submit", function(e) {
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                success: function(data) {

                    new PNotify({
                        title: 'Success!',
                        text: "@lang('fleet.passwordChanged')",
                        type: 'info'
                    });
                },

                dataType: "html"
            });
            $('#changepass').modal("hide");
            e.preventDefault();
        });
        $(function() {

            var table = $('#ajax_data_table').DataTable({
                "language": {
                    "url": '{{ asset('assets/datatables/') . '/' . __('fleet.datatable_lang') }}',
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/user_points-fetch') }}",
                    type: 'POST',
                    data: {}
                },
                columns: [{
                        data: 'check',
                        name: 'check',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: 'No',
                        width: '5%',
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                        // searchable: false,
                        // orderable: false
                    },
                    {
                        data: 'points',
                        name: 'points'
                    },
                    // {
                    //     data: 'points',
                    //     name: 'points'
                    // },
                    
                    // {
                    //     data: 'last_name',
                    //     name: 'last_name'
                    // },
                    // {
                    //     data: 'email',
                    //     name: 'email'
                    // },
                    // {
                    //     data: 'created_at',
                    //     name: 'created_at'
                    // },
                    // {
                    //     data: 'user_type',
                    //     name: 'user_type'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
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
        $(document).on('click', 'input[type="checkbox"]', function() {
            if (this.checked) {
                $('#bulk_delete').prop('disabled', false);

            } else {
                if ($("input[name='ids[]']:checked").length == 0) {
                    $('#bulk_delete').prop('disabled', true);
                }
            }

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
