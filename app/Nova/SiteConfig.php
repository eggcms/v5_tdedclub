<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class SiteConfig extends Resource
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
        return __('Setting config');
    }
    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\SiteConfig';

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
        'id',
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
            Text::make('ชื่อเว็บ','site_name'),
            Text::make('ชื่อโปรแกรมเต็ม','app_full_name'),
            Text::make('ชื่อย่อโปรแกรม','app_short_name'),
            Image::make('Login หน้า login','login_logo')->disk('public')
            ->path('logos'),
            Image::make('Login หน้า Home','home_logo')->disk('public')
            ->path('logos'),
            Text::make('Token เตือน user login','line_token_login_alert'),
            Text::make('Token เตือน ยอดรายชั่วโมง','line_token_hourly_alert'),
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
