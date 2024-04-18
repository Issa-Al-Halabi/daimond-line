<div role="tabpanel">
    {!! Form::open(['route' => ['wallet.update', $wallet->id], 'files' => true, 'method' => 'post']) !!}
    {!! Form::hidden('id', $wallet->id) !!}


    <div class="tab-content">
        <!-- tab1-->

        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <div class="form-group">
                    {!! Form::label('new_amount', __('fleet.new_amount'), ['class' => 'form-label']) !!}
                    {!! Form::text('new_amount', $wallet->new_amount, ['class' => 'form-control', 'required']) !!}
                </div>

            </table>
        </div>



    </div>
    <div class="col-md-12">
        {!! Form::submit(__('fleet.add'), ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>
