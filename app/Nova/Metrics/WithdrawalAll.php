<?php

namespace App\Nova\Metrics;

use App\TSwithdrawal;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class WithdrawalAll extends Value
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
        return $this->sum($request, TSwithdrawal::class,'amount');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => 'วันนี้',
            7 => 'สัปดาห์',
            30 => 'เดือน',
            365 => 'ปี',
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
        return 'withdrawal-all';
    }
}
