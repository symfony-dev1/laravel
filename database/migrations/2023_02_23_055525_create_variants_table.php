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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->decimal('price', 11, 2);
            $table->decimal('sale_price', 11, 2)->nullable(true);
            $table->text('short_description')->nullable(true);
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade');
            $table->index(['sku']);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variants');
    }
};
