<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionMatch extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'promotion_match';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
}
