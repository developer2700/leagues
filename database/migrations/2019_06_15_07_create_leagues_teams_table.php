<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues_teams', function (Blueprint $table) {
            $table->increments('league_team_id');
            $table->unsignedInteger("league_id")->nullable(false);
            $table->foreign("league_id")->references("league_id")->on("leagues")->onDelete('cascade');
            $table->unsignedInteger("team_id")->nullable(false);
            $table->foreign("team_id")->references("team_id")->on("teams")->onDelete('cascade');
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('gf')->default(0);
            $table->integer('ga')->default(0);
            $table->integer('gd')->default(0);
            $table->integer('Points')->default(0);
            $table->integer("percent")->default(0);
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
        Schema::dropIfExists('leagues_teams');
    }
}
