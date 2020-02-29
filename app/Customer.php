<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $casts = [
        'birth_date' => 'date',
        'open_user_date' => 'date'
    ];
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'customers';

	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];


    public function bank()
    {
        return $this->hasOne('App\Banks');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'staff_id');
    }

    public function market()
    {
        return $this->belongsTo(Marketing::class,'ref');
    }

    public function transection()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
    * Get the phone record associated with the user.
    */
    public function deposit()
    {
        return $this->hasMany('App\TSdeposit');
    }
    public function withdrawal()
    {
        return $this->hasMany('App\TSwithdrawal');
    }

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
