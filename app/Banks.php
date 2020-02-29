<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'banks';
    
	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];     


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function webbank()
    {
        return $this->belongsTo('App\WebBank');
    }

    public function tsFrom()
    {
        return $this->hasOne('App\Transection','from_bank');
    }

    public function tsTo()
    {
        return $this->belongsTo('App\Transection','to_bank');
    }

    public function newUser()
    {
        return $this->belongsTo('App\NewCustomerZean');
    }



}
