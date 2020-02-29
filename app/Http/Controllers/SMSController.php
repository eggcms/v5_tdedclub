<?php

namespace App\Http\Controllers;

use App\Setting;
use App\SMSLogs;
use App\WebBank;
use App\Customer;
use Carbon\Carbon;
use App\Transection;
use App\TSduplicateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;
use Illuminate\Database\QueryException;

class SMSController extends Controller
{
    public function smsListening(Request $request){

         $smsData = $request->all();


        //Log::debug($smsData);

        //$sender = 'BANGKOKBANK';
        $sender = $smsData['sender'];
        $message = $smsData['body'];
        $ref = $smsData['id'];
        $webBank = $smsData['tag'];

        try {
            SMSLogs::create([
                'sender' => $sender,
                'body' => $message,
                'ref' => $ref,
                'tag' => $webBank,
                ]);
            } catch (QueryException $exception) {
                Log::debug($exception);
            }

            $referentData = ['sms_id' => $ref,'bank_ref'=> $webBank];
            $referentData = json_encode($referentData);

            if($sender == 'KBank'){
                // $messageKBANK_01 = '08/01/62 22:59 บชX153594X เงินเข้า500.00บ';
                // $messageKBANK_02 = '09/02/62 19:54 บชX153594X รับโอนจากX189776X 500.00บ คงเหลือ9350.00บ';
                $dataProcessed = $this->kbank($message);
                $this->insertData($dataProcessed,$referentData);

            }elseif($sender == '027777777'){

                // $messageSCB_01 = 'เงิน 180.00บ เข้าบ/ชx354011 07/03@17:21 ใช้ได้ 4,280.00';
                // $messageSCB_02 = '08/09@10:46 100.00 โอนจากPHUCHONG SONGเข้าx354011 ใช้ได้2,375.46บ';
                $dataProcessed = $this->scb($message);
                $this->insertData($dataProcessed,$referentData);

            }elseif($sender == 'Krungthai'){

                //$message = '11-09-18@02:08 บช X29499X:เงินเข้า +180.00 บ. ใช้ได้ 180.70 บ.';
                $dataProcessed = $this->ktb($message);
                $this->insertData($dataProcessed,$referentData);

            }elseif($sender == 'TMBBank'){

                // $message = 'มีเงิน 100.00บ.เข้าบ/ชxx4484เหลือ 500.00 บ.09/09/18@15:16';
                $dataProcessed = $this->tmb($message);
                $this->insertData($dataProcessed,$referentData);

            }elseif($sender == 'BANGKOKBANK'){
                // $message = 'ฝาก/โอนเงินเข้าบ/ชX3806ผ่านMB 1,000.00บ ใช้ได้ 1,000.39บ';
                $dataProcessed = $this->bbl($message);
                $this->insertData($dataProcessed,$referentData);
            }elseif($sender == 'Krungsri'){
                Log::debug($message);
                //$message = 'โอนเข้า xxx128227x  10.00 เหลือ 20.00 (4/11/62,02:21)';
                $dataProcessed = $this->bay($message);
                $this->insertData($dataProcessed,$referentData);
            }
        }

        /**
        * Insert data to database
        */
        protected function insertData($data,$referentData){

                $smsData = json_decode($data,true);

                //Log::debug($smsData);

                $refData = json_decode($referentData,true);
                    $dateTime = $smsData['date'].' '.$smsData['time'];
                    $dateTimeTS = Carbon::parse($smsData['date'].' '.$smsData['time'])->timestamp;
                    $amount = $smsData['total'];

                    $customerID = NULL;
                    $customerCode = NULL;
                    $customerBankID = NULL;
                    $customerBankAccID = NULL;
                    $customerBankAcc = NULL;


                    $webBank = WebBank::where('bank_id','=',$smsData['bank_id'])
                    ->where('ref','=',$refData['bank_ref'])
                    ->get();

                    if($smsData['bank'] == 'KBANK' && $smsData['search_word'] != ''){
                        $query = $smsData['search_word'];
                        $bankID = 4;

                        $customerSearch = Customer::where('acc_no','like','___' . $query . '_')
                        ->where('bank_id',$bankID)
                        ->orderBy('id','desc')
                        ->get();

                        $checkDuplicate = count($customerSearch);

                        if($checkDuplicate == 1){
                            $customerID = $customerSearch[0]->id;
                            $customerCode = $customerSearch[0]->mm_user;
                            $customerBankID = $customerSearch[0]->bank_id;
                            $customerBankAccID = $customerSearch[0]->customer_bank_id;
                            $customerBankAcc = $customerSearch[0]->acc_no;
                        }
                    }

                    if($smsData['bank'] == 'SCB' && $smsData['search_word'] != ''){
                        $query = $smsData['search_word'];
                        $bankID = 14;

                        $customerSearch = Customer::where('text_sms_ref','=',$query)
                        ->orderBy('id','desc')
                        ->get();

                        $checkDuplicate = count($customerSearch);

                        if($checkDuplicate == 1){
                            $customerID = $customerSearch[0]->id;
                            $customerCode = $customerSearch[0]->mm_user;
                            $customerBankID = $customerSearch[0]->bank_id;
                            $customerBankAccID = $customerSearch[0]->customer_bank_id;
                            $customerBankAcc = $customerSearch[0]->acc_no;
                        }
                    }

                    $findDuplicateTS = Transection::where('sms_ref','=',$refData['sms_id'])
                    ->where('bank_tag','=',$refData['bank_ref'])
                    ->count();

                    if($findDuplicateTS > 0){

                        TSduplicateLog::create([
                            'sms_ref' => $refData['sms_id'],
                            'bank_tag' => $refData['bank_ref'],
                            // 'sms' => $message
                            ]);
                            return "Saved";

                        }else{
                            $transection = Transection::create([
                                'type' => 1,
                                'date_time' => $dateTime,
                                'date_time_ts' => $dateTimeTS,
                                'amount' => $amount,
                                'agent_status' => 0,
                                'customer_id' => $customerID,
                                'customer_code' => $customerCode,
                                'csb_bank_id' => $customerBankID,
                                'cs_bank_acc_id' => $customerBankAccID,
                                'cs_bank_acc_no' => $customerBankAcc,
                                'wb_bank_id' => $smsData['bank_id'],
                                'web_bank_acc_id' => $webBank[0]['id'],
                                'web_bank_acc' => $webBank[0]['acc_no'],
                                'web_bank_name' => $webBank[0]['name'],
                                'staff_id' => 100000,
                                'is_sms' => 1,
                                'sms_ref' => $refData['sms_id'],
                                'bank_tag' => $refData['bank_ref'],
                                'agent_id' => $webBank[0]['agent_id'],
                                ]);


                                $lineMessage = 'แจ้งฝาก '.$amount.' เข้าบัญชี '.$smsData['bank'].' '.$webBank[0]['name'];

                                /**
                                * Line nortify
                                */
                                $this->lineAlert($lineMessage);

                                return "Saved";
                            }

                        }

                        /**
                        * KBANK
                        */
                        protected function kbank($message){
                            if( stripos($message , "เงินเข้า")	!= null )
                            {
                                $text_explode = explode(" ",$message);
                                $text_explode_date = explode("/",$text_explode[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $text_explode_date[2]+1957;
                                $text_explode_time = explode(":",$text_explode[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min = $text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                // $date_strto=strtotime($date_format);
                                $cut_word = array(	'เงินเข้า'	=> "" 	,'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[3],$cut_word);

                                $data = [
                                    'bank' => 'KBANK',
                                    'search_word' => '',
                                    'bank_id' => 4,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);

                            }elseif(stripos($message , "รับโอนจาก")	!= null){

                                $text_explode = explode(" ",$message);
                                $text_explode_date = explode("/",$text_explode[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $text_explode_date[2]+1957;
                                $text_explode_time = explode(":",$text_explode[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min = $text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                // $date_strto = strtotime($date_format);

                                $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[4],$cut_word);

                                $cut_customer_word = array(	'รับโอนจาก'	=> "" 	,'X'	=> "" 	);
                                $query = strtr($text_explode[3],$cut_customer_word);

                                $data = [
                                    'bank' => 'KBANK',
                                    'search_word' => $query,
                                    'bank_id' => 4,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);

                            }elseif(stripos($message , "received") != null)
                            {
                                $text_explode = explode(" ",$message);
                                $text_explode_date = explode("/",$text_explode[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $text_explode_date[2]+2000;
                                $text_explode_time = explode(":",$text_explode[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min = $text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;

                                $cut_word = array( 'บ' => "" , ',' => "");
                                $pay = strtr($text_explode[5],$cut_word);

                                $cut_customer_word = array( 'received' => "" ,'X' => "" );
                                $query = strtr($text_explode[4],$cut_customer_word);

                                $data = [
                                    'bank' => 'KBANK',
                                    'search_word' => $query,
                                    'bank_id' => 1,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' => $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                return json_encode($data);
                            }
                            elseif(stripos($message , "Deposit") != null)
                            {
                                $text_explode = explode(" ",$message);
                                $text_explode_date = explode("/",$text_explode[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $text_explode_date[2]+2000;
                                $text_explode_time = explode(":",$text_explode[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min = $text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $cut_word = array( 'Deposit' => "" ,',' => "");
                                $pay = strtr($text_explode[4],$cut_word);

                                $data = [
                                    'bank' => 'KBANK',
                                    'search_word' => '',
                                    'bank_id' => 1,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' => $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                return json_encode($data);
                            }
                        }

                        /**
                        * SCB
                        */
                        protected function scb($message){
                            //Log::debug($message);
                            //if( stripos($message , "เข้าx354011")	!= null )
                            // 02/04@19:19 300.00 จากBBLA/x165912เข้าx245209 ใช้ได้21,100.00บ
                            // 02/04@20:29 300.00 โอนจากKAMPANAT CHAIเข้าx245209 ใช้ได้3,200.00บ
                            if( stripos($message , "เข้าx")	!= null )
                            {
                                $date_come_year = date('Y');
                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode("@",$text_explode[0]);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $date_come_year;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min=$text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                // $date_strto=strtotime($date_format);

                                $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[1],$cut_word);

                                $bank_text_ref = explode("เข้าx",$text_explode[2]);
                                $query = $bank_text_ref[0];

                                $start =  strpos($message, "จาก");
                                $end =  strpos($message, "เข้าx");
                                $query = substr($message,$start,$end-$start);
                                $query = str_replace("จาก","",$query);

                                $data = [
                                    'bank' => 'SCB',
                                    'search_word' => $query,
                                    'bank_id' => 14,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                //print_r($data);
                                return json_encode($data);
                            }

                            //if( stripos($message , "เข้าบ/ชx354011")	!= null )
                            if( stripos($message , "เข้าบ/ชx")	!= null )
                            {
                                $date_come_year=date('Y');
                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode("@",$text_explode[3]);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$date_come_year;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];
                                $time_min=$text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[1],$cut_word);


                                $data = [
                                    'bank' => 'SCB',
                                    'search_word' => '',
                                    'bank_id' => 14,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                //print_r($data);
                                return json_encode($data);

                            }


                            // Transfer amount THB 500.00 to your account x417876 on 28/06@11:12.
                            if( stripos($message , "ransfer amount THB")	!= null )
                            {
                                $date_come_year=date('Y');
                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode("@",$text_explode[9]);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$date_come_year;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];

                                $cut_word = array(	'.'	=> "" 	,	','	=> "");
                                $time_min = strtr($text_explode_time[1],$cut_word);
                                // $time_min= trim($text_explode_time[1]);

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[3],$cut_word);


                                $data = [
                                    'bank' => 'SCB',
                                    'search_word' => '',
                                    'bank_id' => 14,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                //print_r($data);
                                return json_encode($data);

                            }
                            // $message ='Cash/transfer deposit amount THB 80.00 via CDM to your A/C x417876 on 20/05@19:06 Avai. Bal. is THB 80.00.';
                            // $message = 'Transfer from SERMCHAI SAHA amount THB 100.00 to your account x417876 on 20/05@20:42 Avai. Bal. is THB 380.00.';
                            // $message = 'Transfer from KBNK/x577470 amount THB 280.00 to your account x417876  on 19/05@09:24  Available balance is THB 280.00.';
                            // $message = 'Transfer from KTBA/x495362 amount THB 100.00 to your account x417876  on 21/05@22:03  Available balance is THB 200.00.';
                            //             Transfer from GSBA/x490907 amount THB 300.00 to your account x417876  on 01/07@19:15
                            if( stripos($message , "to your account")	!= null )
                            {
                                $date_come_year = date('Y');
                                $text_explode = explode(" ",$message);


                                if( stripos($message , ".  Avai")	!= null )
                                {
                                $start =  strpos($message, "on ");
                                $end =  strpos($message, ".  Avai");
                                $query = substr($message,$start,$end-$start);
                                $query = str_replace("on ","",$query);

                                $text_explode_date_time = explode("@",$query);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $date_come_year;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min= trim($text_explode_time[1]);

                                } else {

                                        $text_explode_date_time = explode("@",$text_explode[12]);
                                        $text_explode_date = explode("/",$text_explode_date_time[0]);
                                        $date_day = $text_explode_date[0];
                                        $date_mon = $text_explode_date[1];
                                        $date_year = $date_come_year;
                                        $text_explode_time = explode(":",$text_explode_date_time[1]);
                                        $time_hour = $text_explode_time[0];
                                        $time_min= trim($text_explode_time[1]);
                                    }

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                // $date_strto=strtotime($date_format);

                                $start =  strpos($message, "THB ");
                                $end =  strpos($message, " to your");
                                $query = substr($message,$start,$end-$start);
                                $query = str_replace("THB ","",$query);

                                $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($query,$cut_word);

                                $bank_text_ref = explode("เข้าx",$text_explode[2]);
                                $query = $bank_text_ref[0];

                                $start =  strpos($message, "Transfer from ");
                                $end =  strpos($message, " amount THB");
                                $query = substr($message,$start,$end-$start);
                                $query = str_replace("Transfer from ","",$query);

                                $data = [
                                    'bank' => 'SCB',
                                    'search_word' => $query,
                                    'bank_id' => 14,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);
                            }

                            //if( stripos($message , "เข้าบ/ชx354011")	!= null )
                            // Cash/transfer deposit amount THB 80.00 via CDM to your A/C x417876 on 20/05@19:06 Avai. Bal. is THB 80.00.
                            if( stripos($message , "to your A/C")	!= null )
                            {
                                $date_come_year=date('Y');
                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode("@",$text_explode[12]);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$date_come_year;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];
                                $time_min= trim($text_explode_time[1]);

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[4],$cut_word);


                                $data = [
                                    'bank' => 'SCB',
                                    'search_word' => '',
                                    'bank_id' => 14,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                //print_r($data);
                                return json_encode($data);
                            }
                        }

                        /**
                        * KTB
                        */
                        protected function ktb($message){
                            if( stripos($message , "เงินเข้า")	!= null )
                            {

                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode("@",$text_explode[0]);
                                $text_explode_date = explode("-",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$text_explode_date[2]+2000;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];
                                $time_min=$text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                $cut_word = array(	'+'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[3],$cut_word);

                                $data = [
                                    'bank' => 'KTB',
                                    'search_word' => '',
                                    'bank_id' => 6,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);
                            }
                            // 20-05-19@21:24 Acct X49536X: Deposit +100.00 THB Avail 100.00 THB
                            if( stripos($message , "Deposit")	!= null )
                            {

                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode("@",$text_explode[0]);
                                $text_explode_date = explode("-",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$text_explode_date[2]+2000;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];
                                $time_min=$text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                $cut_word = array(	'+'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[4],$cut_word);

                                $data = [
                                    'bank' => 'KTB',
                                    'search_word' => '',
                                    'bank_id' => 6,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);
                            }
                        }

                        /**
                        * TMB
                        */
                        protected function tmb($message){

                            $stripos01 =  stripos($message , "เข้าบ/ชxx") ;
                            $stripos02 =  stripos($message , "มีเงิน") ;

                            if( $stripos01 !== false  && $stripos02 !== false  )
                            {
                                $text_explode = explode(".",$message);
                                $cut_word = array(	'บ.'	=> "" );
                                $text_explode_date_time = strtr($text_explode[4],$cut_word);
                                $text_explode_date_time = explode("@",$text_explode_date_time);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$text_explode_date[2]+2000;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];
                                $time_min=$text_explode_time[1];
                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                $start =  strpos($message, "มีเงิน");
                                $end =  strpos($message, "บ.");
                                $query = substr($message,$start,$end-$start);
                                $query = str_replace("มีเงิน","",$query);
                                $cut_word = array(	'บ.'	=> "" 	,	','	=> "");
                                $pay = strtr($query,$cut_word);

                                $data = [
                                    'bank' => 'TMB',
                                    'search_word' => '',
                                    'bank_id' => 11,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);
                            }

                            // Deposit amount 100.00 Baht to account xx8431.Available balance 200.00 Baht.20/05/19@21:39
                            // Deposit amount 100.00 Baht to account xx8431.Available balance 100.00 Baht.20/05/19@21:11
                            // Deposit of 300.00 Baht to account xx6534. Available balance 1,006.00 Baht. 08/01/20@14:46
                            $stripos01 =  stripos($message , "eposit of") ;
                            $stripos02 =  stripos($message , "to account") ;

                            if( $stripos01 !== false  && $stripos02 !== false  )
                            {

                                 $message = str_replace(". Available ",".Available ",$message);
                                 $message = str_replace("Baht. ","Baht.",$message);
                                $text_explode = explode(" ",$message);
                                $cut_word = array(	'Baht.' => "" );
                                $text_explode_date_time = strtr($text_explode[9],$cut_word);
                                $text_explode_date_time = explode("@",$text_explode_date_time);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day=$text_explode_date[0];
                                $date_mon=$text_explode_date[1];
                                $date_year=$text_explode_date[2]+2000;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour=$text_explode_time[0];
                                $time_min=$text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);

                                // $explode_pay = explode("บ.",$text_explode[1]);
                                $cut_word = array(	'บ.'	=> "" 	,	','	=> "");
                                $pay = strtr($text_explode[2],$cut_word);


                                $data = [
                                    'bank' => 'TMB',
                                    'search_word' => '',
                                    'bank_id' => 11,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                // print_r($data);
                                return json_encode($data);
                            }
                        }

                        /**
                        * BBL
                        */
                        protected function bbl($message){

                            if( stripos($message , "โอนเงินเข้า")	!= null )
                            {

                                $count_blank_char = substr_count($message," ");
                                echo $count_blank_char."<br>";
                                $date_year=date('Y');
                                $date_mon=date('m');
                                $date_day=date('d');
                                $time_hour=date('H');
                                $time_min=date('i');
                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $date_strto=strtotime($date_format);
                                $text_explode = explode(" ",$message);


                                if($count_blank_char == 3){
                                    $cut_word = array(	'บ'	=> "" 	,	','	=> "");
                                    $pay = strtr($text_explode[1],$cut_word);

                                }   else if($count_blank_char == 4){
                                    $cut_word = array(	'บ'	=> "" 	,	','	=> "",	'ผ่านMB'	=> "",	'ผ่านATM'	=> "",	'ผ่านIB'	=> "",	'ผ่านCDM'	=> "");
                                    $pay = strtr($text_explode[2],$cut_word);

                                }



                                $data = [
                                    'bank' => 'BBL',
                                    'search_word' => '',
                                    'bank_id' => 2,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' =>  $time_hour.':'. $time_min,
                                    'total' => $pay
                                ];
                                return json_encode($data);
                                // print_r($data);
                            }
                        }

                        /**
                        * TBANK
                        */
                        protected function tbank($message){

                        }


                        /**
                        * BAY
                        */
                        protected function bay($message){
                            //Log::debug($message);
                            $stripos = stripos($message , "โอนเข้า");
                            if( $stripos !== false )
                            {
                                $text_replace = array("  ", "(", ")");
                                $dummy = array("@", "", "");
                                $message = str_replace($text_replace, $dummy, $message);

                                $text_explode = explode(" ",$message);

                                $text_explode_date_time = explode(",",$text_explode[4]);
                                $text_explode_date = explode("/",$text_explode_date_time[0]);
                                $date_day = $text_explode_date[0];
                                $date_mon = $text_explode_date[1];
                                $date_year = $text_explode_date[2]+1957;
                                $text_explode_time = explode(":",$text_explode_date_time[1]);
                                $time_hour = $text_explode_time[0];
                                $time_min = $text_explode_time[1];

                                $date_format = $date_mon."/".$date_day."/".$date_year;
                                $cut_word = array( 'บ' => "" , ',' => "");
                                $pay_explode = explode("@",$text_explode[1]);
                                $pay = strtr($pay_explode[1],$cut_word);

                                $data = [
                                    'bank' => 'BAY',
                                    'search_word' => '',
                                    'bank_id' => 25,
                                    'date' => Carbon::parse($date_format)->format('Y-m-d'),
                                    'time' => $time_hour.':'. $time_min,
                                    'total' => $pay,
                                    'cust_bank_id' => ''
                                ];

                                //Log::debug($data);


                                return json_encode($data);
                            }
                        }

                        /**
                        * Line Nortify
                        */
                        protected function lineAlert($message){
                            $curl = curl_init();
                            $setting = Setting::find(1);
                            $token = $setting->line_token;

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
                    }
