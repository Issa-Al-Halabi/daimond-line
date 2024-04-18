<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Userpoints;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class UserpointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userpoints=Userpoints::all();
        return view('points.user_points',compact('userpoints'));
    }
    public function fetch_data(Request $request)
    {
        
        if ($request->ajax()) {
            $coupons = Userpoints::select('user_points.*');
            foreach ($coupons as $c) {
                # code...
                $user = User::find($c->user_id);
                if(isset($user)){
                    $c['fullname'] = $user->first_name;  
                   
                }
                
            }
            return DataTables::eloquent($coupons)
                ->addColumn('check', function ($cat) {
                    $tag = '';
                    // if ($user->user_type = "driver") {
                    $tag = '<input type="checkbox" name="ids[]" value="' . $cat->id . '" class="checkbox" id="chk' . $cat->id . '" onclick=\'checkcheckbox();\'>';
                    // }
                    return $tag;
                })
                ->addColumn('action', function ($cat) {
                    return view('points.list-actions', ['row' => $cat]);
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
    }
}
