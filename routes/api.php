<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/optimize', function () {
	Artisan::call('optimize:clear');
	//Artisan::call('config:cache');
	// Artisan::call('route:clear');
	//Artisan::call('route:cache');
	//  Artisan::call('optimize:clear');
	return 'Qusdae processed successfully.';
});

Route::middleware('test')->group(function () {

	Route::namespace('Api')->group(function () {
		Route::post('/MTN', 'UsersApi@MTN');
		Route::post('/login', 'Auth@login');
		Route::post('/otp', 'OTPController@signUp');
		Route::post('/otp/verify', 'OTPController@verifyOTP');
		Route::post('/otp_email', 'OTPController@signUp1');
		Route::post('/otp/verify_email', 'OTPController@verifyOTP1');
		Route::post('send_noification', 'UsersApi@send');
		Route::post('/join_us', 'DriversApi@join_us');
		Route::post('/user-registration', 'UsersApi@user_registration');
		Route::post('/user1-registration', 'UsersApi@user1_registration');
		Route::post('/user-login', 'UsersApi@user_login'); //without social media connected
		Route::post('/userforiegn-registration', 'UsersApi@userforiegn_registration');
		Route::post('/userforiegn-login', 'UsersApi@userforiegn_login');
		Route::post('/user-login-sm', 'UsersApi@login_with_sm'); //login through social media
		Route::post('/forgot-password', 'UsersApi@forgot_password');
		Route::post('/forgot-password-email', 'UsersApi@forgot_password_email');

		Route::post('/complete_register', 'DriversApi@complete_register');
		Route::get('/terms', 'UsersApi@get_terms');
		Route::post('/ecash-payment', 'DriversApi@ecash_payment');


		Route::post('/get-settings', 'DriversApi@get_settings');
		Route::get('/codes', 'DriversApi@get_code');
	});

	Route::namespace('Api')->middleware(['throttle', 'auth:api'])->group(function () {


		//----------------------------
		Route::post('delete_rider', 'UsersApi@DeleteRideAccount');
		Route::post('delete_driver', 'DriversApi@DeleteDriverAccount');
		//----------------------------
		Route::post('map-details', 'UsersApi@map_api');
		Route::post('get_profile', 'UsersApi@get_profile');
		Route::post('/trip_outcity', 'UsersApi@trip_outcity');
		Route::post('/edit-user-profile', 'UsersApi@edit_profile');
		Route::post('/edit-user1-profile', 'UsersApi@edit_profile1');
		Route::post('/edit-userforiegn-profile', 'UsersApi@edit_userforiegn_profile');
		Route::get('/category', 'UsersApi@getcategory');
		Route::post('/subcategory', 'UsersApi@getsubcategory');
		Route::get('/goverment', 'UsersApi@goverment');
		Route::post('/filter_vehicle', 'UsersApi@filter_vehicle');

		Route::post('/map_cars', 'UsersApi@Map_Cars');
		Route::post('/trip_order', 'UsersApi@trip_order');

		Route::post('/get_mytrip', 'UsersApi@user_trip');
		Route::post('/order_tour', 'UsersApi@tour');
		Route::post('/cancle-trip', 'UsersApi@cancle_trip');
		Route::post('/accepted-trip', 'UsersApi@accept_trip');
		Route::post('/cancle-tour', 'UsersApi@cancle_tour');
		Route::post('/get-tour', 'UsersApi@get_tour');
		Route::post('/update-tour', 'UsersApi@update_tour');
		Route::post('/siginout', 'UsersApi@SignOut');
		Route::get('/user-trips', 'UsersApi@user_trips');


		Route::post('/driver-trip', 'DriversApi@driver_trip');

		Route::post('/create-orderid', 'DriversApi@create_orderid');

		Route::post('/delete_order', 'DriversApi@delete_order');
		Route::post('/cancle', 'DriversApi@Cancle');

		Route::post('/driver-notification', 'DriversApi@driver_notification');

		Route::post('/change-password', 'UsersApi@change_password');
		Route::post('/location', 'UsersApi@get_location');
		Route::post('/message-us', 'UsersApi@message_us');
		// get nearest
		Route::post('/book-now', 'UsersApi@book_now');

		// source+destnation
		Route::post('/source-destination', 'UsersApi@source_destination');
		Route::get('/emergency', function () {
			return view('emergency');
		});

		Route::get('/policy', function () {
			return view('policy');
		});

		// get nearest delayed
		Route::post('/book-now-delayed', 'UsersApi@book_now_delayed');
		// delayed
		Route::post('/source-destination-delayed', 'UsersApi@source_destination_delay');
		// order trip now
		Route::post('/order_trip_now', 'UsersApi@order_trip_now');
		// get_type_option
		Route::post('/type-option', 'UsersApi@type_option');

		Route::post('/book-later', 'UsersApi@book_later');
		Route::post('/update-destination', 'UsersApi@update_destination');
		// review
		Route::post('/review', 'UsersApi@review');
		// contact
		Route::get('/contact', 'UsersApi@contact_us');
		// 	driver
		Route::post('/driver-trip', 'DriversApi@driver_trip');
		Route::post('/driver-status', 'DriversApi@driver_status');
		Route::post('/accept-trip', 'DriversApi@accept_trip');
		Route::post('/trip-start', 'DriversApi@trip_started');
		Route::post('/trip-waiting-for-payment', 'DriversApi@trip_ended_waiting_for_payment');
		Route::post('/trip-user-choosed-payment-method', 'DriversApi@user_choosed_payment_method');
		Route::post('/trip-end', 'DriversApi@trip_ended');

		//trip expense
		Route::post('/trip-expense', 'DriversApi@trip_expense');
		Route::post('/trip-payment', 'DriversApi@trip_payment');
		Route::get('/started-inside-trips', 'DriversApi@started_inside_trips');
		Route::post('/driver-wallet', 'DriversApi@driver_wallet');
		Route::post('/charge-wallet', 'DriversApi@charge_wallet');
		Route::post('/check-status', 'DriversApi@check_status');
		Route::post('/outcity_trip', 'DriversApi@Trip_OutCity');
		Route::post('/send-complaint', 'DriversApi@send_complaint');
		Route::post('/ride-history', 'UsersApi@ride_history');
		Route::post('/user-single-ride', 'UsersApi@user_single_ride_info');
		Route::post('/get-reviews', 'UsersApi@get_reviews');
		Route::post('/user-logout', 'UsersApi@user_logout');
		Route::post('/change-availability', 'DriversApi@change_availability');
		Route::post('/ride-requests', 'DriversApi@ride_requests');
		Route::post('/single-ride-request', 'DriversApi@single_ride_request');
		Route::post('/accept-ride-request', 'DriversApi@accept_ride_request');
		Route::post('/cancel-ride-request', 'DriversApi@cancel_ride_request');
		Route::post('/reject-ride-request', 'DriversApi@reject_ride_request');
		Route::post('/driver-rides', 'DriversApi@driver_rides');
		Route::post('/single-ride-info', 'DriversApi@single_ride_info');
		Route::post('/start-ride', 'DriversApi@start_ride');
		Route::post('/destination-reached', 'DriversApi@destination_reached');
		Route::post('/confirm-payment', 'DriversApi@confirm_payment');
		Route::post('/active-drivers', 'DriversApi@active_drivers');
		Route::post('update-fcm-token', 'UsersApi@update_fcm');
	});

	//For E-Payments Fatora
	Route::namespace('Api')->group(function () {

		Route::middleware(['throttle', 'auth:api'])->group(function () {
			Route::get('/get-payment-status/{paymentId}', 'FatoraPaymentController@getPaymentStatus');
			Route::post('/create-payment', 'FatoraPaymentController@createPayment');
			Route::post('/cancel-payment', 'FatoraPaymentController@cancelPayment');
		});
		Route::get('/payment-callbackURL/{merchant}', 'FatoraPaymentController@callback');
		Route::get('/payment-triggerURL/{merchant}', 'FatoraPaymentController@trigger');

		// Route::get('/payment-callbackURL/{test}', function ($test) {
		// 	$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");

		// 	$txt = $test;
		// 	fwrite($myfile, $txt);
		// 	$txt = "\n";
		// 	fwrite($myfile, $txt);
		// 	fclose($myfile);
		// 	return 'Hello World';
		// });

		// Route::get('/payment-triggerURL/{test}', function ($test) {
		// 	$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");

		// 	$txt = $test;
		// 	fwrite($myfile, $txt);
		// 	$txt = "\n";
		// 	fwrite($myfile, $txt);
		// 	fclose($myfile);
		// 	return 'Hello World';
		// });

	});

	Route::middleware('auth:api')->post('/user', function (Request $request) {
		return $request->user();
	});
});
