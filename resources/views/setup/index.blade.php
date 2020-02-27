@extends('layouts.app', ['activePage' => 'setup', 'titlePage' => __('Setup Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('ตั้งค่า') }}</h4>
                        <p class="card-category"> {{ __('ตั้งค่าระบบ') }}</p>
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


                            <script>
                                window.onload = function() {
                                    if (window.jQuery) {  
                                        bsCustomFileInput.init();
                                        $("#addSetup").hide();
                                        $('#addButton').click(function(){
                                            $("#addSetup").show();
                                            $("#listBlog").hide();
                                        });
                                        $('#cancelButton').click(function(){
                                            $("#addSetup").hide();
                                            $("#listBlog").show();
                                        });        
                                    } 
                                }
                            </script>
<div class="row" id="listBlog">
                        <div class="col-12">
                            <button id="addButton" class="btn btn-success"><i class="far fa-plus-square"></i> เพิ่มการตั้งค่า</button>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class=" text-primary">
                                        <th>ID</th>
                                        <th>Key</th>
                                        <th>Value</th>
                                        <th>&nbsp;</th>
                                    </thead>
                                    <tbody>
                                    @if(!count($setups))
                                        <tr>
                                            <td colspan="4" class="text-center">
                                            ยังไม่มีข้อมูลในฐานข้อมูล.
                                            </td>
                                        </tr>
                                    @else                    
                                        @foreach($setups as $s)
                                        <tr>
                                            <td width="16%">{{$s->id}}</td>
                                            <td width="17%"> 
                                                <a href="{{url('setup/'.$s->id.'/edit')}}" rel="tooltip" title="แก้ไข ( id.{{$s->id}} )">{{$s->key}}</a>
                                            </td>
                                            <td class=" text-left">{{$s->value}}</td>

                                            <td class="td-actions text-right">
                                                <form action="/setup/{{$s->id}}" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" rel="tooltip" title="Remove" class="btn btn-outline-danger btn-link btn-sm p-0" onclick="return confirm('ลบโพส์ต ID #{{$s->id}} แน่ใจ?');">
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
</div>
                        <div class="row" id="addSetup" style="display:none">
                            <div class="col-md-12">
                                <button id="cancelButton" class="btn btn-warning"><i class="far fa-window-close"></i> &nbsp; ยกเลิก &nbsp; </button>
                                <form method="post" action="/setup" autocomplete="off" class="form-horizontal">
                                    <div class="row">
                                    @csrf
                                        <label class="col-sm-2 col-form-label mt-2" for="line-token">{{ __(' Configs') }}</label>
                                        <div class="col-sm-2">
                                            <div class="form-group text-danger">
                                                <input class="form-control" input type="text" name="key" placeholder="key" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group text-danger">
                                                <input class="form-control" input type="text" name="value" placeholder="value" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <button type="submit" class="btn btn-primary ml-auto mr-auto">{{ __('เพิ่มข้อมูล') }}</button>
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