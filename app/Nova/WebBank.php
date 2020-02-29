<?php

namespace App\Nova;

use App\Setting;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Koss\LaravelNovaSelect2\Select2;
use App\Nova\Filters\WebBankAgentSeach;
use Laravel\Nova\Http\Requests\NovaRequest;

class WebBank extends Resource
{

    
    /**
    * The logical group associated with the resource.
    *
    * @var string
    */
    public static $group = 'การจัดการ';

    public static function label()
    {
        return __('บัญชีเว็บ');
    }
    
    /**
    * Get the displayble singular label of the resource.
    *
    * @return string
    */
    public static function singularLabel()
    {
        return __('บัญชีเว็บ');
    }
    
    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\WebBank';
    
    /**
    * The single value that should be used to represent the resource when being displayed.
    *
    * @var string
    */
    public static $title = 'acc_name';
    
    /**
    * The columns that should be searched.
    *
    * @var array
    */
    public static $search = [
        'id','name','acc_no','ref'
    ];
    
    /**
    * Get the fields displayed by the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function fields(Request $request)
    {
        $agents = Setting::all()->pluck('agent_name','id');
        return [
            ID::make()->sortable(),
            Select2::make('เว็บ / เอเย่น', 'agent_id')
            ->sortable()
            ->options($agents)
            /**
            * Documentation
            * https://select2.org/configuration/options-api
            */
            ->configuration([
                'placeholder'             => __('Choose an option'),
                'allowClear'              => true,
                'minimumResultsForSearch' => 1
                ]),
            BelongsTo::make('Bank','bank','App\Nova\Banks')->searchable(),   
            Text::make('ชื่อบัญชี','name'),
            Text::make('เลขที่บัญชี','acc_no'),
            Select::make('ประเภท','type')->options([
                '1' => 'ฝาก',
                '2' => 'ถอน',
                '3' => 'ฝาก + ถอน',
            ])->displayUsingLabels(),
            Text::make('Tag','ref')->sortable(),
            Boolean::make('ใช้งาน','active')->sortable(),
            Number::make('ลำดับจัดเรียง','sort')->sortable()
           
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
        return [
            new WebBankAgentSeach,
        ];
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
