
@extends('layouts.app', ['activePage' => 'youtube', 'titlePage' => __('Youtubes Management')])

@section('content')
    <div class="content">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">{{ __('Youtubes') }}</h4>
                                <p class="card-category"> {{ __('ระบบจัดการยูทูป') }}</p>
                            </div>

                            <div class="card-body">



<div class="row" id="addBlog">
    <div class="col-md-12">

        <form method="POST" action="{{url('youtube/'.$youtube->id)}}" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">ชื่อเรื่อง</label>
                <div class="col-sm-7">
                    <div class="form-group">
                    <input class="form-control" name="title" id="title" type="text" value="{{$youtube->title}}" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">คำบรรยาย</label>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control" name="description" id="description" type="text" value="{{$youtube->description}}" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">Url</label>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control" name="clip" id="clip" type="text" value="{{$youtube->clip}}" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">รูปภาพ</label>
                <div class="col-sm-7">
                    
                   @if($youtube->image)
                   <img src="{{url('imgs/'.$youtube->image)}}" alt="{{$youtube->title}}" width="250px">
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
                    <a class="btn btn-warning" style="text-decoration:none;" href="{{url('youtube/')}}"> &nbsp; &nbsp; Cancel &nbsp; &nbsp; </a> &nbsp;
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

@endsection