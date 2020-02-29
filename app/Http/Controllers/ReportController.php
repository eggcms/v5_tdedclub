<?php

namespace App\Http\Controllers;

use App\Banks;
use App\Setting;
use App\Marketing;
use App\SiteConfig;
use App\Transection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function money()
    {
        $year = Carbon::now()->format('Y');
        $sumDopositByMonth = [];
        $sumWithdrwalByMonth = [];

        $doposit = DB::select("SELECT MONTH(`date_time`) as `month`, SUM(`amount`) as 'total'
            FROM transections
            WHERE `date_time` BETWEEN '2019-01-01' AND '2019-12-31' AND `type` = 1 AND `status` != 3
            GROUP BY MONTH(`date_time`)");


        foreach ($doposit as  $key => $value) {
            $sumDopositByMonth[] = [
                $sumDopositByMonth[$value->month] = $value->total
            ];
        }



        $sumDopositByMonth = json_encode($sumDopositByMonth);

        return view('reports/money',compact('year','sumDopositByMonth'));
    }

    public function customer(Request $request)
    {

        $year = Carbon::now()->format('Y');

        if($request->get('date') != NULL){
            $date = $request->get('date');
        }else{
            $date = Carbon::now()->format('Y-m-d');
        }
        $customer_count = DB::select("SELECT id  FROM customers
            WHERE open_user_date ='$date'
            "
        );
        $customer_count=count($customer_count);
    // ->paginate(10);
    // return $customer_count;

        $customer = DB::table('customers')
        ->join('marketing', 'customers.ref', '=', 'marketing.id')
        ->join('users', 'users.id', '=', 'customers.staff_id')
        ->select('customers.id','customers.mm_user','customers.first_name','customers.last_name','customers.open_user_date','customers.first_deposit', 'marketing.name as mar_channel', 'users.name as staff_name')
        ->where('customers.open_user_date',$date)
        ->paginate(100);


        $userPerMonth = DB::select("SELECT MONTH(`open_user_date`) as `month`, COUNT(id) AS totalUser FROM `customers` WHERE YEAR(`open_user_date`) ='$year' GROUP BY MONTH(`open_user_date`),YEAR(`open_user_date`) ORDER BY MONTH(`open_user_date`),YEAR(`open_user_date`) ASC");

        $labels = ['01/'.$year,'02/'.$year,'03/'.$year,'04/'.$year,'05/'.$year,'06/'.$year,'07/'.$year,'08/'.$year,'09/'.$year,'10/'.$year,'11/'.$year,'12/'.$year,];

        return view('reports/customer',compact('year','customer','date','userPerMonth','customer_count','labels'));
    }


    public function userReferenceBy(Request $request){

        if($request->get('startDate')){
            $startDate = $request->get('startDate');
            $endDate = $request->get('endDate');
        }else{

            $startDate = Carbon::now()->subMonth(1)->format('Y-m-26');
            $endDate = Carbon::now()->format('Y-m-25');
        }


         $customer = DB::table('customers')
        ->join('marketing', 'customers.ref', '=', 'marketing.id')
        ->join('users', 'users.id', '=', 'customers.staff_id')
        ->select('customers.id','customers.ref','customers.mm_user','customers.first_name','customers.last_name','customers.open_user_date','marketing.id' ,'marketing.name as mar_channel','marketing.group as mar_group', 'users.name as staff_name')
        ->whereBetween('customers.open_user_date',[$startDate,$endDate])
        ->orderBy('mar_group','desc')
        ->get();
    // ->paginate(100);

        $marketing = Marketing::all();

        $marketingChannel = [];

        foreach ($customer as $key => $cs) {

            foreach ($marketing as $mar) {
                if($cs->ref == $mar->id ){
                    $marketingChannel[$mar->name][] = $cs->mm_user;
                }
            }
        }

        //return $marketingChannel;

        $data = [];
        foreach($marketingChannel as $key => $marReport){
            $data[$key] =  count($marReport);
        }
         array_multisort($data, SORT_DESC);

        //return $data;


        return view('reports/customer-ref',compact('data','startDate','endDate'));
    }


    public function daily(Request $request){

        if($request->user()->role == 'boss' || $request->user()->role == 'programmer'){
            if($request->get('startDate')){
                $startDate = $request->get('startDate');
                $startDate = $startDate." 00:00";
                $endDate = $request->get('endDate');
                $endDate = $endDate." 23:59";
            }else{

                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i');
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i');
            }

            $doposit = DB::select("SELECT DATE(`date_time`) AS `date`, SUM(amount) AS total_deposit
                FROM transections
                WHERE `type` = 1
                AND `date_time` BETWEEN '$startDate' AND '$endDate'
                AND `status` != 3
                GROUP BY `date`");

            $withdrawal = DB::select("SELECT DATE(`date_time`) AS `date`, SUM(amount) AS total_withdrawal
                FROM transections
                WHERE `type` = 2
                AND `agent_status` = 1
                AND `date_time` BETWEEN '$startDate' AND '$endDate'
                AND `status` != 3
                GROUP BY `date`");

            $data = [];
            $dataSet = [];

            foreach ($doposit as $key => $dp) {

                $data['doposit'][$dp->date] = $dp->total_deposit;

                foreach ($withdrawal as $key => $wd) {
                    $data['withdrawal'][$wd->date] = $wd->total_withdrawal;

                    if($dp->date == $wd->date){

                        $dataSet[] = [
                            'date' => $dp->date,
                            'doposit' => $dp->total_deposit,
                            'withdrawal' => $wd->total_withdrawal,
                            'diff' => $dp->total_deposit - $wd->total_withdrawal,
                        ];
                    }
                }
            }



            $sumDeposit = DB::select("SELECT  SUM(amount) AS total
                FROM transections
                WHERE `type` = 1
                AND `date_time` BETWEEN '$startDate' AND '$endDate'
                AND `status` != 3");

            $sumwithdrawal = DB::select("SELECT  SUM(amount) AS total
                FROM transections
                WHERE `type` = 2
                AND `agent_status` = 1
                AND `date_time` BETWEEN '$startDate' AND '$endDate'
                AND `status` != 3");

            $sumDiff = $sumDeposit[0]->total - $sumwithdrawal[0]->total;

            return view('reports/monthly-overview',compact('dataSet','sumDeposit','sumwithdrawal','sumDiff','startDate','endDate'));

        }else{
            alert()->error('ไม่อนุญาติให้เข้าถึงได้');
            return redirect()->back();
        }
    }

    //TODO
    public function sumDaily(Request $request){
        if($request->user()->role == 'boss' || $request->user()->role == 'programmer'){

            if($request->get('date') == null){
                $today = Carbon::now()->format('Y-m-d');
            }else{
                $today = Carbon::parse($request->get('date'))->format('Y-m-d');
            }

            $agent_select_id = $request->get('agent_id') == null ? $request->user()->agent_id : $request->get('agent_id');

            $agent = DB::select("SELECT id,agent_name,customer_prefix FROM setting order by id asc;");

                //Query Main Data
                $deposit = DB::select("SELECT ts.`web_bank_name`,count(ts.`id`) AS `count_ts`
                ,SUM(ts.`amount`) AS `total` , count(DISTINCT ts.`customer_code`) AS totalUser
                FROM `transections` ts
                WHERE DATE(ts.`date_time`) = '$today'
                AND ts.`status` != 3
                AND ts.`type` = 1
                AND  ts.agent_id = $agent_select_id
                GROUP BY `web_bank_name`");

                $withdraw = DB::select("SELECT ts.`web_bank_name`,count(ts.`id`) AS `count_ts`
                ,SUM(ts.`amount`) AS `total` , count(DISTINCT ts.`customer_code`) AS totalUser
                FROM `transections` ts
                WHERE DATE(ts.`date_time`) = '$today'
                AND ts.`status` != 3
                AND ts.`type` = 2
                AND `agent_status` = 1
                AND  ts.agent_id = $agent_select_id
                GROUP BY `web_bank_name`");



                //Query Count Volum Data
                $dataSet= DB::select("SELECT sum(ts.`amount`)  AS amount ,ts.`web_bank_name`,ts.`bank_tag`,cs.`mm_user`,concat(cs.`first_name`,' ',cs.`last_name`) AS cs_name
                ,count(ts.`customer_id`) AS countTotal,ts.`type`
                FROM `transections` ts
                LEFT JOIN `customers` cs ON ts.`customer_id` = cs.`id`
                WHERE DATE(`date_time`) = '$today'
                AND ts.`status` != 3
                AND ts.agent_id = $agent_select_id
                GROUP BY ts.`customer_id`,ts.`web_bank_name`,ts.`bank_tag`,ts.`type`

                ") ;



                //Data Group
                $mainData = $this->sumDailyData($deposit,$withdraw,$dataSet) ;

                //ข้อมูลฝาก - ถอน ทั้งหมด
                $deposit = $mainData['deposit'];
                $withdraw = $mainData['withdraw'];
                //รายชื่อลูกค้าที่ฝาก - ถอน ทั้งหมด
                $depositList = $mainData['dataList_deposit'];
                $withdrawList = $mainData['dataList_withdraw'];


                  $withdraw_graph = $this->withdraw_graph($agent_select_id,$today);


            return view('reports/daily-sum',compact('today','agent_select_id','agent','deposit','depositList','withdraw','withdrawList','withdraw_graph'));
        }else{
            alert()->error('ไม่อนุญาติให้เข้าถึงได้');
            return redirect()->back();
        }
    }

    public function sumHourly(Request $request){


        $date = Carbon::now()->format('Y-m-d');
        $now = Carbon::now()->format('H:i');

        $deposit = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.' '.$now)->timestamp])
        ->where('type',1)
        ->where('status','!=',3)
        ->sum('amount');

        $withdrawal = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.' '.$now)->timestamp])
        ->where('type',2)
        ->where('agent_status','=',1)
        ->where('status','!=',3)
        ->sum('amount');

        $depositCount = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.' '.$now)->timestamp])
        ->where('type',1)
        ->where('status','!=',3)
        ->count();

        $withdrawalCount = Transection::whereBetween('date_time_ts',[Carbon::parse($date.'  00:00')->timestamp,Carbon::parse($date.' '.$now)->timestamp])
        ->where('type',2)
        ->where('agent_status','=',1)
        ->where('status','!=',3)
        ->count();

        $diff = $deposit -  $withdrawal;

        $message = "\n".
        '💹 ยอดฝาก'. $now .' รวม '.number_format($deposit,2).' บาท'.
        "\n".
        '❌ ยอดถอน'.$now .' รวม '.number_format($withdrawal,2).' บาท'.
        "\n".
        '🏛️ รวมฝาก '.number_format($depositCount).' ครั้ง'.
        "\n".
        '👎 รวมถอน '.number_format($withdrawalCount).' ครั้ง'.
        "\n".
        '💲ส่วนต่าง '.number_format($diff,2).' บาท';

        $this->lineAlert($message);
    }


     //User ของเซียนที่เปิดยูสเซอร์ เฉพาะในเดือนนี้
     public function userByZian(Request $request)
     {
         if($request->user()->role == 'boss' || $request->user()->role == 'programmer')
         {
             // $marketing = Marketing::where('active',1)
             // ->where('group','zean')
             // ->orderBy('group','desc')
             // ->get();
             $marketing = DB::select("SELECT * FROM marketing
             WHERE `active` = 1
             AND `group` IN ('zean','Online','friend','call','fb_ads','social')
             ORDER BY `group` DESC,`name` ASC
             ");


             $customers = [];
             $customerSearch = [];
             $transections = [];
             $masterBanks = Banks::all();

             if($request->get('startDate')){
                 $startDate = $request->get('startDate');
                 $endDate = $request->get('endDate');
             }else{

                 $startDate = Carbon::now()->subMonth(1)->format('Y-m-26');
                 $endDate = Carbon::now()->format('Y-m-25');
             }

             $customer = [];
             $countCustomerAll=1;
             if($request->get('ref') != null){
                 $custRef = $request->get('ref');

                 if($request->get('countDeposit') == null){
                     $countDeposit = 1;
                 }else{
                     $countDeposit = $request->get('countDeposit');
                 }


                 $customer = DB::select("SELECT customers.mm_user,customers.first_name,customers.last_name,customers.open_user_date,
                     customers.first_deposit,
                     COUNT(transections.customer_code) AS play,
                     SUM(transections.amount) AS total ,
                     customers.id AS customer_id , (SELECT
                     SUM(ts.amount)
                     FROM customers
                     JOIN transections ts
                     ON(customers.id = ts.customer_id)
                     WHERE customers.ref= $custRef
                     AND customers.open_user_date BETWEEN '$startDate' AND '$endDate'
                     AND ts.type = 2
                     AND ts.agent_status = 1
                     AND ts.status != 3
                     AND customers.mm_user = transections.customer_code
                     ) AS totalWithdraw

                     FROM customers
                     JOIN transections
                     ON(customers.mm_user=transections.customer_code)
                     WHERE customers.ref = $custRef
                     AND customers.open_user_date BETWEEN '$startDate' AND '$endDate'
                     AND transections.type = 1
                     AND transections.status != 3
                     GROUP BY transections.customer_code
                     HAVING play >= $countDeposit");


                 $customerAll = DB::select("SELECT customers.mm_user,customers.first_name,customers.last_name,customers.open_user_date,
                     customers.first_deposit,
                     COUNT(transections.customer_code) AS play,
                     SUM(transections.amount) AS total ,
                     customers.id AS customer_id
                     FROM customers
                     JOIN transections
                     ON(customers.mm_user=transections.customer_code)
                     WHERE customers.ref = $custRef
                     AND customers.open_user_date BETWEEN '$startDate' AND '$endDate'
                     AND transections.type = 1
                     AND transections.status != 3
                     GROUP BY transections.customer_code
                    ");

                    $countCustomerAll = count($customerAll);

             }




             $text = '';

             return view('reports/customer-zian',compact('marketing','customer','customers','transections','masterBanks','startDate','endDate','marketing','text','countCustomerAll'));
         }else{
             alert()->error('ไม่อนุญาติให้เข้าถึงได้');
             return redirect()->back();
         }
     }

    //User ทุกยูสของเซียน ที่ฝากเข้าในเดือนนี้
    public function userByZianAll(Request $request)
    {


        if($request->user()->role == 'boss' || $request->user()->role == 'programmer')
        {
            // $marketing = Marketing::where('active',1)
            // ->where('group','zean')
            // ->orderBy('group','desc')
            // ->get();
            $marketing = DB::select("SELECT * FROM marketing
            WHERE `active` = 1
            AND `group` IN ('zean','Online','friend','call','fb_ads','social')
            ORDER BY `group` DESC,`name` ASC
            ");


            $customers = [];
            $customerSearch = [];
            $transections = [];
            $masterBanks = Banks::all();

            if($request->get('startDate')){
                $startDate = $request->get('startDate');
                $endDate = $request->get('endDate');
            }else{

                $startDate = Carbon::now()->subMonth(1)->format('Y-m-26');
                $endDate = Carbon::now()->format('Y-m-25');
            }

            $customer = [];
            $countCustomerAll=1;
            if($request->get('ref') != null){
                $custRef = $request->get('ref');

                if($request->get('countDeposit') == null){
                    $countDeposit = 1;
                }else{
                    $countDeposit = $request->get('countDeposit');
                }


                $customer = DB::select("SELECT customers.mm_user,customers.first_name,customers.last_name,customers.open_user_date,
                customers.first_deposit,
                COUNT(transections.customer_code) AS play,
                SUM(transections.amount) AS total ,
                customers.id AS customer_id , (SELECT
                SUM(ts.amount)
                FROM customers
                JOIN transections ts
                ON(customers.id = ts.customer_id)
                WHERE customers.ref= $custRef
                AND ts.`date_time` BETWEEN '$startDate 00:00' AND '$endDate 23:59'
                AND ts.type = 2
                AND ts.agent_status = 1
                AND ts.status != 3
                AND customers.mm_user = transections.customer_code
                ) AS totalWithdraw

                FROM customers
                JOIN transections
                ON(customers.mm_user=transections.customer_code)
                WHERE customers.ref = $custRef
                AND transections.`date_time` BETWEEN '$startDate 00:00' AND '$endDate 23:59'
                AND transections.type = 1
                AND transections.status != 3
                GROUP BY transections.customer_code
                HAVING play >= $countDeposit
                ");


                $customerAll = DB::select("SELECT customers.mm_user,customers.first_name,customers.last_name,customers.open_user_date,
                    customers.first_deposit,
                    COUNT(transections.customer_code) AS play,
                    SUM(transections.amount) AS total ,
                    customers.id AS customer_id
                    FROM customers
                    JOIN transections
                    ON(customers.mm_user=transections.customer_code)
                    WHERE customers.ref = $custRef
                    AND transections.`date_time` BETWEEN '$startDate 00:00' AND '$endDate 23:59'
                    AND transections.type = 1
                    AND transections.status != 3
                    GROUP BY transections.customer_code
                   ");

                   $countCustomerAll = count($customerAll);

            }




            $text = '';

            return view('reports/customer-zian-all',compact('marketing','customer','customers','transections','masterBanks','startDate','endDate','marketing','text','countCustomerAll'));
        }else{
            alert()->error('ไม่อนุญาติให้เข้าถึงได้');
            return redirect()->back();
        }
    }




    public function moneyGraph(Request $request){

        $year = Carbon::now()->format('Y');

        if($request->user()->role == 'boss' || $request->user()->role == 'programmer'){

            $dataDeposit = DB::select("SELECT DATE_FORMAT(`date_time`, '%m/%Y') AS MONTH, SUM(`amount`) AS total
            FROM `transections`
            WHERE `type` = 1
            AND `status` != 3
            AND YEAR(`date_time`) = '$year'
            GROUP BY DATE_FORMAT(`date_time`, '%m/%Y')
            ORDER BY MONTH");


            $dataWithdrawal = DB::select("SELECT DATE_FORMAT(`date_time`, '%m/%Y') AS MONTH, SUM(`amount`) AS total
            FROM `transections`
            WHERE `type` = 2
            AND `agent_status` = 1
            AND `status` != 3
            AND YEAR(`date_time`) = '$year'
            GROUP BY DATE_FORMAT(`date_time`, '%m/%Y')
            ORDER BY MONTH");

        $labels = [];
        foreach ($dataDeposit as $key => $value) {
            $labels[] =
                $value->MONTH = $value->MONTH
            ;
        }

        $deposit = [];
        foreach ($dataDeposit as $key => $dp) {
            $deposit[] =
                $dp->MONTH = number_format($dp->total, 2, '.', '')
            ;
        }

        $withdraw = [];
        foreach ($dataWithdrawal as $key => $wd) {
            $withdraw[] =
                $wd->MONTH = number_format($wd->total, 2, '.', '')
            ;
        }
        return view('reports/money-graph',compact('labels','deposit','withdraw'));
     }else{
            alert()->error('ไม่อนุญาติให้เข้าถึงได้');
            return redirect()->back();
    }
}

/**
* Line Nortify
*/
    protected function lineAlert($message){
        $curl = curl_init();

        $setting = SiteConfig::find(1);
        $token = $setting->line_token_hourly_alert;


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

    public function sumDailyData($deposit,$withdraw,$dataSet){

        //Keep Count Volum Data
        $data_deposit = [] ;
        $data_withdraw = [] ;


        foreach($dataSet as $value){
            switch($value->type){
                case 1 :
                    if(empty($data_deposit[$value->web_bank_name])){
                        $data_deposit[$value->web_bank_name]  = [
                        'less1k' => 0,
                        'between1k5k' => 0,
                        'between5k10k' => 0,
                        'more10k' => 0,];
                        $countLess_deposit[$value->web_bank_name] = ['count' => 0];
                        $count1kto5k_deposit[$value->web_bank_name] = ['count' => 0];
                        $count5kto10k_deposit[$value->web_bank_name] = ['count' => 0];
                        $countMore_deposit[$value->web_bank_name] = ['count' => 0];
                }
                if($value->amount <= 1000){
                    $countLess_deposit[$value->web_bank_name]['count']++;
                    $data_deposit[$value->web_bank_name]['less1k'] = $countLess_deposit[$value->web_bank_name]['count']   ;

                }else if ($value->amount > 1000 && $value->amount <= 5000){
                    $count1kto5k_deposit[$value->web_bank_name]['count']++;
                    $data_deposit[$value->web_bank_name]['between1k5k'] = $count1kto5k_deposit[$value->web_bank_name]['count'] ;

                }else if ($value->amount > 5000 && $value->amount <= 10000){
                    $count5kto10k_deposit[$value->web_bank_name]['count']++;
                    $data_deposit[$value->web_bank_name]['between5k10k'] = $count5kto10k_deposit[$value->web_bank_name]['count'] ;

                }else{
                    $countMore_deposit[$value->web_bank_name]['count']++;
                    $data_deposit[$value->web_bank_name]['more10k'] = $countMore_deposit[$value->web_bank_name]['count'] ;

                }

                   //Datalist Name
                if($value->amount >= 5000 && $value->amount <= 10000){
                    $dataList_deposit['between'][] = [
                        'name' => $value->cs_name ,
                        'count' => $value->countTotal ,
                        'sum' => $value->amount ,
                        ] ;
                }else if($value->amount > 10000){
                    $dataList_deposit['10kplus'][] = [
                        'name' => $value->cs_name ,
                        'count' => $value->countTotal ,
                        'sum' => $value->amount ,
                        ] ;
                }
            break;
            case 2 :
                if(empty($data_withdraw[$value->web_bank_name])){
                    $data_withdraw[$value->web_bank_name]  = [
                    'less1k' => 0,
                    'between1k5k' => 0,
                    'between5k10k' => 0,
                    'more10k' => 0,];
                    $countLess_withdraw[$value->web_bank_name] = ['count' => 0];
                    $count1kto5k_withdraw[$value->web_bank_name] = ['count' => 0];
                    $count5kto10k_withdraw[$value->web_bank_name] = ['count' => 0];
                    $countMore_withdraw[$value->web_bank_name] = ['count' => 0];
                    }
                    if($value->amount <= 1000){
                        $countLess_withdraw[$value->web_bank_name]['count']++;
                        $data_withdraw[$value->web_bank_name]['less1k'] = $countLess_withdraw[$value->web_bank_name]['count']   ;

                    }else if ($value->amount > 1000 && $value->amount <= 5000){
                        $count1kto5k_withdraw[$value->web_bank_name]['count']++;
                        $data_withdraw[$value->web_bank_name]['between1k5k'] = $count1kto5k_withdraw[$value->web_bank_name]['count'] ;

                    }else if ($value->amount > 5000 && $value->amount <= 10000){
                        $count5kto10k_withdraw[$value->web_bank_name]['count']++;
                        $data_withdraw[$value->web_bank_name]['between5k10k'] = $count5kto10k_withdraw[$value->web_bank_name]['count'] ;

                    }else{
                        $countMore_withdraw[$value->web_bank_name]['count']++;
                        $data_withdraw[$value->web_bank_name]['more10k'] = $countMore_withdraw[$value->web_bank_name]['count'] ;

                    }

                        //Datalist Name
                        if($value->amount >= 5000 && $value->amount <= 10000){
                            $dataList_withdraw['between'][] = [
                                'name' => $value->cs_name ,
                                'count' => $value->countTotal ,
                                'sum' => $value->amount ,
                                ] ;
                        }else if($value->amount > 10000){
                            $dataList_withdraw['10kplus'][] = [
                                'name' => $value->cs_name ,
                                'count' => $value->countTotal ,
                                'sum' => $value->amount ,
                                ] ;
                        }

            break;
            default: break ;

            }

    }



    //Merge dataVolum to Maindata
    foreach( $deposit as $keykeepvalue => $keepvalue){
         foreach($data_deposit as $keyname => $value){
            if($deposit[$keykeepvalue]->web_bank_name == $keyname){
                foreach ($value as $label => $countdata){
                    $deposit[$keykeepvalue]->$label = $countdata;
                }
            }
        }
    }

    foreach( $withdraw as $keykeepvalue => $keepvalue){
        foreach($data_withdraw as $keyname => $value){
           if($withdraw[$keykeepvalue]->web_bank_name == $keyname){
               foreach ($value as $label => $countdata){
                   $withdraw[$keykeepvalue]->$label = $countdata;
               }
           }
       }
   }


    //set data null
    if(empty($dataList_deposit['between'])){
        $dataList_deposit['between'][] = [
            'name' => 0,
            'count' => 0,
            'sum' => 0,
            ] ;
    }if (empty( $dataList_deposit['10kplus'])){
        $dataList_deposit['10kplus'][] = [
            'name' => 0,
            'count' => 0 ,
            'sum' => 0,
            ] ;

    }if (empty($dataList_withdraw['between'])){
        $dataList_withdraw['between'][] = [
            'name' => 0 ,
            'count' => 0,
            'sum' => 0,
            ] ;
    }if (empty($dataList_withdraw['10kplus'])){
        $dataList_withdraw['10kplus'][] = [
            'name' => 0 ,
            'count' =>0,
            'sum' => 0 ,
            ] ;
    }


    return array('deposit' => $deposit
    ,'dataList_deposit' => $dataList_deposit
    ,'withdraw' => $withdraw
    ,'dataList_withdraw' => $dataList_withdraw
    ) ;

}


public function withdraw_graph($agent_select_id,$today){

    $dataSet = DB::select("SELECT ts.`amount`,ts.`wd_ball`,ts.`wd_step`,ts.`wd_sport`,ts.`wd_game`,ts.`wd_casino`,ts.`wd_lotto`
    FROM `transections` ts
    WHERE DATE(`date_time`) = '$today'
    AND ts.`type` = 2
    AND `agent_status` = 1
    AND ts.`status` != 3
    AND  agent_id = $agent_select_id
    ") ;
    $data = [
        'wd_ball' => 0,
        'wd_step'=> 0,
        'wd_sport' => 0,
        'wd_game' => 0,
        'wd_casino' => 0,
        'wd_lotto' => 0,
    ];
    foreach ($dataSet as $value){

            $data['wd_ball'] += $value->wd_ball== 1 ? $value->amount : 0 ;
            $data['wd_step'] += $value->wd_step== 1 ? $value->amount : 0 ;
            $data['wd_sport'] += $value->wd_sport== 1 ? $value->amount : 0 ;
            $data['wd_game'] += $value->wd_game== 1 ? $value->amount : 0 ;
            $data['wd_casino'] += $value->wd_casino== 1 ? $value->amount : 0 ;
            $data['wd_lotto'] += $value->wd_lotto== 1 ? $value->amount : 0 ;

    }


    $sumWithdraw = array_sum($data);

    @$data['wd_ball'] =    $data['wd_ball']/$sumWithdraw*100 ;
    @$data['wd_step'] =    $data['wd_step']/$sumWithdraw*100 ;
    @$data['wd_sport'] =    $data['wd_sport']/$sumWithdraw*100 ;
    @$data['wd_game'] =    $data['wd_game']/$sumWithdraw*100 ;
    @$data['wd_casino'] =    $data['wd_casino']/$sumWithdraw*100 ;
    @$data['wd_lotto'] =    $data['wd_lotto']/$sumWithdraw*100 ;



    return $data;

}

}

