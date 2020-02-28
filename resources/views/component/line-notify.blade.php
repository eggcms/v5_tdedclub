<div class="col-12">
    <div class="rounded mt-2">
        <div class="panel panel-info" >
            <div class="rounded" style="border:solid 2px #ee8f21; padding:5px 5px 0px 5px; background: linear-gradient(135deg, rgba(76,76,76,1) 0%, rgba(51,51,51,1) 12%, rgba(51,51,51,1) 25%, rgba(51,51,51,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);">
                <div class="panel panel-info register" >
                    <div class="panel-heading">
                        <img style="width:100%;" src="/images/logo-online.png" class="mx-auto d-block" alt="...">
                        <h4 class="text-center" style="color:#fff; font-size:24px; margin:10px; 0px">สมัครสมาชิกผ่านหน้าเว็บ</h4>
                    </div>
                    <div class="panel-body" >
                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            <form name="line-notify" class="form-horizontal" role="form" action="{{url('/line-notify')}}" method="post">
                            <div class="input-group" style="margin-bottom: 5px">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <span class="fa fa-user text-primary"></span>
                                    </span>
                                </div>
                                <input name="fullname" id="fullname" type="text" class="form-control" value="" placeholder="ชื่อ - นามสกุล" required>
                            </div>
                            <div class="input-group" style="margin-bottom: 5px">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span style="padding:0px 2px;" class="fas fa-mobile"></span>
                                    </span>
                                </div>
                                <input name="phone" id="phone" type="text" class="form-control" maxlength="10" placeholder="เบอร์โทรศัพท์" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
                            </div>
                            <div class="input-group" style="margin-bottom: 5px">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fab fa-line text-success"></span>
                                    </span>
                                </div>
                                <input name="lineid" id="lineid" type="text" class="form-control" placeholder="lineID" required>
                            </div>
                            <div class="form-group mb-1">
                                <div class="col-sm-12 col-md-12 col-xs-12 p-0">
                                    <button class="btn text-white" style="font-size:17px; background-color:#00c200; width:100%;" name="submit" type="submit">ยืนยันข้อมูลการสมัคร</button>
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


