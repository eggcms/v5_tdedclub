@extends('layouts.app', ['activePage' => 'tstep', 'titlePage' => __('TededStep Management')])

@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">{{ __('ฺBallstep Guidelines') }}</h4>
						<p class="card-category"> {{ __('ทีเด็ดสเต็ป') }}</p>
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



						<div class="row" id="addTstep">
							<div class="col-md-12">
                                <a href="/tstep" class="btn btn-warning"><i class="far fa-window-close"></i> &nbsp; ยกเลิก &nbsp; </a>
                                
								<form method="post" action="{{url('tstep/'.$tstep->id)}}" autocomplete="off" class="form-horizontal">
									<div class="row">
										<div class="col">
										@php $user = DB::table('users')->where('id',$tstep->uid)->first(); @endphp
										<h3>{{$user->name}}</h3>
										<img src="{{ url('avatar/'.$user->avatar) }}" />
										<input type="hidden" name="id" value="{{$tstep->id}}" />

											<div class="form-group">

												
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													  <input class="form-check-input" type="radio" name="team1w" id="team1w" value="0" @if($tstep->team1w == 0) checked @endif > เสมอ
													  <span class="circle">
														  <span class="check"></span>
													  </span>
													</label>
												  </div>
												  <div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													  <input class="form-check-input" type="radio" name="team1w" id="team1w" value="1" @if($tstep->team1w == 1) checked @endif > ต่อ
													  <span class="circle">
														  <span class="check"></span>
													  </span>
													</label>
												  </div>
												  <div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													  <input class="form-check-input" type="radio" name="team1w" id="team1w" value="2" @if($tstep->team1w == 2) checked @endif > รอง
													  <span class="circle">
														  <span class="check"></span>
													  </span>
													</label>
												  </div>


												<input class="form-control" type="text" name="team1" value="{{$tstep->team1}}" />									
											</div>
											<div class="form-group">
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													<input class="form-check-input" type="radio" name="team2w" id="team2w" value="0" @if($tstep->team2w == 0) checked @endif > เสมอ
													<span class="circle">
														<span class="check"></span>
													</span>
													</label>
												</div>
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													<input class="form-check-input" type="radio" name="team2w" id="team2w" value="1" @if($tstep->team2w == 1) checked @endif > ต่อ
													<span class="circle">
														<span class="check"></span>
													</span>
													</label>
												</div>
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													<input class="form-check-input" type="radio" name="team2w" id="team2w" value="2" @if($tstep->team2w == 2) checked @endif > รอง
													<span class="circle">
														<span class="check"></span>
													</span>
													</label>
												</div>

												<input class="form-control" type="text" name="team2" value="{{$tstep->team2}}" />

											</div>
											<div class="form-group">
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													<input class="form-check-input" type="radio" name="team3w" id="team3w" value="0" @if($tstep->team3w == 0) checked @endif > เสมอ
													<span class="circle">
														<span class="check"></span>
													</span>
													</label>
												</div>
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													<input class="form-check-input" type="radio" name="team3w" id="team3w" value="1" @if($tstep->team3w == 1) checked @endif > ต่อ
													<span class="circle">
														<span class="check"></span>
													</span>
													</label>
												</div>
												<div class="form-check form-check-radio form-check-inline">
													<label class="form-check-label">
													<input class="form-check-input" type="radio" name="team3w" id="team3w" value="2" @if($tstep->team3w == 2) checked @endif > รอง
													<span class="circle">
														<span class="check"></span>
													</span>
													</label>
												</div>	
												<input class="form-control" type="text" name="team3" value="{{$tstep->team3}}" />
											</div>
                                            <input type="hidden" name="_method" value="PUT">
											<div class="row ">
												<button type="submit" class="btn btn-primary ml-auto mr-auto">{{ __('บันทึกข้อมูล') }}</button>
											</div>
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