<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reminder_id');
            $table->unsignedBigInteger('birthday_id');
            $table->timestamp('remind_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('birthday_id')
                ->references('id')->on('birthdays')
                ->onDelete('cascade');

            $table->foreign('reminder_id')
                ->references('id')->on('reminders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_schedules');
    }
}
