<?php

namespace App\Http\Controllers;

use Alert;
use App\User;
use App\Banks;
use App\Setting;
use App\Customer;
use App\Blacklist;
use App\Marketing;
use Carbon\Carbon;
use App\Transection;
use App\CustomerBank;
use App\CustomerMaster;
use App\NewCustomerZean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function userList(Request $request)
    {

        $marketing = Marketing::all();
        $customers = [];
        $customerSearch = [];
        $transections = [];
        $masterBanks = Banks::all();

        if($request->get('q') != null){
            $query = $request->get('q');

            $explodeName = explode(' ',$query);

            if(count($explodeName) > 1){

                $customerSearch = Customer::where('first_name','like','%' . trim(current($explodeName)) . '%')
                ->orWhere('last_name','like','%' . trim(end($explodeName)) . '%')
                ->orWhere('mm_user','like','%' . $query . '%')
                ->orWhere('tel','like','%' . $query . '%')
                ->orWhere('line_id','like','%' . $query . '%')
                ->orWhere('acc_no','like','%' . $query . '%')
                ->where('active','=',1)
                ->paginate(100);
            }else{
                $customerSearch = Customer::where('first_name','like','%' . $query . '%')
                ->orWhere('last_name','like','%' . $query . '%')
                ->orWhere('mm_user','like','%' . $query . '%')
                ->orWhere('tel','like','%' . $query . '%')
                ->orWhere('line_id','like','%' . $query . '%')
                ->orWhere('acc_no','like','%' . $query . '%')
                ->where('active','=',1)
                ->paginate(100);
            }
        }

        $startDate = '';
        $endDate = '';
        $text = '';

        if($request->get('customer_id') != null){

            $customers = [];

            $customerID = $request->get('customer_id');

            $customers = DB::table('customers')
            ->where('customers.id', '=', $customerID)
            ->join('customer_bank_acc', 'customers.id', '=', 'customer_bank_acc.customer_id')
            ->get();



            foreach ($customers as $key => $cs) {
                $customers = [
                    'customer_id' => $cs->customer_id,
                    'mm_user' => $cs->mm_user,
                    'first_name' => $cs->first_name,
                    'last_name' => $cs->last_name,
                    'birth_date' => $cs->birth_date,
                    'tel' => $cs->tel,
                    'line_id' => $cs->line_id,
                    'customer_bank_acc_id' => $cs->customer_bank_id,
                    'bank_id' => $cs->bank_id,
                    'acc_no' => $cs->acc_no,
                    'ref' => $cs->ref,
                    'first_deposit' => $cs->first_deposit,
                    'open_user_date' => $cs->open_user_date,
                    'extra' => $cs->extra,
                    'active' => $cs->active,
                    'staff_id' => $cs->staff_id,
                    'staff_name' => User::find($cs->staff_id)->name,
                    'status' => $cs->status,
                    'user_note' => $cs->user_note,
                    'address_1' => $cs->address_1,
                    'address_2' => $cs->address_2,
                    'address_3' => $cs->address_3,
                ];
            }

            $transections = Transection::where('customer_id',$customerID)
            ->where('status','!=',3)
            ->orderBy('date_time','desc')
            ->paginate(100);


            $deposit =  Transection::where('customer_id',$customerID)
            ->where('type',1)
            ->where('status','!=',3)
            ->sum('amount');

            $withdrawal =  Transection::where('customer_id',$customerID)
            ->where('type',2)
            ->where('agent_status',1)
            ->where('status','!=',3)
            ->sum('amount');


            if($request->get('date_start') != null){
                $startDate = Carbon::parse($request->get('date_start'))->format('Y-m-d');
                $endDate = Carbon::parse($request->get('date_end'))->format('Y-m-d');

                $transections = Transection::where('customer_id',$customerID)
                ->whereBetween('date_time',[$startDate,$endDate])
                ->orderBy('date_time','desc')
                ->paginate(100);

                $deposit =  Transection::where('customer_id',$customerID)
                ->where('type',1)
                ->whereBetween('date_time',[$startDate,$endDate])
                ->where('status','!=',3)
                ->sum('amount');

                $withdrawal =  Transection::where('customer_id',$customerID)
                ->where('type',2)
                ->whereBetween('date_time',[$startDate,$endDate])
                ->where('agent_status',1)
                ->where('status','!=',3)
                ->sum('amount');

            }

            $customerWealth = $withdrawal - $deposit;

            if($customerWealth > 0){
                $text = 'ลูกค้ากำไร';
            }else{
                $text = 'ลูกค้าขาดทุน';
            }

            $customers['user_deposit'] = $deposit;
            $customers['user_withdrawal'] = $withdrawal;
            $customers['user_wealth'] =  $customerWealth;

            //return $customers;

        }

        return view('customer/customer-list',compact('customers','customerSearch','transections','masterBanks','startDate','endDate','marketing','text'));
    }

    public function register(Request $request)
    {
        $marketing = Marketing::where('active',1)->get();
        $banks = Banks::where('active','=',1)->get();

        $customerFound = [];
        $blacklistFound = [];
        $today = Carbon::now()->format('Y-m-d');

        if($request->get('q') != null){


            $query = $request->get('q');

            $explodeName = explode(' ',$query);

            if(count($explodeName) > 1){

                $customerFound = Customer::where('first_name','like','%' . trim(current($explodeName)) . '%')
                ->orWhere('last_name','like','%' . trim(end($explodeName)) . '%')
                ->where('active','=',1)
                ->get();

            }else{
                $customerFound = Customer::where('first_name','like','%' . $query . '%')
                ->orWhere('last_name','like','%' . $query . '%')
                ->orWhere('mm_user','like','%' . $query . '%')
                ->orWhere('tel','like','%' . $query . '%')
                ->orWhere('line_id','like','%' . $query . '%')
                ->orWhere('acc_no','like','%' . $query . '%')
                ->where('active','=',1)
                ->get();

                $blacklistFound = Blacklist::where('full_name','like','%' . $query . '%')
                ->orWhere('tel','like','%' . $query . '%')
                ->orWhere('line_id','like','%' . $query . '%')
                ->orWhere('acc_no','like','%' . $query . '%')
                ->get();
            }

        }

        $setting = Setting::where('id',$request->user()->agent_id)->first();

        return view('customer/create-user',compact('marketing','banks','request','blacklistFound','customerFound','today','setting'));


    }
    public function registerPost(Request $request)
    {
        $setting = Setting::where('id',$request->user()->agent_id)->first();
        //$setting = Setting::find(1);
        $customer_code = $setting->customer_prefix.$request->get('mm_user');

        $validator = Validator::make($request->all(), [
            'mm_user' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'ref' => 'required',
            'tel' => 'required',
            'bank_id' => 'required',
            'acc_no' => 'required',
            ]);

            if ($validator->fails()) {

                alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);

                return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();


            }

            $customerDuplicateChceker = Customer::where('mm_user','=',trim($customer_code))
            ->count();

            if($customerDuplicateChceker > 0){
                alert()->error('บันทึกข้อมูลไม่สำเร็จ ตรวจพบข้อมูลที่มีในระบบแล้ว')->autoclose(5000);
                return redirect()->back();
            }


            $firstDeposit = $request->get('first_deposit') ? :0;

            try {
                $customer =  Customer::create([
                    'mm_user' => trim($customer_code),
                    'first_name' => trim($request->get('first_name')),
                    'last_name' => trim($request->get('last_name')),
                    'tel' => $request->get('tel'),
                    'line_id' => trim($request->get('line_id')),
                    'bank_id' => $request->get('bank_id'),
                    'acc_no' => trim($request->get('acc_no')),
                    'ref' => $request->get('ref'),
                    'user_note' => $request->get('user_note'),
                    'first_deposit' =>  trim($firstDeposit),
                    'open_user_date' => $request->get('open_user_date'),
                    'birth_date' => $request->get('birth_date'),
                    'extra' => $request->get('extra'),
                    'staff_id' => $request->user()->id,
                    'agent_id' => $request->user()->agent_id,
                    ]);

                    $customer_bank_id = CustomerBank::create([
                        'bank_id' => $request->get('bank_id'),
                        'acc_no' =>trim($request->get('acc_no')),
                        'customer_id' => $customer->id,
                        ]);

                        $updateCustomerBank = Customer::find($customer->id);
                        $updateCustomerBank->customer_bank_id = $customer_bank_id->id;
                        $updateCustomerBank->save();

                        if($request->get('create_on_agent')== 1){
                            $mbet = new  MBETAPIController();
                            $user_master = Setting::find($request->user()->agent_id);
                            $arrData = array(
                                'user_master'   => $user_master->master_user,
                                'user_create'   => $customer_code,
                                'user_password' => $request->get('tel'),
                                'skip_changepass' => 'N',
                                'user_credit'   => 0,
                                'fullname'      => trim($request->get('first_name')).' '.trim($request->get('last_name')),
                                'telephone'     => $request->get('tel'),
                                'agent'         => $user_master->agent_code,
                                'date'          => date('Y-m-d H:i:s')
                            );

                            $arrData  = json_encode($arrData);
                            $mbet->createUserFromMaster($arrData,$customer_code);

                        }

                        alert()->success('บันทึกข้อมูลสำเร็จ');
                        return redirect('/customer/user-list?customer_id='.$customer->id);

                    } catch (QueryException $e) {
                        return $e;
                        alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                        return redirect()->back();
                    }

                }

                public function updateCustomer($id,Request $request){

                    //return $request->get('ref');
                    $customer = Customer::find($id);

                    if($request->user()->role != 'call'){
                        $customer->ref = $request->get('ref');
                    }else{
                        $customer->ref =  $customer->ref;
                    }


                    $customer->first_name = $request->get('first_name');
                    $customer->last_name = $request->get('last_name');
                    $customer->birth_date = $request->get('birth_date');
                    $customer->tel = $request->get('tel');
                    $customer->line_id = $request->get('line_id');
                    $customer->bank_id = $request->get('bank_id');
                    $customer->acc_no = $request->get('acc_no');
                    $customer->first_deposit = $request->get('first_deposit');
                    $customer->user_note = $request->get('user_note');
                    $customer->extra = $request->get('extra');
                    $customer->staff_confirm_id = $request->get('staff_confirm_id');
                    $customer->status = $request->get('status');
                    $customer->address_1 = $request->get('address_1');
                    $customer->address_2 = $request->get('address_2');
                    $customer->address_3 = $request->get('address_3');

                    $customer->save();

                    $bookBank = CustomerBank::where('customer_id',$id)->first();
                    $bookBank->acc_no = $request->get('acc_no');
                    $bookBank->bank_id = $request->get('bank_id');

                    $bookBank->save();

                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect()->back();

                }


                public function waitForOpen(Request $request){

                    $data = NewCustomerZean::where('status',0)->paginate(50);
                    return view('customer/waiting',compact('data'));
                }


                public function waitForOpenDetail($id,Request $request){
                    if($request->user()->role != 'zean'){
                        $masterBanks = Banks::all();
                        $customer = NewCustomerZean::find($id);
                        return view('customer/waiting-detail',compact('customer','masterBanks'));
                    }else{
                        alert()->error('ไม่สามารถเข้าถึงข้อมูลส่วนนี้ได้');
                        return redirect('https://zean.mm88up.com');
                    }
                }

                public function closeJobNewUser($id,Request $request){
                    $customer = NewCustomerZean::find($id);
                    $customer->status = 1;
                    $customer->staff_id = $request->user()->id;
                    $customer->save();

                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect('/customer/wait-for-open');
                }
                public function cancelJobNewUser($id,Request $request){
                    $customer = NewCustomerZean::find($id);
                    $customer->status = 2;
                    $customer->staff_id = $request->user()->id;
                    $customer->save();

                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect('/customer/wait-for-open');
                }

            }
