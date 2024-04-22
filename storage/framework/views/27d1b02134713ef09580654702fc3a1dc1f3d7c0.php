<div role="tabpanel">
    <?php echo Form::open(['route' => ['wallet.update', $wallet->id], 'files' => true, 'method' => 'post']); ?>

    <?php echo Form::hidden('id', $wallet->id); ?>



    <div class="tab-content">
        <!-- tab1-->

        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <div class="form-group">
                    <?php echo Form::label('new_amount', __('fleet.new_amount'), ['class' => 'form-label']); ?>

                    <?php echo Form::text('new_amount', $wallet->new_amount, ['class' => 'form-control', 'required']); ?>

                </div>

            </table>
        </div>



    </div>
    <div class="col-md-12">
        <?php echo Form::submit(__('fleet.add'), ['class' => 'btn btn-success']); ?>

    </div>
    <?php echo Form::close(); ?>

</div>
<?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/wallet/view_event.blade.php ENDPATH**/ ?>