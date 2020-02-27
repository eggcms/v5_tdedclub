<div class="col-12">
    <div class="rounded mt-2">
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div><img src="/images/logo-online.png" alt="" width="100%"></div>
                <h3 class="text-center"> สมัครสมาชิกผ่านหน้าเว็บ</h3>
            </div>
            <div class="panel-body" >
                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form name="line-notify" class="form-horizontal" role="form" action="{{url('/line-notify')}}" method="post">
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
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>