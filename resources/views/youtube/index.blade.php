
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

                                @if(count($errors)>0)
                                    @foreach($errors->all() as $error)
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="alert alert-danger">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <i class="material-icons">close</i>
                                                </button>
                                                <strong>Oh snap!!</strong> &nbsp; &nbsp;<span>{{$error}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                @endif
                                @if(session('success'))

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="alert alert-warning">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <strong>Well done! </strong> &nbsp; &nbsp; <span>{{session('success')}}</span>
                                        </div>
                                    </div>
                                </div>

                                @endif
                                @if(session('error'))

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <strong>Oh snap!!</strong> &nbsp; &nbsp;<span>{{session('error')}}</span>
                                        </div>
                                    </div>
                                </div>
    
                                @endif


<script>
window.onload = function() {
    if (window.jQuery) {  
        bsCustomFileInput.init();
        $("#addBlog").hide();
        $('#addButton').click(function(){
            $("#addBlog").show();
            $("#listBlog").hide();
        });
        $('#cancelButton').click(function(){
            $("#addBlog").hide();
            $("#listBlog").show();
        });        
    } 
}
</script>


                                            
<div class="row" id="listBlog">{{-- content list --}}
    <div class="col-sm-12">
        <button id="addButton" class="btn btn-success"><i class="far fa-plus-square"></i> เพิ่มคลิปใหม่</button>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class=" text-primary">
                    <th>ไอดี</th>
                    <th>ชื่อคลิปวิดีโอ</th>
                    <th>โพส์ตโดย</th>
                    <th class=" text-center">ออนไลน์</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody>
                @if(!count($youtubes))
        
                <tr>
                    <td colspan="5" class="text-center">
                    ยังไม่มีข้อมูลในฐานข้อมูล.
                    </td>
                </tr>
                @else                    
                    @foreach($youtubes as $y) 
                    <tr>
                        <td>{{$y->id}}</td>
                        <td> 
                            <a href="{{url('youtube/'.$y->id.'/edit')}}" rel="tooltip" title="แก้ไข ( id.{{$y->id}} )">{{$y->title}}</a>
                        </td>
                        <td>{{creator($y->uid)}}</td>
                        <td class="td-actions text-center">
                            @php
                            if($y->status == 1) { $status='ออนไลน์'; $scolor='danger'; }
                            else { $status='ออฟไลน์'; $scolor='grey'; }
                            @endphp
                            <form action="/youtube/{{$y->id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT" />
                                <input type="hidden" name="switch" value="status" />
                                <button type="submit" rel="tooltip" title="{{ $status }}" class="btn btn-link">
                                    <i class="fas fa-rss text-{{ $scolor }}"></i>
                                </button>
                                @csrf
                            </form>                            
                        </td> 
                        <td class="td-actions text-right">
                        <form action="/youtube/{{$y->id}}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" rel="tooltip" title="Remove" class="btn btn-outline-danger btn-link btn-sm p-0" onclick="return confirm('ลบโพส์ต ID #{{$y->id}} แน่ใจ?');">
                                <i class="material-icons">close</i>
                            </button>
                            @csrf
                        </form>

                        </td>
                    </tr>

                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="float-right mt-3">{{ $youtubes->links() }}</div>
        </div>        
    </div>
</div>{{-- content list --}}


<div class="row" id="addBlog" style="display:none;">
    <div class="col-md-12">
        <button id="cancelButton" class="btn btn-warning"><i class="far fa-window-close"></i> &nbsp; ยกเลิก &nbsp; </button>
        <form method="POST" action="youtube/" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">ชื่อเรื่อง</label>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control" name="title" id="title" type="text" placeholder="ใส่ชื่อบทความ" value="" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">คำบรรยาย</label>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control" name="description" id="description" type="text" placeholder="ใส่คำบรรยาย" value="" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">ยูทูป</label>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control" name="clip" id="clip" type="text" placeholder="Youtube Url" value="" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-1 col-form-label mt-2" for="title">รูปภาพ</label>
                <div class="col-sm-7">

                    <div class="custom-file mt-2">
                        <input type="file" name="image" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile"><u>เลือกภาพอัพโหลด</u></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card-footer ml-auto mr-auto">
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