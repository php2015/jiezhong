<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id', 0,0)->nullable()->comment('用户id');
            $table->integer('children_id',0,0)->nullable()->comment('孩子id');
            $table->integer('organization_id', 0,0)->nullable()->comment('机构id');
            $table->date('time')->comment('预约时间');
            $table->integer('hour', 0,0)->nullable()->comment('预约时段');
            $table->string('wx_code', 255,'')->nullable()->comment('二维码');
            $table->integer('is_del', 0,0)->nullable()->comment('是否删除 1是 0 否');
            $table->integer('is_ok',0,0)->nullable()->comment('是否完成 1是0否');
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
        Schema::dropIfExists('bookings');
    }
}
