<div class="btn-group" style="background:#075296;">
    <button type="button" class="btn dropdown-toggle" style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Customer edit')): ?>
            <a class="dropdown-item" href="<?php echo e(url('admin/customers/' . $row->id . '/edit')); ?>"><span aria-hidden="true"
                    class="fa fa-edit" style="color: #075296;"></span> <?php echo app('translator')->get('fleet.edit'); ?></a>
        <?php endif; ?>

        
        <a class="dropdown-item" href="<?php echo e(url('admin/customers/' . $row->id)); ?>"><span aria-hidden="true"
                class="fa fa-eye" style="color: #075296;"></span> <?php echo app('translator')->get('fleet.show'); ?></a>
        


        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Customer delete')): ?>
            <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span
                    class="fa-light fa-trash" aria-hidden="true" style="color: #dd4b39"></span> <?php echo app('translator')->get('fleet.delete'); ?></a>
        <?php endif; ?>
        <?php if($row->is_active == 'active'): ?>
            <a class="dropdown-item" href="<?php echo e(url('admin/customers/disable/' . $row->id)); ?>" class="mybtn"
                data-toggle="tooltip" title="<?php echo app('translator')->get('fleet.disable_driver'); ?>"><span class="fa fa-times" aria-hidden="true"
                    style="color: #5cb85c;"></span> <?php echo app('translator')->get('fleet.disable_driver'); ?></a>
        <?php else: ?>
            <a class="dropdown-item" href="<?php echo e(url('admin/customers/enable/' . $row->id)); ?>" class="mybtn"
                data-toggle="tooltip" title="<?php echo app('translator')->get('fleet.enable_driver'); ?>"><span class="fa fa-check" aria-hidden="true"
                    style="color: #5cb85c;"></span> <?php echo app('translator')->get('fleet.enable_driver'); ?></a>
        <?php endif; ?>
    </div>
</div>
<?php echo Form::open([
    'url' => 'admin/customers/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]); ?>

<?php echo Form::hidden('id', $row->id); ?>

<?php echo Form::close(); ?>

<?php /**PATH /home/diamond/public_html/framework/resources/views/customers/list-actions.blade.php ENDPATH**/ ?>