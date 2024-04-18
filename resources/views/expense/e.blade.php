<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">@lang('fleet.addRecord')
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {!! Form::open(['route' => 'expense.store', 'method' => 'post', 'class' => 'form-inline', 'id' => 'exp_form']) !!}

                    {{-- vehicle --}}
                    {{-- <div class="col-md-4 col-sm-6">
                        <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" style="width: 100%"
                            required>
                            <option value="">@lang('fleet.selectVehicle')</option>
                            @foreach ($vehicels as $vehicle)
                                <option value="{{ $vehicle->id }}">
                                    {{ $vehicle->maker->make }}-{{ $vehicle->vehiclemodel->model }}-{{ $vehicle->license_plate }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                    {{-- driver --}}
                    <div class="col-md-4 col-sm-6">
                        <select id="driver_id" name="driver_id" class="form-control vehicles" style="width: 100%"
                            required>
                            <option value="">@lang('fleet.assign_drivers')</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4" style="margin-top: 5px;">
                        <select id="expense_type" name="expense_type[]" class="form-control vehicles" required
                            style="width: 100%" multiple="true">
                            <option value="">@lang('fleet.expenseType')</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                            <optgroup label="@lang('fleet.serviceItems')">
                                @foreach ($service_items as $item)
                                    <option value="s_{{ $item->id }}">{{ $item->description }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    {{-- <div class="col-md-4" style="margin-top: 5px">
                        <select id="vendor_id" name="vendor_id" class="form-control vendor" style="width: 100%">
                            <option value="">@lang('fleet.select_vendor')</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    {{-- <div class="col-md-4" style="margin-top: 5px;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ $currency }}</span>
                            </div>
                            <input required="required" name="revenue" type="number" id="revenue"
                                class="form-control">
                        </div>
                    </div> --}}
                    <div class="col-md-4" style="margin-top: 10px;">
                        <div class="input-group">
                            <input name="comment" type="text" id="comment" class="form-control"
                                placeholder=" @lang('fleet.note')" style="width: 250px">
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top: 10px;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input name="date" type="text" id="date" value="{{ date('Y-m-d') }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1" style="margin-top: 10px;">
                        @can('Transactions add')
                            <button type="submit" class="btn btn-success">@lang('fleet.add')</button>
                        @endcan
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
