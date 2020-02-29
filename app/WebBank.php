<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Transaction;

class WebBank extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'web_bank_acc';
    
	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    public function bank()
    {
        return $this->belongsTo('App\Banks','bank_id');
    }

    public function balance($id){
        $total = Transaction::where('web_bank_id',$id)
        ->where('status',1)->sum('amount');
        return   $total;
    }

}
