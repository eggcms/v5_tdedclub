<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Zean;
use Auth;

class ZeanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Zean $model)
    {
        return view('zean.index', ['zeans' => $model->orderBy('id','desc')->paginate(20)]);
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
        ]);
        if(Youtube::where('team1', $request->input('team1'))->where('team2', $request->input('team2'))->first()) {       
            return redirect()->back()->withInput()->with('error', 'Error! Title has data already exists.');
        } 
        $zs = new Zean;
        $zs->uid = Auth::user()->id;
        $zs->team1 = $request->input('team1');
        $zs->team2 = $request->input('team2');
        $zs->content = $request->input('content');
        $zs->bet = $request->input('bet');
        $zs->prevision = $request->input('prevision');
        $zs->save();
        return redirect('/zean')->with('success','Success! New ZeanReview has been Created.');
    }


    public function show(Zean $zean)
    {
        //
    }

    public function edit($id) {
        return view('zean.edit', ['zean' => Zean::where('id',$id)->first()]);
    }

    public function change($switch, $id) {
        $y = Zean::find($id);
        if ($switch == 'status') $y->status = ($y->status != 1) ? '1':'0';
        if ($switch == 'hot') $y->hot = ($y->hot != 1) ? '1':'0';
        $y->save();
        return redirect('/zean')->with('success', ' Success! ZeanReview post ID: '.$id.' has been Updated.');
    } 

    public function update(Request $request, $id)
    {
        if ($request->switch) return $this->change($request->switch,$id);
        $validatedData = $request->validate([
            'team1' => 'required',
            'team2' => 'required',
        ]);
        $zs = Zean::find($id);
        $zs->uid = Auth::user()->id;
        $zs->team1 = $request->input('team1');
        $zs->team2 = $request->input('team2');
        $zs->content = $request->input('content');
        $zs->bet = $request->input('bet');
        $zs->prevision = $request->input('prevision');
        $zs->updated_at = date('Y-m-d H:i:s');
        $zs->save();
        return redirect('/zean')->with('success','Success! ZeanReview post id #'.$id.' has been updated.');
    }

    public function destroy($id)
    {
        $y = Zean::find($id);
        $cover_path  = str_replace('/','\\',public_path('imgs/'.$y->image));
        if (file_exists($cover_path)) unlink($cover_path);
        $y->delete();
        return redirect()->back()->with('success','ZeanReview post ID:'.$id.' has been Removed.');  
    }
}
