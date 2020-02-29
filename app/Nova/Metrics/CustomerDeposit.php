<?php

namespace App\Nova\Metrics;

use App\TSdeposit;
use Carbon\Carbon;
use App\Nova\Deposit;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;
use Illuminate\Support\Facades\Log;

class CustomerDeposit extends Value
{
    public $name = 'ยอดฝากทั้งหมด';
    /**
    * Calculate the value of the metric.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return mixed
    */
    public function calculate(Request $request)
    {
        $days = Carbon::now()->subDays($request->range);
        $deposits = TSdeposit::where('customer_id','=',$request->resourceId)
        ->where('date_time','>=',$days)
        ->get();
        
        // Log::debug($days);
        $tmpDep = 0;
        foreach ($deposits as  $value) {
            $tmpDep += $value->amount;
        }

      

        return $this->result($tmpDep)->suffix('THB')->format('0,0') ;
    }
    
    /**
    * Get the ranges available for the metric.
    *
    * @return array
    */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            // 'MTD' => 'Month To Date',
            // 'QTD' => 'Quarter To Date',
            // 'YTD' => 'Year To Date',
        ];
    }
    
    /**
    * Determine for how many minutes the metric should be cached.
    *
    * @return  \DateTimeInterface|\DateInterval|float|int
    */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
    
    /**
    * Get the URI key for the metric.
    *
    * @return string
    */
    public function uriKey()
    {
        return 'customer-deposit';
    }
}
