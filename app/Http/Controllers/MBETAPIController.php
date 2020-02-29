<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Customer;
use Carbon\Carbon;
use App\Transection;
use App\CustomerMaster;
use App\CustomerMasterLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MBETAPIController extends Controller
{
    protected $user;
    protected $setting;
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cashWeb = 'mm8bet';
        $this->cashDesKey = 'srZzAYFe8Z5JAzsUVju7BiqbA9XslWjM';
        //$this->casAPIUrl = 'https://cashapiv2.sunmacau.com';
        $this->casAPIUrl = 'https://cashapiv3.sunmacau.com';
    }

    public function deposit($transectionID,$mm_user){


        $transection = Transection::find($transectionID);
        $customer = Customer::find($transection->customer_id);

        $setting = Setting::find($customer->agent_id);
        $agent = $setting->customer_prefix;

        // Cancel deposit transection
        $this->getDepositList($agent,$mm_user);

        $csBankID = $this->getBankID($transection->csb_bank_id);
        $webBankID = $this->getBankID($transection->wb_bank_id);

        /**
         *  Agent key
         */
        $agentKey = $setting->agent_key;
        // Log::debug('Agent Key '.  $agentKey);

        $arrData = array(
            //'agent'                 => $mm_user,
            'agent'                 => $agent,
            'username'              => $transection->customer_code,
            'type_deposit'          => $transection->channel,
            'bank_id'               => $csBankID,
            'amount'                => $transection->amount,
            'bank_account_name'     => $customer->first_name. ' ' .$customer->last_name,
            'bank_account_number'   => $transection->cs_bank_acc_no,
            'branch'                =>'-',
            'deposit_datetime'      => Carbon::parse($transection->date_time)->format('d/m/Y H:i'),
            'to_bank_id'            => $webBankID,
            'to_bank_account_number'=> $transection->web_bank_acc,
            'date'                  => date('Y-m-d H:i:s')
        );

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/deposit_user',"post",$param);

        Log::debug("Data sent " .$data);

        $returnData = json_decode($data,true);

        $transection->auto_doposit_api = 1;
        $transection->auto_doposit_msg = $returnData['msg'];

        if($returnData['msg'] == 'success' || $returnData['msg'] ==  'wait checking'){
            $transection->agent_status = 1;
            $transection->ds_api_id = $returnData['deposit_id'];
        }

        // complete transaction and add credit
        if($transection->is_sms == 1){
            $isHuman = 0;
        }else{
            $isHuman = 1; 
        }
        $this->updateDepositID($mm_user,$returnData['deposit_id'],$isHuman);

        $transection->save();

    }

    // complete transaction and add credit
    public function updateDepositID($mm_user,$dsID,$isHuman){

        $customer = Customer::where('mm_user',$mm_user)->first();
        $setting = Setting::find($customer->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;

        if($isHuman == 1){
            $arrData = array( 
                'ds_id'      	=> $dsID, 
                'username'      => $mm_user, 
                'status'        => 'Y',
                'skip_b'        => 'Y',
                'agent'         => $agent, 
                'date'          => date('Y-m-d H:i:s')
            );
        }else{
            $arrData = array( 
                'ds_id'      	=> $dsID, 
                'username'      => $mm_user, 
                'status'        => 'Y',
                'agent'         => $agent, 
                'date'          => date('Y-m-d H:i:s')
            );
        }
  

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/depositUpdate',"post",$param);

        $returnData = json_decode($data,true);

        if($returnData['msg'] == 'success'){

            return response()->json([
                'status' => true,
                'msg' => 'Deposit '.$dsID.' is updated'
            ],201);

        }else{
            return response()->json([
                'status' => false,
                'msg' => $returnData['msg']
            ],401);

        }
    }


    public function withdrawal($mm_user,$amount){

        $customer = Customer::where('mm_user',$mm_user)->first();
        $setting = Setting::find($customer->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;

        $csBankID = $this->getBankID($customer->bank_id);

        $arrData = array(
            'agent'                 => $agent,
            'username'              => $mm_user,
            'amount'                => $amount,
            'bank_id'               => $csBankID,
            'bank_account_name'     => $customer->first_name.' '.$customer->last_name,
            'bank_account_number'   => $customer->acc_no,
            'branch'                =>'-',
            'date'                  => date('Y-m-d H:i:s')
        );

        //return $arrData;

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/withdrawal_user',"post",$param);

        $returnData = json_decode($data,true);
        return $returnData;
    }

    public function withdrawalData(Request $request){

        $agent = trim($request->get('agent'));
        $setting = Setting::where('agent_code',$agent)->first();
        $agentID = $setting->id;
        $agentKey = $setting->agent_key;

        $arrData = array(
            'agent'         => $agent,
            'username'      => '',
            //'status'        => 'NB',
            'status'        => 'Y',
            'page'          => '1',
            'limit'         => '50',
            'date'          => date('Y-m-d H:i:s')
        );

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/withdrawal',"post",$param);

        $returnData = json_decode($data,true);


        if($returnData['msg'] == 'success'){

            foreach ($returnData['data'] as $key => $value) {

                $customer = Customer::where('mm_user',$value['us_id'])->first();
                $csBankID = $this->getBankIDWD($value['us_bankid']);
                $timeNow = Carbon::now();

                $findWDexisting = DB::table('transections')
                ->where('wd_api_id',$value['wd_id'])
                ->first();

                if(!$findWDexisting){

                    DB::table('transections')
                    ->insert(
                            [
                            'wd_api_id'    => $value['wd_id'],
                            'agent_id'     => $agentID,
                            'agent_status' => 0,
                            'type'         => 2,
                            'date_time'    => Carbon::parse($value['wd_datetime']),
                            'date_time_ts' => Carbon::parse($value['wd_datetime'])->timestamp,
                            'customer_id'  => $customer->id,
                            'customer_code'=> $value['us_id'],
                            'csb_bank_id'   => $csBankID,
                            'cs_bank_acc_id' => $customer->customer_bank_id,
                            'cs_bank_acc_no' => $value['us_booknumber'],
                            'amount'         => $value['wd_amount'],
                            'staff_id'       => 100000,
                            'wd_ag_confirm'  => $value['ag_confirm'],
                            'wd_ag_accept'   => $value['ag_accept'],
                            'created_at'     => $timeNow,
                            'updated_at'     => $timeNow]
                    );
                }
            }
            return response()->json([
                'status' => true,
                'msg' => 'Created',
            ],201);
        }else{
            return response()->json([
                'status' => false,
                'msg' => 'No Content',
            ],204);
        }
    }

    public function changePassword($mm_user,Request $request){

        /**
         *  Agent key
         */
        $user = auth()->user();
        $setting = Setting::find($user->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;
        // Log::debug('Agent Key '.  $agentKey);

        $arrData = array(
            'agent'                 => $agent,
            'username'              => $mm_user,
            'passold'               => 'mm1234',
            'passnew'               => $request->get('password'),
            'date'                  => date('Y-m-d H:i:s')
        );

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/changepass_user',"post",$param);

        $returnData = json_decode($data,true);

        if($returnData['msg'] == 'Successfully'){
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        }else{
            alert()->error('ไม่สามารถบันทึกข้อมูลได้');
            return redirect()->back();
        }
    }

    public function resetPassword($mm_user,Request $request){

        /**
         *  Agent key
         */
        $user = auth()->user();
        $setting = Setting::find($user->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;
        // Log::debug('Agent Key '.  $agentKey);

        $arrData = array(
            'agent'                 => $agent,
            'username'              => $mm_user,
            // 'passold'               => 'mm1234',
            'passnew'               => $request->get('password'),
            'date'                  => date('Y-m-d H:i:s')
        );

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/resetpass_user',"post",$param);

        $returnData = json_decode($data,true);

        if($returnData['msg'] == 'Successfully'){
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        }else{
            alert()->error('ไม่สามารถบันทึกข้อมูลได้');
            return redirect()->back();
        }
    }

    public function getDepositList($agent,$mm_user){
        /**
         *  Agent key
         */
        $user = auth()->user();
        $setting = Setting::find($user->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;
        // Log::debug('Agent Key '.  $agentKey);

        $arrData = array(
            'username'      => '',
            'status'        => 'NB',
            'page'          => '1',
            'limit'         => '50',
            'agent'         => $agent,
            'date'          => date('Y-m-d H:i:s')
        );

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/deposit',"post",$param);

         $returnData = json_decode($data,true);
         try {
            foreach ($returnData['data'] as $value) {

                if($value['us_id'] == $mm_user){
                    $status = $this->canCelUserDeposit($value['ds_id'],$value['us_id'],$agent);
                }
             }
         } catch (\Throwable $th) {
             //throw $th;
         }
    }

    public  function canCelUserDeposit($ds_id,$username,$agent){
        /**
         *  Agent key
         */
        $user = auth()->user();
        $setting = Setting::find($user->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;
        // Log::debug('Agent Key '.  $agentKey);

        $arrData = array(
            'ds_id'      	=> $ds_id,
            'username'      => $username,
            'status'        => 'C',
            'agent'         => $agent,
            'date'          => date('Y-m-d H:i:s')
        );

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/depositUpdate',"post",$param);

       return  $returnData = json_decode($data,true);

        if($returnData['msg'] == 'Update Success'){
            return 'OK';
        }else{
            return 'Error';
        }

    }

    public function createUserFromMaster($data,$mm_user){
        /**
         *  Agent key
         */
        $user = auth()->user();
        $setting = Setting::find($user->agent_id);
        $agentKey = $setting->agent_key;


        $dataInfo   = $this->encryptText($data,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/create_user',"post",$param);

        $returnData = json_decode($data,true);

        CustomerMasterLogs::create([
            'mm_user' => $mm_user,
            'agent_response' => $returnData['msg'],
        ]);

        if($returnData['msg'] == 'success'){
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        }else{
            alert()->error('ไม่สามารถบันทึกข้อมูลได้');
            return redirect()->back();
        }
    }

    public function checkUserCredit($mm_user){

        $customer = Customer::where('mm_user',$mm_user)->first();
        $setting = Setting::find($customer->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;


        $arrData = array(
            'agent'                 => $agent,
            'username'              => $mm_user,
            'date'                  => date('Y-m-d H:i:s')
        );

        //return $arrData;

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/credit_user',"post",$param);

        $returnData = json_decode($data,true);
        return $returnData;
    }

    public function checkUserWinloss($mm_user){

        $user = Auth::user();
        $setting = Setting::find($user->agent_id);
        $agentKey = $setting->agent_key;
        $agent = $setting->customer_prefix;


        $begin_date = Carbon::now()->subDay(3)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');

        $arrData = array(
            'agent'      => $agent,
            'username'   => $mm_user,
            'begin_date' => $begin_date,
		    'end_date'   =>  $end_date,
            'date'       => date('Y-m-d H:i:s')
        );

       // return $arrData;

        $arrData    = json_encode($arrData);
        $dataInfo   = $this->encryptText($arrData,$this->cashDesKey);
        $dataInfo   = base64_encode($dataInfo);

        $param = http_build_query(array('data' => $dataInfo,'web'=>$this->cashWeb,'agentkey'=>$agentKey,'random'=>mt_rand()));
        $data =  $this->cUrl($this->casAPIUrl.'/winloss',"post",$param);

        $returnData = json_decode($data,true);
        return $returnData;
    }

    public function checkUserCreditWinLoss($mm_user){
        $userCredit = $this->checkUserCredit($mm_user);
        $userWinLoss = $this->checkUserWinloss($mm_user);
        
        return response()->json([
            'status' => true,
            'user_credit' => $userCredit,
            'user_winloss' => $userWinLoss,
        ],200);
    }

    public function cUrl($url, $method = "get", $data = "", $ssl = false){
        if ($method == "post"){
            if ($data == "") return false;
        }
        $ch = curl_init();
        if ($method == "get") curl_setopt($ch, CURLOPT_URL, $url.($data != "" ? "?".$data : ""));
        else if ($method == "post") curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == "post"){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    public function des_crypt( $string, $action = 'e',$sckey) {

        $secret_key = $sckey;
        $secret_iv  = $sckey;

        $output = false;

        $encrypt_method = "AES-256-CBC";

        $key = hash( 'sha256', $secret_key );

        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {

            $output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );

        }else if( $action == 'd' ){

            $output = openssl_decrypt( $string, $encrypt_method, $key, 0, $iv );

        }

        return $output;
    }

    public function encryptText($string,$key){

        $salting = substr(md5(microtime()),-1) . $string;

        return $this->des_crypt( $salting, 'e' ,$key);

    }

    public function decryptText($string,$key){

        $encode = $this->des_crypt( $string, 'd' ,$key);

        return substr($encode, 1);

    }

    public  function getBankID($id){

        switch ($id) {
            case 2:
                $bankID = 2;
            break;
            case 4:
                $bankID = 1;
            break;
            case 6:
                $bankID = 5;
            break;
            case 11:
                $bankID = 8;
            break;
            case 14:
                $bankID = 4;
            break;
            case 22:
                $bankID = 10;
            break;
            case 24:
                $bankID = 7;
            break;
            case 25:
                $bankID = 3;
            break;
            case 30:
                $bankID = 16;
            break;
            case 33:
                $bankID = 14;
            break;
            case 34:
                $bankID = 17;
            break;
            case 65:
                $bankID = 12;
            break;
            case 67:
                $bankID = 9;
            break;
            case 73:
                $bankID = 15;
            break;
           

            default:
            $bankID = 0;
            break;
        }

        return $bankID;
    }

    public  function getBankIDWD($id){

        switch ($id) {
            case 1: // Kasikorn Bank
                $bankID = 4;
            break;
            case 2: // Bangkok Bank
                $bankID = 2;
            break;
          
            case 3: // Bank of  Ayudhya
                $bankID = 25;
            break;
            case 4: // Siam Commercial Bank
                $bankID = 14;
            break;
            case 5: // Krung Thai Bank
                $bankID = 6;
            break;
            case 6: // Siam City Bank
                $bankID = 0;
            break;
            case 7: // United Overseas Bank
                $bankID = 24;
            break;
            case 8: // Thai Military Bank
                $bankID = 11;
            break;
            case 9: // Tisco bank
                $bankID = 67;
            break;
            case 10: // ICBC
                $bankID = 0;
            break;
            case 11: //Kiatnakin Bank
            $bankID = 0;
            break;
            case 12: // Thanachart Bank
                $bankID = 65;
            break;
            case 13: // STANDARD CHARTERED BANK (THAI)
                $bankID = 0;
            break;
            case 14: // Government Housing Bank
                $bankID = 33;
            break;
            case 15: // Land and House Bank
                $bankID = 73;
            break;
            case 16: // Government Savings Bank (GSB)
                $bankID = 30;
            break;
            case 17: // Bank for Agriculture and Agricultural Cooperatives (BAAC)
                $bankID = 34;
            break;
            case 18: // Islamic Bank of Thailand
                $bankID = 0;
            break;

            default:
                $bankID = 0;
            break;
        }

        return $bankID;
    }
}
