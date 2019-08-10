<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersMakeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table){
            $table->string('username',100)->nullable(true)->change();
            $table->string('no_hp',30)->nullable(true)->change();
            $table->integer('location_id')->nullable(true)->unsigned()->change();
            $table->string('access_token',200)->nullable(true)->change();
            $table->string('email')->nullable(true)->change();
            $table->string('name')->nullable(true)->change();
            $table->string('password')->nullable(true)->change();
            
            //$table->string('confirmed')->default("0")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        //
    }
}
