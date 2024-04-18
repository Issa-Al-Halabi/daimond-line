
<?php $__env->startSection('extra_css'); ?>
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">

    <style type="text/css">
        .select2-selection:not(.select2-selection--multiple) {
            height: 38px !important;
        }

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('drivers.index')); ?>"><?php echo app('translator')->get('fleet.drivers'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.edit_driver'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.edit_driver'); ?></h3>
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

                    <?php echo Form::open(['route' => ['drivers.update', $driver->id], 'files' => true, 'method' => 'PATCH']); ?>

                    <?php echo Form::hidden('id', $driver->id); ?>

                    <?php echo Form::hidden('edit', '1'); ?>

                    <?php echo Form::hidden('detail_id', $driver->getMeta('id')); ?>

                    <?php echo Form::hidden('user_id', Auth::user()->id); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('first_name', $driver->first_name, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label required']); ?>

                                <?php echo Form::text('last_name', $driver->last_name, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label required']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::email('email', $driver->email, ['class' => 'form-control', 'required']); ?>

                                </div>
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

                                         <?php echo Form::text('phone', $driver->phone, ['class' => 'form-control', 'required']); ?>

                                    </div> 
                                   
                              
                           
                            </div>
                            </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('place_of_birth', __('fleet.place_of_birth'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('place_of_birth', $driver->place_of_birth, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::date('date_of_birth', $driver->date_of_birth, ['class' => 'form-control', 'required']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('start_date', __('fleet.join_date'), ['class' => 'form-label']); ?>

                                <div class="input-group date">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                    <?php echo Form::text('start_date', $driver->getMeta('start_date'), ['class' => 'form-control']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('password', __('fleet.password'), ['class' => 'form-label']); ?>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                      
                                    </div>
    <?php echo Form::password('password', ['class' => 'form-control', 'required']); ?>                                
	                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('personal_identity', __('fleet.personal_identity'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($driver->personal_identity != null ? asset('uploads/' . $driver->personal_identity) : asset('assets/images/no-user.jpg')); ?>>
                                <?php echo Form::file('personal_identity', null, ['class' => 'form-control', 'required']); ?>


                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('driving_certificate', __('fleet.driving_certificate'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($driver->driving_certificate != null ? asset('uploads/' . $driver->driving_certificate) : asset('assets/images/no-user.jpg')); ?>>
                                <?php echo Form::file('driving_certificate', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('car_mechanic', __('fleet.car_mechanic'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($driver->car_mechanic != null ? asset('uploads/' . $driver->car_mechanic) : asset('assets/images/no-user.jpg')); ?>>
                                <?php echo Form::file('car_mechanic', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('car_insurance', __('fleet.car_insurance'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($driver->car_insurance != null ? asset('uploads/' . $driver->car_insurance) : asset('assets/images/no-user.jpg')); ?>>
                                <?php echo Form::file('car_insurance', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('car_image', __('fleet.car_image'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($driver->car_image != null ? asset('uploads/' . $driver->car_image) : asset('assets/images/no-user.jpg')); ?>>

                                <?php echo Form::file('car_image', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <?php echo Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']); ?>

                                <img class='img'
                                    width='60px'src=<?php echo e($driver->profile_image != null ? asset('uploads/' . $driver->profile_image) : asset('assets/images/no-user.jpg')); ?>>

                                <?php echo Form::file('profile_image', null, ['class' => 'form-control']); ?>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-success']); ?>

                            
                        </div>

                    </div>
                    


                </div>
                <div class="row">
                    
                    


                    
                </div>
                <div class="row">
                    

                    

                    
                </div>
                <div class="row">

                    
                    
                </div>
                <div class="row">
                    
                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        
                        
                        
                        

                    </div>
                    

                </div>

                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
    <!-- bootstrap datepicker -->
    <script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#driver_commision_type').on('change', function() {
                var val = $(this).val();
                if (val == '') {
                    $('#driver_commision_container').hide();
                } else {
                    if (val == 'amount') {
                        $('#driver_commision').attr('placeholder', "<?php echo app('translator')->get('fleet.enter_amount'); ?>");
                    } else {
                        $('#driver_commision').attr('placeholder', "<?php echo app('translator')->get('fleet.enter_percent'); ?>")
                    }
                    $('#driver_commision_container').show();
                }
            });
            $('#driver_commision_type').trigger('change');
            $('.code').select2();
            $('#vehicle_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.selectVehicle'); ?>"
            });
            $('#end_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#exp_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#issue_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#start_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/diamond/public_html/framework/resources/views/drivers/edit.blade.php ENDPATH**/ ?>