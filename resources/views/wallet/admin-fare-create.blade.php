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
            <div class="box box-info">
            	    <!-------------------------------------->
               <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.admin_fare'):
                    </h3>
                </div>
          
               <div class="card-body">

					{!! Form::open(['url' => 'admin/admin-fare/create', 'class' => 'form-horizontal']) !!}
					<div class="box-body">
						<div class="form-group">
						    
							<label for="input_service_fee" class="col-sm-3 control-label">Minimum Wallet</label>
							<div class="col-md-7 col-sm-offset-1">
								<div class="input-group">
									{!! Form::text('minimum_wallet',$val[3]->value, ['class' => 'form-control', 'id' => 'input_service_fee', 'placeholder' => 'Minimum Wallet']) !!}
									<span class="text-danger">{{ $errors->first('minimum_wallet') }}</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Admin External Fare
							</label>
							<div class="col-md-7 col-sm-offset-1">
								<div class="input-group">
									{!! Form::text('external_fare',$val[1]->value, ['class' => 'form-control', 'id' => 'input_driver_peak_fare', 'placeholder' => 'Admin Peak Fare']) !!}
									<div class="input-group-addon" >%</div>
									<span class="text-danger">{{ $errors->first('external_fare') }}</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Admin Internal Fare
							</label>
							<div class="col-md-7 col-sm-offset-1">
								<div class="input-group">
									{!! Form::text('internal_fare',$val[2]->value, ['class' => 'form-control', 'id' => 'input_driver_peak_fare', 'placeholder' => 'Admin Peak Fare']) !!}
									<div class="input-group-addon" >%</div>
									<span class="text-danger">{{ $errors->first('internal_fare') }}</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Admin Wallet
							</label>
							<div class="col-md-7 col-sm-offset-1">
								<div class="input-group">
									{!! Form::text('admin_wallet',$val[0]->value, ['class' => 'form-control', 'id' => 'input_driver_service_fee', 'placeholder' => 'Admin Wallet']) !!}
									<span class="text-danger">{{ $errors->first('admin_wallet') }}</span>
								</div>
							</div>
						</div>
					<div class="box-footer text-center">
						<button type="submit" class="btn btn-success" name="submit" value="submit">Submit</button>
						<button type="reset" class="btn btn-default" name="cancel">Cancel</button>
					</div>
					{!! Form::close() !!}
				</div>

                    </div>
            </div>
        </div>
				
			
			<!---------------------------------------------------->
            
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
                    {!! Form::open(['url' => 'admin/delete-expense', 'method' => 'POST', 'id' => 'form_delete']) !!}
                    <div id="bulk_hidden"></div>
                    <p>@lang('fleet.confirm_bulk_delete')</p>
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
        $(document).on('click', '.openBtn', function() {
            // alert($(this).data("id"));
            var id = $(this).attr("data-id");
            $('#myModal2 .modal-body').load('{{ url('admin/faretour_edit') }}/' + id, function(result) {
                $('#myModal2').modal({
                    show: true
                });
            });
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
