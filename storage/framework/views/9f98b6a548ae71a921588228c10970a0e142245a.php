<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
        <span class="fa fa-gear"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu custom" role="menu">

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Customer edit')): ?>
            <a class="dropdown-item" href="<?php echo e(url('admin/points/' . $row->id . '/edit')); ?>"><span aria-hidden="true"
                    class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->get('fleet.edit'); ?></a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Customer delete')): ?>
            <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span
                    class="fa-light fa-eye" aria-hidden="true" style="color: #dd4b39"></span> <?php echo app('translator')->get('fleet.delete'); ?></a>
        <?php endif; ?>
    </div>
</div>
<?php echo Form::open([
    'url' => 'admin/points/' . $row->id,
    'method' => 'DELETE',
    'class' => 'form-horizontal',
    'id' => 'form_' . $row->id,
]); ?>

<?php echo Form::hidden('id', $row->id); ?>

<?php echo Form::close(); ?>

<?php /**PATH /home/diamond/public_html/framework/resources/views/points/list-actions.blade.php ENDPATH**/ ?>