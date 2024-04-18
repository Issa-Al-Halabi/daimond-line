<div role="tabpanel">
    {!! Form::open(['route' => ['car_option.update', $type->id], 'files' => true, 'method' => 'PATCH']) !!}
    {!! Form::hidden('id', $type->id) !!}


    <div class="tab-content">
        <!-- tab1-->

        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
               <div class="form-group">
                    {!! Form::label('name', __('fleet.name'), ['class' => 'form-label']) !!}
                    {!! Form::text('name', $type->name, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('price', __('fleet.price'), ['class' => 'form-label']) !!}
                    {!! Form::text('price', $type->price, ['class' => 'form-control']) !!}
                </div>

            </table>
        </div>

        <div style="margin-top: 5px">
            <h5>@lang('fleet.isenable')</h5>
        </div>
        <div class="col-md-3" style="margin-top: 5px">
            <label class="switch">
                <input type="checkbox" name="isenable" value="1"  @if ($type->is_enable == '1') checked @endif>
                <span class="slider round"></span>
            </label>
        </div>
    </div>
    <div class="col-md-12">
        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>
