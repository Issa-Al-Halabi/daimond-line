<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\ReviewModel;

class ReviewRatings extends Controller
{
    public function index()
    {
        $data['reviews'] = ReviewModel::select('bookings.category_id','bookings.id','bookings.request_type','bookings.date','bookings.created_at','reviews.id as id','reviews.booking_id','reviews.ratings', 'reviews.review_text','reviews.type', 'reviews.driver_id','bookings.user_id','bookings.driver_id')
            
            ->leftjoin("bookings", 'bookings.id', 'reviews.booking_id')
            ->orderBy('reviews.id', 'desc')->get();
        return view('reviews', $data);
      
    }
  public function delete($id){
    
    ReviewModel::where('id',$id)->delete();
    return back();
  }
}
