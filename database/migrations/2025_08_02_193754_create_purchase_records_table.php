<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id'); // FK to items table
            $table->unsignedBigInteger('supplier_id')->nullable(); // FK to users table, now nullable
            $table->integer('pack_qty');
            $table->decimal('purchase_rate', 10, 2);
            $table->integer('purchase_qty');
            $table->decimal('sale_rate', 10, 2);
            $table->text('remarks')->nullable();
            $table->text('batch_code')->nullable();
            $table->date('expiry')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_records');
    }
};
