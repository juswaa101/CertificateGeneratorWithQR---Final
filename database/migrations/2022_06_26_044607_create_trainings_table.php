<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training', function (Blueprint $table) {
            $table->id('training_id')->autoIncrement();
            $table->string('training');
            $table->string('image')->nullable();
            $table->string('img')->nullable();
            $table->string('logo')->nullable();
            $table->string('company');
            $table->date('from_start_date');
            $table->date('until_end_date');
            $table->string('signature')->nullable();
            $table->string('description');
            $table->string('organizer');
            $table->string('position');
            $table->string('status');
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
        Schema::dropIfExists('training');
    }
};
