<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username', 60)->unique()->nullable();
            $table->string('password')->nullable()->change();
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('avatar_id')->unsigned()->nullable();
            $table->boolean('super_user')->default(0);
            $table->boolean('manage_supers')->default(0);
        });

        Schema::create('activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('code', 120);
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 120)->unique();
            $table->string('name', 120);
            $table->text('permissions')->nullable();
            $table->string('description', 255)->nullable();
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->integer('created_by')->unsigned()->references('id')->on('users')->index();
            $table->integer('updated_by')->unsigned()->references('id')->on('users')->index();
            $table->timestamps();
        });

        Schema::create('role_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->integer('role_id')->unsigned()->references('id')->on('roles')->index();
            $table->nullableTimestamps();
        });

        Schema::create('user_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
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
        Schema::dropIfExists('activations');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_meta');
    }
}
