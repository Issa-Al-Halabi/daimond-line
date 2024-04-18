
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
 
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="card-title"> <?php echo app('translator')->get('fleet.ManageWallet'); ?> : <strong><span id="total_today"></span> </strong>
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

                                <th><?php echo app('translator')->get('fleet.driver_name'); ?></th>
                            
                                <th><?php echo app('translator')->get('fleet.amount'); ?></th>
                                <th><?php echo app('translator')->get('fleet.new_amount'); ?></th>
                                                            

                             <th><?php echo app('translator')->get('fleet.method'); ?></th>
                              <th><?php echo app('translator')->get('fleet.date'); ?></th>
                                <th><?php echo app('translator')->get('fleet.status'); ?></th>
                                <th><?php echo app('translator')->get('fleet.action'); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <?php
                          
                          
                          $date=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $wallet->updated_at)->format('Y-m-d');
                          ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="<?php echo e($wallet->id); ?>" class="checkbox"
                                            id="chk<?php echo e($wallet->id); ?>" onclick='checkcheckbox();'>
                                    </td>
                                    <td><?php echo e($wallet->first_name); ?>  <?php echo e($wallet->last_name); ?></td>
                                   
                                    <td><?php echo e($wallet->amount); ?></td>
                                  
                                    <td>
                                      <?php if(isset( $wallet->new_amount)): ?>
                                      <?php echo e($wallet->new_amount); ?>

                                      <?php else: ?>
                                      ----
                                      <?php endif; ?>
                                  </td>
                                
                                     <td>
                                       <?php if(isset ($wallet->method)): ?>
                                       <?php echo e($wallet->method); ?>

                                       <?php if($wallet->method=='transfer'): ?>
                                       <a
                                            href="<?php echo e(asset('uploads/' . $wallet->transfare_image)); ?>" target="_blank">
                                           (view)
                                       <?php endif; ?>
                                       <?php else: ?>
                                       ----
                                       <?php endif; ?></a>
                                  </td>
                                   <td><?php echo e($date); ?></td>
                                  <td>
                                    <div class="form-group ">
                                <label class="switch">
                                    <input type="checkbox" data-id="<?php echo e($wallet->id); ?>"class="toggle-class" name="status"  <?php if($wallet->status == '1'): ?> checked <?php endif; ?> >
                                    <span class="slider round"></span>
                                </label>
                            </div>

                                  </td>
                                  
                                    <td>

                                        <div class="btn-group" style="background:#075296;">
                                            <button type="button" class="btn dropdown-toggle" style="color:white;"
                                                data-toggle="dropdown">
                                                <span class="fa fa-gear"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu custom" role="menu">


                                                <?php echo Form::hidden('id', $wallet->id); ?>

                                                <a class="dropdown-item openBtn" data-id="<?php echo e($wallet->id); ?>"
                                                    data-toggle="modal" data-target="#myModal2" id="openBtn">
                                                    <span class="fa fa-plus" aria-hidden="true"
                                                        style="color: #075296;"></span> <?php echo app('translator')->get('fleet.add'); ?>
                                                </a>
                                                <a class="dropdown-item" data-id="<?php echo e($wallet->id); ?>" data-toggle="modal"
                                                    data-target="#myModal"><span aria-hidden="true" class="fa fa-trash"
                                                        style="color: #dd4b39"></span>
                                                    <?php echo app('translator')->get('fleet.delete'); ?></a>


                                            </div>
                                            <?php echo Form::open([
                                                'url' => 'admin/wallet/delete/' . $wallet->id,
                                                'method' => 'DELETE',
                                                'class' => 'form-horizontal del_form',
                                                'id' => 'form_' . $wallet->id,
                                            ]); ?>

                                            <?php echo Form::hidden('id', $wallet->id); ?>


                                            <?php echo Form::close(); ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>

                                    <button class="btn btn-danger" id="bulk_delete" data-toggle="modal"
                                        data-target="#bulkModal" disabled title="<?php echo app('translator')->get('fleet.delete'); ?>"><i
                                            class="fa fa-trash"></i></button>

                                </th>
                                <th><?php echo app('translator')->get('fleet.driver_name'); ?></th>
                                <th><?php echo app('translator')->get('fleet.amount'); ?></th>
                                <th><?php echo app('translator')->get('fleet.new_amount'); ?></th>
                              
                                <th><?php echo app('translator')->get('fleet.method'); ?></th>
  
                              <th><?php echo app('translator')->get('fleet.date'); ?></th>
                              <th><?php echo app('translator')->get('fleet.status'); ?></th>
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
                    <?php echo Form::open(['url' => 'admin/delete-wallet', 'method' => 'POST', 'id' => 'form_delete']); ?>

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
    <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('fleet.editamount'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body">

                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo app('translator')->get('fleet.close'); ?>
                    </button>
                </div>

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
        $(document).on('click', '.openBtn', function() {
            // alert($(this).data("id"));
            var id = $(this).attr("data-id");
            $('#myModal2 .modal-body').load('<?php echo e(url('admin/wallet/edit')); ?>/' + id, function(result) {
                $('#myModal2').modal({
                    show: true
                });
            });
        });

        $(document).ready(function() {
            // $('#vehicle_id').select2({
            //     placeholder: "<?php echo app('translator')->get('fleet.selectVehicle'); ?>"
            // });
            $('#vendor_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.select_vendor'); ?>"
            });
            $('#driver_id').select2({
                placeholder: "<?php echo app('translator')->get('fleet.Selectdriver'); ?>"
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
        $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var wallet_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'Transfer-approval',
            data: {'status': status, 'wallet_id': wallet_id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/wallet/index.blade.php ENDPATH**/ ?>