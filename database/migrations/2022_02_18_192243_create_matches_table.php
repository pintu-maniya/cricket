<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');
            $table->string('sub_title');
            $table->string('venue');
            $table->string('tournament_key');
            $table->string('format');
            $table->string('status');
            $table->string('team_a');
            $table->string('team_b');
            $table->dateTime('start_at');
            $table->dateTime('start_at_local');
            $table->string('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
