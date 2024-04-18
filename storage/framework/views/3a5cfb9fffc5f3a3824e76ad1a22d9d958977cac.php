<!DOCTYPE html>
<?php if(Auth::user()->getMeta('language') != null): ?>
    <?php ($language = Auth::user()->getMeta('language')); ?>
<?php else: ?>
    <?php ($language = Hyvikk::get('language')); ?>
<?php endif; ?>

<html>
  
<!--
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>  -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="manifest" href="<?php echo e(asset('web-manifest.json?v2')); ?>">
 
  <script src="https://cdn.tiny.cloud/1/<your-api-key>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>tinymce.init({selector:'textarea#detail'})</script>


    
    <title>Dimond-Line</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('uploads/32-32-PX-01.png')); ?>" type="image/png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/font-awesome/css/all.min.css')); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/ionicons.min.css')); ?>">
    <!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/fullcalendar/fullcalendar.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/fullcalendar/fullcalendar.print.css')); ?>" media="print">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/buttons.dataTables.min.css')); ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/select2/select2.min.css')); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dist/adminlte.min.css')); ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/iCheck/flat/blue.css')); ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/iCheck/all.css')); ?>">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/morris/morris.css')); ?>">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')); ?>">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')); ?>">
    <!-- Google Font: Source Sans Pro -->
    <link href="<?php echo e(asset('assets/css/fonts/fonts.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('assets/css/pnotify.custom.min.css')); ?>" media="all" rel="stylesheet" type="text/css" />
    <style>
        [data-toggle='modal'] {
            cursor: pointer;
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            display: none;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <?php echo $__env->yieldContent('extra_css'); ?>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'subscription_url' => asset('assets/push_notification/push_subscription.php'),
            'serviceWorkerUrl' => asset('serviceWorker.js'),
        ]); ?>;
    </script>
    <!-- browser notification -->
    <script src="<?php echo e(asset('assets/push_notification/app.js')); ?>"></script>
    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
            font-size: 0.6em;
            height: 35px !important;
        }

        .error {
            font-weight: 400 !important;
            color: red;
        }

        .input-group input {
            width: 65% !important;
        }
    </style>
    <?php if($language == 'Arabic-ar'): ?>
        <style type="text/css">
            .sidebar {
                text-align: right;
            }

            .nav-sidebar .nav-link>p>.right {
                position: absolute;
                right: 0rem;
                top: 12px;
            }

            .nav-sidebar>.nav-item {
                margin-right: -20px;
            }
        </style>
    <?php endif; ?>
</head>

<body class="hold-transition <?php echo e(auth()->user()->theme); ?> layout-fixed sidebar-mini"
    <?php if($language == 'Arabic-ar'): ?> dir="rtl" <?php endif; ?>>
    <?php echo Form::hidden('loggedinuser', Auth::user()->id, ['id' => 'loggedinuser']); ?>

    <?php echo Form::hidden('user_type', Auth::user()->user_type, ['id' => 'user_type']); ?>

    <div class="wrapper">
        <!-- Navbar -->
        <nav
            class="main-header navbar navbar-expand  <?php if(auth()->user()->theme == 'dark-mode'): ?> navbar-dark <?php else: ?> bg-white navbar-light <?php endif; ?> border-bottom">
            <!-- Left navbar links -->
            
<li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- Notifications Dropdown Menu -->
                <?php if(Auth::user()->user_type == 'S'): ?>
                    <?php ($r = 0); ?>
                    <?php ($i = 0); ?>
                    <?php ($l = 0); ?>
                    <?php ($d = 0); ?>
                    <?php ($s = 0); ?>
                    <?php ($user = Auth::user()); ?>
                    <?php $__currentLoopData = $user->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($notification->type == 'App\Notifications\RenewRegistration'): ?>
                            <?php ($r++); ?>
                        <?php elseif($notification->type == 'App\Notifications\RenewInsurance'): ?>
                            <?php ($i++); ?>
                        <?php elseif($notification->type == 'App\Notifications\RenewVehicleLicence'): ?>
                            <?php ($l++); ?>
                        <?php elseif($notification->type == 'App\Notifications\RenewDriverLicence'): ?>
                            <?php ($d++); ?>
                        <?php elseif($notification->type == 'App\Notifications\ServiceReminderNotification'): ?>
                            <?php ($s++); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php ($n = $r + $i + $l + $d + $s); ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">
                                <?php if($n > 0): ?>
                                    <?php echo e($n); ?>

                                <?php endif; ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <?php if($n > 0): ?>
                                <span class="dropdown-item dropdown-header"> <?php echo e($n); ?> Notifications </span>
                                <div class="dropdown-divider"></div>
                            <?php endif; ?>
                            <a href="<?php echo e(url('admin/vehicle_notification', ['type' => 'renew-registrations'])); ?>"
                                class="dropdown-item">
                                <i class="fa fa-id-card-o mr-2"></i> <?php echo app('translator')->get('fleet.renew_registration'); ?>
                                <span class="float-right text-muted text-sm">
                                    <?php if($r > 0): ?>
                                        <?php echo e($r); ?>

                                    <?php endif; ?>
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo e(url('admin/vehicle_notification', ['type' => 'renew-insurance'])); ?>"
                                class="dropdown-item">
                                <i class="fa fa-file-text mr-2"></i> <?php echo app('translator')->get('fleet.renew_insurance'); ?>
                                <span class="float-right text-muted text-sm">
                                    <?php if($i > 0): ?>
                                        <?php echo e($i); ?>

                                    <?php endif; ?>
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo e(url('admin/vehicle_notification', ['type' => 'renew-licence'])); ?>"
                                class="dropdown-item">
                                <i class="fa fa-file-o mr-2"></i> <?php echo app('translator')->get('fleet.renew_licence'); ?>
                                <span class="float-right text-muted text-sm">
                                    <?php if($l > 0): ?>
                                        <?php echo e($l); ?>

                                    <?php endif; ?>
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo e(url('admin/driver_notification', ['type' => 'renew-driving-licence'])); ?>"
                                class="dropdown-item">
                                <i class="fa fa-file-text-o mr-2"></i> <?php echo app('translator')->get('fleet.renew_driving_licence'); ?>
                                <span class="float-right text-muted text-sm">
                                    <?php if($d > 0): ?>
                                        <?php echo e($d); ?>

                                    <?php endif; ?>
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo e(url('admin/reminder', ['type' => 'service-reminder'])); ?>"
                                class="dropdown-item">
                                <i class="fa fa-clock-rotate-left mr-2"></i> <?php echo app('translator')->get('fleet.serviceReminders'); ?>
                                <span class="float-right text-muted text-sm">
                                    <?php if($s > 0): ?>
                                        <?php echo e($s); ?>

                                    <?php endif; ?>
                                </span>
                            </a>
                        </div>
                    </li>
                <?php endif; ?>
                <!-- logout -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-user-circle"></i>
                        <span class="badge badge-danger navbar-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <?php if(Auth::user()->user_type == 'D' && Auth::user()->getMeta('driver_image') != null): ?>
                                    <?php if(starts_with(Auth::user()->getMeta('driver_image'), 'http')): ?>
                                        <?php ($src = Auth::user()->getMeta('driver_image')); ?>
                                    <?php else: ?>
                                        <?php ($src = asset('uploads/' . Auth::user()->getMeta('driver_image'))); ?>
                                    <?php endif; ?>
                                    <img src="<?php echo e($src); ?>" class="img-size-50 mr-3 img-circle"
                                        alt="User Image">
                                <?php elseif(Auth::user()->user_type == 'S' || Auth::user()->user_type == 'O'): ?>
                                    <?php if(Auth::user()->getMeta('profile_image') == null): ?>
                                        <img src="<?php echo e(asset(' assets/images/no-user.jpg')); ?>"
                                            class="img-size-50 mr-3 img-circle" alt="User Image">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('uploads/' . Auth::user()->getMeta('profile_image'))); ?>"
                                            class="img-size-50 mr-3 img-circle" alt="User Image">
                                    <?php endif; ?>
                                <?php elseif(Auth::user()->user_type == 'C' && Auth::user()->getMeta('profile_pic') != null): ?>
                                    <?php if(starts_with(Auth::user()->getMeta('profile_pic'), 'http')): ?>
                                        <?php ($src = Auth::user()->getMeta('profile_pic')); ?>
                                    <?php else: ?>
                                        <?php ($src = asset('uploads/' . Auth::user()->getMeta('profile_pic'))); ?>
                                    <?php endif; ?>
                                    <img src="<?php echo e($src); ?>" class="img-size-50 mr-3 img-circle"
                                        alt="User Image">
                                <?php else: ?>
                                    <img src="<?php echo e(asset(' assets/images/no-user.jpg')); ?>"
                                        class="img-size-50 mr-3 img-circle" alt="User Image">
                                <?php endif; ?>

                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <?php echo e(Auth::user()->name); ?>


                                        <span class="float-right text-sm text-danger">

                                        </span>
                                    </h3>
                                    <p class="text-sm text-muted"><?php echo e(Auth::user()->email); ?></p>
                                    <p class="text-sm text-muted"></p>

                                </div>
                            </div>
                        </a>
                        <div>
                            <div style="margin: 5px;">
                                <a href="<?php echo e(url('admin/change-details/' . Auth::user()->id)); ?>"
                                    class="btn btn-secondary btn-flat"><i class="fa fa-edit"></i>
                                    <?php echo app('translator')->get('fleet.editProfile'); ?></a>

                                <a href="<?php echo e(route('logout')); ?>"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="btn btn-secondary btn-flat pull-right"> <i class="fa fa-sign-out"></i>
                                    <?php echo app('translator')->get('menu.logout'); ?>
                                </a>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                    style="display: none;">
                                    <?php echo e(csrf_field()); ?>

                                </form>

                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- Message End -->

                    </div>
                </li>
                
                <!-- logout -->
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">

            
            
            <img src="<?php echo e(asset('uploads/without.png')); ?>" alt="Fleet Logo" class="brand-image"
                style="opacity: .8; display: flex; width: 55%; height: 125px; margin: auto;">
            <!--<span class="brand-text font-weight-light"><?php echo e(Hyvikk::get('app_name')); ?></span>-->
            

            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="sidebar-search-results">
                        <div class="list-group">
                            <a href="#" class="list-group-item">
                                <div class="search-title">
                                    <div class="search-path"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar nav-flat flex-column" data-widget="treeview"
                            role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
                            <!-- customer -->
                            <?php if(Auth::user()->user_type == 'C'): ?>

                                <?php if(Request::is('admin/bookings*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>

                                <li class="nav-item has-treeview <?php echo e($class); ?>">
                                    <a href="#" class="nav-link <?php echo e($active); ?>">
                                        <i class="nav-icon fa fa-address-card"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.bookings'); ?>
                                            <i class="right fa fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('bookings.create')); ?>"
                                                class="nav-link <?php if(Request::is('admin/bookings/create')): ?> active <?php endif; ?>">
                                                <i class="fa fa-address-book nav-icon "></i>
                                                <p>
                                                    <?php echo app('translator')->get('menu.newbooking'); ?></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('bookings.index')); ?>"
                                                class="nav-link <?php if(Request::is('admin/bookings*') &&
                                                    !Request::is('admin/bookings/create') &&
                                                    !Request::is('admin/bookings_calendar')): ?> active <?php endif; ?>">
                                                <i class="fa fa-tasks nav-icon"></i>
                                                <p>
                                                    <?php echo app('translator')->get('menu.manage_bookings'); ?></p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/change-details/' . Auth::user()->id)); ?>"
                                        class="nav-link <?php if(Request::is('admin/change-details*')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-edit"></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.editProfile'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/addresses')); ?>"
                                        class="nav-link <?php if(Request::is('admin/addresses*')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-map-marker"></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.addresses'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/')); ?>"
                                        class="nav-link <?php if(Request::is('admin')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-money-bill-alt"></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.expenses'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <!-- customer -->
                            <!-- user-type S or O -->

                            
                            <!-- user-type S or O -->

                            <!-- driver -->
                            <?php if(Auth::user()->user_type == 'D'): ?>

                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/')); ?>"
                                        class="nav-link <?php if(Request::is('admin/')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-user"></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.myProfile'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('my_bookings')); ?>"
                                        class="nav-link <?php if(Request::is('admin/my_bookings')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-book"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.my_bookings'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/vehicle-inspection')); ?>"
                                        class="nav-link <?php if(Request::is('admin/vehicle-inspection*') ||
                                            Request::is('admin/view-vehicle-inspection*') ||
                                            Request::is('admin/print-vehicle-inspection*')): ?> active <?php endif; ?>">
                                        <i class="fa fa-briefcase nav-icon"></i>
                                        <p><?php echo app('translator')->get('fleet.vehicle_inspection'); ?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/change-details/' . Auth::user()->id)); ?>"
                                        class="nav-link <?php if(Request::is('admin/change-details*')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-edit"></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.editProfile'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                <?php if(!empty(Hyvikk::chat('pusher_app_id')) &&
                                    !empty(Hyvikk::chat('pusher_app_key')) &&
                                    !empty(Hyvikk::chat('pusher_app_secret')) &&
                                    !empty(Hyvikk::chat('pusher_app_cluster'))): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(url('admin/chat/')); ?>"
                                            class="nav-link <?php if(Request::is('admin/chat')): ?> active <?php endif; ?>">
                                            <i class="nav-icon fa fa-comments-o"></i>
                                            <p>
                                                <?php echo app('translator')->get('fleet.chat'); ?>
                                                <span class="right badge badge-danger"></span>
                                            </p>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if(Request::is('admin/notes*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>

                                <li class="nav-item has-treeview <?php echo e($class); ?>">
                                    <a href="#" class="nav-link <?php echo e($active); ?>">
                                        <i class="nav-icon fa fa-sticky-note"></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.notes'); ?>
                                            <i class="right fa fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('notes.index')); ?>"
                                                class="nav-link <?php if(Request::is('admin/notes*') && !Request::is('admin/notes/create')): ?> active <?php endif; ?>">
                                                <i class="fa fa-flag nav-icon"></i>
                                                <p> <?php echo app('translator')->get('fleet.manage_note'); ?></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('notes.create')); ?>"
                                                class="nav-link <?php if(Request::is('admin/notes/create')): ?> active <?php endif; ?>">
                                                <i class="fa fa-plus-square nav-icon"></i>
                                                <p><?php echo app('translator')->get('fleet.create_note'); ?></p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <?php if(Request::is('admin/driver-reports*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>

                                <li class="nav-item has-treeview <?php echo e($class); ?>">
                                    <a href="#" class="nav-link <?php echo e($active); ?>">
                                        <i class="nav-icon fa fa-book"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.reports'); ?>
                                            <i class="right fa fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('dreports.monthly')); ?>"
                                                class="nav-link <?php if(Request::is('admin/driver-reports/monthly')): ?> active <?php endif; ?>">
                                                <i class="fa fa-calendar nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.monthlyReport'); ?></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(route('dreports.yearly')); ?>"
                                                class="nav-link <?php if(Request::is('admin/driver-reports/yearly')): ?> active <?php endif; ?>">
                                                <i class="fa fa-calendar nav-icon"></i>
                                                <p><?php echo app('translator')->get('fleet.yearlyReport'); ?></p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <?php if(Hyvikk::get('fuel_enable_driver') == 1): ?>
                                    <?php if(Request::is('admin/fuel*')): ?>
                                        <?php ($class = 'menu-open'); ?>
                                        <?php ($active = 'active'); ?>
                                    <?php else: ?>
                                        <?php ($class = ''); ?>
                                        <?php ($active = ''); ?>
                                    <?php endif; ?>

                                    <li class="nav-item has-treeview <?php echo e($class); ?>">
                                        <a href="#" class="nav-link <?php echo e($active); ?>">
                                            <i class="nav-icon fa fa-filter"></i>
                                            <p>
                                                <?php echo app('translator')->get('fleet.fuel'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>

                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="<?php echo e(route('fuel.create')); ?>"
                                                    class="nav-link <?php if(Request::is('admin/fuel/create')): ?> active <?php endif; ?>">
                                                    <i class="fa fa-plus-square nav-icon"></i>
                                                    <p> <?php echo app('translator')->get('fleet.add_fuel'); ?></p>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="<?php echo e(route('fuel.index')); ?>"
                                                    class="nav-link <?php if(Request::is('admin/fuel*') && !Request::is('admin/fuel/create')): ?> active <?php endif; ?>">
                                                    <i class="fa fa-history nav-icon"></i>
                                                    <p><?php echo app('translator')->get('fleet.manage_fuel'); ?></p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if(Hyvikk::get('income_enable_driver') == 1 || Hyvikk::get('expense_enable_driver') == 1): ?>
                                    <?php if(Request::is('admin/income') ||
                                        Request::is('admin/expense') ||
                                        Request::is('admin/transaction') ||
                                        Request::is('admin/income_records') ||
                                        Request::is('admin/expense_records')): ?>
                                        <?php ($class = 'menu-open'); ?>
                                        <?php ($active = 'active'); ?>
                                    <?php else: ?>
                                        <?php ($class = ''); ?>
                                        <?php ($active = ''); ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Transactions list')): ?>
                                        <li class="nav-item has-treeview <?php echo e($class); ?>">
                                            <a href="#" class="nav-link <?php echo e($active); ?>">
                                                <i class="nav-icon fa fa-money-bill-alt"></i>
                                                <p>
                                                    <?php echo app('translator')->get('menu.transactions'); ?>
                                                    <i class="right fa fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <?php if(Hyvikk::get('income_enable_driver') == 1): ?>
                                                    <li class="nav-item">
                                                        <a href="<?php echo e(route('income.index')); ?>"
                                                            class="nav-link <?php if(Request::is('admin/income') || Request::is('admin/income_records')): ?> active <?php endif; ?>">
                                                            <i class="fa fa-newspaper nav-icon"></i>
                                                            <p><?php echo app('translator')->get('fleet.manage_income'); ?></p>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if(Hyvikk::get('expense_enable_driver') == 1): ?>
                                                    <li class="nav-item">
                                                        <a href="<?php echo e(route('expense.index')); ?>"
                                                            class="nav-link <?php if(Request::is('admin/expense') || Request::is('admin/expense_records')): ?> active <?php endif; ?>">
                                                            <i class="fa fa-newspaper nav-icon"></i>
                                                            <p><?php echo app('translator')->get('fleet.manage_expense'); ?></p>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                
                            <?php endif; ?>
                            <!-- driver -->

                            <!-- sidebar menus for office-admin and super-admin -->

                            <?php if(Auth::user()->user_type == 'S' || Auth::user()->user_type == 'O'): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/')); ?>"
                                        class="nav-link <?php if(Request::is('admin')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-tachograph-digital"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.Dashboard'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(!Auth::guest() && Auth::user()->user_type != 'D' && Auth::user()->user_type != 'C'): ?>

                                <?php if(Request::is('admin/drivers*') ||
                                    Request::is('admin/users*') ||
                                    Request::is('admin/customers*') ||
                                    Request::is('admin/chat')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Users list', 'Drivers list', 'Customer list'])): ?>
                                    <li class="nav-item has-treeview <?php echo e($class); ?>">
                                        <a href="#" class="nav-link <?php echo e($active); ?>">
                                            <i class="nav-icon fa fa-users"></i>
                                            <p>
                                                <?php echo app('translator')->get('menu.users'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Drivers list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('drivers.index')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/drivers*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-id-card nav-icon"></i>
                                                        <p><?php echo app('translator')->get('fleet.all_exdriver'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Users list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('users.index')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/users*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-user nav-icon"></i>
                                                        <p><?php echo app('translator')->get('fleet.all_user'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Customer list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('customers.index')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/customers*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-address-card nav-icon"></i>
                                                        <p><?php echo app('translator')->get('fleet.all_driver'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <li class="nav-item">
                                                <a href="<?php echo e(route('roles.index')); ?>"
                                                    class="nav-link <?php if(Request::is('admin/roles*')): ?> active <?php endif; ?>">
                                                    <i class="fa fa-tasks nav-icon"></i>
                                                    <p><?php echo app('translator')->get('fleet.Roles'); ?></p>
                                                </a>
                                            </li>
                                            <?php if(!empty(Hyvikk::chat('pusher_app_id')) &&
                                                !empty(Hyvikk::chat('pusher_app_key')) &&
                                                !empty(Hyvikk::chat('pusher_app_secret')) &&
                                                !empty(Hyvikk::chat('pusher_app_cluster'))): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('chat.index')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/chat')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-comments-o nav-icon"></i>
                                                        <p><?php echo app('translator')->get('fleet.chat'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if(Request::is('admin/driver-logs') ||
                                    Request::is('admin/vehicle-types*') ||
                                    Request::is('admin/car_option*') ||
                                    Request::is('admin/vehicles*') ||
                                    Request::is('admin/vehicle_group*') ||
                          
                                   
                                    Request::is('admin/vehicle-reviews*') ||
                                    Request::is('admin/view-vehicle-review*') ||
                                    Request::is('admin/vehicle-review*') ||
                                    Request::is('admin/vehicle-make*') ||
                                    Request::is('admin/vehicle-model*') ||
                                    Request::is('admin/vehicle-color*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any([
                                    'Vehicles list',
                                    'VehicleType list',
                                    'VehicleGroup list',
                                    'VehicleInspection list',
                                    'VehicleColors
                                    list',
                                    'VehicleModels list',
                                    'VehicleMaker list',
                                    ])): ?>
                                    <li class="nav-item has-treeview <?php echo e($class); ?>">
                                        <a href="#" class="nav-link <?php echo e($active); ?>">
                                            <i class="nav-icon fa fa-taxi"></i>
                                            <p>
                                                <?php echo app('translator')->get('menu.vehicles'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Vehicles list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('vehicles.index')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/vehicles*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-truck nav-icon"></i>
                                                        <p><?php echo app('translator')->get('menu.manageVehicles'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('VehicleType list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('vehicle-types.index')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/vehicle-types*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-th-list nav-icon"></i>
                                                        <p><?php echo app('translator')->get('fleet.manage_vehicle_types'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <li class="nav-item">
                                                <a href="<?php echo e(route('subcategories.index')); ?>"
                                                    class="nav-link <?php if(Request::is('admin/subcategories*')): ?> active <?php endif; ?>">
                                                    <i class="fa fa-truck nav-icon"></i>
                                                    <p><?php echo app('translator')->get('menu.governorate'); ?></p>
                                                </a>
                                            </li>

                                            
                                            
                                            
                                            
                                            

                                            
                                        </ul>
                                    </li>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('VehicleType list')): ?>
                                        <li class="nav-item">
                                            <a href="<?php echo e(url('admin/car_option')); ?>"
                                                class="nav-link <?php if(Request::is('admin/car_option*')): ?> active <?php endif; ?>">
                                                <i class="fa fa-th-list nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.manage_vehicle_option'); ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(Request::is('admin/income') ||
                                    Request::is('admin/expense*') ||
                                    Request::is('admin/transaction') ||
                                    Request::is('admin/income_records') ||
                                    Request::is('admin/maintcategory') ||
                                    Request::is('admin/expense_records')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Transactions list')): ?>
                                    <li class="nav-item has-treeview <?php echo e($class); ?>">
                                        <a href="#" class="nav-link <?php echo e($active); ?>">
                                            <i class="nav-icon fa fa-money-bill-alt"></i>
                                            <p>
                                                <?php echo app('translator')->get('menu.expenses'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                           

                                            <li class="nav-item">
                                                <a href="<?php echo e(url('admin/expense')); ?>"
                                                    class="nav-link <?php if(Request::is('admin/expense') ): ?> active <?php endif; ?>">
                                                    <i class="fa fa-newspaper nav-icon"></i>
                                                    <p><?php echo app('translator')->get('fleet.manage_expense'); ?></p>
                                                </a>
                                            </li>
                                            
                                        </ul>
                                    </li>
                                <?php endif; ?>
                           <?php if(Request::is('admin/maintcategory*') ||
                         Request::is('admin/maintenance*')): ?>
                          
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                
                                <li class="nav-item has-treeview ">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fa fa-wrench"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.Maintenance'); ?>
                                            <i class="right fa fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                     
                                        <li class="nav-item">
                                            <a href="<?php echo e(url('admin/maintcategory')); ?>" class="nav-link <?php if(Request::is('admin/maintcategory*')): ?> active <?php endif; ?>">
                                                <i class="fa fa-truck nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.managemainttype'); ?></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(url('admin/maintenance')); ?>" class="nav-link
                                             <?php if(Request::is('admin/maintenance*')): ?> active <?php endif; ?>">
                                                <i class="fa fa-truck nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.manage_aintenance'); ?></p>
                                            </a>
                                        </li>

                                        

                                    </ul>
                                </li>

                                <?php if(Request::is('admin/transactions*') ||
                                    Request::is('admin/bookings*') ||
                          Request::is('admin/time_out/create*')||
                          Request::is('admin/booking/index*')||
                          Request::is('admin/booking_delay/index*')||
                          
                                    Request::is('admin/bookings_calendar') ||
                                    Request::is('admin/booking-quotation*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Bookings list', 'Bookings add', 'BookingQuotations list'])): ?>
                                    <li class="nav-item has-treeview <?php echo e($class); ?>">
                                        <a href="#" class="nav-link <?php echo e($active); ?>">
                                            <i class="nav-icon fa fa-address-card"></i>
                                            <p>
                                                <?php echo app('translator')->get('menu.trips'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Bookings list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(url('admin/bookings')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/bookings*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-tasks nav-icon"></i>
                                                        <p>
                                                            <?php echo app('translator')->get('menu.out_city_trips'); ?></p>
                                                    </a>
                                                </li>
                                                <li class="nav-item has-treeview ">
                                                    <a href="#" class="nav-link ">
                                                        <i class="nav-icon fa fa-address-card"></i>
                                                        <p>
                                                            <?php echo app('translator')->get('menu.in_city_trips'); ?>
                                                            <i class="right fa fa-angle-left"></i>
                                                        </p>
                                                    </a>

                                                    <ul class="nav nav-treeview">
                                                       <li class="nav-item">
                                    <a href="<?php echo e(url('admin/time_out/create')); ?>"
                                        class="nav-link <?php if(Request::is('admin/time_out/create')): ?> active <?php endif; ?>">
                                        <i class="nav-icon far fa-clock"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.trip_time_out'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                                        <li class="nav-item">
                                                            <a href="<?php echo e(url('admin/booking/index')); ?>"
                                                                class="nav-link <?php if(Request::is('admin/booking/index')): ?> 
                                                                    active
                                                                    <?php endif; ?>">
                                                                <i class="fa fa-tasks nav-icon"></i>
                                                                <p>
                                                                    <?php echo app('translator')->get('menu.instant_trip'); ?></p>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="<?php echo e(route('incity_delay.index')); ?>"
                                                                class="nav-link <?php if(Request::is('admin/booking_delay/index')): ?> active <?php endif; ?>">
                                                                <i class="fa fa-tasks nav-icon"></i>
                                                                <p>
                                                                    <?php echo app('translator')->get('menu.delayed_trip'); ?></p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>

                                                
                                            <?php endif; ?>

                                            
                                            
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if(Request::is('admin/fare-settings*') || Request::is('admin/booking-quotation*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>

                                
                                <li class="nav-item has-treeview <?php echo e($class); ?>">
                                    <a href="#" class="nav-link <?php echo e($active); ?>">
                                        <i class="nav-icon fa fa-dollar " style='color: #f0efe9'></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.fare_manage'); ?>
                                            <i class="right fa fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        
                                        
                                        <li class="nav-item">
                                            <a href="<?php echo e(url('admin/fare')); ?>"
                                                class="nav-link <?php if(Request::is('admin/fare')): ?> active <?php endif; ?>">
                                                <i class="fa fa-gear nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.outside_city_fare'); ?></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(url('admin/faretour_create')); ?>"
                                                class="nav-link <?php if(Request::is('admin/faretour_create')): ?> active <?php endif; ?>">
                                                <i class="fa fa-gear nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.tour_fare'); ?></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?php echo e(url('admin/fare1')); ?>"
                                                class="nav-link <?php if(Request::is('admin/fare1')): ?> active <?php endif; ?>">
                                                <i class="fa fa-gear nav-icon"></i>
                                                <p><?php echo app('translator')->get('menu.inside_city_fare'); ?></p>
                                            </a>
                                        </li>
                                        
                                        
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/wallet')); ?>"
                                        class="nav-link <?php if(Request::is('admin/wallet')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-wallet"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.ManageWallet'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/admin-fare/create')); ?>"
                                        class="nav-link <?php if(Request::is('admin/admin-fare/create')): ?> active <?php endif; ?>">
                                        <i class="nav-icon fa fa-wallet"></i>
                                        <p>
                                            <?php echo app('translator')->get('menu.ManageAdminFare'); ?>
                                            <span class="right badge badge-danger"></span>
                                        </p>
                                    </a>
                                </li>
                                

                                <?php if(Request::is('admin/reports*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Reports list')): ?>
                                    
                                <?php endif; ?>

                              

                                <?php if(Request::is('admin/vendors*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                


                                
                                

                                
                                

                                
                                

                                
                                
                                
                                

                                
                                

                                <?php if(Request::is('admin/settings*') ||
                                    Request::is('admin/roles*') ||
                                    Request::is('admin/fare-settings') ||
                                    Request::is('admin/api-settings') ||
                                    Request::is('admin/expensecategories*') ||
                                    Request::is('admin/incomecategories*') ||
                                    Request::is('admin/expensecategories*') ||
                          Request::is('admin/complaints*') ||
                                    Request::is('admin/send-email') ||
                                    Request::is('admin/contact') ||
                           Request::is('admin/set-email') ||
                                    Request::is('admin/cancel-reason*') ||
                                    Request::is('admin/frontend-settings*') ||
                                    Request::is('admin/company-services*') ||
                                    Request::is('admin/payment-settings*') ||
                                    Request::is('admin/twilio-settings*') ||
                                    Request::is('admin/chat-settings*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Settings list')): ?>
                                    
                                <?php endif; ?>

                                <?php if(Hyvikk::api('api_key') != null && Hyvikk::api('db_url') != null): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(url('admin/driver-maps')); ?>"
                                            class="nav-link <?php if(Request::is('admin/driver-maps') || Request::is('admin/track-driver*')): ?> active <?php endif; ?>">
                                            <i class="nav-icon fa fa-map"></i>
                                            <p>
                                                <?php echo app('translator')->get('fleet.maps'); ?>
                                                <span class="right badge badge-danger"></span>
                                            </p>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <!-- super-admin -->
                                    <li class="nav-item">
                                        <a href="<?php echo e(url('admin/reviews')); ?>"
                                            class="nav-link <?php if(Request::is('admin/reviews')): ?> active <?php endif; ?>">
                                            <i class="nav-icon fa fa-star"></i>
                                            <p>
                                                <?php echo app('translator')->get('fleet.reviews'); ?>
                                                <span class="right badge badge-danger"></span>
                                            </p>
                                        </a>
                                    </li>
                            
                                    <li class="nav-item has-treeview ">
                                        <a href="#" class="nav-link">
                                  
                                            <i class="fa fa-medal nav-icon"></i>
                                            <p>
                                                <?php echo app('translator')->get('menu.points'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Vehicles list')): ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('points')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/points*')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-medal nav-icon"></i>
                                                        <p><?php echo app('translator')->get('menu.trip_points'); ?></p>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                        </ul>
                          </li>
                          <li class="nav-item">
                                        <a href="<?php echo e(url('admin/coupons')); ?>"
                                            class="nav-link <?php if(Request::is('admin/coupons')): ?> active <?php endif; ?>">
                                            <i class="nav-icon fa fa-star"></i>
                                            <p>
                                                <?php echo app('translator')->get('fleet.coupons'); ?>
                                                <span class="right badge badge-danger"></span>
                                            </p>
                                        </a>
                                    </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/contact')); ?>"
                                        class="nav-link <?php if(Request::is('admin/contact')): ?> active <?php endif; ?>">
                                        
                                        <i class='nav-icon fa fa-phone' style='color: white'></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.contact_us'); ?>
                                            
                                        </p>
                                    </a>
                                </li>
                          
                          <li class="nav-item">
                                    <a href="<?php echo e(url('admin/complaints')); ?>"
                                        class="nav-link <?php if(Request::is('admin/complaints')): ?> active <?php endif; ?>">
                                       
                                        <i class='nav-icon fa fa-volume-control-phone' style='color: white'></i>
                                        <p>
                                            <?php echo app('translator')->get('fleet.complaints'); ?>
                                            
                                        </p>
                                    </a>
                                </li>
                          
                          
                          
                            <?php endif; ?>
                          <?php if(Request::is('admin/fuel*')): ?>
                                    <?php ($class = 'menu-open'); ?>
                                    <?php ($active = 'active'); ?>
                                <?php else: ?>
                                    <?php ($class = ''); ?>
                                    <?php ($active = ''); ?>
                                <?php endif; ?>
                                    <li class="nav-item has-treeview <?php echo e($class); ?>">
                                        <a href="#" class="nav-link <?php echo e($active); ?>">
                                            <i class="nav-icon fa fa-filter"></i>
                                            <p>
                                                <?php echo app('translator')->get('fleet.settings'); ?>
                                                <i class="right fa fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                                <li class="nav-item">
                                                    <a href="<?php echo e(route('fuel.create')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/fuel/create')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-plus-square nav-icon"></i>
                                                        <p> <?php echo app('translator')->get('fleet.Terms_and_Conditions'); ?></p>
                                         </a>
                                          </li>
                                         
                                          
                                          <!-- zena  -->
                                          
                                       <!--   <li class="nav-item">
                                                    <a href="<?php echo e(url('http://diamond-line.com.sy/api/trip_outcity')); ?>"
                                                        class="nav-link <?php if(Request::is('admin/fuel/create')): ?> active <?php endif; ?>">
                                                        <i class="fa fa-plus-square nav-icon"></i>
                                                        <p> <?php echo app('translator')->get('fleet.Terms_and_Conditions'); ?></p>
                                         </a>
                                          </li>  -->
                                        </ul>
                                    </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><?php echo $__env->yieldContent('heading'); ?> </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <?php if(!Request::is('admin')): ?>
                                    <li class="breadcrumb-item"><a href="<?php echo e(url('admin/')); ?>"><?php echo app('translator')->get('fleet.home'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php echo $__env->yieldContent('breadcrumb'); ?>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div><!-- /.container-fluid -->
            </section>
            <div id="chat-overlay" class="row"></div>
            <audio id="chat-alert-sound" style="display: none">
                <source src="<?php echo e(asset('assets/chats/sound.mp3')); ?>" />
            </audio>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
       
        <footer class="main-footer">
            
            © PeakLink Solutions . All Rights Reserved. | Powered By PeakLink
            <div class="float-right d-none d-sm-inline-block">
                <b><?php echo app('translator')->get('fleet.version'); ?></b> 1.1
            </div>
        </footer>



        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php echo $__env->yieldContent('script2'); ?>
    <script src="<?php echo e(asset('assets/plugins/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery-ui.min.js')); ?>"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="<?php echo e(asset('assets/plugins/bootstrap/js/bootstrap.bundle1.min.js')); ?>"></script>
   <script src="<?php echo e(asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/select2/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/iCheck/icheck.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/fastclick/fastclick.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/cdn/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/cdn/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/cdn/buttons.print.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/adminlte.js')); ?>"></script>
    <script src="<?php echo e(asset('web-sw.js?v3')); ?>"></script>
    <script>
        $('[title]').tooltip();

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?php echo e(asset('web-sw.js?v3')); ?>', {
                scope: '.' // <--- THIS BIT IS REQUIRED
            }).then(function(registration) {
                // Registration was successful
                // console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                // console.log('ServiceWorker registration failed: ', err);
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type=search]").on("keydown", function() {
                if (this.value.length > 0) {
                    $(".nav-sidebar li").hide().filter(function() {
                        return $(this).text().toLowerCase().indexOf($("input[type=search]").val()
                            .toLowerCase()) != -1;
                    }) //.show(); 
                } else {
                    $(".nav-sidebar li").show();
                }
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

            });
            $('#data_table tfoot th').each(function() {
                // console.log($('#data_table tfoot th').length);
                if ($(this).index() != 0 && $(this).index() != $('#data_table tfoot th').length - 1) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="' + title + '" />');
                }
            });

            $('#ajax_data_table tfoot th').each(function() {
                // console.log($('#data_table tfoot th').length);
                if ($(this).index() != 0 && $(this).index() != $('#data_table tfoot th').length - 1) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="' + title + '" />');
                }
            });

            $('#data_table1 tfoot th').each(function() {
                // console.log($(this).index());
                if ($(this).index() != 0 && $(this).index() != $('#data_table1 tfoot th').length - 1) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="' + title + '" />');
                }

            });

            var table1 = $('#data_table1').DataTable({
                dom: 'Bfrtip',

                buttons: [{
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> <?php echo e(__('fleet.print')); ?>',

                    exportOptions: {
                        columns: ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<h3><?php echo e(__('fleet.bookings')); ?></h3>'
                            );
                        $(win.document.body).find('table')
                            .addClass('table-bordered');
                        // $(win.document.body).find( 'td' ).css( 'font-size', '10pt' );

                    }
                }],
                "language": {
                    "url": '<?php echo e(asset('assets/datatables/') . '/' . __('fleet.datatable_lang')); ?>',
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0]
                }],
                // individual column search
                "initComplete": function() {
                    table1.columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change', function() {
                            that.search(this.value).draw();
                        });
                    });
                }
            });

            var table = $('#data_table').DataTable({
                "language": {
                    "url": '<?php echo e(asset('assets/datatables/') . '/' . __('fleet.datatable_lang')); ?>',
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0]
                }],
                // individual column search
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
            $('[data-toggle="tooltip"]').tooltip();

        });
    </script>
    <script src="<?php echo e(asset('assets/js/pnotify.custom.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('script'); ?>
    <script>
        var base_url = '<?php echo e(url('/')); ?>';
    </script>
</body>

</html>
<?php /**PATH D:\EM_Projects\xampp\htdocs\Laravel\diamond_backend\resources\views/layouts/app.blade.php ENDPATH**/ ?>