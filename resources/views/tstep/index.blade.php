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
					<script>
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
							$('.addButton').click(function(){
								$("#addTstep").show();
								$("#listBlog").hide();
							});							      
						} 
					}
					</script>
						<div class="row" id="listBlog">
							<div class="col-md-12">
								<div class="d-none">
									<a href="#" class="btn btn-success" id="addButton">เพิ่มทำนายผล</a>
								</div>

								<div class="table-responsive">
									<table class="table table-hover">
										<thead class=" text-primary">
											<th>ID</th>
											<th>เซียน</th>
											<th>ทำนายผล</th>
											<th>ข้อมูลล่าสุด</th>
										</thead>
										<tbody>
											@foreach($users as $user)
											<?php $step = DB::table('tsteps')->where('uid',$user->id)->get(); ?>
											<tr>
												<td>{{$user->id}}</td>
												<td>
                                                    @if($step->count())
													{{$user->name}}
													@else
													{{$user->name}}
													@endif
												</td>
												@if($step->count())
													@foreach($step as $ts)
														@if($loop->first)
														<td>
															<a href="{{url('tstep/'.$ts->id.'/edit')}}" rel="tooltip" >
																{{$ts->team1}} | {{$ts->team2}} | {{$ts->team3}}
															</a>
														</td>
														<td>{{$ts->created_at}}</td>
														@endif
													@endforeach
												@else
													<td>ยังไม่มีการเพิ่มข้อมูล.</td>
													<td>-</td>
												@endif									
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="row" id="addTstep" class="d-none">
							<div class="col-md-12">
								<button id="cancelButton" class="btn btn-warning"><i class="far fa-window-close"></i> &nbsp; ยกเลิก &nbsp; </button>
								<form method="post" action="/tstep" autocomplete="off" class="form-horizontal">
									<div class="row">
										<div class="col">
											<div class="form-group">
												<label for="exampleFormControlSelect1">เลือกเซียน</label>
												<select class="form-control" id="uid" name="uid">
												@foreach($users as $user)
												<option value="{{$user->id}}">{{$user->name}}</option>
												@endforeach
												</select>
											</div>
											<div class="form-group">
												<input class="form-control" input type="text" name="team1" placeholder="ผลทำนาย ทีม 1" />
											</div>
											<div class="form-group">
												<input class="form-control" input type="text" name="team2" placeholder="ผลทำนาย ทีม 2" />
											</div>
											<div class="form-group">
												<input class="form-control" input type="text" name="team3" placeholder="ผลทำนาย ทีม 3" />
											</div>

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