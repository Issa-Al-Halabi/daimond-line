<div class="btn-group" style="background:#075296;">

    <button type="button" class="btn  dropdown-toggle"style="color:white;" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">

        

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Users edit')): ?>
            <a class="dropdown-item" href="<?php echo e(url('admin/users/' . $row->id . '/edit')); ?>"> <span aria-hidden="true"
                    class="fa fa-edit" style="color: #075296;"></span> <?php echo app('translator')->get('fleet.edit'); ?></a>
        <?php endif; ?>
        
        <a class="dropdown-item" href="<?php echo e(url('admin/users/' . $row->id)); ?>"> <span aria-hidden="true" class="fa fa-eye"
                style="color: #075296;"></span> <?php echo app('translator')->get('fleet.show'); ?></a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Users delete')): ?>
            <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span
                    aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->get('fleet.delete'); ?></a>
        <?php endif; ?>

    </div>
</div>
<?php echo Form::open([
    'url' => 'admin/users/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]); ?>


<?php echo Form::hidden('id', $row->id); ?>


<?php echo Form::close(); ?>

<?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/users/list-actions.blade.php ENDPATH**/ ?>