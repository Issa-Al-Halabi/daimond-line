<div class="btn-group" style="background:#075296;">
    <button type="button" class="btn dropdown-toggle" style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">
        {{-- <a class="dropdown-item" class="mybtn changepass" data-id="{{ $row->id }}" data-toggle="modal"
            data-target="#changepass" title="@lang('fleet.change_password')"><i class="fa fa-key" aria-hidden="true"
                style="color:#269abc;"></i> @lang('fleet.change_password')</a> --}}
        @can('Customer edit')
            <a class="dropdown-item" href="{{ url('admin/customers/' . $row->id . '/edit') }}"><span aria-hidden="true"
                    class="fa fa-edit" style="color: #075296;"></span> @lang('fleet.edit')</a>
        @endcan

        {{-- @can('Customer edit') --}}
        <a class="dropdown-item" href="{{ url('admin/customers/' . $row->id) }}"><span aria-hidden="true"
                class="fa fa-eye" style="color: #075296;"></span> @lang('fleet.show')</a>
        {{-- @endcan --}}


        @can('Customer delete')
            <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal"><span
                    class="fa-light fa-trash" aria-hidden="true" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
        @endcan
        @if ($row->is_active == 'active')
            <a class="dropdown-item" href="{{ url('admin/customers/disable/' . $row->id) }}" class="mybtn"
                data-toggle="tooltip" title="@lang('fleet.disable_driver')"><span class="fa fa-times" aria-hidden="true"
                    style="color: #5cb85c;"></span> @lang('fleet.disable_driver')</a>
        @else
            <a class="dropdown-item" href="{{ url('admin/customers/enable/' . $row->id) }}" class="mybtn"
                data-toggle="tooltip" title="@lang('fleet.enable_driver')"><span class="fa fa-check" aria-hidden="true"
                    style="color: #5cb85c;"></span> @lang('fleet.enable_driver')</a>
        @endif
    </div>
</div>
{!! Form::open([
    'url' => 'admin/customers/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]) !!}
{!! Form::hidden('id', $row->id) !!}
{!! Form::close() !!}
