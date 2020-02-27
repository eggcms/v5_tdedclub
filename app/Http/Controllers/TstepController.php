<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Tstep;
use App\User;
use Auth;
class TstepController extends Controller
{
    public function __construct()
    {
        
    }
    public function index()
    {
        if(Auth::user()->level == 1 || Auth::user()->level >= 100) {
        // if ($t->created_at < date('Y-m-d 06:00:00')) echo $t->created_at.' / '.date('Y-m-d 06:00:00');
            $us = new User;
            $us = $us->where('level','1')->get();
            return view('tstep.index', ['users' => $us]);
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
            'team1' => 'required',
            'team2' => 'required',
            'team3' => 'required',
        ]);
        $tstep = Tstep::where('uid', $request->input('uid'))->first();
        if (count((array)$tstep)) {
            $tstep->delete();
            $tstep->uid = $request->input('uid');
            $tstep->team1 = $request->input('team1');
            $tstep->team2 = $request->input('team2');
            $tstep->team3 = $request->input('team3');
            $ts->team1w = $request->input('team1w');
            $ts->team2w = $request->input('team2w');
            $ts->team3w = $request->input('team3w');
        }
        else {
            $tstep = new Tstep;
            $tstep->uid = $request->input('uid');
            $tstep->team1 = $request->input('team1');
            $tstep->team2 = $request->input('team2');
            $tstep->team3 = $request->input('team3');
            $ts->team1w = $request->input('team1w');
            $ts->team2w = $request->input('team2w');
            $ts->team3w = $request->input('team3w');
        }
        $tstep->save();
        return redirect('/tstep')->with('success','Success! New TededStep has been Created.');

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
        return view('tstep.edit', ['tstep' => Tstep::where('id',$id)->first()]);
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
//dd($request);
        $validatedData = $request->validate([
            'team1' => 'required',
            'team2' => 'required',
            'team3' => 'required',
        ]);
        if($request->hasFile('image')) $fileNameToStore=uploadImage($request->file('image'),'imgs');

        $ts = Tstep::find($id);
        //dd($ts);
        $ts->uid = $ts->uid;
        $ts->team1 = $request->input('team1');
        $ts->team2 = $request->input('team2');
        $ts->team3 = $request->input('team3');
        $ts->team1w = $request->input('team1w');
        $ts->team2w = $request->input('team2w');
        $ts->team3w = $request->input('team3w');
        $ts->save();
        return redirect('/tstep')->with('success','Success! TededStep has been updated.');
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
