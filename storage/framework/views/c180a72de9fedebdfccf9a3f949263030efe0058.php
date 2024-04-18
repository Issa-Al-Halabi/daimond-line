
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('customers.index')); ?>"><?php echo app('translator')->get('fleet.customers'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.addDriver'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.addDriver'); ?>
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

                    <?php echo Form::open(['route' => 'customers.store', 'files' => true, 'method' => 'post']); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('first_name', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('last_name', null, ['class' => 'form-control', 'required']); ?>

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

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('email', __('fleet.email'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <?php echo Form::email('email', null, ['class' => 'form-control', 'required']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('password', __('fleet.password'), ['class' => 'form-label']); ?>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
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
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

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
                        <div class="col-md-6  mt-3">
                            <div class="form-group mt-3">
                                <?php echo Form::label('profile_image', __('fleet.profile_photo'), ['class' => 'form-label']); ?>


                                <?php echo Form::file('profile_image', null, ['class' => 'form-control']); ?>

                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <?php echo Form::label('is_active', __('fleet.is_active'), ['class' => 'form-label']); ?>

                            <div class="form-group ">
                                <label class="switch">
                                    <input type="checkbox" name="is_active" value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        
                        



                    </div>
                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.addDriver'), ['class' => 'btn btn-success']); ?>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/customers/create.blade.php ENDPATH**/ ?>