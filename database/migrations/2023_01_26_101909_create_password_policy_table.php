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
        Schema::create('password_policy', function (Blueprint $table) {
            $table->id();
            $table->integer('min')->default(8);
            $table->integer('max')->nullable();
            $table->boolean('mixed_case')->default(false);
            $table->boolean('uncompromised')->default(false);
            $table->boolean('symbols')->default(false);
            $table->boolean('numbers')->default(false);
            $table->boolean('letters')->default(false);
            $table->integer('dont_repeat_last_n_passwords')->default(1);
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
        Schema::dropIfExists('password_policy');
    }
};
