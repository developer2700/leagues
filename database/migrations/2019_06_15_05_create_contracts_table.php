<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('contract_id');
            $table->unsignedInteger("player_id")->nullable(false);
            $table->foreign("player_id")->references("player_id")->on("players")->onDelete('cascade');
            $table->unsignedInteger("team_id")->nullable(false);
            $table->foreign("team_id")->references("team_id")->on("teams")->onDelete('cascade');
            $table->dateTime('start_at')->nullable('false');
            $table->dateTime('end_at')->nullable('true')->default(null);
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
        Schema::dropIfExists('contracts');
    }
}
