<?php

namespace App\Nova\Metrics;

use App\TSdeposit;
use Carbon\Carbon;
use App\TSwithdrawal;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;
use Illuminate\Support\Facades\Log;

class CustomerWebProfit extends Value
{
    public $name = 'เว็บกำไร / ขาดทุน';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        // return $this->count($request, Model::class);
        $days = Carbon::now()->subDays($request->range);
        $deposits = TSdeposit::where('customer_id','=',$request->resourceId)
        ->where('date_time','>=',$days)
        ->get();
        $withdrawal = TSwithdrawal::where('customer_id','=',$request->resourceId)
        ->where('date_time','>=',$days)
        ->get();

        //Log::debug($withdrawal);

        $tmpDep = 0;
        $tmpwith = 0;
        foreach ($deposits as  $value) {
            $tmpDep += $value->amount;
        }

        foreach ($withdrawal as  $withdraw) {
            $tmpwith += $withdraw->amount;
        }

        $webProfit = $tmpDep-$tmpwith;

        return $this->result($webProfit)->suffix('THB')->format('0,0') ;
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
        return 'customer-web-profit';
    }
}
