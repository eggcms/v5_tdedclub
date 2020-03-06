@extends('layouts.main')

@section('title')
@if($ans->title)
    {{$ans->title}}
@else
    ทีเด็ดคลับดอทคอม ศูนย์รวมทีเด็ดบอลสเต็ป โดยบรรดากูรู ระดับเซียนในวงการลูกหนัง
@endif
@endsection

@section('description')
@if($ans->description)
    {{ $ans->description }}
@else
    ทีเด็ดคลับดอทคอม ศูนย์รวมทีเด็ดบอลสเต็ป ข้อมูลบอลจากลีกดังทั่วโลก โดยมุ่งเน้นข้อมูลที่ถูกต้อง ฉับไวเที่ยงตรง โดยบรรดากูรู ระดับเซียนในวงการลูกหนัง
@endif
@endsection


@section('content')
<div class="vicrow-page">
    <div class="container bg-black py-3">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h3>{{$ans->title}}</h3>
                <p><i class="fas fa-home"></i><a href="{{url('/')}}"> <span>หน้าแรก</span></a> <i class="fas fa-angle-right"></i> <span>{{$ans->title}}</span></p>
                <div class="pb-3 text-warning">
                    <b>{!!$ans->description!!}</b>
                </div>
                <div>
                    <img src="{{serv_url('imgs/'.$ans->image)}}" class="img-fluid" />
                    <div class="py-3">
                        {!!$ans->content!!}
                    </div>
                </div>
                <div class="int-page py-3">
                    <div class="title">
                        <div class="row">
                            <div class="col-12"><h1>อัพเดทล่าสุด</h1></div>
                        </div>
                    </div>
                </div>
                <div class="int-link">
                    <ul class="interest">
                        @foreach($ans_update as $ansx)
                        <li><i class="fas fa-angle-double-right text-warning"></i>
                            <a href="{{url('/vview/'.$ansx->id)}}"><span>{{$ansx->title}}</span></a>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>
            <div class="col-md-4 py-1">
                <div id="sidebar-scroll">
                    <div class="sidebar">
                        <a href="https://www.mm88online.com/" target="_blank">
                            <img src="/images/pro.png" alt="Snow" style="width:100%">
                        </a>
                        @include('component.line-notify')
                        <a href="https://www.mm88zean.com/" target="_blank">
                            <img src="/images/bn-2.gif" alt="Snow" style="width:100%">
                        </a>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
