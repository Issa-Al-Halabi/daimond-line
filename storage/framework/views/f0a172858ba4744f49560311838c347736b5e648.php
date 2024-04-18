


<?php $__env->startSection('content'); ?>
        <section class="hero-section--about d-flex">
            <div class="container-fluid mt-auto d-flex flex-column p-0">
                <div class="row no-gutters">
                    <div class="col-sm-12">
                        <div class="hero-content--about w-100">
                            <div class="inner-content">
                                <h1 class="mb-0"><?php echo app('translator')->get('frontend.about'); ?> <?php echo e(Hyvikk::get('app_name')); ?></h1>
                                <h5 class="karla"><?php echo app('translator')->get('frontend.vehicle_mgmt'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Ends hero section -->
        <!-- Fleet introduction -->
        <section class="about-fleet">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="text-center"><?php echo e(Hyvikk::frontend('about_title')); ?></h2>
                        <p> <?php echo e(Hyvikk::frontend('about_description')); ?></p>
                    </div>
                </div>
            </div>
        </section>
        <!-- / Fleet introduction -->
        <!-- Fleet features -->
        <section class="py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-4 col-md-6">
                        <img src="<?php echo e(asset('assets/frontend/images/fleet-about-city.png')); ?>" alt="" class="d-block ml-5 ml-sm-0 mx-sm-auto">
                    </div>
                    <div class="col-lg-8 col-md-6 d-flex flex-column justify-content-center pl-5 about-feature">
                        <h3 class="primary karla font-weight-bold mb-0"> <?php echo e(Hyvikk::frontend('cities')); ?>+</h3>
                        <p><?php echo app('translator')->get('frontend.city_desc'); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 ">
                        <img src="<?php echo e(asset('assets/frontend/images/fleet-about-vehicles.png')); ?>" alt="" class="d-block ml-auto">
                    </div>
                    <div class="col-lg-8 col-md-6 d-flex flex-column justify-content-center pl-5 about-feature">
                        <h3 class="primary karla font-weight-bold mb-0"><?php echo e(Hyvikk::frontend('vehicles')); ?>+</h3>
                        <p><?php echo app('translator')->get('frontend.vehicle_desc'); ?></p>
                    </div>
                </div>
            </div>
        </section>
        <!-- / End Fleet features -->
        <!-- Fleet Team -->
        <section class="my-5 py-5">
            <div class="container">
                <h2 class="mb-0 text-center mb-5 pb-3"><?php echo app('translator')->get('frontend.minds_behind'); ?> <?php echo e(Hyvikk::get('app_name')); ?></h2>
                <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$teams): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($key % 2 == 0): ?> 
                        <div class="row fleet-member">
                            <div class="col-lg-3 col-12 col fleet-shape">
                                <div class="fleet-member_image">
                                    <?php if($teams->image != null): ?>
                                        <img src="<?php echo e(url('uploads/' . $teams->image)); ?>" alt="Image">
                                    <?php else: ?>
                                        <img src="<?php echo e(url('assets/images/no-user.jpg')); ?>" alt="Image">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-9 col-12 flex-col-center col">
                                <div class="fleet-member_content">
                                    <h5 class="mb-0"><?php echo e($teams->name); ?></h5>
                                    <span class="primary d-block"><?php echo e($teams->designation); ?></span>
                                    <p class="mb-0 mt-2"><?php echo e($teams->details); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row fleet-member--flip">
                            <div class="col-lg-9 col-12 flex-col-center col order-2 order-lg-0">
                                <div class="fleet-member_content">
                                    <h5 class="mb-0"><?php echo e($teams->name); ?></h5>
                                    <span class="primary d-block"><?php echo e($teams->designation); ?></span>
                                    <p class="mb-0 mt-2"><?php echo e($teams->details); ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-12 col fleet-shape">
                                <div class="fleet-member_image">
                                    <?php if($teams->image != null): ?> 
                                        <img src="<?php echo e(url('uploads/' . $teams->image)); ?>" alt="Image">
                                    <?php else: ?>
                                        <img src="<?php echo e(url('assets/images/no-user.jpg')); ?>" alt="Image">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               
            </div>
        </section>
        <!-- /End Fleet team -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/diamond/public_html/framework/resources/views/frontend/about.blade.php ENDPATH**/ ?>