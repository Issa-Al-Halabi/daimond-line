<div class="btn-group" style="background:#075296;">
    <button type="button" class="btn dropdown-toggle" style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">
        @can('VehicleType edit')
            <a class="dropdown-item" href="{{ url('admin/vehicle-types/' . $row->id . '/edit') }}"> <span aria-hidden="true"
                    class="fa fa-edit" style="color: #075296;"></span> @lang('fleet.edit')</a>
        @endcan
        {!! Form::hidden('id', $row->id) !!}

        @can('VehicleType delete')
            <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal"> <span
                    aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
        @endcan
    </div>
</div>
{!! Form::open([
    'url' => 'admin/vehicle-types/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]) !!}

{!! Form::hidden('id', $row->id) !!}

{!! Form::close() !!}
