<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Model\VehicleTypeModel;
use App\Model\CarOptions;
use Illuminate\Http\Request;
use Validator;

class CarOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['types'] = VehicleTypeModel::where('isenable', '1')->get();
        $data['price_types'] = VehicleTypeModel::join('car_option', 'vehicle_types.id', 'car_option.type_id')
            ->orderby('car_option.type_id')
            ->get();


        return view("caroption.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'type_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'isenable' => 'required',
        ]);
        if ($request->isenable == '1') {
            $enable = '1';
        } else {
            $enable = '0';
        }
        $option = new CarOptions();
        $option->type_id = $request->get('type_id');
        $option->name = $request->get('name');
        $option->price = $request->get('price');
        $option->is_enable = $enable;
        $option->save();
        return redirect("admin/car_option");
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
        $data['type'] = CarOptions::find($id);

        return view("caroption.view_event", $data);
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

        $validation = Validator::make($request->all(), [

            'price' => 'required',
            'isenable' => 'required',
        ]);
        if ($request->isenable == '1') {
            $enable = '1';
        } else {
            $enable = '0';
        }
        $option = CarOptions::find($id);
        $option->price = $request->get('price');
        $option->name = $request->get('name');
        $option->is_enable = $enable;
        $option->save();
        return redirect("admin/car_option");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $option = CarOptions::find($id)->delete();
        
        return redirect("admin/car_option")->with('success', 'User is successfully deleted');;
    }
    public function bulk_delete(Request $request)
    {

        $options = CarOptions::whereIn('id', $request->ids)->get();
        foreach ($options as $option) {
          
            $option->delete();
        }
        return redirect('admin/car_option');
        return back();
    }
}
