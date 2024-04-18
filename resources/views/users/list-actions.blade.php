<div class="btn-group" style="background:#075296;">

    <button type="button" class="btn  dropdown-toggle"style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">

        {{-- <a class="dropdown-item" class="mybtn changepass" data-id="{{ $row->id }}" data-toggle="modal"
            data-target="#changepass" title="@lang('fleet.change_password')"><i class="fa fa-key" aria-hidden="true"
                style="color:#269abc;"></i> @lang('fleet.change_password')</a> --}}

        @can('Users edit')
            <a class="dropdown-item" href="{{ url('admin/users/' . $row->id . '/edit') }}"> <span aria-hidden="true"
                    class="fa fa-edit" style="color: #075296;"></span> @lang('fleet.edit')</a>
        @endcan
        {{-- lena --}}
        <a class="dropdown-item" href="{{ url('admin/users/' . $row->id) }}"> <span aria-hidden="true" class="fa fa-eye"
                style="color: #075296;"></span> @lang('fleet.show')</a>
        @can('Users delete')
            <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal"><span
                    aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
        @endcan

    </div>
</div>
{!! Form::open([
    'url' => 'admin/users/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]) !!}

{!! Form::hidden('id', $row->id) !!}

{!! Form::close() !!}
