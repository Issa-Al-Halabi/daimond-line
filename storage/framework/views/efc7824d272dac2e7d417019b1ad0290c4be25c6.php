
<?php $__env->startSection('extra_css'); ?>
    <style type="text/css">
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            display: none;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .img {
            border-radius: 60px;
            height: 40px
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('users.index')); ?>"> <?php echo app('translator')->get('fleet.users'); ?> </a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.editUser'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title" style="color:#fbfbfb;"><?php echo app('translator')->get('fleet.editUser'); ?></h3>
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

                    

                    <?php echo Form::open(['route' => ['users.update', $user->id], 'files' => true, 'method' => 'PATCH']); ?>

                    <?php echo Form::hidden('id', $user->id); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('first_name', $user->first_name, ['class' => 'form-control']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('mother_name', __('fleet.mother_name'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('mother_name', $user->mother_name, ['class' => 'form-control']); ?>

                            </div>


                            <div class="form-group">
                                <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::email('email', $user->email, ['class' => 'form-control']); ?>

                                </div>
                            </div>
                            <!--<div class="form-group">-->
                            <!--    <?php echo Form::label('phone', __('fleet.phone'), ['class' => 'form-label']); ?>-->
                            <!--    <?php echo Form::text('phone', $user->phone, ['class' => 'form-control']); ?>-->
                            <!--</div>-->
                            <div class="form-group">
                                <?php echo Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']); ?>



                                <div class="input-group-prepend">
                                    <?php echo Form::text('phone_code', '+963', [
                                        'class' => 'form-control,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              code',
                                        'disabled' => 'disabled',
                                        'style' => 'width:40px',
                                    ]); ?>

                                    <?php echo Form::text('phone', $user->phone, ['class' => 'form-control']); ?>

                                </div>


                            </div>



                            <div class="form-group">
                                <?php echo Form::label('role_id', __('fleet.role'), ['class' => 'form-label']); ?>

                                <select id="role_id" name="role_id" class="form-control">
                                    <option value=""><?php echo app('translator')->get('fleet.role'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->name); ?>"
                                            <?php if($user->roles->first()->id == $role->id): ?> selected <?php endif; ?>><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('last_name', $user->last_name, ['class' => 'form-control']); ?>

                            </div>
                            <div class="form-group">
                                <?php echo Form::label('father_name', __('fleet.father_name'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('father_name', $user->father_name, ['class' => 'form-control']); ?>

                            </div>
                            

                            <div class="form-group">
                                <?php echo Form::label('password', __('fleet.password'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <?php echo Form::password('password', ['class' => 'form-control', 'id' => 'password']); ?>

                                    <div class="invalid-feedback" id="password-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('place_of_birth', __('fleet.place_of_birth'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('place_of_birth', $user->place_of_birth, ['class' => 'form-control']); ?>

                            </div>



                            <div class="form-group">
                                <?php echo Form::label('date_of_birth', __('fleet.date_of_birth'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::date('date_of_birth', $user->date_of_birth, ['class' => 'form-control']); ?>

                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group mt-3">
                                    <?php echo Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']); ?>

                                    <img class='img'
                                        width='60px'src=<?php echo e($user->profile_image != null ? asset('uploads/' . $user->profile_image) : asset('assets/images/no-user.jpg')); ?>>

                                    <?php echo Form::file('profile_image', null, ['class' => 'form-control']); ?>

                                </div>
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
        $(document).ready(function() {
            $('#group_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.selectGroup'); ?>"
            });
            $('#role_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.role'); ?>"
            });
            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    </script>



    
    <script>
        var passwordInput = document.getElementById('password');
        var passwordFeedback = document.getElementById('password-feedback');

        passwordInput.addEventListener('input', function(event) {
            var password = passwordInput.value;
            var passwordStrength = getPasswordStrength(password);

            if (passwordStrength < 4) {
                passwordInput.classList.add('is-invalid');
                passwordFeedback.textContent =
                    'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordFeedback.textContent = '';
            }
        });

        function getPasswordStrength(password) {
            var strength = 0;
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (password.match(regex)) {
                strength += 1;
            }
            if (password.length >= 8) {
                strength += 1;
            }
            if (password.length >= 12) {
                strength += 1;
            }
            if (password.match(/[~`!#$%^&*()-_=+[\]{}\\|;:'",.<>/?]+/)) {
                strength += 1;
            }

            return strength;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/users/edit.blade.php ENDPATH**/ ?>