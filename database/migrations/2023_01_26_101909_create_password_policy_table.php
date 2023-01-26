<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->integer('password_expires_n_days')->default(30);
            $table->timestamps();
        });

        //
        DB::table('password_policy')
            ->insert([
                'min' => 8,
                'max' => 20,
                'mix_case' => false,
                'uncompromised' => false,
                'symbols' => false,
                'numbers' => false,
                'letters' => false,
                'dont_repeat_last_n_passwords' => 7,
                'password_expires_n_days' => 30
            ]);
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
