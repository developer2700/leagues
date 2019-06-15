<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('setting_id');
            $table->unsignedInteger("base_setting_id")->nullable(false);
            $table->foreign("base_setting_id")->references("base_setting_id")->on("base_settings")->onDelete('cascade');
            $table->unsignedInteger('parent_id')->nullable(true);
            $table->foreign('parent_id')->references('setting_id')->on('settings')->onDelete('cascade');
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
        Schema::dropIfExists('settings');
    }
}
