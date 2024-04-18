
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('points')); ?>"><?php echo app('translator')->get('fleet.customers'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.add_new_point'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.add_new_point'); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php echo Form::open(['route' => 'points.store', 'files' => true, 'method' => 'post']); ?>

                    <div class="row">
                        <div class="col-md-6">
                           
                            <div class="form-group">
                                <?php echo Form::label('trip_type', __('fleet.trip_type'), ['class' => 'form-label']); ?>

                                <select name="trip_type" id="trip_type" class="form-control" required>
                                    <option selected value="Out city trips">Out city trips</option>
                                    <option value="instant trips">instant trips</option>
                                    <option value="delayed trips">delayed trips</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <?php echo Form::label('point', __('fleet.point'), ['class' => 'form-label']); ?>

                                <?php echo Form::number('point', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('qty', __('fleet.qty'), ['class' => 'form-label']); ?>

                                <?php echo Form::number('qty', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.add_new_point'), ['class' => 'btn btn-success']); ?>

                    </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/diamond/public_html/framework/resources/views/points/create.blade.php ENDPATH**/ ?>