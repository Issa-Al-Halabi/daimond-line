<div class="btn-group" style="background:#075296;">
    <button type="button" class="btn  dropdown-toggle" style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>

    <div class="dropdown-menu custom" role="menu">
        <?php if($row->status == 0 && $row->ride_status != 'Cancelled'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Bookings edit')): ?>
           
                <a class="dropdown-item" href="<?php echo e(url('admin/bookings/' . $row->id . '/edit')); ?>"> <span aria-hidden="true"
                        class="fa fa-eye" style="color: #075296;"></span> <?php echo app('translator')->get('fleet.view'); ?></a>
                        
            <?php endif; ?>
          <?php
      
         $expenses= App\Model\Expense::where('trip_id',$row->id)->where('expense_type','constant')->get();
 
         ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Bookings edit')): ?>
               
                <?php if($row->category_id=='2'&& $row->status=="pending" ): ?>
      <?php if($expenses->isEmpty()): ?>
                <a class="dropdown-item" href="<?php echo e(url('admin/bookings/expense/' . $row->id)); ?>"> <span aria-hidden="true"
                        class="fa fa-plus" style="color: #075296;"></span> <?php echo app('translator')->get('fleet.add_expense'); ?></a>
      <?php else: ?>
      
      <a class="dropdown-item" href="<?php echo e(url('admin/bookings/edit_expense/' . $row->id)); ?>"> <span aria-hidden="true"
                        class="fa fa-edit" style="color: #075296;"></span> <?php echo app('translator')->get('fleet.edit_expense'); ?></a>
               <?php endif; ?>
               
                <?php endif; ?>
            <?php endif; ?>
             <?php if($row->category_id=='2' && $row->status=="pending"): ?>
            <a class="dropdown-item openBtn" data-id="<?php echo e($row->id); ?>"
                                                    data-toggle="modal" data-target="#myModal2" id="openBtn">
                                                    <span class="fa fa-plus" aria-hidden="true"
                                                        style="color: #075296;"></span> <?php echo app('translator')->get('fleet.assign_driver'); ?>
                                                </a>
               <?php endif; ?>
            <?php if($row->receipt != 1): ?>
                <!--<a class="dropdown-item vtype" data-id="<?php echo e($row->id); ?>" data-toggle="modal"-->
                <!--    data-target="#cancelBooking"> <span class="fa fa-times" aria-hidden="true"-->
                <!--        style="color: #dd4b39"></span> <?php echo app('translator')->get('fleet.cancel_booking'); ?></a>-->
            <?php endif; ?>
        <?php endif; ?>
      
         
                      <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span
                    aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->get('fleet.delete'); ?></a>
        

        
    </div>
    <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('fleet.assign_driver'); ?></h4>
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
</div>
<?php echo Form::open([
    'url' => 'admin/bookings/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'book_' . $row->id,
]); ?>

<?php echo Form::hidden('id', $row->id); ?>

<?php echo Form::close(); ?>

<!--<?php $__env->startSection('script'); ?>-->
<!--    <script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>-->
    
<!--    <script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>-->
<!--    <script type="text/javascript">-->
<!--        $(document).on('click', '.openBtn', function() {-->
           
<!--            var id = $(this).attr("data-id");-->
<!--            $('#myModal2 .modal-body').load('<?php echo e(url('admin/bookings/assign')); ?>/' + id, function(result) {-->
<!--                $('#myModal2').modal({-->
<!--                    show: true-->
<!--                });-->
<!--            });-->
<!--        });-->
<!--    </script>-->
<!--<?php $__env->stopSection(); ?>-->
<?php /**PATH /home/diamond/public_html/framework/resources/views/bookings/list-actions.blade.php ENDPATH**/ ?>