<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\ContactModel;
use Validator;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['contact'] = ContactModel::get();
        return view('contact.create', $data);
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
            'phone' => 'required',
            'email' => 'required|email',
        ]);
        $contact = ContactModel::create([
            'phone' => $request->phone,
            'email' => $request->email,
        ]);
        return redirect()->route("contact.create");
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
        $data['contact'] = ContactModel::find($id);

        return view("contact.view_event", $data);
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
        $contact = ContactModel::find($id);
        $contact->update([
            'phone' => $request->phone,
            'email' => $request->email,
        ]);
        return redirect()->route("contact.create");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ContactModel::find($id)->delete();
        return redirect()->route("contact.create");
    }
    public function bulk_delete(Request $request)
    {
       
        $users = ContactModel::whereIn('id', $request->ids)->delete();
       
       
        return back();
    }
}
