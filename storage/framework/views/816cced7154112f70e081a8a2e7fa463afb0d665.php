<div role="tabpanel">
   
   <?php echo Form::open(['route' => ['bookings.update', $booking->id], 'files' => true, 'method' => 'PATCH']); ?>

    <?php echo Form::hidden('id', $booking->id); ?>



    <div class="tab-content">
        <!-- tab1-->

        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
               <div class="form-group">
                                <?php echo Form::label('driver_id', __('fleet.assign_driver'), ['class' => 'form-label']); ?>

                                <select id="role_id" name="driver_id" class="form-control" required>
                                    <option value=""><?php echo app('translator')->get('fleet.selectDriver'); ?></option>
                                    <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($driver->id); ?>"><?php echo e($driver->first_name); ?> <?php echo e($driver->last_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

            </table>
        </div>

        <!--<div style="margin-top: 5px">-->
        <!--    <h5><?php echo app('translator')->get('fleet.isenable'); ?></h5>-->
        <!--</div>-->
        <!--<div class="col-md-3" style="margin-top: 5px">-->
        <!--    <label class="switch">-->
        <!--        <input type="checkbox" name="isenable" value="1">-->
        <!--        <span class="slider round"></span>-->
        <!--    </label>-->
        <!--</div>-->
    </div>
    <div class="col-md-12">
        <?php echo Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']); ?>

    </div>
    <?php echo Form::close(); ?>

</div>
<?php /**PATH /home/diamond/public_html/framework/resources/views/bookings/view_event.blade.php ENDPATH**/ ?>