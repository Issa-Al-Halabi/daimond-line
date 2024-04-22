
<?php ($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y'); ?>
<?php ($currency = Hyvikk::get('currency')); ?>
<?php $__env->startSection('extra_css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
    <style type="text/css">
        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.expense'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.addRecord'); ?>
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <?php if(count($errors) > 0): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php echo Form::open([
                            'route' => ['maintenance.update', $maint->id],
                        
                            'method' => 'PATCH',
                            'class' => 'form-inline',
                            'id' => 'exp_form',
                        ]); ?>

                        <?php echo Form::hidden('id', $maint->id); ?>


                        

                        <div class="col-md-4 ">
                            <select id="vechicle_id" name="vehicle_id" class="form-control vehicles" style="width: 100%"
                                required>
                                <option value=""><?php echo app('translator')->get('fleet.selectVehicle'); ?></option>
                                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($vehicle->id); ?>" <?php if($maint->vehicle_id == $vehicle->id): ?> selected <?php endif; ?>>
                                        <?php echo e($vehicle->types->vehicletype); ?>:<?php echo e($vehicle->car_model); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-5" style="margin-top: 5px;">
                            <select id="type_id" name="type_id[]" class="form-control vehicles" style="width: 100%"
                                multiple="true" required>
                                <option value=""><?php echo app('translator')->get('fleet.select_type'); ?></option>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    
                                    $x = explode(',', $maint->type_id);
                                    
                                    $selected = in_array($type->id, $x) ? 'selected' : '';
                                    
                                    ?>
                                    <option value="<?php echo e($type->id); ?>"<?php echo e($selected); ?>>
                                        <?php echo e($type->type); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>



                        <div class="col-md-5"style="margin-top: 5px;">
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-calendar"></i></span></div>
                                <?php echo Form::text('date', $maint->date, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        <div class="col-md-2"style="margin-top: 5px;">
                            <div class="input-group ">
                                <label class="switch">
                                    <input type="checkbox" class="input-group-prepend" name="status" value="1"
                                        <?php if($maint->status == '1'): ?> Checked <?php endif; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            </label>
                        </div>


                        <div class="col-md-4" style="margin-top: 10px;">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Transactions add')): ?>
                                <button type="submit" class="btn btn-success"><?php echo app('translator')->get('fleet.update'); ?></button>
                            <?php endif; ?>
                        </div>

                        

                        <?php echo Form::close(); ?>


                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div id="bulkModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo app('translator')->get('fleet.delete'); ?></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <?php echo Form::open(['url' => 'admin/delete-expense', 'method' => 'POST', 'id' => 'form_delete']); ?>

                            <div id="bulk_hidden"></div>
                            <p><?php echo app('translator')->get('fleet.confirm_bulk_delete'); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button id="bulk_action" class="btn btn-danger" type="submit"
                                data-submit=""><?php echo app('translator')->get('fleet.delete'); ?></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get('fleet.close'); ?></button>
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo app('translator')->get('fleet.delete'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo app('translator')->get('fleet.confirm_delete'); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button id="del_btn" class="btn btn-danger" type="button"
                                data-submit=""><?php echo app('translator')->get('fleet.delete'); ?></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get('fleet.close'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
        <?php $__env->stopSection(); ?>


        <?php $__env->startSection('script'); ?>
            <script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
            <!-- bootstrap datepicker -->
            <script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#vehicle_id').select2({
                        placeholder: "<?php echo app('translator')->get('fleet.selectVehicle'); ?>"
                    });
                    $('#vendor_id').select2({
                        placeholder: "<?php echo app('translator')->get('fleet.select_vendor'); ?>"
                    });
                    $('#expense_type').select2({
                        placeholder: "<?php echo app('translator')->get('fleet.expenseType'); ?>"
                    });
                    $('#type_id').select2({
                        placeholder: "<?php echo app('translator')->get('fleet.select_type'); ?>"
                    });

                    $('#date').datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    });

                    $('#date1').datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    });

                    $('#date2').datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    });

                    $("#del_btn").on("click", function() {
                        var id = $(this).data("submit");
                        $("#form_" + id).submit();
                    });

                    $('#myModal').on('show.bs.modal', function(e) {
                        var id = e.relatedTarget.dataset.id;
                        $("#del_btn").attr("data-submit", id);
                    });


                    $(document).on("click", ".delete", function(e) {
                        var hvk = confirm("Are you sure?");
                        if (hvk == true) {
                            var id = $(this).data("id");
                            var action = "<?php echo e(url('admin/expense')); ?>" + "/" + id;
                            $.ajax({
                                type: "POST",
                                url: action,
                                data: "_method=DELETE&_token=" + window.Laravel.csrfToken + "&id=" + id,
                                success: function(data) {
                                    $("#expenses").empty();
                                    $("#expenses").html(data);
                                    new PNotify({
                                        title: 'Deleted!',
                                        text: '<?php echo app('translator')->get('fleet.deleted'); ?>',
                                        type: 'wanring'
                                    })
                                },
                                dataType: "HTML",
                            });
                        }
                    });
                });

                $('input[type="checkbox"]').on('click', function() {
                    $('#bulk_delete').removeAttr('disabled');
                });

                $('#bulk_delete').on('click', function() {
                    // console.log($( "input[name='ids[]']:checked" ).length);
                    if ($("input[name='ids[]']:checked").length == 0) {
                        $('#bulk_delete').prop('type', 'button');
                        new PNotify({
                            title: 'Failed!',
                            text: "<?php echo app('translator')->get('fleet.delete_error'); ?>",
                            type: 'error'
                        });
                        $('#bulk_delete').attr('disabled', true);
                    }
                    if ($("input[name='ids[]']:checked").length > 0) {
                        // var favorite = [];
                        $.each($("input[name='ids[]']:checked"), function() {
                            // favorite.push($(this).val());
                            $("#bulk_hidden").append('<input type=hidden name=ids[] value=' + $(this).val() + '>');
                        });
                        // console.log(favorite);
                    }
                });


                $('#chk_all').on('click', function() {
                    if (this.checked) {
                        $('.checkbox').each(function() {
                            $('.checkbox').prop("checked", true);
                        });
                    } else {
                        $('.checkbox').each(function() {
                            $('.checkbox').prop("checked", false);
                        });
                    }
                });

                // Checkbox checked
                function checkcheckbox() {
                    // Total checkboxes
                    var length = $('.checkbox').length;
                    // Total checked checkboxes
                    var totalchecked = 0;
                    $('.checkbox').each(function() {
                        if ($(this).is(':checked')) {
                            totalchecked += 1;
                        }
                    });
                    // console.log(length+" "+totalchecked);
                    // Checked unchecked checkbox
                    if (totalchecked == length) {
                        $("#chk_all").prop('checked', true);
                    } else {
                        $('#chk_all').prop('checked', false);
                    }
                }
            </script>
        <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/maintenance/edit.blade.php ENDPATH**/ ?>