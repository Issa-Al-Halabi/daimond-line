@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item active">@lang('fleet.reviews')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('fleet.reviews')
                    </h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table" id="data_table">
                        <thead class="thead-inverse">
                            <tr>
                                <th>@lang('fleet.id')</th>
                                <th>@lang('fleet.rider_name')</th>
                                <th>@lang('fleet.driver_name')</th>
                                <th>@lang('fleet.trip_date')</th>
                               <th>@lang('fleet.ratings')</th>
                                <th>@lang('fleet.rating_type')</th>
                                <th>@lang('fleet.review')</th>
                              <th>@lang('fleet.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $a = 1;
                            @endphp

                            @foreach ($reviews as $review)
                                @php
                                    $driver_name = App\Model\User::where('id', $review->driver_id)->first();
                                    $rider_name = App\Model\User::where('id', $review->user_id)->first();
                         
                                @endphp
                                <tr>
                                  	@if(isset($rider_name))
                                    <td>{{ $a++ }}</td>
                                       <td> {{ $rider_name->first_name }} {{ $rider_name->last_name }}</td>
                                    <td> {{ $driver_name->first_name }} {{ $driver_name->last_name }}</td>
                                  @endif
                               
                                  
                                    @if($review->category_id=='1'&& $review->request_type=="moment")
                                    <td>{{ $review->created_at }}</td>
                                    @elseif($review->category_id=='1'&& $review->request_type=="delayed")
                                      <td>{{ $review->date }}</td>
                                      @elseif($review->category_id=='2')
                                       <td>{{ $review->date }}</td>
                                   @endif
                                    <td>
                                        @php($flot = ltrim($review->ratings - floor($review->ratings), '0.'))
                                        @for ($i = 1; $i <= $review->ratings; $i++)
                                            <i class="fa fa-star" style='color: #f3da35'></i>
                                        @endfor
                                        @if ($flot > 0 && $review->ratings < 5)
                                            <i class="fa fa-star-half"></i>
                                        @endif
                                    </td>
                                  
                                   <td>{{ $review->type }}</td>
                                  @if(isset($review->review_text ))
                                    <td>{{ $review->review_text }}</td>
                                  @else
                                  <td>---</td>
                                  @endif
                                    <td>
                                     
                                        <div class="btn-group" style="background:#075296;">
                                            <button type="button" class="btn  dropdown-toggle" style="color:white;"
                                                data-toggle="dropdown">
                                                <span class="fa fa-gear"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu custom" role="menu">
                                                
                                                {!! Form::hidden('id', $review->id) !!}
                                                <a class="dropdown-item" data-id="{{ $review->id }}" data-toggle="modal"
                                                    data-target="#myModal66"><span aria-hidden="true" class="fa fa-trash"
                                                        style="color: #dd4b39"></span>
                                                    @lang('fleet.delete')</a>

                                            </div>
                                            {!! Form::open([
                                                'url' => 'admin/review/delete/'.$review->id,
                                                'method' => 'DELETE',
                                                'class' => 'form-horizontal del_form',
                                                'id' => 'form_' . $review->id,
                                            ]) !!}
                                            {!! Form::hidden('id', $review->id) !!}


                                            {!! Form::close() !!}
                                        </div>
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
    <div id="myModal66" class="modal fade" role="dialog">
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
     
              $("#del_btn").on("click", function() {
               var id = $(this).data("submit");
               $("#form_" + id).submit();
            });


            $('#myModal66').on('show.bs.modal', function(e) {
                var id = e.relatedTarget.dataset.id;
                $("#del_btn").attr("data-submit", id);
            });

 });

    </script>

@endsection