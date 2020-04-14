<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('remindable_id');
            $table->unsignedSmallInteger('remindable_type')->default(1);
            $table->unsignedInteger('before_amount')->nullable();
            $table->unsignedSmallInteger('before_unit')->nullable();
            $table->timestamp('remind_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['remindable_type', 'remindable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
