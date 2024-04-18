<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <h3 class="card-title"> @lang('fleet.todayExpense') : <strong><span id="total_today">{{ $currency . ' ' . $total }} </span> </strong> </h3>
                    </div>
                    <div class="col-md-8 pull-right">
                        {!! Form::open(['url' => 'admin/expense_records', 'class' => 'form-inline']) !!}

                        <div class="form-group">
                            {!! Form::label('date1', 'From', ['class' => 'control-label']) !!}
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                {!! Form::text('date1', $date1, [
                                'class' => 'form-control',
                                'placeholder' => __('fleet.start_date'),
                                'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group" style="margin-right: 10px">
                            {!! Form::label('date2', 'To') !!}
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                {!! Form::text('date2', $date2, ['class' => 'form-control', 'placeholder' => __('fleet.end_date'), 'required']) !!}
                            </div>
                        </div>
                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="card-body table-responsive" id="expenses">
                <table class="table" id="data_table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>
                                @if ($expenses->count() > 0)
                                <input type="checkbox" id="chk_all">
                                @endif
                            </th>
                            {{-- <th>@lang('fleet.make')</th> --}}
                            {{-- <th>@lang('fleet.model')</th> --}}
                            <th>@lang('fleet.driver_name')</th>
                            <th>@lang('fleet.expenses')</th>
                            <th>@lang('fleet.total')</th>
                            <th>@lang('fleet.delete')</th>
                            {{-- <th>@lang('fleet.licensePlate')</th>
                            <th>@lang('fleet.expenseType')</th>
                            <th>@lang('fleet.vendor')</th>
                            <th>@lang('fleet.date')</th>
                            <th>@lang('fleet.amount')</th>
                            <th>@lang('fleet.note')</th>
                            <th>@lang('fleet.delete')</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // use App\Model\Expense;
                        $data1['expenses'] = App\Model\Expense::select('expense.*', 'users.first_name', 'users.last_name')
                            ->join('users', 'users.id', 'expense.driver_id')
                            ->join('expense_cat', 'expense.expense_type', 'expense_cat.id')
                            ->groupby('users.id')
                            ->get();

                        ?>
                        @foreach ($data1 as $row)
                        @foreach ($row as $ex)
                        <?php

                        $v = explode(',', $ex->expense_type);
                        $exp = App\Model\ExpCats::select('name', 'cost')
                            ->wherein('id', $v)
                            ->get();
                        $expenese = App\Model\ExpCats::select('cost')
                            ->wherein('id', $v)
                            ->sum('cost');
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $ex->id }}" class="checkbox" id="chk{{ $ex->id }}" onclick='checkcheckbox();'>
                            </td>
                            {{-- <td>{{ $ex->vehicle->maker->make }}</td> --}}
                            <td>{{ $ex->first_name }}{{ $ex->last_name }}</td>

                            <td>
                                @foreach ($exp as $a)
                                {{ $a->name }}:{{ $a->cost }}Sy<br>
                                @endforeach
                            </td>
                            <td> {{ $expenese }}.SY</td>
                            {{-- <td>{{ $expeneses }}</td> --}}
                            {{-- <td>
                                        @if ($row->type == 's')
                                            {{ $row->service->description }}
                            @else
                            {{ $row->category->name }}
                            @endif
                            </td> --}}
                            {{-- <td>
                                        @if ($row->vendor_id != null)
                                            {{ $row->vendor->name }}
                            @endif
                            </td> --}}
                            {{-- <td>{{ date($date_format_setting, strtotime($row->date)) }}</td> --}}
                            {{-- <td>
                                        {{ $currency }}
                            {{ $row->amount }}</td>
                            <td>{{ $row->comment }}</td> --}}
                            <td>
                                {{-- <button type="button" class="btn btn-danger delete" id="btn_delete"
                                            data-id="{{ $ex->id }}" title="@lang('fleet.delete')">
                                <span class="fa fa-edit" aria-hidden="true"></span>
                                </button> --}}
                                {!! Form::open([
                                'url' => 'admin/expense/' . $ex->id,
                                'method' => 'DELETE',
                                'class' => 'form-horizontal del_form',
                                'id' => 'form_' . $ex->id,
                                ]) !!}
                                {!! Form::hidden('id', $ex->id) !!}
                                @can('Transactions delete')
                                <button type="button" class="btn btn-danger delete" id="btn_delete" data-id="{{ $ex->id }}" title="@lang('fleet.delete')">
                                    <span class="fa fa-times" aria-hidden="true"></span>
                                </button>
                                @endcan
                                {!! Form::close() !!}
                                {{-- <a class="dropdown-item"
                                            href="{{ url('admin/vehicle-types/' . $ex->id . '/edit') }}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span>
                                @lang('fleet.edit')</a> --}}
                            </td>

                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            {{-- <th>
                                @if ($today->count() > 0)
                                    @can('Transactions delete')
                                        <button class="btn btn-danger" id="bulk_delete" data-toggle="modal"
                                            data-target="#bulkModal" disabled title="@lang('fleet.delete')"><i
                                                class="fa fa-trash"></i></button>
                                    @endcan
                                @endif
                            </th> --}}
                            {{-- <th>@lang('fleet.driver_name')</th>
                            <th>@lang('fleet.expenses')</th>
                            <th>@lang('fleet.total')</th> --}}
                            {{-- <th>@lang('fleet.make')</th>
                            <th>@lang('fleet.model')</th>
                            <th>@lang('fleet.licensePlate')</th>
                            <th>@lang('fleet.expenseType')</th>
                            <th>@lang('fleet.vendor')</th>
                            <th>@lang('fleet.date')</th>
                            <th>@lang('fleet.amount')</th>
                            <th>@lang('fleet.note')</th>
                            <th>@lang('fleet.delete')</th> --}}
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>