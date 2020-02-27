<?php

use Illuminate\Database\Seeder;

class TstepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 9; $i++) { 
            \App\Tstep::insert([
                'uid' => ceil($i+1),
                'team1' => 'AZ อัลค์ม่าร์ 2 รอง 0.25',
                'team2' => 'พีเอสวี ไอนด์โฮเฟ่น รอง 0.25',
                'team3' => 'ทรัวส์ รอง 0.25',
                'team1w' => 0,
                'team2w' => 1,
                'team3w' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
