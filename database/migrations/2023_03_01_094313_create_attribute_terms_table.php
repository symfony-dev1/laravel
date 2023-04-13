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
        Schema::create('attribute_terms', function (Blueprint $table) {
            $table->id();
            $table->string("slug")->unique();
            $table->string("name");
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->on('attributes')->references('id')->onDelete('cascade');
            $table->tinyText('short_description')->nullable(true);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index(["name"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_terms');
    }
};
