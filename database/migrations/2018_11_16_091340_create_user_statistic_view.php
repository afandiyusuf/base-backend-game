<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStatisticView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $databseName = env('DB_DATABASE');
        DB::statement('CREATE ALGORITHM = UNDEFINED VIEW `player-statistic-view` AS select `'.env('DB_DATABASE').'`.`users`.`id` AS `id`, `'.env('DB_DATABASE').'`.`users`.`username` AS `player_username`,`'.env('DB_DATABASE').'`.`statistics`.`name` AS `statistic_name`,`'.env('DB_DATABASE').'`.`statistic_players`.`value` AS `value`,`'.env('DB_DATABASE').'`.`statistic_players`.`updated_at` AS `updated_at` from ((`'.env('DB_DATABASE').'`.`users` join `'.env('DB_DATABASE').'`.`statistic_players` on((`'.env('DB_DATABASE').'`.`users`.`id` = `'.env('DB_DATABASE').'`.`statistic_players`.`user_id`))) join `'.env('DB_DATABASE').'`.`statistics` on((`'.env('DB_DATABASE').'`.`statistic_players`.`statistic_id` = `'.env('DB_DATABASE').'`.`statistics`.`id`)))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement("DROP VIEW `player-statistic-view`");
    }
}
