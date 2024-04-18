<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Coupon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $coupons = Coupon::all();
        return view('coupons.index',compact('coupons'));
    }
    public function fetch_data(Request $request)
    {
        
        if ($request->ajax()) {
            $coupons = Coupon::select('coupons.*');
            return DataTables::eloquent($coupons)
               ->addIndexColumn()
                ->addColumn('check', function ($cat) {
                    $tag = '';
                    // if ($user->user_type = "driver") {
                    $tag = '<input type="checkbox" name="ids[]" value="' . $cat->id . '" class="checkbox" id="chk' . $cat->id . '" onclick=\'checkcheckbox();\'>';
                    // }
                    return $tag;
                })
                ->addColumn('action', function ($cat) {
                    return view('coupons.list-actions', ['row' => $cat]);
                })

                ->rawColumns(['action', 'check'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $coupon = Coupon::create([
            'title' => $request->title,
            'code'    => $request->code,
            'discount'    => $request->discount,
            'limit'    => $request->discount,
            'start_date'    => $request->start_date,
            'expire_date'    => $request->expire_date,
        ]);
        return redirect()->route('coupons');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $coupon = Coupon::whereid($id)->first();
        return view('coupons.edit',compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $coupon = Coupon::find($id);
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->discount = $request->discount;
        $coupon->limit = $request->limit;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
     
        $coupon->save();
        return redirect()->route('coupons');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('coupons');
    }
}
