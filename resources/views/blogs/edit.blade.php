
@extends('layouts.app', ['activePage' => 'blogs', 'titlePage' => __('Blogs Management')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">{{ __('Blogs') }}</h4>
                            <p class="card-category"> {{ __('ระบบจัดการข่าว') }}</p>
                        </div>

                        <div class="card-body">
                            <div class="row" id="addBlog">
                                <div class="col-md-12">

                                    <form method="POST" action="{{url('blogs/'.$blog->id)}}" class="form-horizontal" enctype="multipart/form-data">
                                        <div class="row">
                                            <label class="col-sm-1 col-form-label mt-2" for="title">ชื่อเรื่อง</label>
                                            <div class="col-sm-7">
                                                <div class="form-group">
                                                <input class="form-control" name="title" id="title" type="text" value="{{$blog->title}}" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-1 col-form-label mt-2" for="title">คำบรรยาย</label>
                                            <div class="col-sm-7">
                                                <div class="form-group">
                                                    <input class="form-control" name="description" id="description" type="text" value="{{$blog->description}}" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-1 col-form-label mt-2" for="title">เนื้อเรื่อง</label>
                                            <div class="col-sm-7">
                                                <div class="form-group">
                                                    <textarea class="form-control" name="content" id="ckeditor" required="" oninvalid="this.setCustomValidity('Content is require.')"
                                                    oninput="setCustomValidity('')">{!!$blog->content!!}</textarea>            
                                                </div>
                                            </div>
                                        </div>        
                                        <div class="row">
                                            <label class="col-sm-1 col-form-label mt-2" for="title">ยูทูป</label>
                                            <div class="col-sm-7">
                                                <div class="form-group">
                                                    <input class="form-control" name="clip" id="clip" type="text" value="{{$blog->clip}}" />
                                                </div>
                                            </div>
                                        </div>                
                                        <div class="row">
                                            <label class="col-sm-1 col-form-label mt-2" for="title">รูปภาพ</label>
                                            <div class="col-sm-7">    
                                                @if($blog->image) 
                                                <div style="display: block; height: 150px; width: 250px;">
                                                    <img src="{{url('imgs/'.$blog->image)}}" alt="{{$blog->title}}" style="height: 100%; width: 100%; object-fit: cover;">
                                                </div>
                                                @endif
                                                <div class="custom-file mt-3">
                                                    <input type="file" name="image" style="cursor: pointer;" class="custom-file-input" id="customFile">
                                                    <label class="custom-file-label" for="customFile"><u>เปลี่ยนภาพอัพโหลด</u></label>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="_method" value="PUT">
                                        <div class="row">
                                            <div class="card-footer ml-auto mr-auto">
                                                <a class="btn btn-warning" style="text-decoration:none;" href="{{url('blogs/')}}"> &nbsp; &nbsp; Cancel &nbsp; &nbsp; </a> &nbsp;
                                                <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                                            </div>
                                        </div>
                                        @csrf
                                    </form>
                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

    <script type="text/javascript">

    CKEDITOR.replace('ckeditor', {
       filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
       filebrowserUploadMethod: 'form',
       height: 400,
       toolbarGroups: [
        {
          "name": "styles",
          "groups": ["styles"]
        },
        {
          "name": "basicstyles",
          "groups": ["basicstyles"]
        },
        {
          "name": "links",
          "groups": ["links"]
        },
        {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        },
        {
          "name": "insert",
          "groups": ["insert"]
        }
      ],
       removeButtons: 'Subscript,Superscript,Anchor,Specialchar'
    });
 </script>  
@endsection