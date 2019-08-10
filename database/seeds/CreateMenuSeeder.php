<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_menu')->insert([
            [
                'parent_id' => 0,
                'order' =>0,
                'title' => 'User Management',
                'icon' => 'fa-user',
                'uri' => 'users'
            ],
            [
                'parent_id' => 0,
                'order' =>0,
                'title' => 'Leaderboard',
                'icon' => 'fa-user',
                'uri' => 'leaderboard'
            ],
            [
                'parent_id' => 0,
                'order' =>0,
                'title' => 'Statistic Player',
                'icon' => 'fa-user',
                'uri' => 'statistic-player'
            ]
        ]);
    }
}
