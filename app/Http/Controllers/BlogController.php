<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Blog;
use Image;
use Auth;

class BlogController extends Controller
{
    public function index(Blog $model)
    {
        if(Auth::user()->level < 100) abort(403, 'Unauthorized action.');
        $model->orderBy('id','desc')->paginate(20);
        return view('blogs.index', ['blogs' => $model->orderBy('id','desc')->paginate(20)]);
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
        if(Blog::where('title', $request->input('title'))->first()) {
            return redirect()->back()->withInput()->with('error', 'Error! Title has data already exists.');
        }   
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|nullable|max:2084',
        ]);
        if($request->hasFile('image')) { $fileNameToStore=uploadImage($request->file('image'),'imgs'); }
        else { $fileNameToStore=''; }

        $bs = new Blog;
        $bs->uid = Auth::user()->id;
        $bs->title = $request->input('title');
        $bs->description = $request->input('description');
        if ($request->input('content')) $bs->content = $request->input('content');
        if ($bs->slug != Slug($bs->title)) $bs->slug = Slug($bs->title);
        $bs->hot = ($request->input('hot') == true) ? '1':'0';
        $bs->created_at = date('Y-m-d H:i:s');
        $bs->updated_at = date('Y-m-d H:i:s');
        $bs->uid = auth()->user()->id;
        $bs->visit = ($bs->visit == '')?0:$bs->visit;
        $bs->clip = ($request->input('clip')!=null) ? getYoutube($request->input('clip')):null;
        $bs->cid = ($request->input('cid')!=null) ? $request->input('cid'):'0';
        if ($fileNameToStore) $bs->image = $fileNameToStore;
        $bs->save();
        return redirect('/blogs')->with('success','Success! New Blog has been Created.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id) {
        return view('blogs.edit', ['blog' => Blog::where('id',$id)->first()]);
    }

    public function change($switch, $id) {
        $y = Blog::find($id);
        if ($switch == 'status') $y->status = ($y->status != 1) ? '1':'0';
        if ($switch == 'hot') $y->hot = ($y->hot != 1) ? '1':'0';
        $y->save();
        return redirect('/blogs')->with('success', ' Success! Blogs post ID: '.$id.' has been Updated.');
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
        $bs = Blog::find($id);
        $bs->title = $request->input('title');
        $bs->description = $request->input('description');
        $bs->content = $request->input('content');
        if ($bs->slug != Slug($bs->title)) $bs->slug = Slug($bs->title);
        $bs->hot = ($request->input('hot') == true) ? '1':'0';
        $bs->updated_at = date('Y-m-d H:i:s');
        $bs->visit = ($bs->visit == '')?0:$bs->visit;
        $bs->clip = ($request->input('clip')!=null) ? getYoutube($request->input('clip')):null;
        if (isset($fileNameToStore)) {
            if (!empty($bs->image)) {
                $cover_path = public_path().'/imgs/'.$bs->image;
                if (is_file($cover_path)) { unlink($cover_path); }            
            }
            $bs->image = $fileNameToStore;
        }
        $bs->save();
        return redirect('/blogs')->with('success','Success! Blog id #'.$id.' has been updated.');
    }


    public function destroy($id)
    {
        $y = Blog::find($id);
        $cover_path  = str_replace('/','\\',public_path('imgs/'.$y->image));
        if (file_exists($cover_path)) unlink($cover_path);
        $y->delete();
        return redirect()->back()->with('success','Blog post ID:'.$id.' has been Removed.');  
    }
}
