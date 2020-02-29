<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBank extends Model
{
    
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'customer_bank_acc';
    
	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
}
