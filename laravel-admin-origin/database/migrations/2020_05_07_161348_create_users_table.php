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
            $table->string('name', 255)->nullable()->comment('姓名');
            $table->string('head', 255)->nullable()->comment('头像');
            $table->string('mobile', 11)->nullable()->comment('手机号');
            $table->string('address', 255)->nullable()->comment(' 地址');
            $table->string('open_id', 255)->nullable()->comment(' open_id');
            $table->integer('id_number', 0,0)->nullable()->comment('身份证号');
            $table->integer('is_organization', 0,0)->nullable()->comment('是否机构人员1是0否');
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
