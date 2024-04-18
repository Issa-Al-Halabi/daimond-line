@extends('layouts.app')
@section('title', 'Create Extra Costs')
@section('breadcum')


@section('content')

    <div class="contentbar">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h5 class="box-tittle">@lang('fleet.edit_expense'):</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update_expense') }}" method="POST" enctype="multipart/form-data">
                         
                                                 
                 
                            {{ csrf_field() }}
                            
                                @php
                                $types = json_decode($expenses->type);
                                $prices = json_decode($expenses->price);
                                $i = 1;
                                
                               @endphp
  <input type="hidden" class="form-control" name="trip_id"
                                   value="{{ $booking->id}}">
                            <table class="courselist table table-bordered table-hover">
                                <thead>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('price') }}</th>
                                    <th>
                                        {{ __('#') }}
                                    </th>
                                </thead>

                                <tbody>
                                   @foreach ($types as $key => $p)
                                    <tr>
                                        <td>
                                            <input type="text" class="course_name form-control" placeholder="Enter Type " value="{{$p}}"
                                                required name="type[]">

                                        </td>
                                        <td>
                                            <div class="input-group">

                                                <input type="number" min="1" class="form-control" placeholder="50" value="{{ $prices[$key]}}"
                                                    required name="price[]">
                                                
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="addnew btn btn-primary btn-sm">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="removeBtn btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";

            $('#married_status').change(function() {

                if ($(this).val() == 'Married') {
                    $('#doaboxxx').show();
                } else {
                    $('#doaboxxx').hide();
                }
            });

            $(function() {
                $("#dob,#doa").datepicker({
                    changeYear: true,
                    yearRange: "-100:+0",
                    dateFormat: 'yy/mm/dd',
                });
            });
            $(function() {
                $('#country_id').change(function() {
                    var up = $('#upload_id').empty();
                    var cat_id = $(this).val();

                    if (cat_id) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "GET",
                            url: @json(url('country/dropdown')),
                            data: {
                                catId: cat_id
                            },
                            success: function(data) {
                                console.log(data);
                                up.append('<option value="0">Please Choose</option>');
                                $.each(data, function(id, title) {
                                    up.append($('<option>', {
                                        value: id,
                                        text: title
                                    }));
                                });
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                console.log(XMLHttpRequest);
                            }
                        });
                    }
                });
            });

            $(function() {

                $('#upload_id').change(function() {
                    var up = $('#grand').empty();
                    var cat_id = $(this).val();
                    if (cat_id) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "GET",
                            url: @json(url('country/gcity')),
                            data: {
                                catId: cat_id
                            },
                            success: function(data) {
                                console.log(data);
                                up.append('<option value="0">Please Choose</option>');
                                $.each(data, function(id, title) {
                                    up.append($('<option>', {
                                        value: id,
                                        text: title
                                    }));
                                });
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                console.log(XMLHttpRequest);
                            }
                        });
                    }
                });
            });
        })(jQuery);


        $(".courselist").on('click', 'button.addnew', function() {

            var n = $(this).closest('tr');
            addNewRow(n);


            function addNewRow(n) {

                // e.preventDefault();

                var $tr = n;
                var allTrs = $tr.closest('table').find('tr');
                var lastTr = allTrs[allTrs.length - 1];
                var $clone = $(lastTr).clone();
                $clone.find('td').each(function() {
                    var el = $(this).find(':first-child');
                    var id = el.attr('id') || null;
                    if (id) {

                        var i = id.substr(id.length - 1);
                        var prefix = id.substr(0, (id.length - 1));
                        el.attr('id', prefix + (+i + 1));
                        el.attr('name', prefix + (+i + 1));
                    }
                });

                $clone.find('input').val('');

                $tr.closest('table').append($clone);

                $('input.course_name').last().focus();

                enableAutoComplete($("input.course_name:last"));
            }

        });

        $('.courselist').on('click', '.removeBtn', function() {

            var d = $(this);
            removeRow(d);

        });

        function removeRow(d) {
            var rowCount = $('.courselist tr').length;
            if (rowCount !== 2) {
                d.closest('tr').remove();
            } else {
                console.log('Atleast one sell is required');
            }
        }
    </script>

@endsection
