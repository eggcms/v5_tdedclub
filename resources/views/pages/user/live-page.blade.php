@extends('layouts.main')

@section('title')
@if($meta_title)
    {{$meta_title}}
@else
	ทีเด็ดคลับดอทคอม ดูบอลสด ราคาบอลสเต็ป
@endif
@endsection

@section('description')
@if($meta_description)
    {{ $meta_description }}
@else
	ทีเด็ดคลับดอทคอม ดูบอลสด ข้อมูลการวิเคราะห์ สเต็ปบอลเดี่ยว สเต็ปบอลคู่ ประจำวัน
@endif
@endsection

@section('content')
<div class="zean1">
	<div class="container bg-black py-2">
		<h3>ดูบอลสด</h3>
			<p><i class="fas fa-home"></i><a href="{{ url('/')}}"> <span>หน้าแรก</span></a>
			<i class="fas fa-angle-right"></i> <span>ดูบอลสด</span></p>
		<div class="row">
			{{--  <div class="container bg-black pb-3">
				<div class="row">
					@foreach($youtubes as $yt)
					<div class="col-xs-12 col-lg-6 pr-3">
						<div class="embed-responsive embed-responsive-16by9">
							<iframe src="{{$yt->clip}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
					@endforeach
				</div>
			</div>  --}}
			<div class="banner-1 pb-2">
				<div class="container bg-black">
					<div class="row">		
						<div class="col"><a href="https://doball24hd.com" target="_blank"><img src="/images/bn7m.gif" alt=""></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="api">
	<div class="container bg-black">
		<div class="row">
				<div class="col-12 pb-2">{{ball_table()}}</div>
		</div>
	</div>
</div>
@endsection
