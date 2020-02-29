<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Banks;
use App\Setting;
use App\Marketing;
use App\SiteConfig;
use App\Transection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class MarketingController extends Controller
{
    public function customerLowPayment()
    {
        $marketing = Marketing::where('active',1)
            ->get();
        $customer = null;
        $market = null;
        $pay_total = null;
        $daycheck = null;
        $old_amount_pay = 1;
        $old_daycheck = 1;
        $old_ref = 'all';
        return view('marketing/low-payment',compact('marketing', 'customer','market', 'pay_total', 'old_amount_pay', 'old_daycheck', 'old_ref', 'daycheck'));
    }

    public function checkLowPayment(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->subDays(5)->format('Y-m-d');

        if($request->daycheck == '1') {
            $trans = Transection::whereBetween('date_time', [Carbon::now()->subDays(7) ,Carbon::now()])->get();
            $daycheck = '1 อาทิตย์';
        }
        if($request->daycheck == '2') {
            $trans = Transection::whereBetween('date_time', [Carbon::now()->subDays(14) ,Carbon::now()])->get();
            $daycheck = '2 อาทิตย์';
        }
        if($request->daycheck == '3') {
            $trans = Transection::whereBetween('date_time', [Carbon::now()->subDays(21) ,Carbon::now()])->get();
            $daycheck = '3 อาทิตย์';
        }
        if($request->daycheck == '4') {
            $trans = Transection::whereBetween('date_time', [Carbon::now()->subDays(30) ,Carbon::now()])->get();
            $daycheck = '1 เดือน';
        }
        if($request->daycheck == '5') {
            $trans = Transection::whereBetween('date_time', [Carbon::now()->subDays(60) ,Carbon::now()])->get();
            $daycheck = '2 เดือน';
        }

        $userTrans = [];

        foreach($trans as $item) {
            $userTrans[] = $item->customer_code;
        }

        $userTransCount = [];

        foreach($userTrans as $item) {
            $userTransCount[$item] = 0;
        }

        foreach($userTrans as $check) {
            foreach($userTransCount as $key => $item){
                if($key == $check) {
                    $userTransCount[$key] = $item+1;
                }
            }
        }

        $checkUnder1 = [];
        $checkUnder2 = [];
        $checkUnder3 = [];

        foreach($userTransCount as $key => $item) {
            if ($item == 1) {
                $checkUnder1[] = $key;
            }
            if ($item == 2) {
                $checkUnder2[] = $key;
            }
            if ($item == 3) {
                $checkUnder3[] = $key;
            }
        }

        if($request->ref == 'all') {
            $customer = Customer::orderBy('ref', 'DESC')->get();
            $market = 'all';
        } else {
            $customer = Customer::where('ref', $request->ref)->orderBy('id', 'DESC')->get();
            $market = Marketing::find($request->ref);
        }


        $list = [];

        if($request->amount_pay == '1') {
            foreach($customer as $item) {
                if(in_array($item->mm_user, $checkUnder1)) {
                    $list[] = $item;
                }
            }
            $pay_total = '1 ครั้ง';
        }
        if($request->amount_pay == '2') {
            foreach($customer as $item) {
                if(in_array($item->mm_user, $checkUnder2)) {
                    $list[] = $item;
                }
            }
            $pay_total = '2 ครั้ง';
        }
        if($request->amount_pay == '3') {
            foreach($customer as $item) {
                if(in_array($item->mm_user, $checkUnder3)) {
                    $list[] = $item;
                }
            }
            $pay_total = '3 ครั้ง';
        }

        $customer = $list;

        $old_amount_pay = $request->amount_pay;
        $old_daycheck = $request->daycheck;
        $old_ref = $request->ref;

        $marketing = Marketing::where('active',1)
            ->get();
        return view('marketing/low-payment',compact('marketing', 'customer', 'pay_total', 'market', 'old_amount_pay', 'old_daycheck', 'old_ref', 'daycheck'));
    }
}

