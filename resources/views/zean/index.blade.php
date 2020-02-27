
@extends('layouts.app', ['activePage' => 'zean', 'titlePage' => __('Zeans Management')])

@section('content')
    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">{{ __('News') }}</h4>
                            <p class="card-category"> {{ __('ระบบเซียน') }}</p>
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
        <button id="addButton" class="btn btn-success"><i class="far fa-plus-square"></i> เพิ่มบทความใหม่</button>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class=" text-primary">
                    <th>ไอดี</th>
                    <th>การแข่งขันระหว่าง</th>
                    <th>วันที่</th>
                    <th>โพส์ตโดย</th>
                    <th class=" text-center">ออนไลน์</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody>
                    
            @if(!count($zeans))
        
            <tr>
                <td colspan="5" class="text-center">ยังไม่มีข้อมูลในฐานข้อมูล.</td>
            </tr>
            @else                    
                @foreach($zeans as $z) 
                    <tr>
                        <td>{{$z->id}}</td>
                        <td> 
                            <a href="{{url('zean/'.$z->id.'/edit')}}" rel="tooltip" title="แก้ไข ( id.{{$z->id}} )">{{$z->team1}} <i class="fas fa-bolt"></i> {{$z->team2}}</a>
                        </td>
                        <td>{{thaiDate($z->created_at)}}</td>
                        <td>{{creator($z->uid)}}</td>
                        <td class="td-actions text-center">
                            @php
                            if($z->status == 1) { $status='ออนไลน์'; $scolor='danger'; }
                            else { $status='ออฟไลน์'; $scolor='grey'; }
                            @endphp
                            <form action="/zean/{{$z->id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT" />
                                <input type="hidden" name="switch" value="status" />
                                <button type="submit" rel="tooltip" title="{{ $status }}" class="btn btn-link">
                                    <i class="fas fa-rss text-{{ $scolor }}"></i>
                                </button>
                                @csrf
                            </form>                            
                        </td> 
                        <td class="td-actions text-right">
                        <form action="/zean/{{$z->id}}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" rel="tooltip" title="Remove" class="btn btn-outline-danger btn-link btn-sm p-0" onclick="return confirm('ลบโพส์ต ID #{{$z->id}} แน่ใจ?');">
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
        </div>        
    </div>
</div>{{-- content list --}}


<div class="row" id="addBlog" style="display:none">
    <div class="col-md-12">
            <button id="cancelButton" class="btn btn-warning"><i class="far fa-window-close"></i> &nbsp; ยกเลิก &nbsp; </button>
            <form method="POST" action="zean/" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ทีมต่อ</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="team1" id="team1" type="text" placeholder="ใส่ชื่อทีมต่อ" value="" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ทีมรอง</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="team2" id="team2" type="text" placeholder="ใส่ชื่อทีมรอง" value="" required />
                    </div>
                </div>
            </div>            
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">วิเคราะห์</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="content" id="content" type="text" placeholder="วิเคราะห์ทีม..." value="" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ราคาต่อรอง</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="bet" id="bet" type="text" placeholder="ออกราคา.." value="" required />
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 col-form-label mt-2" for="title">ฟันธง</label>
                <div class="col-md-7">
                    <div class="form-group">
                        <input class="form-control" name="prevision" id="prevision" type="text" placeholder="ฟันธง..." value="" required />
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