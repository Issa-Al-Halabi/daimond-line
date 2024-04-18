<div role="tabpanel">
   
   {!! Form::open(['route' => ['bookings.update', $booking->id], 'files' => true, 'method' => 'PATCH']) !!}
    {!! Form::hidden('id', $booking->id) !!}


    <div class="tab-content">
        <!-- tab1-->

        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
               <div class="form-group">
                                {!! Form::label('driver_id', __('fleet.assign_driver'), ['class' => 'form-label']) !!}
                                <select id="role_id" name="driver_id" class="form-control" required>
                                    <option value="">@lang('fleet.selectDriver')</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->first_name}} {{ $driver->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>

            </table>
        </div>

        <!--<div style="margin-top: 5px">-->
        <!--    <h5>@lang('fleet.isenable')</h5>-->
        <!--</div>-->
        <!--<div class="col-md-3" style="margin-top: 5px">-->
        <!--    <label class="switch">-->
        <!--        <input type="checkbox" name="isenable" value="1">-->
        <!--        <span class="slider round"></span>-->
        <!--    </label>-->
        <!--</div>-->
    </div>
    <div class="col-md-12">
        {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>
