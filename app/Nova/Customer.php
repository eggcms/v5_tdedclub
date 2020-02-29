<?php

namespace App\Nova;

use App\Banks;
use App\Setting;
use App\Marketing;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Koss\LaravelNovaSelect2\Select2;
use Laravel\Nova\Actions\Actionable;
use App\Nova\Metrics\CustomerDeposit;
use App\Nova\Metrics\CustomerWebProfit;
use App\Nova\Metrics\CustomerWithdrawal;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Http\Requests\NovaRequest;

class Customer extends Resource
{
    use Actionable, Notifiable;
    
    /**
    * The logical group associated with the resource.
    *
    * @var string
    */
    public static $group = 'ลูกค้า';
    
    /**
    * Get the displayble label of the resource.
    *
    * @return string
    */
    public static function label()
    {
        return __('ข้อมูลลูกค้า');
    }
    
    /**
    * Get the displayble singular label of the resource.
    *
    * @return string
    */
    public static function singularLabel()
    {
        return __('ข้อมูลลูกค้า');
    }
    
    
    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\Customer';
    
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
        'id','first_name','last_name','mm_user','acc_no','line_id','tel'
    ];
    
    /**
    * Get the fields displayed by the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function fields(Request $request)
    {
        $banks = Banks::where('active',1)->pluck('code','id');
        $ref_channel = Marketing::pluck('name','id');
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
            Text::make('รหัสลูกค้า','mm_user')->sortable(),
            Text::make('ชื่อ','first_name')->sortable(),
            Text::make('สกุล','last_name')->sortable(),
            Text::make('โทร','tel')->sortable(),
            Text::make('Line ID','line_id')->sortable(), 
            Text::make('ชื่อจาก SMS','text_sms_ref')->sortable(),
            
            //Select::make('ธนาคาร','bank_id')->options($banks)->displayUsingLabels(),
            Select2::make('ธนาคาร', 'bank_id')
            ->sortable()
            ->options($banks)
            /**
            * Documentation
            * https://select2.org/configuration/options-api
            */
            ->configuration([
                'placeholder'             => __('Choose an option'),
                'allowClear'              => true,
                'minimumResultsForSearch' => 1
                ]),
                
                Text::make('เลขที่ บช.','acc_no')->sortable(),
                Date::make('วันเกิด','birth_date')->format('DD/MM/YYYY')->sortable(),
                
                // Text::make('แนะนำโดย','ref')->sortable(),
                
                Select2::make('แนะนำโดย', 'ref')
                ->sortable()
                ->options($ref_channel)
                ->configuration([
                    'placeholder'             => __('Choose an option'),
                    'allowClear'              => true,
                    'minimumResultsForSearch' => 1
                    ]),
                    
                    Text::make('สิทธิพิเศษ / โบนัส','extra')->sortable(),
                   
                    Select::make('สถานะ','status')->options([
                        '0' => 'ปกติ',
                        '1' => 'เฝ้าระวัง',
                        '2' => 'แบน / ปิด BET',
                    ])->displayUsingLabels(),

                    Date::make('วันที่เปิดยูส','open_user_date')->format('DD/MM/YYYY')->sortable(),
                    BelongsTo::make('User')->searchable()->withMeta([
                        'belongsToId' => $this->user_id ?? auth()->user()->id
                        ]),
                        
                        // new Panel('ประวัติการฝาก', $this->depositList()),
                        // new Panel('ประวัติการถอน', $this->withdrawalList()),
                        
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
                    return [
                        // (new CustomerDeposit)->width('1/3')->onlyOnDetail(),
                        // (new CustomerWithdrawal)->width('1/3')->onlyOnDetail(),
                        // (new CustomerWebProfit)->width('1/3')->onlyOnDetail(),
                    ];
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
                
                
                public function depositList(){
                    return [
                        
                        HasMany::make('ประวัติการฝาก','deposit','App\Nova\Deposit')
                    ];
                }   
                public function withdrawalList(){
                    return [
                        
                        HasMany::make('ประวัติการถอน','withdrawal','App\Nova\Withdrawal')
                    ];
                }
            }
            