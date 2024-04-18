<div class="btn-group" style="background:#075296;">
    <button type="button" class="btn  dropdown-toggle" style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">
        @can('Vehicles edit')
            <a class="dropdown-item" href="{{ url('admin/vehicles/' . $row->id . '/edit') }}"> <span aria-hidden="true"
                    class="fa fa-edit" style="color: #075296;"></span> @lang('fleet.edit')</a>
        @endcan

        {{-- <a class="dropdown-item openBtn" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal2"
            id="openBtn">
            <span class="fa fa-eye" aria-hidden="true" style="color: #075296"></span> @lang('fleet.view_vehicle')
        </a> --}}
        {!! Form::hidden('id', $row->id) !!}
        @can('Vehicles delete')
            <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal"><span
                    aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
        @endcan
    </div>
</div>
{!! Form::open([
    'url' => 'admin/vehicles/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]) !!}

{!! Form::hidden('id', $row->id) !!}

{!! Form::close() !!}
