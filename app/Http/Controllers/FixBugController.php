<?php

namespace App\Http\Controllers;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixBugController extends Controller
{
    public function fixMonth(){
          $data = DB::select("SELECT * FROM customer_leab WHERE `bd_mon` = 0");
        
        foreach ($data as $value) {
            try {
                DB::table('customer_leab')
                ->where('customer_id', $value->customer_id)
                ->update(['bd_mon' => $this->findMonth($value->customer_id)]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }
    }
    
    public function findMonth($customer_id){
        $customer = Customer::where('mm_user','=',$customer_id)->first();
        
        return  $monthFix = Carbon::parse($customer->birth_date)->format('m');
    }
}
