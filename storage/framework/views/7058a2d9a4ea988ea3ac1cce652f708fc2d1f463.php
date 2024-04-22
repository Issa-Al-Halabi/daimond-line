
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
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.add_fare'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.add_fare'); ?></h3>
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

                    <?php echo Form::open(['route' => 'farestore', 'files' => true, 'method' => 'post']); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('category_id', __('fleet.select_category'), ['class' => 'col-xs-5 control-label']); ?>

                                <div class="input-group mb-3">
                                    <?php echo Form::select('category_id', ['1' => 'Inside City', '2' => 'Oustide Damascus'], null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo Form::label('category_id', __('fleet.selectUser'), ['class' => 'col-xs-5 control-label']); ?>

                                <div class="input-group mb-3">
                                    <?php echo Form::select('user_type', ['Organizations' => ' Organizations', 'User' => ' User'], null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo Form::label('base_km', __('fleet.base_km'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('base_km', null, ['class' => 'form-control', 'required']); ?>

                            </div>

                          <div class="form-group">
                                <?php echo Form::label('cost', __('fleet.cost'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('cost', null, ['class' => 'form-control']); ?>

                            </div>
                          
                          
                          
                            

                            




                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('type_id', __('fleet.select_type'), ['class' => 'form-label']); ?>

                                <select id="type_id" name="type_id" class="form-control" required>
                                    <option value=""><?php echo app('translator')->get('fleet.role'); ?></option>
                                    <?php $__currentLoopData = $vehicle_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>"><?php echo e($type->vehicletype); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <?php echo Form::label('base_time', __('fleet.base_time'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('base_time', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                          
                          <div class="form-group">
                                <?php echo Form::label('limit_distance', __('fleet.Limit_distance'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('limit_distance', null, ['class' => 'form-control']); ?>

                            </div>

                            
                            

                        </div>
                    </div>

                    <div class="col-md-12">
                        <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success']); ?>

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
            $('#type_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.select_type'); ?>"
            });
            //Flat green color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/utilities/create.blade.php ENDPATH**/ ?>