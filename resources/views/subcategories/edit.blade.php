@extends('layouts.app')
@section('extra_css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">

    <style type="text/css">
        .select2-selection:not(.select2-selection--multiple) {
            height: 38px !important;
        }

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">@lang('fleet.edit_subcategory')</h3>
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

                    {!! Form::open(['route' => ['subcategories.update', $subcategory->id], 'files' => true, 'method' => 'PATCH']) !!}
                    {!! Form::hidden('id', $subcategory->id) !!}
                    {!! Form::hidden('edit', '1') !!}



                    <div class="form-group">
                        {!! Form::label('category_id', __('fleet.select_category'), ['class' => 'col-xs-5 control-label']) !!}
                        <div class="col-md-6">
                            <select id="category_id" name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"@if ($subcategory->category_id == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
                                {!! Form::text('title', $subcategory->title, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>


                    </div>



                    <div class="col-md-12">
                        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
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




            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

        });
    </script>
@endsection
