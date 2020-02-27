<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Youtube;
use Image;
use Auth;

class YoutubeController extends Controller
{

    public function index(Youtube $model) {
        if(Auth::user()->level < 100) abort(403, 'Unauthorized action.');
        return view('youtube.index', ['youtubes' => $model->orderBy('id','desc')->paginate(20)]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request) {
  
        if(Youtube::where('title', $request->input('title'))->first()) {
            return redirect()->back()->withInput()->with('error', 'Error! Title has data already exists.');
        }   
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'images' => 'image|nullable|max:2084',
        ]);
        if($request->hasFile('image')) $fileNameToStore=uploadImage($request->file('image'),'imgs');

        $ys = new Youtube;
        $ys->uid = Auth::user()->id;
        $ys->title = $request->input('title');
        $ys->description = $request->input('description');

        if ($ys->slug != Slug($ys->title)) $ys->slug = Slug($ys->title);
        $ys->hot = ($request->input('hot') == true) ? '1':'0';
        $ys->created_at = date('Y-m-d H:i:s');
        $ys->updated_at = date('Y-m-d H:i:s');
        $ys->uid = auth()->user()->id;
        $ys->visit = ($ys->visit == '')?0:$ys->visit;
        $ys->clip = ($request->input('clip')!=null) ? getYoutube($request->input('clip')):null;
        $ys->cid = ($request->input('cid')!=null)?$request->input('cid'):'0';
        if ($request->hasFile('image')!=false) $ys->image = $fileNameToStore;
        $ys->save();
        return redirect('/youtube')->with('success','Success! New Youtube has been Created.');
    }


    public function show(Youtube $youtube) {
        //
    }


    public function edit($id) {
        return view('youtube.edit', ['youtube' => Youtube::where('id',$id)->first()]);
    }

    public function change($switch, $id) {
        $y = Youtube::find($id);
        if ($switch == 'status') $y->status = ($y->status != 1) ? '1':'0';
        if ($switch == 'hot') $y->hot = ($y->hot != 1) ? '1':'0';
        $y->save();
        return redirect('/youtube')->with('success', ' Success! Youtube post ID: '.$id.' has been Updated.');
    }    

    public function update(Request $request, $id) {
        if ($request->switch) return $this->change($request->switch,$id);
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|nullable|max:2084',
        ]);
        if($request->hasFile('image')) $fileNameToStore=uploadImage($request->file('image'),'imgs');

        $ys = Youtube::find($id);
        $ys->title = $request->input('title');
        $ys->description = $request->input('description');
        if ($ys->slug != Slug($ys->title)) $ys->slug = Slug($ys->title);
        $ys->hot = ($request->input('hot') == true) ? '1':'0';
        $ys->updated_at = date('Y-m-d H:i:s');
        $ys->visit = ($ys->visit == '')?0:$ys->visit;
        $ys->clip = ($request->input('clip')!=null) ? getYoutube($request->input('clip')):null;
        if (isset($fileNameToStore)) {
            if (!empty($ys->image)) {
                // $cover_path  = str_replace('/','\\',public_path('imgs/'.$ys->image));
                // if (file_exists($cover_path)) unlink($cover_path);        

                $cover_path = public_path().'/imgs/'.$ys->image;
                if (is_file($cover_path)) { unlink($cover_path); }            
                       
            }
            else $ys->image = $fileNameToStore;
        }
        $ys->save();
        return redirect('/youtube')->with('success','Success! Youtube post id #'.$id.' has been updated.');
    }


    public function destroy($id)
    {
        $y = Youtube::find($id);
        $cover_path  = str_replace('/','\\',public_path('imgs/'.$y->image));
        if (file_exists($cover_path)) unlink($cover_path);
        $y->delete();
        return redirect()->back()->with('success','Youtube post ID:'.$id.' has been Removed.');  
    }
}
