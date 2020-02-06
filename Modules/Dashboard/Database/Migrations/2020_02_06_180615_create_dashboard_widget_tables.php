<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardWidgetTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->timestamps();
        });

        Schema::create('dashboard_widget_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('settings')->nullable();
            $table->integer('user_id')->unsigned()->index()->references('id')->on('users');
            $table->integer('widget_id')->unsigned()->index()->references('id')->on('widgets');
            $table->tinyInteger('order')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(1);
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
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('dashboard_widget_settings');
    }
}
