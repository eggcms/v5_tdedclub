<?php

namespace App\Nova\Metrics;

use Carbon\Carbon;
use App\TSwithdrawal;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class CustomerWithdrawal extends Value
{
    public $name = 'ยอดถอนทั้งหมด';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $days = Carbon::now()->subDays($request->range);
        $withdrawal = TSwithdrawal::where('customer_id','=',$request->resourceId)
        ->where('date_time','>=',$days)
        ->get();

        $data = 0;
        foreach ($withdrawal as  $value) {
            $data += $value->amount;
        }

      

        return $this->result($data)->suffix('THB')->format('0,0') ;
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
        return 'customer-withdrawal';
    }
}
