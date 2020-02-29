<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMasterLogs extends Model
{
/**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table = 'customer_master_create_logs';
    
   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   protected $guarded = ['id'];
}
