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
        Schema::create('attribute_term_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("attribute_term_id");
            $table->foreign("attribute_term_id")->on("attribute_terms")->references("id")->onDelete("cascade");
            $table->unsignedBigInteger("product_id");
            $table->foreign("product_id")->on("products")->references("id")->onDelete("cascade");
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_term_product');
    }
};
