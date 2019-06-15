<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_settings', function (Blueprint $table) {
            $table->increments('base_setting_id');
            $table->unsignedInteger('parent_id')->nullable(true);
            $table->foreign('parent_id')->references('base_setting_id')->on('base_settings')->onDelete('cascade');
            $table->string("title")->nullable(false);
            $table->text('description')->nullable(true);
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
        Schema::dropIfExists('base_settings');
    }
}
