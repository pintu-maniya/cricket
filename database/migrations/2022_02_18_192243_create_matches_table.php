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
            $table->string('name')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('venue')->nullable();
            $table->string('tournament_key')->nullable();
            $table->string('format')->nullable();
            $table->string('status')->nullable();
            $table->string('team_a')->nullable();
            $table->string('team_b')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('start_at_local')->nullable();
            $table->string('message')->nullable();
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
