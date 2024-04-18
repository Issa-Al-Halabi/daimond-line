
<?php $__env->startSection('breadcrumb'); ?>
    
    <li class="breadcrumb-item active"> <?php echo app('translator')->get('fleet.edit_driver'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">

    <style type="text/css">
        /* .select2-selection:not(.select2-selection--multiple) {
                                                        height: 38px !important;
                                                    } */

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo app('translator')->get('fleet.edit_driver'); ?>
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

                    <?php echo Form::open(['route' => ['customers.update', $data->id], 'files' => true, 'method' => 'PATCH']); ?>

                    <?php echo Form::hidden('id', $data->id); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('first_name', __('fleet.first_name'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('first_name', $data['first_name'], ['class' => 'form-control']); ?>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('last_name', __('fleet.last_name'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('last_name', $data['last_name'], ['class' => 'form-control']); ?>

                            </div>
                        </div>
                        
                       

<div class="col-md-6">
                        <div class="form-group">
                                <?php echo Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']); ?>

                            

                                   <div class="input-group-prepend">
                                        <?php echo Form::text('phone_code',  '+963', [
                                            'class' => 'form-control,
                                                                                                                                                                                                           code',
        'disabled'=>'disabled',
                                            'style' => 'width:40px',
                                        ]); ?>

                                         <?php echo Form::text('phone', $data['phone'], ['class' => 'form-control', 'required']); ?>

                                    </div> 
                                   
                              
                            </div>
                            </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::email('email', $data['email'], ['class' => 'form-control', 'required']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('password', __('fleet.password'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        
                                    </div>
                                  <?php echo Form::password('password', ['class' => 'form-control', 'required', 'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}', 'title' => 'Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, and one digit.']); ?>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('role_id', __('fleet.role'), ['class' => 'form-label']); ?>

                                <select id="role_id" name="role_id" class="form-control" required>
                                    <option value=""><?php echo app('translator')->get('fleet.role'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($role->id); ?>"<?php if($data->roles->first()->id == $role->id): ?> selected <?php endif; ?>>
                                            <?php echo e($role['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"></span>
                                    </div>
                                    <?php echo Form::date('date_of_birth', $data['date_of_birth'], ['class' => 'form-control']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group mt-3">
                                <?php echo Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($data['profile_image'] != null ? asset('uploads/' . $data['profile_image']) : asset('assets/images/no-user.jpg')); ?>>

                                <?php echo Form::file('profile_image', null, ['class' => 'form-control']); ?>

                            </div>
                        </div>

                        

                    </div>
                    

                    
                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-success']); ?>

                    </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/customers/edit.blade.php ENDPATH**/ ?>