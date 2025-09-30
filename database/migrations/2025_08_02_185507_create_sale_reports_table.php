<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReportsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sale_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('item_name');
            $table->string('pack_size')->nullable();
            $table->integer('sale_qty');
            $table->integer('foc')->default(0);
            $table->decimal('sale_rate', 10, 2);
            $table->decimal('amount', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->string('batch_code')->nullable();
            $table->date('expiry')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_reports');
    }
}
