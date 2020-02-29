<?php

namespace App\Nova;

use App\Setting;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Koss\LaravelNovaSelect2\Select2;

class User extends Resource
{
    /**
    * The logical group associated with the resource.
    *
    * @var string
    */
    public static $group = 'พนักงาน / ผู้ใช้งานระบบ';


    /**
    * Get the displayble label of the resource.
    *
    * @return string
    */
    public static function label()
    {
        return __('พนักงาน');
    }
    
    /**
    * Get the displayble singular label of the resource.
    *
    * @return string
    */
    public static function singularLabel()
    {
        return __('พนักงาน');
    }

    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\\User';
    
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
        'id', 'name', 'email',
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

        if($request->user()->role == 'programmer'){
            $optionRole = [
                'zean' => 'เซียน / การตลาด',
                'call' => 'Call center',
                'leader' => 'หัวหน้างาน',
                'programmer' => 'Programmer',
                'boss' => 'ผู้บริหาร',];
        }else{
            $optionRole = [
                'zean' => 'เซียน / การตลาด',
                'call' => 'Call center',
                'leader' => 'หัวหน้างาน',];
        }

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
            Gravatar::make(),
            
            Text::make('Name')
            ->sortable()
            ->rules('required', 'max:255'),
            
            Text::make('Email')
            ->sortable()
            ->rules('required', 'email', 'max:254')
            ->creationRules('unique:users,email')
            ->updateRules('unique:users,email,{{resourceId}}'),
            
            Text::make('Tel','tel'),
            Text::make('Line','line_id'),

            Select::make('ตำแหน่ง','role')->options($optionRole)->displayUsingLabels(),
            
            Password::make('Password')
            ->onlyOnForms()
            ->creationRules('required', 'string', 'min:6')
            ->updateRules('nullable', 'string', 'min:6'),
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
