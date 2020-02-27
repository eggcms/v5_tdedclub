
@extends('layouts.app', ['activePage' => 'zean', 'titlePage' => __('Youtubes Management')])

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
            <button id="cancelButton" class="btn btn-warning"><i class="far fa-window-close"></i> &nbsp; ยกเลิก &nbsp; </button>
            <form method="POST" action="{{url('zean/'.$zean->id)}}" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ทีมต่อ</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="team1" id="team1" type="text" value="{{$zean->team1}}" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ทีมรอง</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="team2" id="team2" type="text" value="{{$zean->team2}}" required />
                    </div>
                </div>
            </div>            
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">วิเคราะห์</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="content" id="content" type="text" value="{{$zean->content}}" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ราคาต่อรอง</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="bet" id="bet" type="text" value="{{$zean->bet}}" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ฟันธง</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="prevision" id="prevision" type="text" value="{{$zean->prevision}}" required />
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="card-footer ml-auto mr-auto">
                    <input type="hidden" name="_method" value="PUT">
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