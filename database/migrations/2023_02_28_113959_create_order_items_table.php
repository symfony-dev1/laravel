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
        try {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id')->nullable(true);
                $table->unsignedBigInteger('variant_id')->nullable(true);
                $table->bigInteger('quantity');
                $table->decimal('unit_amount', 11, 2);
                $table->foreign('order_id')->on('orders')->references('id')->onDelete('cascade');
                $table->foreign('variant_id')->on('variants')->references('id')->onDelete('set null');
                $table->foreign('product_id')->on('products')->references('id')->onDelete('set null');
                $table->timestamp("created_at")->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
