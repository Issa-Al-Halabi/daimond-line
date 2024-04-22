<?php $__env->startSection('extra_css'); ?>
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
    <style type="text/css">
        .select2-selection:not(.select2-selection--multiple) {
            height: 38px !important;
        }

        .input-group-append,
        .input-group-prepend {
            display: flex;
            /* width: calc(100% / 2); */
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('drivers.index')); ?>"><?php echo app('translator')->get('fleet.drivers'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.addDriver'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.addDriver'); ?></h3>
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

                    <?php echo Form::open(['route' => 'drivers.store', 'files' => true, 'method' => 'post', 'id' => 'driver-create-form']); ?>

                    <?php echo Form::hidden('is_active', 0); ?>

                    <?php echo Form::hidden('is_available', 0); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label required', 'autofocus']); ?>

                                <?php echo Form::text('first_name', null, ['class' => 'form-control', 'required', 'autofocus']); ?>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label required']); ?>

                                <?php echo Form::text('last_name', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label required']); ?>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::email('email', null, ['class' => 'form-control', 'required']); ?>

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

                                         <?php echo Form::text('phone', null, ['class' => 'form-control', 'required']); ?>

                                    </div> 
                                   
                              
                            </div>
                            </div>
                        
                    </div>
                    <div class="row">
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('place_of_birth', __('fleet.place_of_birth'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('place_of_birth', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::date('date_of_birth', null, ['class' => 'form-control', 'required']); ?>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        
                        
                    </div>
                    <div class="row">
                        

                        

                        

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('start_date', __('fleet.join_date'), ['class' => 'form-label']); ?>

                                <div class="input-group date">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                    <?php echo Form::date('start_date', null, ['class' => 'form-control', 'required']); ?>

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
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <?php echo Form::label('is_active', __('fleet.is_active'), ['class' => 'form-label']); ?>

                            <div class="form-group ">
                                <label class="switch">
                                    <input type="checkbox" name="is_active" value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <?php echo Form::label('role_id', __('fleet.role'), ['class' => 'form-label']); ?>

                                <select id="role_id" name="role_id" class="form-control" required>
                                    <option value=""><?php echo app('translator')->get('fleet.role'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        

                    </div>
                    
                    <div class="row">
                        <div class="col-md-6  mt-3">
                            <div class="form-group">
                                <?php echo Form::label('personal_identity', __('fleet.personal_identity'), ['class' => 'form-label']); ?>

                                <?php echo Form::file('personal_identity', null, ['class' => 'form-control', 'required']); ?>

                            </div>


                            

                            
                            
                            
                        </div>
                        <div class="col-md-6  mt-3">
                            <div class="form-group">
                                <?php echo Form::label('driving_certificate', __('fleet.driving_certificate'), ['class' => 'form-label']); ?>

                                <?php echo Form::file('driving_certificate', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        

                        
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6  mt-3">
                            <div class="form-group">
                                <?php echo Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']); ?>


                                <?php echo Form::file('profile_image', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6  mt-3">
                            <div class="form-group">
                                <?php echo Form::label('car_image', __('fleet.car_image'), ['class' => 'form-label']); ?>

                                <?php echo Form::file('car_image', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6  mt-3">
                            <div class="form-group">
                                <?php echo Form::label('car_insurance', __('fleet.car_insurance'), ['class' => 'form-label']); ?>

                                <?php echo Form::file('car_insurance', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-6  mt-3">
                            <div class="form-group">
                                <?php echo Form::label('car_mechanic', __('fleet.car_mechanic'), ['class' => 'form-label']); ?>

                                <?php echo Form::file('car_mechanic', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.saveDriver'), ['class' => 'btn btn-success']); ?>

                    </div>
                    <?php echo Form::close(); ?>


                </div>

            </div>
        </div>
    </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // $('#driver_commision_type').on('change', function() {
            //     var val = $(this).val();
            //     if (val == '') {
            //         $('#driver_commision_container').hide();
            //     } else {
            //         if (val == 'amount') {
            //             $('#driver_commision').attr('placeholder', "<?php echo app('translator')->get('fleet.enter_amount'); ?>");
            //         } else {
            //             $('#driver_commision').attr('placeholder', "<?php echo app('translator')->get('fleet.enter_percent'); ?>")
            //         }
            //         $('#driver_commision_container').show();
            //     }
            // });

            $('.code').select2();
            $('#vehicle_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.selectVehicle'); ?>"
            });

            $("#first_name").focus();
            // $('#end_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#exp_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#issue_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });
            // $('#start_date').datepicker({
            //     autoclose: true,
            //     format: 'yyyy-mm-dd'
            // });

            $("#driver-create-form").validate({
                // in 'rules' user have to specify all the constraints for respective fields
                rules: {
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                // in 'messages' user have to specify message as per rules
                messages: {
                    vehicle_id: "Assign Vehicle field is required.",
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('select2-hidden-accessible') && element.next(
                            '.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.prop('type') === 'radio' && element.parent('.radio-inline')
                        .length) {
                        error.insertAfter(element.parent().parent());
                    } else if (element.prop('type') === 'checkbox' || element.prop('type') ===
                        'radio') {
                        error.appendTo(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    if ($(element).hasClass('select2-hidden-accessible') && $(element).next(
                            '.select2-container').length) {

                        $(element).next('.select2-container').find('.select2-selection').addClass(
                            'border-danger');
                    } else {

                        $(element).addClass('is-invalid');
                    }
                    // return false;
                },
                unhighlight: function(element, errorClass, validClass) {
                    if ($(element).hasClass('select2-hidden-accessible') && $(element).next(
                            '.select2-container').length) {
                        console.log(element, errorClass, validClass)

                        $(element).next('.select2-container').find('.select2-selection').removeClass(
                            'border-danger');
                    } else {
                        $(element).removeClass('is-invalid');
                    }
                }
            });

            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/drivers/create.blade.php ENDPATH**/ ?>