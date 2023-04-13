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
        Schema::create('carts', function (Blueprint $table) {
            // $table->id();

            $table->uuid('id')->primary();
            $table->string('guest_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // $table->uuid('id')->primary();
            // $table->string('guest_id')->nullable();
            // $table->unsignedBigInteger('user_id')->nullable(true);
            // $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            // $table->string('uu_id', 50)->nullable(true);
            // $table->unsignedBigInteger('product_id');
            // $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade');
            // $table->foreignId('variant_id')->nullable()->constrained("variants")->nullOnDelete();
            // $table->unsignedInteger('quantity');
            // $table->string("image");
            // $table->timestamp("created_at")->useCurrent();
            // $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
