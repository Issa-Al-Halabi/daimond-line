<?php

namespace App\Http\Middleware;

use App\Model\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {
       $response = $next($request);
        $requestMethod = $request->method(); // GET, POST, PUT, DELETE
        $requestUrl = $request->fullUrl(); // Full URL including query parameters
        $requestData = $request->all(); // All input data
        $requestHeaders = $request->headers->all(); // All request headers
        $user = Auth::user();
        $requestInfo = [
            'method' => $requestMethod,
            'url' => $requestUrl,
            'data' => $requestData,
            'headers' => $requestHeaders,
            'user' => $user,
           
        ];
       
        // Log activity if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // if ($request->is('Api/login')) {
            //     $message = "User {$user->name} has logged in, path: {$request->path()}";
            // } elseif ($request->is('Api/join_us')) {
            //     $message = "New join us request from a driver, path: {$request->path()}";
            // } elseif ($request->is('Api/edit-user-profile')) {
            //     $message = "User {$user->name} has updated his profile, path: {$request->path()}";
            // }
            if($request->is('admin/users') && $request->isMethod('post')){
                $message = "new User has been created , path: {$request->path()}";
            }
            if($request->is('admin/users') && $request->isMethod('post') && $request->has('id')){
                $message = "User has been updated , path: {$request->path()}";
            }
            if($request->is('admin/drivers') && $request->isMethod('post')){
                $message = "new driver has been created , path: {$request->path()}";
            }
            if($request->is('admin/drivers') && $request->isMethod('post') && $request->has('id')){
                $message = "driver has been updated , path: {$request->path()}";
            }
            if($request->is('admin/delete-drivers') && $request->isMethod('post')){
                $message = "drivers has been deleted , path: {$request->path()}";
            }
            if($request->is('admin/enable')){
                $message = "driver status has been updated (enabled) , path: {$request->path()}";
            }
            if($request->is('admin/disable')){
                $message = "driver status has been updated (disable) , path: {$request->path()}";
            }
          
            if (isset($message) || isset($requestInfo)) {
                if(!isset($message)){
                    $message = null;
                }
               // ActivityLog::create([
              //      'user_id' => $user->id,
               //     'message' => $message,
             //       'request_body' => json_encode($requestInfo),
             //   ]);
            }
        }

       return $response;
    }
}
