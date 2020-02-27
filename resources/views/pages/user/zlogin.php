@extends('layouts.main')

@section('content')
<div class="news">
    <div class="container bg-black py-3">
        <div class="row">
            <div class="col-md-8">
                <h3>หมวดข่าวกีฬา</h3>
                <p><i class="fas fa-home"></i><a href="{{url('/')}}"> <span>หน้าแรก</span></a> <i class="fas fa-angle-right"></i> <span>หมวดข่าวกีฬา</span></p>
                <div class="row">

                    @foreach($allnews as $news)
                    <div class="col-12 col-lg-4  pb-2">
                        <div class="all-cat">
                            <a href="{{url('/news/'.$news->id)}}">
                                <img src="{{url('imgs/'.$news->image)}}" alt="Snow" style="width:100%" >
                                <p>{{$news->title}}</p>
                            </a>
                            <span><i class="far fa-clock"></i> {{ thaiDate(date('d-m-Y H:i',strtotime($news->created_at)),'a') }} <i class="far fa-eye"></i> 
                                {{ $news->visit }}
                                views</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 py-1">
                <div id="sidebar-scroll">
                    <div class="sidebar">

                        <a href="#">
                            <img src="/images/pro.png" alt="Snow" style="width:100%">
                        </a>
                        @include('component.line-notify')
                        {{-- <div class="col-12">
                            <div class="rounded mt-2">
                                <div class="panel panel-info" >
                                    <div class="panel-heading">
                                        <div><img src="/images/logo-online.png" alt="" width="100%"></div>
                                        <h4 class="text-center"> สมัครสมาชิกผ่านหน้าเว็บ</h4>
                                    </div>
                                    <div class="panel-body" >
                                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                                            <form name="line-notify" class="form-horizontal" role="form" action="{{url('/api/line')}}" method="post">
                                            <div class="input-group" style="margin-bottom: 10px">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                    <span class="fa fa-user text-primary"></span>
                                                    </span>
                                                </div>
                                                <input name="fullname" id="fullname" type="text" class="form-control" value="" placeholder="ชื่อ - นามสกุล" required>
                                            </div>
                                            <div class="input-group" style="margin-bottom: 10px">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fas fa-mobile"></span>
                                                    </span>
                                                </div>
                                                <input name="phone" id="phone" type="text" class="form-control" maxlength="10" placeholder="เบอร์โทรศัพท์" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
                                            </div>
                                            <div class="input-group" style="margin-bottom: 10px">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fab fa-line text-success"></span>
                                                    </span>
                                                </div>
                                                <input name="lineid" id="lineid" type="text" class="form-control" placeholder="lineID" required>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12 col-md-12 col-xs-12 p-0">
                                                    <button class="btn text-white" style="font-size:17px; background-color:#00c200; width:100%;" name="submit" type="submit">ยืนยันข้อมูลการสมัคร</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <a href="#">
                            <img src="/images/bn-2.gif" alt="Snow" style="width:100%">
                        </a>




                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
