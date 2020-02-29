<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewCustomerZean extends Model
{
    public $casts = [
        // 'start_date' => 'date',
        // 'end_date' => 'date'
    ];
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'customer_zean';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    public function bank()
    {
        return $this->hasOne('App\Banks','id','bank_id');
    }
    public function zean()
    {
        return $this->hasOne('App\User','id','zean_id');
    }
    
}
