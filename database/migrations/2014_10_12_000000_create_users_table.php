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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('ntn_strn')->nullable(); 
            $table->string('license_no')->nullable(); 
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('status')->default('active');
            $table->string('contact')->nullable();
            $table->text('area')->nullable();
            $table->text('address')->nullable();
            $table->string('usertype')->default('customer'); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
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
};
