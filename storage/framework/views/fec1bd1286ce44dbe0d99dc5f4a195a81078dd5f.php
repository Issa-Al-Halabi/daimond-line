
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

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('fleet.addRecord'); ?>:
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
                            'route' => 'maintenance.store',
                            'method' => 'post',
                            'class' => 'form-inline',
                            'id' => 'exp_form',
                        ]); ?>


                        
                        <div class="col-md-4 ">
                            <select id="vechicle_id" name="vehicle_id" class="form-control vehicles" style="width: 100%"
                                required>
                                <option value=""><?php echo app('translator')->get('fleet.assign_drivers'); ?></option>
                                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($vehicle->id); ?>">
                                        <?php echo e($vehicle->types->vehicletype); ?>:<?php echo e($vehicle->car_model); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4" style="margin-top: 5px;">
                            <select id="type_id" name="type_id[]" class="form-control vehicles" style="width: 100%"
                                multiple="true" required>
                                <option value=""><?php echo app('translator')->get('fleet.select_type'); ?></option>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>">
                                        <?php echo e($type->type); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>



                        <div class="col-md-4"style="margin-top: 5px;">
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"></span></div>
                                <?php echo Form::date('date', null, ['class' => 'form-control', 'required']); ?>

                            </div>
                        </div>
                        
                        
                        
                    </div>
                    

                    <div class="col-md-1" style="margin-top: 10px;">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Transactions add')): ?>
                            <button type="submit" class="btn btn-success"><?php echo app('translator')->get('fleet.add'); ?></button>
                        <?php endif; ?>
                    </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header" style="color:#fbfbfb;">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="card-title"> <?php echo app('translator')->get('fleet.manage_maintenance'); ?> :
                            </h3>
                        </div>
                        
                    </div>
                </div>

                <div class="card-body table-responsive" id="expenses">
                    <table class="table" id="data_table">
                        <thead class="thead-inverse">
                            <tr>
                                <th>
                                    
                                    <input type="checkbox" id="chk_all">
                                    
                                </th>
                                
                                
                                <th>#</th>
                                <th><?php echo app('translator')->get('fleet.vehicle'); ?></th>
                                <th><?php echo app('translator')->get('fleet.maintnance_type'); ?></th>
                                <th><?php echo app('translator')->get('fleet.status'); ?></th>
                                <th><?php echo app('translator')->get('fleet.date'); ?></th>


                                
                                <th><?php echo app('translator')->get('fleet.action'); ?></th>
                            </tr>
                        </thead>
                        <?php
                        // use App\Model\Expense;
                        $data1['expenses'] = DB::table('maintenances')->select('vehicles.car_model', 'maintenances.*')->join('vehicles', 'vehicles.id', 'maintenances.vehicle_id')->join('maint_categories', 'maint_categories.id', 'maintenances.type_id')->get();
                        $i = 1;
                        
                        ?>
                        <tbody>
                            <?php $__currentLoopData = $data1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    
                                    $v = explode(',', $ex->type_id);
                                    $exp = App\Model\MaintenanceCategory::select('type')->wherein('id', $v)->get();
                                    // $expenese = App\Model\ExpCats::select('cost')
                                    //     ->wherein('id', $v)
                                    //     ->sum('cost');
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="<?php echo e($ex->id); ?>"
                                                class="checkbox" id="chk<?php echo e($ex->id); ?>" onclick='checkcheckbox();'>
                                        </td>
                                        <td><?php echo e($i++); ?></td>
                                        <td><?php echo e($ex->car_model); ?></td>
                                        <td>
                                            <?php $__currentLoopData = $exp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($a->type); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td><?php echo e($ex->status); ?></td>
                                        <td><?php echo e($ex->date); ?></td>
                                        
                                        
                                        
                                        
                                        
                                        
                                        <td>
                                            
                                            <div class="btn-group" style="background:#075296;">
                                                <button type="button" class="btn  dropdown-toggle" style="color:white;"
                                                    data-toggle="dropdown">
                                                    <span class="fa fa-gear"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu custom" role="menu">
                                                    <a class="dropdown-item"
                                                        href="<?php echo e(url('admin/maintenance/' . $ex->id . '/edit')); ?>"> <span
                                                            aria-hidden="true" class="fa fa-edit"
                                                            style="color: #075296;"></span> <?php echo app('translator')->get('fleet.edit'); ?></a>
                                                    <?php echo Form::hidden('id', $ex->id); ?>

                                                    <a class="dropdown-item" data-id="<?php echo e($ex->id); ?>"
                                                        data-toggle="modal" data-target="#myModal"><span aria-hidden="true"
                                                            class="fa fa-trash" style="color: #dd4b39"></span>
                                                        <?php echo app('translator')->get('fleet.delete'); ?></a>
                                                    
                                                </div>
                                                <?php echo Form::open([
                                                    'url' => 'admin/maintenance/' . $ex->id,
                                                    'method' => 'DELETE',
                                                    'class' => 'form-horizontal del_form',
                                                    'id' => 'form_' . $ex->id,
                                                ]); ?>

                                                <?php echo Form::hidden('id', $ex->id); ?>

                                                
                                                <?php echo Form::close(); ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Transactions delete')): ?>
                                        <button class="btn btn-danger" id="bulk_delete" data-toggle="modal"
                                            data-target="#bulkModal" disabled title="<?php echo app('translator')->get('fleet.delete'); ?>"><i
                                                class="fa fa-trash"></i></button>
                                    <?php endif; ?>
                                    
                                </th>
                                <th>#</th>
                                <th><?php echo app('translator')->get('fleet.vehicle'); ?></th>
                                <th><?php echo app('translator')->get('fleet.maintnance_type'); ?></th>
                                <th><?php echo app('translator')->get('fleet.status'); ?></th>
                                <th><?php echo app('translator')->get('fleet.date'); ?></th>
                                <th><?php echo app('translator')->get('fleet.action'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                    <?php echo Form::open(['url' => 'admin/maincate_delete', 'method' => 'POST', 'id' => 'form_delete']); ?>

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
            // $('#vehicle_id').select2({
            //     placeholder: "<?php echo app('translator')->get('fleet.selectVehicle'); ?>"
            // });
            $('#vendor_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.select_vendor'); ?>"
            });
            $('#vechicle_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.selectVehicle'); ?>"
            });
            $('#type_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.select_type'); ?>"
            });
            $('#expense_type').select2({
                placeholder: "<?php echo app('translator')->get('fleet.expenseType'); ?>"
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/maintenance/index.blade.php ENDPATH**/ ?>