<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $points = Point::all();
        $this->add_points(1,100);
        return view('points.index',compact('points'));
    }
    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $points = Point::select('points.*');
            return DataTables::eloquent($points)
               ->addIndexColumn()
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
        return view('points.create');
        
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
       
        $point = Point::create([
            'trip_type' => $request->trip_type,
            'points'    => $request->point,
            'qty'    => $request->qty
        ]);
        return redirect()->route('points');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function show(Point $point)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $point = Point::whereid($id)->first();
        return view('points.edit',compact('point'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $point = Point::find($id);
        $point->trip_type = $request->trip_type;
        $point->points = $request->point;
        $point->qty = $request->qty;
     
        $point->save();
        return redirect()->route('points');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $point = Point::findOrFail($id);
        $point->delete();

        return redirect()->route('points');
    }
    public function add_points($user_id,$points)
    {
        # code...
        $user_points = DB::table('user_points')->where('user_id',$user_id)->first();
        if(isset($user_points)){
            $total_points = $user_points->points+$points;
            $user_points->points = $total_points;
        }
        else{
            DB::table('user_points')->insert([

                'user_id'=>$user_id,
                'points'=>$points
            ]);
        }
    }
}
