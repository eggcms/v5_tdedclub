<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSLogs extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'sms_logs';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
}
