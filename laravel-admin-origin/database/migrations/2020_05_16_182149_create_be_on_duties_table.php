<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeOnDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('be_on_duties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('on_duty', 10,'')->nullable()->comment('上班时间');
            $table->string('off_duty', 10,'')->nullable()->comment('下班时间');
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
        Schema::dropIfExists('be_on_duties');
    }
}
