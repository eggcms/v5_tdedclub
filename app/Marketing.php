<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'marketing';
    
	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    public function customer(){
        return $this->hasMany(Customer::class);
    }
}
