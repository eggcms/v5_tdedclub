<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([AnalyzesTableSeeder::class]);
        $this->call([BlogsTableSeeder::class]);
        $this->call([ZeansTableSeeder::class]);
        $this->call([UsersTableSeeder::class]);
        $this->call([TstepsTableSeeder::class]);
        // $this->call([ZeanTstepsSeeder::class]);
        // $this->call([YoutubesTableSeeder::class]);

    }
}
