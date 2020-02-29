<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Transection extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'transections';
    
	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];


    public function user(){
        return $this->belongsTo(User::class,'staff_id');
    }
    public function updatedByUser(){
        return $this->belongsTo(User::class,'update_by_staff_id');
    }

    public function bankFrom()
    {
        return $this->belongsTo('App\Banks');
    }
    public function bankTo()
    {
        return $this->belongsTo('App\Banks');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }


}
