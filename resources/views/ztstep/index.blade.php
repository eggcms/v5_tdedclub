@extends('layouts.app', ['activePage' => 'ztstep', 'titlePage' => __('TededStep Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Ballstep master') }}</h4>
                        <p class="card-category"> {{ __('เซียนทีเด็ดสเต็ป') }}</p>
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
                                        <strong>Oh snap!</strong> &nbsp; &nbsp;<span>{{$error}}</span>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        @endif
                        @if(session('success'))

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-success">
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

                        <div class="row">

                            
                        </div>
                            {{-- <script>
                                window.onload = function() {
                                    if (window.jQuery) {  
                                        bsCustomFileInput.init();
                                        $("#addTstep").hide();
                                        $('#addButton').click(function(){
                                            $("#addTstep").show();
                                            $("#listBlog").hide();
                                        });
                                        $('#cancelButton').click(function(){
                                            $("#addTstep").hide();
                                            $("#listBlog").show();
                                        });        
                                    } 
                                }
                            </script>
<div class="row" id="listBlog">
                        <div class="col-12">
                            <button id="addButton" class="btn btn-success"><i class="far fa-plus-square"></i> เพิ่มทีเด็ดสเต็ป</button>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class=" text-primary">
                                        <th>ID</th>
                                        <th>เซียน</th>
                                        
                                        <th>ไลน์</th>
                                        <th>เฟสบุ๊ค</th>
                                        <th>วันที่</th>
                                        <th>&nbsp;</th>
                                    </thead>
                                    <tbody>
                                    @if(!count($tsteps))
                                        <tr>
                                            <td colspan="4" class="text-center">
                                            ยังไม่มีข้อมูลในฐานข้อมูล.
                                            </td>
                                        </tr>
                                    @else                    
                                        @foreach($tsteps as $t)
                                        <tr>
                                            <td width="16%">{{$s->id}}</td>
                                            <td width="17%"> 
                                                <a href="{{url('tstep/'.$t->id.'/edit')}}" rel="tooltip" title="แก้ไข ( id.{{$t->id}} )">{{$t->key}}</a>
                                            </td>
                                            <td class=" text-left">{{$t->value}}</td>
<td>ss</td>
<td>sss</td>
                                            <td class="td-actions text-right">
                                                <form action="/setup/{{$t->id}}" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" rel="tooltip" title="Remove" class="btn btn-outline-danger btn-link btn-sm p-0" onclick="return confirm('ลบโพส์ต ID #{{$t->id}} แน่ใจ?');">
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
</div> --}}

                                    






                                    </div>

                                    <div class="row pb-3">
                                        <button type="submit" class="btn btn-primary ml-auto mr-auto">{{ __('บันทึกข้อมูล') }}</button>
                                    </div>
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