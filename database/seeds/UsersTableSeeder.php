<?php

use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin@admin.com'),
            'line' => '',
            'facebook' => '',
            'avatar' => '',
            'level' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        for ($i=1; $i < 9; $i++) { 
            \App\User::insert([
                'name' => 'เซียน '.$i,
                'email' => 'z'.$i.'@z'.$i.'.com',
                'email_verified_at' => now(),
                'password' => bcrypt('z'.$i.'@z'.$i.'.com'),
                'line' => '',
                'facebook' => '',
                'avatar' => 'z'.$i.'.gif',                
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);         
        }      
    }
}
