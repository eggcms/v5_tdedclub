<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Setting extends Resource
{
    /**
    * The logical group associated with the resource.
    *
    * @var string
    */
    public static $group = 'Config';

    /**
    * Get the displayble label of the resource.
    *
    * @return string
    */
    public static function label()
    {
        return __('Setting Agents');
    }
    
    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\Setting';
    
    /**
    * The single value that should be used to represent the resource when being displayed.
    *
    * @var string
    */
    public static $title = 'id';
    
    /**
    * The columns that should be searched.
    *
    * @var array
    */
    public static $search = [
        'id','customer_prefix',
    ];
    
    /**
    * Get the fields displayed by the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Agent / ชื่อเว็บ','agent_name'),
            Text::make('รหัส Agent','agent_code'),
            Text::make('รหัสนำหน้า','customer_prefix'),
            Text::make('Master user','master_user'),
            Text::make('Line token ฝาก','line_token'),
            Text::make('Line token ถอน','line_token_withdrawal'),
            Text::make('Line เมื่อยอดเกิน','withdraw_condition'),
            Text::make('Agent Key','agent_key'),
        ];
    }
    
    /**
    * Get the cards available for the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function cards(Request $request)
    {
        return [];
    }
    
    /**
    * Get the filters available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function filters(Request $request)
    {
        return [];
    }
    
    /**
    * Get the lenses available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function lenses(Request $request)
    {
        return [];
    }
    
    /**
    * Get the actions available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function actions(Request $request)
    {
        return [];
    }
}
