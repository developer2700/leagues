<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->unsignedInteger("match_id")->nullable(false);
            $table->foreign("match_id")->references("match_id")->on("matches")->onDelete('cascade');
            $table->unsignedInteger("event_type_id")->nullable(false);
            $table->foreign("event_type_id")->references("setting_id")->on("settings")->onDelete('cascade');
            $table->unsignedInteger("player_id")->nullable(true);
            $table->foreign("player_id")->references("player_id")->on("players")->onDelete('cascade');
            $table->integer('minute')->nullable(false);
            $table->index(['minute']);
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('events');
    }
}
