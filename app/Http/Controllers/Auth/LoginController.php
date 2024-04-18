<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Auth;

use App;
use App\Http\Controllers\Controller;
use Hyvikk;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;


    // use AuthenticatesUsers;


    // protected function authenticated(Request $request, $user)
    // {
    // if ( $user->isAdmin() ) {// do your magic here
    //     return redirect()->route('dashboard');
    // }

    //  return redirect('/home');
    // } 
    
    protected function authenticated($request, $user)
{
       return redirect('admin/users');
      
      
      //return  redirect()->route('/admin');
}



    // protected $redirectTo = 'admin/users';
    
    //  protected function redirectTo()
    // {
    //     // return '/admin/users';
    //     return redirect('admin/users');
    // }

    public function __construct()
    {
        App::setLocale(Hyvikk::get('language'));
        $this->middleware('guest', ['except' => 'logout']);
    }
}
