<?php

namespace App\Nova;

use App\Setting;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Koss\LaravelNovaSelect2\Select2;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Http\Requests\NovaRequest;

class Marketing extends Resource
{
    use Actionable, Notifiable;
    /**
    * Indicates if the resource should be displayed in the sidebar.
    *
    * @var bool
    */
    public static $displayInNavigation = true;
    /**
    * The logical group associated with the resource.
    *
    * @var string
    */
    public static $group = 'การจัดการ';
    
    /**
    * Get the displayble label of the resource.
    *
    * @return string
    */
    public static function label()
    {
        return __('ช่องทางการตลาด');
    }
    
    /**
    * Get the displayble singular label of the resource.
    *
    * @return string
    */
    public static function singularLabel()
    {
        return __('ช่องทางการตลาด');
    }
    
    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\Marketing';
    
    /**
    * The single value that should be used to represent the resource when being displayed.
    *
    * @var string
    */
    public static $title = 'name';
    
    /**
    * The columns that should be searched.
    *
    * @var array
    */
    public static $search = [
        'id','name',
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
            Text::make('ชื่อ','name'),
            Select::make('กลุ่ม','group')->options([
                'call' => 'Call center',
                'web' => 'Website',
                'social' => 'Social',
                'zean' => 'เซียน',
            ])->displayUsingLabels(),
            Boolean::make('สถานะ','active')->sortable(),
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
