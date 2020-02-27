<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BlogsTableSeeder extends Seeder
{

    public function run()
    {
        \App\Blog::insert([
            'title' => 'Test no.1 Versioning Scheme',
            'description' => 'Laravel\'s versioning scheme maintains the following convention: paradigm.major.minor.',
            'content' => 'When referencing the Laravel framework or its components from your application or package, you should always use a version constraint such as 5.8.*',
            'image' => 'noimg.jpg',
            'tag' => 'One, command, column',
            'slug' => 'Test-no-1-Versioning-Scheme',
            'status' => '1',
            'hot' => '0',
            'switch1' => '0',
            'switch2' => '0',
            'visit' => '0',               
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),                
        ]); 
        \App\Blog::insert([
            'title' => 'Test no.2 Versioning Scheme',
            'description' => 'Laravel\'s versioning scheme maintains the following convention: paradigm.major.minor.',
            'content' => 'When referencing the Laravel framework or its components from your application or package, you should always use a version constraint such as 5.8.*',
            'image' => 'noimg.jpg',
            'tag' => 'One, command, column',
            'slug' => 'Test-no-2-Versioning-Scheme',
            'status' => '1',
            'hot' => '0',
            'switch1' => '0',
            'switch2' => '0',
            'visit' => '0',                            
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),               
        ]); 
    }
}