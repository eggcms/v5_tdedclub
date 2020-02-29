<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
	protected $table = 'site_config';
    
	/**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
}
