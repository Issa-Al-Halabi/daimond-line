<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo e(link_to_route('roles.index', __('fleet.roles'))); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.add_role'); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->get('fleet.add_role'); ?></h3>
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

        <?php echo Form::open(['route' => 'roles.store','method'=>'post']); ?>

        <div class="row">
          <div class="form-group col-md-6">
            <?php echo Form::label('name', __('fleet.name'), ['class' => 'form-label']); ?>

            <?php echo Form::text('name', null,['class' => 'form-control','required']); ?>

          </div>
        </div>
        <div class="row">
          <?php echo Form::label('permission',__('fleet.module_permission').":", ['class' => 'col-xs-5 control-label']); ?>

        </div>
        <div class="row">  
        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-md-4">
            <div class="form-group">
              <?php echo Form::label('permission',$row, ['class' => 'col-xs-5 control-label']); ?>

            <br>
              <?php if(!in_array($row,["Inquiries","Reports","Settings"])): ?>
                <input type="checkbox" name="<?php echo e($row." list"); ?>" value="1" class="flat-red form-control">&nbsp; List &nbsp; &nbsp;&nbsp;&nbsp;
              <?php endif; ?>
              <?php if(!in_array($row,["Inquiries","Reports","Settings"])): ?>
                <input type="checkbox" name="<?php echo e($row." add"); ?>" value="1" class="flat-red form-control">&nbsp; Add &nbsp; &nbsp;&nbsp;&nbsp;
              <?php endif; ?>
              <?php if(!in_array($row,["Inquiries","Reports","Transactions","ServiceReminders","Settings"])): ?>
                <input type="checkbox" name="<?php echo e($row." edit"); ?>" value="1" class="flat-red form-control">&nbsp; Edit &nbsp; &nbsp;&nbsp;&nbsp;
              <?php endif; ?>
              <?php if(!in_array($row,["Inquiries","Reports","Settings"])): ?>
                <input type="checkbox" name="<?php echo e($row." delete"); ?>" value="1" class="flat-red form-control">&nbsp; Delete &nbsp; &nbsp;&nbsp;&nbsp;
              <?php endif; ?>
              <?php if(in_array($row,["Drivers","Customer","Vendors"])): ?>
                <input type="checkbox" name="<?php echo e($row." import"); ?>" value="1" class="flat-red form-control">&nbsp; Import Excel &nbsp; &nbsp;&nbsp;&nbsp;
              <?php endif; ?>
              <?php if(in_array($row,["Inquiries","Reports","Settings"])): ?>
                <input type="checkbox" name="<?php echo e($row." list"); ?>" value="1" class="flat-red form-control">&nbsp; All 
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
        </div> 
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="form-group col-md-4">
            <?php echo Form::submit(__('fleet.save'), ['class' => 'btn btn-success']); ?>

          </div>
        </div>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/roles/create.blade.php ENDPATH**/ ?>