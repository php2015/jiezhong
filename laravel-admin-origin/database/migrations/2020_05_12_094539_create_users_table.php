<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id', 0,0)->nullable()->comment('用户id');
            $table->integer('set_num', 0,0)->nullable()->comment('设置步数');
            $table->integer('user_num', 0,0)->nullable()->comment('步数');
            $table->integer('time', 0,0)->nullable()->comment('时间');
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
        Schema::dropIfExists('users');
    }
}
