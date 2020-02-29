<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Log;
use Koss\LaravelNovaSelect2\Select2;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Setting;

class Promotion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Promotion';

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
        'name',
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
            ID::make('ลำดับ','id')->sortable(),
         
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
            
            Text::make('ชื่อ promotion','name'),
            Date::make('เริ่มใช้งาน','start_date')->format('DD/MM/Y')->sortable(),
            Date::make('สิ้นสุด','end_date')->format('DD/MM/Y')->sortable(),
            Boolean::make('ใช้งาน','status')->sortable(),
            Image::make('ไฟล์ Banner','image')
            ->disk('public')
            ->path('promotion')
            ->storeAs(function (Request $request){
                return $request->image->getClientOriginalName();
            }),
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
