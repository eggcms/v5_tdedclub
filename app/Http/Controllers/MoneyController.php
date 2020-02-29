<?php

namespace App\Http\Controllers;

use App\Banks;
use Exception;
use App\CutOff;
use App\Setting;
use App\WebBank;
use App\Customer;
use App\Promotion;
use Carbon\Carbon;
use App\Transection;
use App\PromotionMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MBETAPIController;

class MoneyController extends Controller
{

    public function index(Request $request)
    {

        if($request->get('date') == null){
            $date = Carbon::now()->format('Y-m-d');
            $startDay = Carbon::now()->startOfDay()->timestamp;
            $endDay = Carbon::now()->endOfDay()->timestamp;
        }else{
            $date = Carbon::parse($request->get('date'))->format('Y-m-d');
            $startDay = Carbon::parse($date)->startOfDay()->timestamp;
            $endDay = Carbon::parse($date)->endOfDay()->timestamp;
        }




        if($request->user()->role == 'call'){
            if($request->get('bankAccID') != null){
                $bankAccID = $request->get('bankAccID');
                $type = $request->get('type');

                if($request->get('type') != null){
                    switch ($type) {
                        case '0': //ฝาก-ถอน
                        $transections = Transection::orderBy('agent_status','asc')
                        ->orderBy('date_time', 'desc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('status','!=',3)
                        ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);
                            break;
                        case '3': //ยกเลิก
                        $transections = Transection::orderBy('agent_status','asc')
                        ->orderBy('date_time', 'desc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('status',3)
                        ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);

                            break;
                        case '4': //ทั้งหมด
                        $transections = Transection::orderBy('date_time', 'desc')
                        ->orderBy('agent_status','asc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);
                            break;

                        default: // ฝาก หรือ ถอน
                        $transections = Transection::orderBy('agent_status','asc')
                        ->orderBy('date_time', 'desc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('status','!=',3)
                        ->where('type', $type)
                        ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);
                            break;
                    }
                }else{
                    $transections = Transection::orderBy('agent_status','asc')
                    ->where('web_bank_acc_id',$bankAccID)
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status','!=',3)
                    ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                }


            }elseif($request->get('type') != null){
                $type = $request->get('type');
                switch ($type) {
                    case '0': //ฝาก-ถอน
                    $transections = Transection::orderBy('agent_status','asc')
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status','!=',3)
                    ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                        break;
                    case '3': //ยกเลิก
                    $transections = Transection::orderBy('agent_status','asc')
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status',3)
                    ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);

                        break;
                    case '4': //ทั้งหมด
                    $transections = Transection::orderBy('date_time', 'desc')
                    ->orderBy('agent_status','asc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                        break;

                    default: // ฝาก หรือ ถอน
                    $transections = Transection::orderBy('agent_status','asc')
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status','!=',3)
                    ->where('type', $type)
                    ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                        break;
                }

            }else{
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time', 'desc')
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }
        }else{
            if($request->get('bankAccID') != null){
                $bankAccID = $request->get('bankAccID');
                if($request->get('type') != null){
                    $type = $request->get('type');
                    switch ($type) {
                        case '0':
                        $transections = Transection::orderBy('agent_status','asc')
                        ->orderBy('date_time', 'desc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('status','!=',3)
                     //   ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);
                            break;
                        case '3':
                        $transections = Transection::orderBy('agent_status','asc')
                        ->orderBy('date_time', 'desc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('status',3)
                      //  ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);

                            break;
                        case '4':
                        $transections = Transection::orderBy('date_time', 'desc')
                        ->orderBy('agent_status','asc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                       // ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);
                            break;

                        default:
                        $transections = Transection::orderBy('agent_status','asc')
                        ->orderBy('date_time', 'desc')
                        ->where('web_bank_acc_id',$bankAccID)
                        ->whereBetween('date_time_ts',[$startDay,$endDay])
                        ->where('status','!=',3)
                        ->where('type', $type)
                       // ->where('agent_id',$request->user()->agent_id)
                        ->paginate(50);
                            break;
                    }
                }else{

                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time', 'desc')
                ->where('web_bank_acc_id',$bankAccID)
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                //->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
                }

            }elseif($request->get('type') != null){
                $type = $request->get('type');

                switch ($type) {
                    case '0':
                    $transections = Transection::orderBy('agent_status','asc')
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status','!=',3)
                    //->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                        break;
                    case '3':
                    $transections = Transection::orderBy('agent_status','asc')
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status',3)
                   // ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);

                        break;
                    case '4':
                    $transections = Transection::orderBy('date_time', 'desc')
                    ->orderBy('agent_status','asc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    //->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                        break;

                    default:
                    $transections = Transection::orderBy('agent_status','asc')
                    ->orderBy('date_time', 'desc')
                    ->whereBetween('date_time_ts',[$startDay,$endDay])
                    ->where('status','!=',3)
                    ->where('type', $type)
                  //  ->where('agent_id',$request->user()->agent_id)
                    ->paginate(50);
                        break;
                }


            }else{
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time', 'desc')
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                //->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }
        }



        if($request->user()->role == 'call'){
            $webBank = WebBank::where('active',1)
            ->where('agent_id',$request->user()->agent_id)
            ->orderBy('sort','asc')
            ->get();
        }else{
            $webBank = WebBank::where('active',1)
            ->orderBy('sort','asc')
            ->get();
        }

        $depositAcc = [];
        $withdrawalAcc = [];
        $sumDeposit = 0;
        $sumWithdrawal = 0;

        foreach ($webBank as $wb) {

            if($wb->type == 1){
                if($wb->name == $wb->name)
                {

                    $depositAcc[$wb->name . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                        'id' => $wb->id,
                        'bank_id' => $wb->bank_id,
                        'name' => $wb->name,
                        'acc_no' => $wb->acc_no,
                        'total' => $this->sumAccBalance($wb->id,$startDay,$endDay),
                    ];
                    //$sumDeposit += $this->sumAccBalance($wb->id,$startDay,$endDay);
                }

            }elseif($wb->type == 2){
                if($wb->name == $wb->name){
                    $withdrawalAcc[$wb->name  . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                        'id' => $wb->id,
                        'bank_id' => $wb->bank_id,
                        'name' => $wb->name,
                        'acc_no' => $wb->acc_no,
                        'total' => $this->sumAccBalance($wb->id,$startDay,$endDay),
                    ];
                    //$sumWithdrawal  += $this->sumAccBalance($wb->id,$startDay,$endDay);
                }
            }
        }
        $sumDeposit = Transection::where('type',1)
        ->where('status','!=',3)
        //->whereBetween('date_time',[$startDay,$endDay])
        ->whereBetween('date_time_ts',[$startDay,$endDay])
        ->sum('amount');
        $sumWithdrawal  = Transection::where('type',2)
        ->where('status','!=',3)
        ->where('agent_status','=',1)
        //->whereBetween('date_time',[$startDay,$endDay])
        ->whereBetween('date_time_ts',[$startDay,$endDay])
        ->sum('amount');
        //return $sumDeposit;

        return view('money/index',compact(  'transections','depositAcc','withdrawalAcc','sumDeposit','sumWithdrawal','date'));
    }

    public function index_wait_confirm(Request $request)
    {
        try {
            $date_mon_group = DB::select("SELECT count(*), DATE_FORMAT(date_time,'%Y-%m') as mon_group_select FROM `transections` GROUP BY mon_group_select DESC");
        } catch (\Throwable $th) {
            //throw $th;
        }


        if($request->get('date_mon') == null){

            $date_mon_group_frist = DB::select("SELECT count(*), DATE_FORMAT(date_time,'%Y-%m') as mon_group_select FROM `transections` GROUP BY mon_group_select DESC limit 0,1");

            foreach ($date_mon_group_frist as $value)
            {
                $date_mon=$value->mon_group_select;
            }

        }else{
            $date_mon = $request->get('date_mon');
        }

        $selectedMonth = Carbon::parse($request->get('date_mon'))->format('Y-m-15');
        $startDay = Carbon::parse($selectedMonth)->startOfMonth()->format('Y-m-d 00:00');
        $endDay = Carbon::parse($selectedMonth)->endOfMonth()->format('Y-m-d 23:59');
        $startDay = Carbon::parse($startDay)->timestamp;
        $endDay = Carbon::parse($endDay)->timestamp;

        if($request->user()->role == 'call'){
            if($request->get('bankAccID') != null){
                $bankAccID = $request->get('bankAccID');
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time_ts', 'desc')
                ->where('web_bank_acc_id',$bankAccID)
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_status','=',0)
                ->where('type','=',1)
                ->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }elseif($request->get('type') != null){
                $type = $request->get('type');
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time_ts', 'desc')
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_status','=',0)
                ->where('type', $type)
                ->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }else{
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time_ts', 'desc')
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_status','=',0)
                ->where('type','=',1)
                ->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }
        }else{
            if($request->get('bankAccID') != null){
                $bankAccID = $request->get('bankAccID');
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time_ts', 'desc')
                ->where('web_bank_acc_id',$bankAccID)
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_status','=',0)
                ->where('type','=',1)
                //->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }elseif($request->get('type') != null){
                $type = $request->get('type');
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time_ts', 'desc')
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_status','=',0)
                ->where('type', $type)
                //->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }else{
                $transections = Transection::orderBy('agent_status','asc')
                ->orderBy('date_time_ts', 'desc')
                ->whereBetween('date_time_ts',[$startDay,$endDay])
                ->where('status','!=',3)
                ->where('agent_status','=',0)
                ->where('type','=',1)
                //->where('agent_id',$request->user()->agent_id)
                ->paginate(50);
            }
        }

        if($request->user()->role == 'call'){
            $webBank = WebBank::where('active',1)
            ->where('agent_id',$request->user()->agent_id)
            ->orderBy('sort','asc')
            ->get();
        }else{
            $webBank = WebBank::where('active',1)
            ->orderBy('sort','asc')
            ->get();
        }

        $depositAcc = [];
        $withdrawalAcc = [];
        $sumDeposit = 0;
        $sumWithdrawal = 0;

        foreach ($webBank as $wb) {

            if($wb->type == 1){
                if($wb->name == $wb->name)
                {

                    $depositAcc[$wb->name . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                        'id' => $wb->id,
                        'bank_id' => $wb->bank_id,
                        'name' => $wb->name,
                        'acc_no' => $wb->acc_no,
                        'total' => $this->sumAccBalance($wb->id,$startDay,$endDay),
                    ];
                }

            }elseif($wb->type == 2){
                if($wb->name == $wb->name){
                    $withdrawalAcc[$wb->name  . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                        'id' => $wb->id,
                        'bank_id' => $wb->bank_id,
                        'name' => $wb->name,
                        'acc_no' => $wb->acc_no,
                        'total' => $this->sumAccBalance($wb->id,$startDay,$endDay),
                    ];
                }
            }
        }
        $sumDeposit = Transection::where('type',1)
        ->where('status','!=',3)
        //->whereBetween('date_time',[$startDay,$endDay])
        ->whereBetween('date_time_ts',[$startDay,$endDay])
        ->sum('amount');
        $sumWithdrawal  = Transection::where('type',2)
        ->where('agent_status','=',1)
        ->where('status','!=',3)
        //->whereBetween('date_time',[$startDay,$endDay])
        ->whereBetween('date_time_ts',[$startDay,$endDay])
        ->sum('amount');

        return view('money/index_wait_confirm',compact(  'date_mon','date_mon_group','transections','depositAcc','withdrawalAcc','sumDeposit','sumWithdrawal'));
    }
    /**
    * SUM ACC
    */
    protected function sumAccBalance($account_id,$startDay,$endDay){

        if($startDay < 1552323600){
            $startDay =  Carbon::createFromTimestamp($startDay)->toDateTimeString();
            $endDay =  Carbon::createFromTimestamp($endDay)->toDateTimeString();
            return Transection::where('web_bank_acc_id',$account_id)
            ->where('status','!=',3)
            ->whereBetween('date_time',[$startDay,$endDay])
            ->sum('amount');
        }else{
            return Transection::where('web_bank_acc_id',$account_id)
            ->where('status','!=',3)
            ->whereBetween('date_time_ts',[$startDay,$endDay])
            ->sum('amount');
        }

    }

    public function deposit(Request $request){
        $validator = Validator::make($request->all(), [
            'date_time' => 'required',
            'amount' => 'required',
            'web_bank_id' => 'required',
        ]);

        if ($validator->fails()) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
        }

        $webBank = WebBank::find($request->get('web_bank_id'));
            //$webBankCode = $this->bankCode($request->get('web_bank_id'));

        $customerID = 0;
        $customer_code = '';
        $csb_code = 0;
        $customer_bank_id = 0;
        $customer_bank_acc = '';


        if($request->get('customer_id') != ''){
            $mm_user_id = trim($request->get('customer_id'));

            $customer = DB::table('customers')
            ->where('mm_user', '=', $mm_user_id)
            ->join('customer_bank_acc', 'customers.id', '=', 'customer_bank_acc.customer_id')
            ->get();
            $customerID = $customer[0]->customer_id;
            $customer_code = $customer[0]->mm_user;
            $customer_bank_id = $customer[0]->customer_bank_id;
            $csb_code = $customer[0]->bank_id;
            $customer_bank_acc = $customer[0]->acc_no;

                //return $customer;
        }

        $dateToday = Carbon::parse($request->get('date'))->format('Y-m-d');
        $dateTime = $dateToday.' '.$request->get('date_time');
        $dateTime = Carbon::parse($dateTime);
        $dateTimeTS =  $dateTime->timestamp;

        Transection::create([
            'type' => 1,
            'customer_id' =>  $customerID,
            'date_time'  => $dateTime,
            'date_time_ts' => $dateTimeTS,
            'customer_code' => $customer_code,
            'csb_bank_id' => $csb_code,
            'cs_bank_acc_id' => $customer_bank_id,
            'cs_bank_acc_no' => $customer_bank_acc,
            'amount'  => $request->get('amount'),
            'credit'  => $request->get('credit'),
            'staff_id'  => $request->user()->id,
            'wb_bank_id'  =>   $webBank->bank_id,
            'web_bank_acc_id'  => $request->get('web_bank_id'),
            'web_bank_acc'  => $webBank->acc_no,
            'web_bank_name'  => $webBank->name,
            'bonus'  => $request->get('bonus'),
            'agent_id' => $request->user()->agent_id,
            'bank_tag' => $webBank->ref,
        ]);
        alert()->success('บันทึกข้อมูลสำเร็จ');
        return redirect()->back();
    }


            /**
            * Withdrawal
            */
            public function withdrawal(Request $request)
            {


                $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'date_time' => 'required',
                    'amount' => 'required',
                    'web_bank_id' => 'required',
                ]);

                if ($validator->fails()) {

                    alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                    return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
                }

                $wd_ball = 0;
                $wd_step = 0;
                $wd_sport = 0;
                $wd_game = 0;
                $wd_casino = 0;
                $wd_lotto = 0;

                $wdtypeCheck = [];
                if($request->get('wd_ball')){
                    array_push($wdtypeCheck,'ball');
                    $wd_ball = 1;
                }
                if($request->get('wd_step')){
                    array_push($wdtypeCheck,'step');
                    $wd_step = 1;
                }
                if($request->get('wd_sport')){
                    array_push($wdtypeCheck,'sport');
                    $wd_sport = 1;
                }
                if($request->get('wd_game')){
                    array_push($wdtypeCheck,'game');
                    $wd_game = 1;
                }
                if($request->get('wd_casino')){
                    array_push($wdtypeCheck,'casino');
                    $wd_casino = 1;
                }
                if($request->get('wd_lotto')){
                    array_push($wdtypeCheck,'lotto');
                    $wd_lotto = 1;
                }

                if(count($wdtypeCheck) == 0){
                    alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบ ลูกค้าเล่นอะไรได้')->autoclose(5000);
                    return redirect()
                    ->back();
                }


                $mm_user = trim($request->get('customer_id'));

                $customer = DB::table('customers')
                ->where('mm_user', '=',  $mm_user)
                ->join('customer_bank_acc', 'customers.id', '=', 'customer_bank_acc.customer_id')
                ->get();

                $webBank = WebBank::find($request->get('web_bank_id'));

                if(count($customer)>0){
                    $customerID = $customer[0]->customer_id;
                    $mm_user = $customer[0]->mm_user;
                    $customer_bank_id = $customer[0]->customer_bank_id;
                    $csb_code = $customer[0]->bank_id;
                    $customer_bank_acc = $customer[0]->acc_no;

                    $before_balance = trim($request->get('before_balance')) ? : 0;
                    $playing = trim($request->get('playing_credit')) ? :0;
                    $amount =  trim($request->get('amount'));

                    $balance = $before_balance + $playing - $amount;

                    $dateToday = Carbon::parse($request->get('date'))->format('Y-m-d');
                    $dateTime = $dateToday.' '.$request->get('date_time');
                    $dateTime = Carbon::parse($dateTime);
                    $dateTimeTS =  $dateTime->timestamp;

                    Transection::create([
                        'type' => 2,
                        'customer_id' =>  $customerID,
                        'date_time'  => $dateTime ,
                        'date_time_ts' => $dateTimeTS,
                        'customer_code' => $mm_user,
                        'csb_bank_id' => $csb_code,
                        'cs_bank_acc_id' => $customer_bank_id,
                        'cs_bank_acc_no' => $customer_bank_acc,
                        'amount'  => trim($request->get('amount')),
                        'staff_id'  => $request->user()->id,
                        'update_by_staff_id'  => $request->user()->id,
                        'wb_bank_id'  =>   $webBank->bank_id,
                        'web_bank_acc_id'  => $request->get('web_bank_id'),
                        'web_bank_acc'  => $webBank->acc_no,
                        'web_bank_name'  => $webBank->name,

                        'before_balance' => trim($request->get('before_balance')),
                        'playing_credit' => trim($request->get('playing_credit')),
                        'balance'   => $balance,
                        'agent_status'   => 1,
                        'status'   => 2,

                        'wd_ball'   => $wd_ball,
                        'wd_step'   => $wd_step,
                        'wd_sport'   => $wd_sport,
                        'wd_game'   => $wd_game,
                        'wd_casino'   => $wd_casino,
                        'wd_lotto'   => $wd_lotto,
                        'bank_tag' => $webBank->ref,
                        'agent_id' => $request->user()->agent_id,

                    ]);


                    $sumDeposit = Transection::where('customer_id',$customerID)
                    ->where('type',1)
                    ->where('status','!=',3)
                    ->sum('amount');

                    $sumWithdrawal = Transection::where('customer_id',$customerID)
                    ->where('type',2)
                    ->where('agent_status','=',1)
                    ->where('status','!=',3)
                    ->sum('amount');

                    if($sumWithdrawal > $sumDeposit ){
                        $diff = $sumWithdrawal - $sumDeposit;

                        $user = $request->user();
                        $setting = Setting::find($user->agent_id);
                        $condition = $setting->withdraw_condition;

                        if($diff > $condition){
                            $message = 'User ID '.$mm_user .' ชื่อ '.$customer[0]->first_name .' '.$customer[0]->last_name. ' มีกำไรจากเว็บ '. number_format($diff,2). ' บาท';
                            try {
                                $this->lineAlert($message);
                            } catch (\Throwable $th) {
                                //throw $th;
                            }

                        }
                    }

                    $messageWD = 'User ID '.$mm_user .' ชื่อ '.$customer[0]->first_name .' '.$customer[0]->last_name. ' แจ้งถอน '. number_format(trim($request->get('amount')),2). ' บาท    พนักงานที่ทำรายการ '. Auth::user()->name;
                    try {
                        $this->lineAlert($messageWD);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }



                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect()->back();

                }else{
                    alert()->error('ไม่พบข้อมูลลูกค้า');
                    return redirect()->back();
                }

            }

            public function withdrawalAPI($tsID,Request $request){

                if($request->get('confirm_type') == 2){

                        $mbet = new MBETAPIController();

                        $validator = Validator::make($request->all(), [
                            'web_bank_id' => 'required',
                            //'before_balance' => 'required',
                        ]);

                        if ($validator->fails()) {

                            alert()->error('กรุณาเลืิอกบัญชีถอน')->autoclose(5000);
                            return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
                        }

                        $ts = Transection::find($tsID);
                        $webBank = WebBank::find($request->get('web_bank_id'));

                        if($webBank){

                        // User Win Loss
                        $userWinLoss = $mbet->checkUserWinloss($ts->customer_code);

                        if($userWinLoss['sflag'] == true){

                        $wd_ball = 0;
                        $wd_step = 0;
                        $wd_sport = 0;
                        $wd_game = 0;
                        $wd_casino = 0;
                        $wd_lotto = 0;

                        foreach ($userWinLoss['data'] as $winLoss) {

                            if($winLoss['wl_type'] == 'sp'){
                                $wd_sport = 1;
                            }
                            if($winLoss['wl_type'] == 'st'){
                                $wd_step = 1;
                            }
                            if($winLoss['wl_type'] == 'gm'){
                                $wd_game = 1;
                            }
                            if($winLoss['wl_type'] == 'cn'){
                                $wd_casino = 1;
                            }
                            if($winLoss['wl_type'] == 'sc'){
                                $wd_ball = 1;
                            }
                            if($winLoss['wl_type'] == 'lr' || $winLoss['wl_type'] == 'lg' || $winLoss['wl_type'] == 'ls' || $winLoss['wl_type'] == 'll'){
                                $wd_lotto = 1;
                            }
                        }
                    }else{
                        $wd_ball = 0;
                        $wd_step = 0;
                        $wd_sport = 0;
                        $wd_game = 0;
                        $wd_casino = 0;
                        $wd_lotto = 0;
                    }

                    $dateToday = Carbon::parse($request->get('date'))->format('Y-m-d');
                    $dateTime = $dateToday.' '.$request->get('time');
                    $dateTime = Carbon::parse($dateTime);
                    $dateTimeTS =  $dateTime->timestamp;

                    $amount = $ts->amount;

                        // User Credit
                         $userCredit = $mbet->checkUserCredit($ts->customer_code);

                         if($userCredit['sflag'] == true){
                            $balance = $userCredit['bet_credit'] ? : 0; //ยอดคงเหลือในบัญชี
                            $playing_credit = $userCredit['outstanding'] ? : 0; //ยอดเล่นค้่างในระบบ
                            $before_credit = $balance+$playing_credit+$amount;
                        }else{
                            $balance = 0;
                            $playing_credit = 0;
                            $before_credit = $balance+$playing_credit+$amount;
                        }

                            $customerID = $ts->customer_id;
                            $mm_user = $ts->customer_code;
                            $customer = Customer::find($ts->customer_id);



                                // Update database
                                $ts->wb_bank_id = $webBank->bank_id;
                                $ts->web_bank_acc_id = $request->get('web_bank_id');
                                $ts->web_bank_acc = $webBank->acc_no;
                                $ts->web_bank_name = $webBank->name;
                                $ts->wd_ball = $wd_ball;
                                $ts->wd_step = $wd_step;
                                $ts->wd_sport = $wd_sport;
                                $ts->wd_game = $wd_game;
                                $ts->wd_casino = $wd_casino;
                                $ts->wd_lotto = $wd_lotto;
                                $ts->agent_status = 1;
                                $ts->balance = $balance;
                                $ts->playing_credit = $playing_credit;
                                $ts->before_balance = $before_credit;
                                $ts->update_by_staff_id = $request->user()->id;
                                $ts->date_time = $dateTime;
                                $ts->date_time_ts = $dateTimeTS;
                                $ts->save();


                            $sumDeposit = Transection::where('customer_id',$customerID)
                            ->where('type',1)
                            ->where('status','!=',3)
                            ->sum('amount');


                            $sumWithdrawal = Transection::where('customer_id',$customerID)
                            ->where('type',2)
                            ->where('status','!=',3)
                            ->sum('amount');

                            if($sumWithdrawal > $sumDeposit ){
                               $diff = $sumWithdrawal - $sumDeposit;
                               $user = $request->user();
                               $setting = Setting::find($user->agent_id);
                               $condition = $setting->withdraw_condition;

                               if($diff > $condition){
                                $message = "\n".'📄 UserID '.$mm_user ."\n".'👤 ชื่อ '.$customer->first_name .' '.$customer->last_name."\n".'💰 มีกำไรจากเว็บ '. number_format($diff,2). ' บาท'."\n".'👎 ยอดถอนครั้งนี้ '.number_format($ts->amount,2).' บาท';
                                try {
                                    $this->lineAlert($message);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }

                            }
                        }

                        $messageWithdrawal = 'User ID '.$mm_user .' ชื่อ '.$customer->first_name .' '.$customer->last_name. ' แจ้งถอน '. number_format($ts->amount,2). ' บาท    พนักงานที่ทำรายการ '. Auth::user()->name;
                        try {
                            $this->lineAlert($messageWithdrawal);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }

                        alert()->success('บันทึกข้อมูลสำเร็จ');
                        return redirect()->back();

                    }else{
                        alert()->error('ไม่พบข้อมูลลูกค้า');
                        return redirect()->back();
                    }
                }else{
                    $ts = Transection::find($tsID);
                    $ts->status = 3;
                    $ts->save();
                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect()->back();
                }

    }


                    /**
                    * CUT off
                    */
                    public function cutOff(Request $request){

                        if($request->user()->role == 'call'){
                            alert()->error('คุณไม่สามารถเข้าถึงข้อมูลนี้ได้');
                            return redirect('/money/index');
                        }

                        $webBank = WebBank::where('type',1)->where('active',1)->get();

                        if($request->get('date') == null){
                            $date = Carbon::now()->format('Y-m-d');
                            $startDay = Carbon::now()->startOfDay()->timestamp;
                            $endDay = Carbon::now()->endOfDay()->timestamp;



                        }else{
                            $date = Carbon::parse($request->get('date'))->format('Y-m-d');
                            $startDay = Carbon::parse($date)->timestamp;
                            $endDay = Carbon::parse($date)->timestamp;
                        }

                        $webBank = WebBank::where('active',1)
                        ->orderBy('sort','asc')
                        ->get();

                        $depositAcc = [];
                        $withdrawalAcc = [];
                        $sumDeposit = 0;
                        $sumWithdrawal = 0;

                        foreach ($webBank as $wb) {

                            if($wb->type == 1){
                                if($wb->name == $wb->name)
                                {
                                    $depositAcc[$wb->name  . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                                        'id' => $wb->id,
                                        'bank_id' => $wb->bank_id,
                                        'name' => $wb->name,
                                        'acc_no' => $wb->acc_no,

                                        '6' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.'  06:00')->timestamp])
                                        ->where('type',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        '12' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  06:01')->timestamp,Carbon::parse($date.'  12:00')->timestamp])
                                        ->where('type',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        '18' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  12:01')->timestamp,Carbon::parse($date.'  18:00')->timestamp])
                                        ->where('type',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        '24' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  18:01')->timestamp,Carbon::parse($date.'  23:59')->timestamp])
                                        ->where('type',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        'all' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.'  23:59')->timestamp])
                                        ->where('type',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),
                                    ];

                                }

                            }elseif($wb->type == 2){
                                if($wb->name == $wb->name){
                                    $withdrawalAcc[$wb->name  . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                                        'id' => $wb->id,
                                        'bank_id' => $wb->bank_id,
                                        'name' => $wb->name,
                                        'acc_no' => $wb->acc_no,

                                        '6' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.'  06:00')->timestamp])
                                        ->where('type',2)
                                        ->where('status','!=',3)
                                        ->where('agent_status','=',1)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        '12' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  06:01')->timestamp,Carbon::parse($date.'  12:00')->timestamp])
                                        ->where('type',2)
                                        ->where('status','!=',3)
                                        ->where('agent_status','=',1)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        '18' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  12:01')->timestamp,Carbon::parse($date.'  18:00')->timestamp])
                                        ->where('type',2)
                                        ->where('status','!=',3)
                                        ->where('agent_status','=',1)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        '24' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  18:01')->timestamp,Carbon::parse($date.'  23:59')->timestamp])
                                        ->where('type',2)
                                        ->where('agent_status','=',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),

                                        'all' => Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.'  23:59')->timestamp])
                                        ->where('type',2)
                                        ->where('agent_status','=',1)
                                        ->where('status','!=',3)
                                        ->where('web_bank_acc_id',$wb->id)
                                        ->sum('amount'),
                                    ];

                                }
                            }
                        }

                        /**
                        * Deposit
                        */
                        $time_06 = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.'  06:00')->timestamp])
                        ->where('type',1)
                        ->where('status','!=',3)
                        ->sum('amount');

                        $time_12 = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  06:01')->timestamp,Carbon::parse($date.'  12:00')->timestamp])
                        ->where('type',1)
                        ->where('status','!=',3)
                        ->sum('amount');


                        $time_18 = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  12:01')->timestamp,Carbon::parse($date.'  18:00')->timestamp])
                        ->where('type',1)
                        ->where('status','!=',3)
                        ->sum('amount');



                        $time_24 = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  18:01')->timestamp,Carbon::parse($date.'  23:59')->timestamp])
                        ->where('type',1)
                        ->where('status','!=',3)
                        ->sum('amount');

                        /**
                        * Withdrawal
                        */
                        $time_06_wd = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.'  06:00')->timestamp])
                        ->where('type',2)
                        ->where('agent_status','=',1)
                        ->where('status','!=',3)
                        ->sum('amount');

                        $time_12_wd = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  06:01')->timestamp,Carbon::parse($date.'  12:00')->timestamp])
                        ->where('type',2)
                        ->where('agent_status','=',1)
                        ->where('status','!=',3)
                        ->sum('amount');

                        $time_18_wd = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  12:01')->timestamp,Carbon::parse($date.'  18:00')->timestamp])
                        ->where('type',2)
                        ->where('agent_status','=',1)
                        ->where('status','!=',3)
                        ->sum('amount');

                        $time_24_wd = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  18:01')->timestamp,Carbon::parse($date.'  23:59')->timestamp])
                        ->where('type',2)
                        ->where('agent_status','=',1)
                        ->where('status','!=',3)
                        ->sum('amount');

                        /**
                        * Total
                        */
                        $dpTotal = $time_06+$time_12+$time_18+$time_24;
                        $wdTotal = $time_06_wd+$time_12_wd+$time_18_wd+$time_24_wd;

                        return view('money/cut-off',compact('date','depositAcc','withdrawalAcc','sumDeposit','sumWithdrawal','time_06','time_12','time_18','time_24','time_06_wd', 'time_12_wd', 'time_18_wd' ,'time_24_wd','wdTotal','dpTotal'));
                    }

                    /**
                    * Close statement
                    */
                    public function closeStatement(Request $request){
                        return view('money/close-statement');
                    }


                    /**
                    * Confirm transection
                    */
                    public function confirmTransection($ts_id,Request $request){

                        $transection = Transection::find($ts_id);
                        if($request->get('confirm_type') == '2'){
                            if($request->get('customer_code') != ''){
                                $promotionSeach = Promotion::where('status',1)->first();
                                $bonus_match = 0;
                                $bonus_claim = 0;
                                $imagePromotion = '';
                                if($promotionSeach){
                                    $proMatch = $this->promotion_match($request->get('customer_code'),$promotionSeach->id);
                                    if($proMatch){
                                        $bonus_match = 1;

                                        if($request->get('bonus_claim') == 1){
                                            $tsUpdate = Transection::where('bonus_match',1)
                                            ->where('customer_code',$request->get('customer_code'))
                                            ->get();

                                            foreach ($tsUpdate as $key => $value) {
                                                if($value->id != $transection->id){
                                                    $ts = Transection::find($value->id);
                                                    $ts->bonus_match = 0;
                                                    $ts->bonus_claim = 0;
                                                    $ts->save();
                                                }
                                            }


                                            $updatePromotionMatch = PromotionMatch::where('customer_code',$request->get('customer_code'))->first();
                                            $updatePromotionMatch->claim = 1;
                                            $updatePromotionMatch->save();
                                        }

                                        if($request->get('bonus_claim') == 0){
                                            $bonus_match = 1;
                                            $promotionSeach = Promotion::where('status',1)->first();

                                            $tsUpdate = Transection::where('customer_code',$request->get('customer_code'))
                                            ->whereBetween('date_time',[$promotionSeach->start_date,$promotionSeach->end_date])
                                            ->update([
                                                'bonus_match' => 1,
                                                'bonus_claim' => 0,
                                            ]);


                                            $updatePromotionMatch = PromotionMatch::where('customer_code',$request->get('customer_code'))
                                            ->first();
                                            $updatePromotionMatch->claim = 0;
                                            $updatePromotionMatch->save();
                                        }

                                        try{
                                            $sourceFile = public_path('images/promotion.jpg');
                                            $destinationFile = public_path('images/promotion-create/'.$promotionSeach->id.'_'.$request->get('customer_code').'_mark.jpg');
                                            $imagePromotion = 'images/promotion-create/'.$promotionSeach->id.'_'.$request->get('customer_code').'_mark.jpg';
                                            $this->watermarkImage($sourceFile,$destinationFile,$request->get('customer_code'));

                                        }catch(Exception $ex){

                                        }

                                        $transection->bonus_match = $bonus_match;
                                        $transection->bonus_claim = $request->get('bonus_claim');

                                    }
                                }

                                $customer_code = trim($request->get('customer_code'));
                                $customer = DB::table('customers')
                                ->where('mm_user', '=',  $customer_code)
                                ->join('customer_bank_acc', 'customers.id', '=', 'customer_bank_acc.customer_id')
                                ->get();
                                $customerID = $customer[0]->customer_id;
                                $mm_user = $customer[0]->mm_user;
                                $customer_bank_id = $customer[0]->customer_bank_id;
                                $csb_code = $customer[0]->bank_id;
                                $customer_bank_acc = $customer[0]->acc_no;

                                $before_balance = trim($request->get('before_balance')) ? : 0;
                                $playing = trim($request->get('playing_credit')) ? :0;


                                $balance =  $before_balance + $playing +$transection->amount;


                                $transection->status = 2;
                                $transection->customer_code = $mm_user;
                                $transection->customer_id = $customerID;

                                $transection->csb_bank_id = $csb_code;
                                $transection->cs_bank_acc_id = $customer_bank_id;
                                $transection->cs_bank_acc_no = $customer_bank_acc;
                                $transection->credit = trim($request->get('credit'));
                                $transection->before_balance = trim($request->get('before_balance'));
                                $transection->playing_credit = trim($request->get('playing_credit'));
                                $transection->balance = $balance;

                                $transection->channel = $request->get('channel');


                                $transection->update_by_staff_id = $request->user()->id;
                                $transection->note = $request->get('note');

                                $transection->bonus_image = $imagePromotion;
                                $transection->auto_doposit_api = $request->get('auto_doposit_api');

                                $transection->save();

                                    /**
                                    * API MM88BET
                                    */
                                    if($request->get('auto_doposit_api') == 1){
                                        $mbet = new MBETAPIController();

                                        $checkData = Transection::find($ts_id);
                                        if($checkData->agent_status == 0 || $request->user()->role != 'call'){
                                            $mbet->deposit($ts_id,$mm_user);
                                        }else{
                                            alert()->error('ทำรายซำ้ !')->autoclose(5000);;
                                            return redirect()->back();
                                        }
                                    }

                                    alert()->success('บันทึกข้อมูลสำเร็จ');
                                    return redirect()->back();
                                }else{
                                    alert()->error('กรุณาตรวจสอบรหัสลูกค้าอีกครั้ง');
                                    return redirect()->back();
                                }
                            }

                            if($request->get('confirm_type') == '3'){

                                $transection->status = 3;
                                $transection->agent_status = $request->get('agent_status');
                                $transection->update_by_staff_id = $request->user()->id;
                                $transection->note = $request->get('note');
                                $transection->save();

                                alert()->success('บันทึกข้อมูลสำเร็จ');
                                return redirect()->back();
                            }
                        }

                        protected function bankInfo($bankID){
                            return Banks::find($bankID);
                        }

                        protected function bankCode($bankID){
                            switch ($bankID) {
                                case 2:
                                $bankCode = 'BBL';
                                break;
                                case 4:
                                $bankCode = 'KBANK';
                                break;
                                case 6:
                                $bankCode = 'KTB';
                                break;
                                case 11:
                                $bankCode = 'TMB';
                                break;
                                case 14:
                                $bankCode = 'SCB';
                                break;
                                case 24:
                                $bankCode = 'UOB';
                                break;
                                case 25:
                                $bankCode = 'BAY';
                                break;
                                case 30:
                                $bankCode = 'GSB';
                                break;
                                case 33:
                                $bankCode = 'GHB';
                                break;
                                case 65:
                                $bankCode = 'TBANK';
                                break;
                                case 30:
                                $bankCode = 'GSB';
                                break;
                                case 73:
                                $bankCode = 'LH';
                                break;

                                default:
                                # code...
                                break;

                                return $bankCode;
                            }
                        }


                        /**
                        * Line Nortify
                        */
                        protected function lineAlert($message){
                            $curl = curl_init();
                            $user = Auth::user();
                            $setting = Setting::find($user->agent_id);
                            $token = $setting->line_token_withdrawal;

                            curl_setopt_array($curl, array(
                                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n$message\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
                                CURLOPT_HTTPHEADER => array(
                                    "Authorization: Bearer $token",
                                    "Postman-Token: 9f199469-4a59-44c3-9bf5-1c71356bbe25",
                                    "cache-control: no-cache",
                                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
                                ),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);

                            if ($err) {
                                echo "cURL Error #:" . $err;
                            } else {
                                echo $response;
                            }
                        }


                        public function promotion_match($customer_code,$promotion_id){
                            $isMatch = PromotionMatch::where('promotion_id',$promotion_id)
                            ->where('customer_code',$customer_code)
                            ->where('claim',0)
                            ->first();

                            if($isMatch){
                                return true;
                            }else{
                                return false;
                            }
                        }

                        public function watermarkImage ($sourceFile, $destinationFile,$waterMarkText) {

                            list($width, $height) = getimagesize($sourceFile);
                            $image_p = imagecreatetruecolor($width, $height);

                            $image = imagecreatefromjpeg($sourceFile);
                            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
                            $black = imagecolorallocate($image_p, 255, 0, 0);//กำหนดสี
                            $font = public_path('fonts/CORDIA.TTF');//กำหนดชื่อฟอนต์
                            $font_size = 25; //กำหนดขนาดฟอนต์
                            imagettftext($image_p, $font_size, 0, 420, 90, $black, $font, $waterMarkText);
                            //อธิบาย imagettftext($image_p,ขนาดฟอนต์,องศ์ษา,แนวนอน,แนวตั้ง,สี,ชื่อฟอร์ตที่ใช้,ข้อความ);
                            if ($destinationFile<>'') {
                                $status = imagejpeg ($image_p, $destinationFile, 100);
                            } else {
                                header('Content-type: image/jpeg');
                                $status =  imagejpeg($image_p, null, 100);

                            };
                            imagedestroy($image);
                            imagedestroy($image_p);

                            return $status;

                        }


                        public function cutOffDb(Request $request){

                            if($request->user()->role == 'call'){
                                alert()->error('คุณไม่สามารถเข้าถึงข้อมูลนี้ได้');
                                return redirect('/money/index');
                            }

                            $webBank = WebBank::where('type',1)->where('active',1)->get();

                            if($request->get('date') == null){
                                $date = Carbon::now()->format('Y-m-d');
                                $startDay = Carbon::now()->startOfDay()->timestamp;
                                $endDay = Carbon::now()->endOfDay()->timestamp;



                            }else{
                                $date = Carbon::parse($request->get('date'))->format('Y-m-d');
                                $startDay = Carbon::parse($date)->timestamp;
                                $endDay = Carbon::parse($date)->timestamp;
                            }

                            $webBank = WebBank::where('active',1)
                            ->orderBy('sort','asc')
                            ->get();

                            $cutOfDepositTime= CutOff::where('type',1)
                            ->orwhere('type',3)
                            ->orderBy('name','asc')
                            ->orderBy('start_time','asc')
                            ->get();


                            $cutOfWithdrawTime= CutOff::where('type',2)
                            ->orderBy('name','asc')
                            ->orderBy('start_time','asc')
                            ->get();


                            $depositAcc = [];
                            $withdrawalAcc = [];

                            foreach ($webBank as $wb) {

                                if($wb->type == 1){
                                    if($wb->name == $wb->name)
                                    {



                                        $depositAcc[$wb->name  . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [ 'id' => $wb->id,
                                        'bank_id' => $wb->bank_id,
                                        'name' => $wb->name,
                                        'acc_no' => $wb->acc_no ,
                                        'time' => $this->timeAccDeposit($wb->id,$date,$cutOfDepositTime)


                                    ];

                                }

                            }elseif($wb->type == 2){
                                if($wb->name == $wb->name){
                                    $withdrawalAcc[$wb->name  . ' (' .Setting::where('id',$wb->agent_id)->first()->agent_name.')'][] = [
                                        'id' => $wb->id,
                                        'bank_id' => $wb->bank_id,
                                        'name' => $wb->name,
                                        'acc_no' => $wb->acc_no,
                                        'time' => $this->timeAccWithdraw($wb->id,$date,$cutOfWithdrawTime)

                                    ];


                                }
                            }
                        }

                            /**
                            * Deposit & Withdrawal
                            */

                            $sumDeposit = $this->sumDeposit ($date,$cutOfDepositTime);
                            $sumWithdrawal = $this->sumWithdraw ($date,$cutOfWithdrawTime);



                            return view('money/cut-off-db',compact('date','depositAcc','withdrawalAcc','cutOfDepositTime','cutOfWithdrawTime','sumDeposit','sumWithdrawal'));
                        }


                        public function timeAccDeposit($webBankID,$date,$cutOfDepositTime){



                            foreach($cutOfDepositTime as $time){

                                $data[$time->name] =

                                Transection::whereBetween('date_time_ts',[Carbon::parse($date.$time->start_time)->timestamp,Carbon::parse($date.$time->end_time)->timestamp])
                                ->where('type',1)
                                ->where('status','!=',3)
                                ->where('web_bank_acc_id',$webBankID)
                                ->sum('amount') ;

                            }

                            $data['all'] =

                            Transection::whereBetween('date_time_ts',[Carbon::parse($date.'00.00')->timestamp,Carbon::parse($date.'23.59')->timestamp])
                            ->where('type',1)
                            ->where('status','!=',3)
                            ->where('web_bank_acc_id',$webBankID)
                            ->sum('amount') ;

                            return $data ;

                        }

                        public function timeAccWithdraw($webBankID,$date,$cutOfWithdrawTime){

                            foreach($cutOfWithdrawTime as $time){

                                $data[$time->name] =

                                Transection::whereBetween('date_time_ts',[Carbon::parse($date.$time->start_time)->timestamp,Carbon::parse($date.$time->end_time)->timestamp])
                                ->where('type',2)
                                ->where('agent_status','=',1)
                                ->where('status','!=',3)
                                ->where('web_bank_acc_id',$webBankID)
                                ->sum('amount') ;

                            }

                            $data['all'] =

                            Transection::whereBetween('date_time_ts',[Carbon::parse($date.'00.00')->timestamp,Carbon::parse($date.'23.59')->timestamp])
                            ->where('type',2)
                            ->where('agent_status','=',1)
                            ->where('status','!=',3)
                            ->where('web_bank_acc_id',$webBankID)
                            ->sum('amount') ;

                            return $data ;

                        }

                        public function sumDeposit($date,$cutOfDepositTime){
                            $sum=0;

                            foreach($cutOfDepositTime as $time){

                                $data[$time->name] =
                                Transection::whereBetween('date_time_ts',[Carbon::parse($date.$time->start_time)->timestamp,Carbon::parse($date.$time->end_time)->timestamp])
                                ->where('type',1)
                                ->where('status','!=',3)
                                ->sum('amount');

                                if($time->type!=3){
                                    $sum=$sum+Transection::whereBetween('date_time_ts',[Carbon::parse($date.$time->start_time)->timestamp,Carbon::parse($date.$time->end_time)->timestamp])
                                    ->where('type',1)
                                    ->where('status','!=',3)
                                    ->sum('amount');
                                }

                            }
                            $data['all']=$sum;


                            return $data;

                        }

                        public function sumWithdraw($date,$cutOfWithdrawTime){
                            $sum=0;

                            foreach($cutOfWithdrawTime as $time){

                                $data[$time->name] =
                                Transection::whereBetween('date_time_ts',[Carbon::parse($date.$time->start_time)->timestamp,Carbon::parse($date.$time->end_time)->timestamp])
                                ->where('type',2)
                                ->where('agent_status','=',1)
                                ->where('status','!=',3)
                                ->sum('amount');


                                $sum=$sum+Transection::whereBetween('date_time_ts',[Carbon::parse($date.$time->start_time)->timestamp,Carbon::parse($date.$time->end_time)->timestamp])
                                ->where('type',2)
                                ->where('agent_status','=',1)
                                ->where('status','!=',3)
                                ->sum('amount');


                            }

                            $data['all']=$sum;

                            return $data;

                        }

                    }
