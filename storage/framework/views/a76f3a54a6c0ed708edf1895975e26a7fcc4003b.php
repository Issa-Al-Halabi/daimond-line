
<?php ($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
    <style type="text/css">
        .mybtn1 {
            padding-top: 4px;
            padding-right: 8px;
            padding-bottom: 4px;
            padding-left: 8px;
        }

        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }

        td>img {
            border-radius: 50%;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.drivers'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title" style="color:#fbfbfb;"><?php echo app('translator')->get('fleet.manage_drivers'); ?> &nbsp;
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Drivers add')): ?>
                            <a href="<?php echo e(route('drivers.create')); ?>" class="btn btn-success" title="<?php echo app('translator')->get('fleet.addDriver'); ?>"> <i
                                    class="fa fa-plus"></i> </a>
                        <?php endif; ?>
                        
                    </h3>
                </div>

                <div class="card-body table-responsive">
                    <table class="table" id="ajax_data_table" style="padding-bottom: 15px">
                        <thead class="thead-inverse">
                            <tr>
                                <th>
                                    <input type="checkbox" id="chk_all">
                                </th>
                                <th>#</th>
                               <th><?php echo app('translator')->get('fleet.created'); ?></th>
                                <th><?php echo app('translator')->get('fleet.driverImage'); ?></th>
                                <th><?php echo app('translator')->get('fleet.full_name'); ?></th>
                                <th><?php echo app('translator')->get('fleet.email'); ?></th>
                                <th><?php echo app('translator')->get('fleet.is_active'); ?></th>
                                <th><?php echo app('translator')->get('fleet.in_service'); ?></th>
                                <th><?php echo app('translator')->get('fleet.phone'); ?></th>
                                
                                
                                <th><?php echo app('translator')->get('fleet.action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Drivers delete')): ?>
                                        <button class="btn btn-danger" id="bulk_delete" data-toggle="modal"
                                            title="<?php echo app('translator')->get('fleet.delete'); ?>" data-target="#bulkModal" disabled>
                                            <i class="fa fa-trash"></i></button>
                                    <?php endif; ?>
                                </th>
                                <th>#</th>
                                 <th><?php echo app('translator')->get('fleet.created'); ?></th>
                                <th><?php echo app('translator')->get('fleet.driverImage'); ?></th>
                                <th><?php echo app('translator')->get('fleet.full_name'); ?></th>
                                <th><?php echo app('translator')->get('fleet.email'); ?></th>
                                <th><?php echo app('translator')->get('fleet.is_active'); ?></th>
                                <th><?php echo app('translator')->get('fleet.in_service'); ?></th>
                                <th><?php echo app('translator')->get('fleet.phone'); ?></th>
                                
                                
                                <th><?php echo app('translator')->get('fleet.action'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="import" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('fleet.importDrivers'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php echo Form::open(['url' => 'admin/import-drivers', 'method' => 'POST', 'files' => true]); ?>

                    <div class="form-group">
                        <?php echo Form::label('excel', __('fleet.importDrivers'), ['class' => 'form-label']); ?>

                        <?php echo Form::file('excel', ['class' => 'form-control', 'required']); ?>

                    </div>
                    <div class="form-group">
                        <a href="<?php echo e(asset('assets/samples/drivers.xlsx')); ?>"><?php echo app('translator')->get('fleet.downloadSampleExcel'); ?></a>
                    </div>
                    <div class="form-group">
                        <h6 class="text-muted"><?php echo app('translator')->get('fleet.note'); ?>:</h6>
                        <ul class="text-muted">
                            <li><?php echo app('translator')->get('fleet.driverImportNote1'); ?></li>
                            <li><?php echo app('translator')->get('fleet.driverImportNote2'); ?></li>
                            <li><?php echo app('translator')->get('fleet.driverImportNote3'); ?></li>
                            <li><?php echo app('translator')->get('fleet.excelNote'); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="submit"><?php echo app('translator')->get('fleet.import'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get('fleet.close'); ?></button>
                </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
    <!-- Modal -->

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
                    <?php echo Form::open(['url' => 'admin/delete-drivers', 'method' => 'POST', 'id' => 'form_delete']); ?>

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
                    <h4 class="modal-title"><?php echo app('translator')->get('fleet.delete'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get('fleet.confirm_delete'); ?></p>
                </div>
                <div class="modal-footer">
                    <button id="del_btn" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->get('fleet.delete'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get('fleet.close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div id="changepass" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('fleet.change_password'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php echo Form::open(['url' => url('admin/change_password'), 'id' => 'changepass_form']); ?>

                    <form id="change" action="<?php echo e(url('admin/change_password')); ?>" method="POST">
                        <?php echo Form::hidden('driver_id', '', ['id' => 'driver_id']); ?>

                        <div class="form-group">
                            <?php echo Form::label('passwd', __('fleet.password'), ['class' => 'form-label']); ?>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <?php echo Form::password('passwd', ['class' => 'form-control', 'id' => 'passwd', 'required']); ?>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="password" class="btn btn-info" type="submit"><?php echo app('translator')->get('fleet.change_password'); ?></button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get('fleet.close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $("#del_btn").on("click", function() {
            var id = $(this).data("submit");
            $("#form_" + id).submit();
        });

        $('#myModal').on('show.bs.modal', function(e) {
            var id = e.relatedTarget.dataset.id;
            $("#del_btn").attr("data-submit", id);
        });

        $('#changepass').on('show.bs.modal', function(e) {
            var id = e.relatedTarget.dataset.id;
            $("#driver_id").val(id);
        });

        $("#changepass_form").on("submit", function(e) {
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                success: function(data) {
                    new PNotify({
                        title: 'Success!',
                        text: "<?php echo app('translator')->get('fleet.passwordChanged'); ?>",
                        type: 'info'
                    });
                },
                dataType: "html"
            });
            $('#changepass').modal("hide");
            e.preventDefault();
        });
        $(function() {

            var table = $('#ajax_data_table').DataTable({
                "language": {
                    "url": '<?php echo e(asset('assets/datatables/') . '/' . __('fleet.datatable_lang')); ?>',
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(url('admin/drivers-fetch')); ?>",
                    type: 'POST',
                    data: {}
                },
                columns: [{
                        data: 'check',
                        name: 'check',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: 'No',
                        width: '5%',
                    },
                    // {
                    //     data: 'driver_image',
                    //     name: 'driver_image',
                    //     searchable: false,
                    //     orderable: false
                    // },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'profile_image',
                        name: 'profile_image',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'in_service',
                        name: 'in_service'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    // {
                    //     data: 'vehicle',
                    //     name: 'vehicle'
                    // },
                    // {
                    //     data: 'start_date',
                    //     name: 'start_date'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                "initComplete": function() {
                    table.columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change', function() {
                            // console.log($(this).parent().index());
                            that.search(this.value).draw();
                        });
                    });
                }
            });
        });
        $(document).on('click', 'input[type="checkbox"]', function() {
            if (this.checked) {
                $('#bulk_delete').prop('disabled', false);
            } else {
                if ($("input[name='ids[]']:checked").length == 0) {
                    $('#bulk_delete').prop('disabled', true);
                }
            }

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
                $('#bulk_delete').prop('disabled', true);
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/diamond/public_html/framework/resources/views/drivers/index.blade.php ENDPATH**/ ?>