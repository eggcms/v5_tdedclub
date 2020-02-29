<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TSwithdrawal extends Model
{
    public $casts = [
        'date_time' => 'datetime'
    ];
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'ts_withdrawal';
    

        /**
     * Get the post that owns the comment.
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }

    public function webbank()
    {
        return $this->belongsTo('App\WebBank','to_acc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
