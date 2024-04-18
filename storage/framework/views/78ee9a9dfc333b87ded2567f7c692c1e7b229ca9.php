
<?php ($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
    <style type="text/css">
        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }

        .img {
            border-radius: 60px;
            height: 40px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('customers.index')); ?>"><?php echo app('translator')->get('fleet.all_user'); ?></a></li>
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title w-100 d-flex justify-content-between align-items-center"> <span><?php echo app('translator')->get('fleet.user_details'); ?>:
                        </span>
                        
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><b><?php echo app('translator')->get('fleet.full_name'); ?></b>: <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></p>

                            <p><b><?php echo app('translator')->get('fleet.email'); ?></b>: <?php echo e($user->email); ?></p>
                           

                        </div>
                        <div class="col-md-6">
                            <p><b><?php echo app('translator')->get('fleet.phone'); ?></b>: <?php echo e($user->phone); ?></p>
                        
                            
                            <p><b><?php echo app('translator')->get('fleet.date_of_birth'); ?></b>: <?php echo e($user->date_of_birth); ?></p>
                            <p><b><?php echo app('translator')->get('fleet.place_of_birth'); ?></b>: <?php echo e($user->place_of_birth); ?></p>
                            <p><b><?php echo app('translator')->get('fleet.profile_photo'); ?></b>:<img class='img'
                                    width='60px'src=<?php echo e($user->profile_image != null ? asset('uploads/' . $user->profile_image) : asset('assets/images/no-user.jpg')); ?>>
                            </p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/diamond/public_html/framework/resources/views/users/show.blade.php ENDPATH**/ ?>