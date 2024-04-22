
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active"><?php echo app('translator')->get('fleet.reviews'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo app('translator')->get('fleet.reviews'); ?>
                    </h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table" id="data_table">
                        <thead class="thead-inverse">
                            <tr>
                                <th><?php echo app('translator')->get('fleet.id'); ?></th>
                                <th><?php echo app('translator')->get('fleet.rider_name'); ?></th>
                                <th><?php echo app('translator')->get('fleet.driver_name'); ?></th>
                                <th><?php echo app('translator')->get('fleet.trip_date'); ?></th>
                               <th><?php echo app('translator')->get('fleet.ratings'); ?></th>
                                <th><?php echo app('translator')->get('fleet.rating_type'); ?></th>
                                <th><?php echo app('translator')->get('fleet.review'); ?></th>
                              <th><?php echo app('translator')->get('fleet.action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $a = 1;
                            ?>

                            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $driver_name = App\Model\User::where('id', $review->driver_id)->first();
                                    $rider_name = App\Model\User::where('id', $review->user_id)->first();
                         
                                ?>
                                <tr>
                                  	<?php if(isset($rider_name)): ?>
                                    <td><?php echo e($a++); ?></td>
                                       <td> <?php echo e($rider_name->first_name); ?> <?php echo e($rider_name->last_name); ?></td>
                                    <td> <?php echo e($driver_name->first_name); ?> <?php echo e($driver_name->last_name); ?></td>
                                  <?php endif; ?>
                               
                                  
                                    <?php if($review->category_id=='1'&& $review->request_type=="moment"): ?>
                                    <td><?php echo e($review->created_at); ?></td>
                                    <?php elseif($review->category_id=='1'&& $review->request_type=="delayed"): ?>
                                      <td><?php echo e($review->date); ?></td>
                                      <?php elseif($review->category_id=='2'): ?>
                                       <td><?php echo e($review->date); ?></td>
                                   <?php endif; ?>
                                    <td>
                                        <?php ($flot = ltrim($review->ratings - floor($review->ratings), '0.')); ?>
                                        <?php for($i = 1; $i <= $review->ratings; $i++): ?>
                                            <i class="fa fa-star" style='color: #f3da35'></i>
                                        <?php endfor; ?>
                                        <?php if($flot > 0 && $review->ratings < 5): ?>
                                            <i class="fa fa-star-half"></i>
                                        <?php endif; ?>
                                    </td>
                                  
                                   <td><?php echo e($review->type); ?></td>
                                  <?php if(isset($review->review_text )): ?>
                                    <td><?php echo e($review->review_text); ?></td>
                                  <?php else: ?>
                                  <td>---</td>
                                  <?php endif; ?>
                                    <td>
                                     
                                        <div class="btn-group" style="background:#075296;">
                                            <button type="button" class="btn  dropdown-toggle" style="color:white;"
                                                data-toggle="dropdown">
                                                <span class="fa fa-gear"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu custom" role="menu">
                                                
                                                <?php echo Form::hidden('id', $review->id); ?>

                                                <a class="dropdown-item" data-id="<?php echo e($review->id); ?>" data-toggle="modal"
                                                    data-target="#myModal66"><span aria-hidden="true" class="fa fa-trash"
                                                        style="color: #dd4b39"></span>
                                                    <?php echo app('translator')->get('fleet.delete'); ?></a>

                                            </div>
                                            <?php echo Form::open([
                                                'url' => 'admin/review/delete/'.$review->id,
                                                'method' => 'DELETE',
                                                'class' => 'form-horizontal del_form',
                                                'id' => 'form_' . $review->id,
                                            ]); ?>

                                            <?php echo Form::hidden('id', $review->id); ?>



                                            <?php echo Form::close(); ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- Modal -->
    <div id="myModal66" class="modal fade" role="dialog">
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
     
              $("#del_btn").on("click", function() {
               var id = $(this).data("submit");
               $("#form_" + id).submit();
            });


            $('#myModal66').on('show.bs.modal', function(e) {
                var id = e.relatedTarget.dataset.id;
                $("#del_btn").attr("data-submit", id);
            });

 });

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/reviews.blade.php ENDPATH**/ ?>