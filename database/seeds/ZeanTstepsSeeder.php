<?php

use Illuminate\Database\Seeder;

class ZeanTstepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ZeanTstep::insert([
            'name' => 'เซียนหนึ่ง',
            'line' => 'line01',
            'facebook' => 'facebook01',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนสอง',
            'line' => 'line02',
            'facebook' => 'facebook02',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนสาม',
            'line' => 'line03',
            'facebook' => 'facebook03',
            'image' => '',
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนสี่',
            'line' => 'line04',
            'facebook' => 'facebook04',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนห้า',
            'line' => 'line05',
            'facebook' => 'facebook05',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนหก',
            'line' => 'line06',
            'facebook' => 'facebook06',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนเจ็ด',
            'line' => 'line07',
            'facebook' => 'facebook07',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\ZeanTstep::insert([
            'name' => 'เซียนแปด',
            'line' => 'line08',
            'facebook' => 'facebook08',
            'image' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
