@extends('layouts.main')

@section('title')
    {{$meta_title ?? 'เซียน7เอ็มดอทคอม ตรวจสลากกินแบ่งรัฐบาล งวดวันที่ '.$lotto_at.' เว็บดูบอลบสด ศูนย์รวมข่าวสารวงการบอล จากลีกดังทั่วโลก'}}
@endsection
@section('description')
    {{$meta_description ?? 'เซียน7เอ็มดอทคอม ตรวจสลากกินแบ่งรัฐบาล งวดวันที่ '.$lotto_at.' เว็บดูบอลบสด ศูนย์รวมข่าวสารวงการบอล จากลีกดังทั่วโลก เที่ยงตรง กระชับ ฉับไว'}}
@endsection

@section('content')
<div class="vicrow-page">
    <div class="container bg-black py-3">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h4 class="border-bottom pb-3 text-warning">ตรวจสลากกินแบ่งรัฐบาล งวดวันที่ {{ $lotto_at ?? '...' }}</h4>
                <p><i class="fas fa-home"></i><a href="{{ url('/') }}">หน้าแรก</a> <i class="fas fa-angle-right"></i> <span>ตรวจสลากกินแบ่งรัฐบาล งวดวันที่ {{ $lotto_at ?? '...' }}</span></p>
                <div>
                @if($reason!='none')
                    <div class="my-2 p-3 lotto_checked bg-warning rounded">
                        <h1 class="text-center text-danger">ผลการตรวจสลากกินแบ่งรัฐบาล</h1>
                        <p class="text-dark text-center">เลขสลาก: {{ $mylotto }}</p>
                        <h3 class="text-center rounded text-light py-3 bg-danger">{{ $reason }}</h3>
                    </div>
                @endif
                </div>
                    <div class="my-2 p-3 border check-lotto bg-egg rounded">
                        <h3 class="text-center text-dark">ตรวจสลากกินแบ่งรัฐบาล</h3>
                        <form action="/lotto" method="post">
                            <div class="form-group text-center">
                                <input type="type" name="ur_lotto" class="form-control text-center" placeholder="กรอกเลขสลาก" maxlength="6" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
                            </div>
                            <div id="result" class="form-group text-success bg-light py-2 px-3 rounded text-center">รอผลสลากกินแบ่ง</div>
                            <button type="submit" class="btn btn-danger form-control">ตรวจสลากฯ ของคุณ</button>
                            @csrf
                        </form>
                    </div>
                <div class="lotto_result py-3 col bg-egg text-dark">
                    <div class="row">
                        <div class="col-12 py-3 border-bottom">
                            <h1 class="text-center text-danger">ผลสลากกินแบ่งรัฐบาล</h1>
                            <p class="text-center text-dark">งวดวันที่ {{$lotto_at}}</p>
                            <h3 class="text-center text-danger">รางวัลที่ 1 รางวัลละ 6,000,000 บาท</h3>
                            <div class="col-12 text-center">{{$lotto->lotto1}}</div>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h3 class="text-center text-danger">รางวัลข้างเคียงรางวัลที่ 1</h3>
                                    <p class="text-dark">{!! str_replace(' ',' &nbsp; &nbsp; ',$lotto->lotto1closeup) !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 py-3 border-bottom">
                            <div class="row">
                                <div class="col-4 text-center"><h3 class="text-center text-danger">เลขหน้า 3 ตัว</h3><p class="text-dark">{{$lotto->lotto_front3}}</p></div>
                                <div class="col-4 text-center"><h3 class="text-center text-danger">เลขท้าย 3 ตัว</h3><p class="text-dark">{{$lotto->lotto_last3}}</p></div>
                                <div class="col-4 text-center"><h3 class="text-center text-danger">เลขท้าย 2 ตัว</h3><p class="text-dark">{{$lotto->lotto_last2}}</p></div>
                            </div>
                        </div>

                        <div class="col-12 py-3 border-bottom">
                            <h3 class="text-center text-danger">ผลสลากกินแบ่งรัฐบาล รางวัลที่ 2 มี 5 รางวัลๆละ 200,000 บาท</h3>
                            <div class="row">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i == 0) 
                                    <div class="col-2 offset-1">{{$lotto2[$i]}}</div>
                                    @else 
                                    <div class="col-2">{{$lotto2[$i]}}</div>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="col-12 py-3 border-bottom">
                            <h3 class="text-center text-danger">ผลสลากกินแบ่งรัฐบาล รางวัลที่ 3 มี 10 รางวัลๆละ 80,000 บาท</h3>
                            <div class="row">
                                @for($i = 0; $i < 10; $i++)
                                    @if(($i%5) == 0) 
                                    <div class="col-2 offset-1">{{$lotto3[$i]}}</div>
                                    @else 
                                    <div class="col-2">{{$lotto3[$i]}}</div>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="col-12 py-3 border-bottom">
                            <h3 class="text-center text-danger">ผลสลากกินแบ่งรัฐบาล รางวัลที่ 4 มี 50 รางวัลๆละ 40,000 บาท</h3>
                            <div class="row">
                                @for($i = 0; $i < 50; $i++)
                                    @if(($i%5) == 0)
                                    <div class="col-2 offset-1">{{$lotto4[$i]}}</div>
                                    @else 
                                    <div class="col-2">{{$lotto4[$i]}}</div>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="col-12 py-3">
                            <h3 class="text-center text-danger">ผลสลากกินแบ่งรัฐบาล รางวัลที่ 5 มี 100 รางวัลๆละ 20,000 บาท</h3>
                            <div class="row">
                                @for($i = 0; $i < 100; $i++)
                                    @if(($i%5) == 0)
                                    <div class="col-2 offset-1">{{$lotto5[$i]}}</div>
                                    @else
                                    <div class="col-2">{{$lotto5[$i]}}</div>
                                    @endif
                                        
                                @endfor
                            </div>
                        </div>
                        <div class="bg-danger text-light p-3">
                            <p>
                                *** หากท่านใดตรวจผลสลากกินแบ่งรัฐบาล เเล้วถูกรางวัลสลากกินแบ่งรัฐบาล กรุณาตรวจเช็คตัวเลขสลากกินแบ่งที่กองสลากอีกครั้งนะคะ เพื่อความถูกต้อง
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 py-1">
                <div id="sidebar-scroll">
                    <div class="sidebar">
                        <a href="https://www.mm88online.com/" target="_blank">
                            <img src="{{url('/images/pro.png')}}" alt="Snow" style="width:100%">
                        </a>
                        @include('component.line-notify')
                        <a href="https://www.mm88zean.com/" target="_blank">
                            <img src="{{url('/images/bn-2.gif')}}" alt="Snow" style="width:100%">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
