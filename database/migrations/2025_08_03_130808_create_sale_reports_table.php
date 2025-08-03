<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReportsTable extends Migration
{
    public function up()
    {
        Schema::create('sale_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('item_code');
            $table->string('item_name');
            $table->string('pack_size')->nullable();
            $table->integer('sale_qty');
            $table->integer('foc')->default(0);
            $table->decimal('sale_rate', 10, 2);
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_reports');
    }
}
