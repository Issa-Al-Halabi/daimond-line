
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('coupons')); ?>"><?php echo app('translator')->get('fleet.customers'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.add_new_point'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.add_new_coupon'); ?>
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
                    <?php echo Form::open(['route' => 'coupons.store', 'files' => true, 'method' => 'post']); ?>

                    <div class="row">
                        <div class="col-md-6">
                           
                            
                            <div class="form-group">
                                <?php echo Form::label('title', __('fleet.title'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('title', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('code', __('fleet.code'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('code', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('discount', __('fleet.discount'), ['class' => 'form-label']); ?>

                                <?php echo Form::number('discount', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('limit', __('fleet.limit'), ['class' => 'form-label']); ?>

                                <?php echo Form::number('limit', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('start_date', __('fleet.start_date'), ['class' => 'form-label']); ?>

                                <?php echo Form::date('start_date', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('expire_date', __('fleet.expire_date'), ['class' => 'form-label']); ?>

                                <?php echo Form::date('expire_date', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.add_new_coupon'), ['class' => 'btn btn-success']); ?>

                    </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/diamond/public_html/framework/resources/views/coupons/create.blade.php ENDPATH**/ ?>