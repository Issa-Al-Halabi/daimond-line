<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">

        @can('Customer edit')
            <a class="dropdown-item" href="{{ url('admin/categories/' . $row->id . '/edit') }}"><span aria-hidden="true"
                    class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
        @endcan
        @can('Customer delete')
            <a class="dropdown-item" data-id="{{ $row->id }}" data-toggle="modal" data-target="#myModal"><span
                    class="fa-light fa-eye" aria-hidden="true" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
        @endcan
    </div>
</div>
{!! Form::open([
    'url' => 'admin/categories/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]) !!}
{!! Form::hidden('id', $row->id) !!}
{!! Form::close() !!}
