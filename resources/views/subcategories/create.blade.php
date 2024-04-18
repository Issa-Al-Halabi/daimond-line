@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.add_new_subcat')
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

                    {!! Form::open(['route' => 'subcategories.store', 'files' => true, 'method' => 'post']) !!}
                    {{-- <div class="form-group"> --}}
                    {{-- {!! Form::label('category_id', __('fleet.select_category'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-md-6">
                            <select id="category_id" name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                    {{-- <div class="form-group">
                        {!! Form::label('category_id', __('fleet.select_category'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::select(
                                'category_id',
                                ['1' => 'Inside City', '2' => 'Oustide Damascus', '3' => 'Outside Syria'],
                                null,
                                [
                                    'class' => 'form-control',
                                    'required',
                                ],
                            ) !!}
                        </div>
                    </div> --}}
                    {{-- </div> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>


                    </div>

                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']) !!}
                    </div>

                    {!! Form::close() !!}
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
            // $('#group_id').select2({
            //     placeholder: "@lang('fleet.selectGroup')"
            // });
            $('#category_id').select2({
                placeholder: "@lang('fleet.type')"
            });
            // $('#category_id').select2({
            //     placeholder: "@lang('fleet.category')"
            // });
            // $('#make_id').select2({
            //     placeholder: "@lang('fleet.SelectVehicleMake')"
            // });
            // $('#color_id').select2({
            //     placeholder: "@lang('fleet.SelectVehicleColor')"
            // });
            $('#driver_id').select2({
                placeholder: "@lang('fleet.Selectdriver')"
            });
            // $('#make_id').on('change', function() {
            //     // alert($(this).val());
            //     $.ajax({
            //         type: "GET",
            //         url: "{{ url('admin/get-models') }}/" + $(this).val(),
            //         success: function(data) {
            //             var models = $.parseJSON(data);
            //             $('#model_id').empty();
            //             $('#model_id').append('<option value=""></option>');
            //             $.each(models, function(key, value) {
            //                 $('#model_id').append('<option value=' + value.id + '>' +
            //                     value.text + '</option>');
            //                 $('#model_id').select2({
            //                     placeholder: "@lang('fleet.SelectVehicleModel')"
            //                 });
            //             });
            //         },
            //         dataType: "html"
            //     });
            // });
            // $('#start_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#end_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#exp_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#lic_exp_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#reg_exp_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#issue_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });

            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    </script>
@endsection
