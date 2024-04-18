<div role="tabpanel">
    {!! Form::open(['route' => ['contact.update', $contact->id], 'files' => true, 'method' => 'post']) !!}
    {!! Form::hidden('id', $contact->id) !!}


    <div class="tab-content">
        <!-- tab1-->

        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <div class="form-group">
                    {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label']) !!}
                    {!! Form::text('phone', $contact->phone, ['class' => 'form-control', 'required']) !!}
                </div>

            </table>
        </div>
        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <div class="form-group">
                    {!! Form::label('email', __('fleet.email'), ['class' => 'form-label']) !!}
                    {!! Form::text('email', $contact->email, ['class' => 'form-control', 'required']) !!}
                </div>

            </table>
        </div>


    </div>
    <div class="col-md-12">
        {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>
