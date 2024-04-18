<div role="tabpanel">
    {!! Form::open(['route' => ['update_tourfare', $fare->id], 'files' => true, 'method' => 'PATCH']) !!}
    {!! Form::hidden('id', $fare->id) !!}
    <div class="tab-content">
        <!-- tab1-->
        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">

                <tr>
                    <td>
                        <div class="form-group">
                            {!! Form::label('price', __('fleet.price'), ['class' => 'form-label']) !!}
                            {!! Form::text('price', $fare->price, ['class' => 'form-control', 'required']) !!}
                        </div>
                        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
                    </td>
                </tr>
            </table>
        </div>

    </div>
    {!! Form::close() !!}
</div>
