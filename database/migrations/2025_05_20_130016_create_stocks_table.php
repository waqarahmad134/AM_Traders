<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item'); 
            $table->unsignedBigInteger('item_id');     // FK to items
            $table->string('batch_code')->nullable();  // Nullable batch code
            $table->date('expiry')->nullable();        // Nullable expiry
            $table->float('purchase_qty');             // Purchased quantity
            $table->float('sale_qty')->default(0);     // Sold quantity
            $table->float('foc')->default(0);          // Free of cost quantity
            $table->float('in_stock')->default(0);     // Calculated stock
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
