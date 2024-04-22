<?php

namespace App\Http\Controllers\Admin;

use App\Enums\WalletEnums;
use App\Http\Controllers\Controller;

use App\Model\VehicleTypeModel;
use App\Http\Traits\SendNotification;
use App\Model\CarOptions;
use App\Model\User;
use App\Model\WalletModel;
use App\Model\Value;
use Illuminate\Http\Request;
use Validator;
use DB;


class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    //-----------------------------------------------
     use SendNotification;
  
     public function store_admin_fare(Request $request)
    {
        // "minimum_wallet": "7500",
        // "external_fare": "15",
        // "internal_fare": "10",
        // "admin_wallet": "38439881",

        if($request->isMethod("GET")) {
            $val = Value::get();
            $walletEnums = WalletEnums::class;
            return view('wallet.admin-fare-create',compact('val','walletEnums'));
        }
        if($request->submit) {

            $rules = array(
                WalletEnums::getName(WalletEnums::admin_wallet)   => 'required',
                WalletEnums::getName(WalletEnums::external_fare)  => 'required|numeric|min:0|max:100',
                WalletEnums::getName(WalletEnums::internal_fare)  => 'required|numeric|min:0|max:100',
                WalletEnums::getName(WalletEnums::minimum_wallet) => 'required',
                WalletEnums::getName(WalletEnums::admin_fare) => 'required|numeric|min:0|max:100',
            );

            $attributes = array(
                WalletEnums::getName(WalletEnums::admin_wallet) => WalletEnums::admin_wallet ,
                WalletEnums::getName(WalletEnums::external_fare) => WalletEnums::external_fare ,
                WalletEnums::getName(WalletEnums::internal_fare) => WalletEnums::internal_fare ,
                WalletEnums::getName(WalletEnums::minimum_wallet) => WalletEnums::minimum_wallet ,
                WalletEnums::getName(WalletEnums::admin_fare) => WalletEnums::admin_fare ,
            );

            $validator = Validator::make($request->all(), $rules, [], $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            Value::where(['name' => WalletEnums::getName(WalletEnums::admin_wallet)])->update(['value' => $request->admin_wallet]); 
            Value::where(['name' => WalletEnums::getName(WalletEnums::external_fare) ])->update(['value' => $request->external_fare]); 
            Value::where(['name' => WalletEnums::getName(WalletEnums::internal_fare) ])->update(['value' => $request->internal_fare]);
            Value::where(['name' => WalletEnums::getName(WalletEnums::minimum_wallet) ])->update(['value' => $request->minimum_wallet]);
            Value::where(['name' => WalletEnums::getName(WalletEnums::admin_fare) ])->update(['value' => $request->admin_fare]);

        }
        return redirect('admin/admin-fare/create');
    }
    //-----------------------------------------------
  
 
    
    
    
     public function Transfer_approval(Request $request)
    {
       
      
        $wallet = WalletModel::find($request->wallet_id);
        $wallet->status = $request->status;
        $wallet->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
     public function store_time_out(Request $request)
    {
      
        if($request->isMethod("GET")) {
            $val = Value::where('name','time_out')->first();
          
            return view('wallet.time_out',compact('val'));
        }
        if($request->submit) {
          
    
            Value::where(['name' => 'time_out'])->update(['value' => $request->time_out]); 
           
        }
        return redirect('admin/time_out/create');
    }
   //------------------------------------------------
    public function index()
    {

        $data['wallets'] = WalletModel::join('users', 'users.id', 'wallet.driver_id')
            ->select('wallet.*', 'users.first_name', 'users.last_name')
            ->get();
      
      

     
        return view("wallet.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['drivers'] = User::where('user_type', 'driver')->orwhere('user_type', 'external_driver')->where('is_active', 'active')->get();


        return view('wallet.create', $data);
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
            'amount' => 'required',
            'driver_id' => 'required',

        ]);
        

        $wallet = new WalletModel();
        $wallet->amount = $request->get('amount');
        $wallet->driver_id = $request->get('driver_id');

        $wallet->save();
      
        return redirect("admin/wallet");
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
        $data['wallet'] = WalletModel::find($id);

        return view("wallet.view_event", $data);
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

            'new_amount' => 'required',

        ]);
      
         $minimum_wallet=Value::where('name','minimum_wallet')->first();
      
        $wallet = WalletModel::find($id);
        // return $wallet;
        $wallet->amount = $request->get('new_amount')+ $wallet->amount;
        $wallet->save();
      
      if( $wallet->amount >  $minimum_wallet->value)
      {
        
        $driver=DB::table('users')->where('id',$wallet->driver_id)->update(['in_service'=>'off']);
                
        $driver_token=DB::table('users')->where('id',$wallet->driver_id)->first();
    
        $this->send_notification($driver_token->device_token,'تم شحن رصيدك,يمكنك تلقي الطلبات ','أهلا بك في دايموند لاين');
     
        
      }
      else
      {
        $driver=DB::table('users')->where('id',$wallet->driver_id)->update(['in_service'=>'out of service']);
      }
      
      
      
        return redirect("admin/wallet");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $option = WalletModel::find($id)->delete();

        return redirect("admin/wallet");
    }
    public function bulk_delete(Request $request)
    {

        $wallet = WalletModel::whereIn('id', $request->ids)->get();
        foreach ($wallet as $w) {
          
            $w->delete();
        }
       
        return back();
    }
}
