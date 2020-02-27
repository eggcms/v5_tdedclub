<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Setup;
use App\User;
use Auth;
class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Setup $model)
    {
        if(Auth::user()->level >= 100) {
            return view('setup.index', ['setups' => $model->orderBy('id','asc')->paginate(20)]);
        }
        else abort(403, 'Unauthorized action.');
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
        $validatedData = $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);

        $su = new Setup;
        $su->key = $request->input('key');
        $su->value = $request->input('value');
        $su->save();
        return redirect('/setup')->with('success','Success! data setup.');
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
        $y = Setup::find($id);
        $y->delete();
        return redirect()->back()->with('error','Data ID:'.$id.' has been Removed.');  
    }
}
