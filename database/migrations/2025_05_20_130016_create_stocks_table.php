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
            $table->string('item');  // Item Name
            $table->float('purchase_qty');  // Purchase Quantity
            $table->float('sale_qty')->default(0);  // Sale Quantity
            $table->float('foc')->default(0);  // Free of Cost Quantity
            $table->float('in_stock')->default(0);  // Calculated current stock

            $table->unsignedBigInteger('supplier_id')->nullable();  // Nullable Supplier
            $table->timestamps();
            $table->softDeletes();

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
