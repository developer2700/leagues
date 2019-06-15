<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('match_id');
            $table->unsignedInteger("week_id")->nullable(false);
            $table->foreign("week_id")->references("week_id")->on("weeks")->onDelete('cascade');
            $table->unsignedInteger("host_team_id")->nullable(false);
            $table->foreign("host_team_id")->references("team_id")->on("teams")->onDelete('cascade');
            $table->unsignedInteger("guest_team_id")->nullable(false);
            $table->foreign("guest_team_id")->references("team_id")->on("teams")->onDelete('cascade');
            $table->integer("host_result")->nullable(true);
            $table->integer("guest_result")->nullable(true);
            $table->dateTime('start_at')->nullable(true);
            $table->dateTime('end_at')->nullable(true);
            $table->softDeletes();
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
