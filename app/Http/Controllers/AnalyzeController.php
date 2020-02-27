<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Analyze;
use Auth;

class AnalyzeController extends Controller
{
    public function index(Analyze $model)
    {
        if(Auth::user()->level < 100) abort(403, 'Unauthorized action.');
        return view('analyze.index', ['analyze' => $model->orderBy('id','desc')->paginate(15)]);
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


    public function store(Request $request)
    {
        if(Analyze::where('title', $request->input('title'))->first()) {
            return redirect()->back()->withInput()->with('error', 'Error! Title has data already exists.');
        }   
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'images' => 'image|nullable|max:2084',
        ]);
        if($request->hasFile('image')) $fileNameToStore=uploadImage($request->file('image'),'imgs');
        else $fileNameToStore='';
        
        $as = new Analyze;
        $as->uid = Auth::user()->id;
        $as->title = $request->input('title');
        $as->description = $request->input('description');

        if ($request->input('content')) $as->content = $request->input('content');
        if ($as->slug != Slug($as->title)) $as->slug = Slug($as->title);
        $as->hot = ($request->input('hot') == true) ? '1':'0';
        $as->created_at = date('Y-m-d H:i:s');
        $as->updated_at = date('Y-m-d H:i:s');
        $as->visit = ($as->visit == '')?0:$as->visit;
        $as->clip = ($request->input('clip')!=null) ? getYoutube($request->input('clip')):null;
        $as->cid = ($request->input('cid')!=null) ? $request->input('cid'):'0';
        if ($fileNameToStore) $as->image = $fileNameToStore;
        $as->save();
        return redirect('/analyze')->with('success','Success! New Analyze has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Analyze  $analyze
     * @return \Illuminate\Http\Response
     */
    public function show(Analyze $analyze)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Analyze  $analyze
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('analyze.edit', ['analyze' => Analyze::where('id',$id)->first()]);
    }

    public function change($switch, $id) {
        $as = Analyze::find($id);
        if ($switch == 'status') $as->status = ($as->status != 1) ? '1':'0';
        if ($switch == 'hot') $as->hot = ($as->hot != 1) ? '1':'0';
        $as->save();
        return redirect('/analyze')->with('success', ' Success! Analyze post ID: '.$id.' has been Updated.');
    } 

    public function update(Request $request, $id)
    {
        if ($request->switch) return $this->change($request->switch,$id);
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|nullable|max:2084',
        ]);

        if($request->hasFile('image')) $fileNameToStore=uploadImage($request->file('image'),'imgs');

        $as = Analyze::find($id);
        $as->title = $request->input('title');
        $as->description = $request->input('description');
        $as->content = $request->input('content');
        if ($as->slug != Slug($as->title)) $as->slug = Slug($as->title);
        $as->hot = ($request->input('hot') == true) ? '1':'0';
        $as->updated_at = date('Y-m-d H:i:s');
        $as->visit = ($as->visit == '')?0:$as->visit;
        $as->clip = ($request->input('clip')!=null) ? getYoutube($request->input('clip')):null;
        
        if (isset($fileNameToStore)) {
            if (!empty($as->image)) {
                $cover_path = public_path().'/imgs/'.$as->image;
                if (is_file($cover_path)) { unlink($cover_path); }            
            }
            $as->image = $fileNameToStore;
        }
        $as->save();
        return redirect('/analyze')->with('success','Success! Analyze id #'.$id.' has been updated.');
    }

    public function destroy($id)
    {
        $an = Analyze::find($id);
        $cover_path  = str_replace('/','\\',public_path('imgs/'.$an->image));
        if (file_exists($cover_path)) unlink($cover_path);
        $an->delete();
        return redirect()->back()->with('success','Analyze post ID:'.$id.' has been Removed.');  
    }
}
