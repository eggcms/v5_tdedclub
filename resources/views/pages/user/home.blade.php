@extends('layouts.main')

@section('title')
 @if($meta_title)
    {{ $meta_title }}
@else
    ทีเด็ดคลับดอทคอม ศูนย์รวมทีเด็ดบอลสเต็ป โดยบรรดากูรู ระดับเซียนในวงการลูกหนัง
@endif
@endsection

@section('description')
@if($meta_description)
    {{ $meta_description }}
@else
    ทีเด็ดคลับดอทคอม ศูนย์รวมทีเด็ดบอลสเต็ป ข้อมูลบอลจากลีกดังทั่วโลก โดยมุ่งเน้นข้อมูลที่ถูกต้อง ฉับไวเที่ยงตรง โดยบรรดากูรู ระดับเซียนในวงการลูกหนัง
@endif
@endsection

@section('content')
<div class="banner-1">
    <div class="container bg-black">
        <div class="row">
            <div class="col"><a href="https://www.mm88online.com/"><img src="/images/bn-1.gif" alt=""></a></div>
        </div>
    </div>
</div>

{{-- <แถบดูบอลออนไลน์> --}}
<div id="title-doball">
    <div class="container bg-black">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <div class="row">
                        <div class="col-8 col-lg-10"><h1>ดูบอลออนไลน์ฟรี</h1></div>
                        <div class="col-4 col-lg-2"><a href="/live">ดูทั้งหมด</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <แถบดูบอลออนไลน์> --}}

{{-- <ช่องดูบอลออนไลน์> --}}
<div id="doball">
    <div class="container bg-black">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="row">
                    <div class="col">
                        <a href="#"><img class="banner-auto" src="/images/check-lotto1.png" alt=""></a>
                        <a href="http://nav.cx/52dtFXm"><img class="banner-auto" src="/images/promotion-1.jpg" alt=""></a>
                    </div>
                    @include('component.line-notify')
                </div>
            </div>
            <div class="col-8">
                <div>
                    <div id="mPlayer" class="bg-light"></div>
                </div>
                <div>
                    <img class="leauge" src="/images/bn-leauge.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <ช่องดูบอลออนไลน์> --}}


{{-- <แถบข่าวกีฬา> --}}
<div id="home">
    <div class="container bg-black">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <div class="row">
                        <div class="col-8 col-lg-10">
                            <h1>ข่าวฟุตบอลประจำวัน</h1>
                        </div>
                        <div class="col-4 col-lg-2">
                            <a href="{{url('/allnews')}}">ดูทั้งหมด</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{{-- <แถบข่าวกีฬา> --}}

{{-- <หน้าคอนเท้> --}}
        <div class="row py-4">
            @if(isset($last_news))
             <div class="col-lg-8 mb-4 mb-lg-0">
                <a href="{{url('/news/'.$last_news->id)}}" class="imageMain">
                    <section>
                        <img class="img-fluid" src="{{serv_url('/imgs/'.$last_news->image)}}" alt="">
                    </section>
                    <div class="content">
                        <h5 class="px-3">{{$last_news->title}}</h5>
                        <p class="px-3">{{$last_news->description}}</p>
                    </div>
                </a>
            </div>
            @endif
            <div class="col-lg-4">
                @foreach($news as $n)
                <a href="{{url('/news/'.$n->id)}}" class="row mb-2 homeListnews">
                    <div class="col-4 px-lg-0">
                        <div class="imageContent"><img class="img" src="{{serv_url('imgs/'.$n->image)}}" alt=""></div>
                    </div>
                    <div class="col-8 pl-0">
                        <div class="content">
                            <h2>{{$n->title}}</h2>
                            <p>{{$n->description}}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{-- <หน้าคอนเท้> --}}


{{-- <ช่องยูทูป> --}}
<div id="youtube">
    <div class="container bg-black pb-2">
        <div class="row">
             @foreach($youtubes as $yt)
            <div class="col-xs-12 col-lg-6 pr-3">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="{{$yt->clip}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
{{-- <ช่องยูทูป> --}}

<div class="zean">
    <div class="container bg-black py-1">
        <div class="row">
            <div class="col-12">
                <a href="https://www.mm88online.com/"><img src="/images/bn-3.gif" alt=""></a>
                <h2>ทรรศนะบอล ทีเด็ดบอล บ้านผลบอล</h2>
                <p>ทรรศนะบอลวันนี้ วิเคราะห์บอลวันนี้ ทรรศนะเซียนบอล ทีเด็ดบอล ทีเด็ดบอลรายวัน ทีเด็ดบอลวันนี้ บอลเต็ง บอลสเต็ป บ้านผลบอล ทีเด็ดบอลเต็ง ทีเด็ดบอลเดี่ยว วิเคราะห์บอลทุกคู่ วิเคราะห์บอลทุกลีก ทรรศนะเซียนบอลวันนี้ ทีเด็ดบอลวันนี้ แหล่งรวมเซียนบอล ผลบอล วิเคราะห์บอลสุดแม่น ราคาบอล ทีเด็ดฟุตบอล ทีเด็ดบอลวันนี้ โปรแกรมบอลวันนี้</p>
            </div>
        </div>
    </div>
</div>

{{-- <แถบวิเคราะห์บอล> --}}
<div id="analyze-ball">
    <div class="container bg-black">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <div class="row">
                        <div class="col-8 col-lg-10"><h1>วิเคราะห์บอลวันนี้</h1></div>
                        <div class="col-4 col-lg-2"><a href="{{url('/allvicrow')}}">ดูทั้งหมด</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <แถบวิเคราะห์บอล> --}}

{{-- <วิเคราะห์บอล> --}}
<div id="vicrow">
    <div class="container bg-black py-3">
       <div class="row">
            <div class="col-12 pb-2">
                <div class="row">

                     @foreach($analyzes as $an)
                    <div class="col-12 col-lg-4">
                        <a href="{{url('/vview/'.$an->id)}}">
                            <div class="img-zoom"><img src="{{serv_url('imgs/'.$an->image)}}" class="img-fluid" alt=""></div>
                            <p>{{$an->title}}</p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <วิเคราะห์บอล> --}}

{{-- <แถบทีเด็ดเซียน> --}}
<div id="tded-zean">
    <div class="container bg-black">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <div class="row">
                        <div class="col-12 texthead"><h1>ทีเด็ดสเต็ป วันที่ 9 มกราคม 2563</h1></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <แถบทีเด็ดเซียน> --}}

{{-- <ทีเด็ดเซียน> --}}
<div id="zeantded">
    <div class="container bg-black">
        <div class="row">

             @foreach($tsteps as $ts)
            <div class="col-12 col-lg-3 py-2">
                <div class="img-tded">
                    <a href="{{tslineID($ts['img'])}}"><img src="{{url('images/step-' . $ts['img'] . '.gif')}}" class="img-fluid"></a>
                </div>
                <div class="tdedstep">
                <div class="py-2">
                    @if($ts['team1'] != '')
                        <p><img class="img-1" src="{{url('/images/ball.gif')}}" alt=""><span style="color:{{$ts['team1w']}}">{{$ts['team1']}}</span></p>
                    @else
                        <p class="py-1 text-center">กำลังอัพเดทข้อมูล...</p>
                    @endif
                </div>
                    <div class="py-2 bg-grey">
                        @if($ts['team2'] != '')
                            <p><img class="img-1" src="{{url('/images/ball.gif')}}" alt=""><span style="color:{{$ts['team2w']}}">{{$ts['team2']}}</span></p>
                        @else
                            <p class="py-1 text-center">กำลังอัพเดทข้อมูล...</p>
                        @endif
                    </div>
                    <div class="py-2">
                        @if($ts['team3'] != '')
                            <p><img class="img-1" src="{{url('/images/ball.gif')}}" alt=""><span style="color:{{$ts['team3w']}}">{{$ts['team3']}}</span></p>
                        @else
                            <p class="py-1 text-center">กำลังอัพเดทข้อมูล...</p>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
{{-- <ทีเด็ดเซียน> --}}

{{-- { แถบบอล api } --}}
{{-- <div id="api-ball">
    <div class="container bg-black">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <div class="row">
                        <div class="col-8 col-lg-10"><h1>ทรรศนะบอลวันนี้</h1></div>
                        <div class="col-4 col-lg-2">
                            <p>
                                ทรรศนะบอล ทีเด็ดบอล บ้านผลบอล
                                ทรรศนะบอลวันนี้ วิเคราะห์บอลวันนี้ ทรรศนะเซียนบอล ทีเด็ดบอล ทีเด็ดบอลรายวัน ทีเด็ดบอลวันนี้ บอลเต็ง บอลสเต็ป บ้านผลบอล
                                ทีเด็ดบอลเต็ง ทีเด็ดบอลเดี่ยว วิเคราะห์บอลทุกคู่ วิเคราะห์บอลทุกลีก ทรรศนะเซียนบอลวันนี้ ทีเด็ดบอลวันนี้ แหล่งรวมเซียนบอล ผลบอล
                                วิเคราะห์บอลสุดแม่น ราคาบอล ทีเด็ดฟุตบอล ทีเด็ดบอลวันนี้ โปรแกรมบอลวันนี้
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- { แถบบอล api } --}}


{{-- { ช่องวาง api } --}}
<div class="api">
    <div class="container bg-black">
        <div class="row">
             <div class="col-12 pb-2">{{ballstep($objs)}}</div>
        </div>
    </div>
</div>

{{-- { ช่องวาง api } --}}

<div class="banner-1">
    <div class="container bg-black">
        <div class="row">
            <div class="col">
                <a href="https://gudunung.com/"><img src="/images/bn-4.gif" alt=""></a>
            </div>
        </div>
    </div>
</div>

@endsection
