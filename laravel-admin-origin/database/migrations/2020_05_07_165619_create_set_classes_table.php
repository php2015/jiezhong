<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id', 0,0)->nullable()->comment('机构id');
            $table->integer('time',0,0)->nullable()->comment('时间');
            $table->string('hour', "")->nullable()->comment(' 时间段');
            $table->integer('num', 0,0)->nullable()->comment('预约人数');
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
        Schema::dropIfExists('set_classes');
    }
}
