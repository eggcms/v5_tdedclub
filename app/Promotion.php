<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    public $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'promotions';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
}
