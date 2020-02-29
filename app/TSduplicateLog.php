<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TSduplicateLog extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'ts_duplicate_log';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];     
}
