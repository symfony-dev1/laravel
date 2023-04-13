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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("slug");
            $table->longText('description')->nullbale(true);
            $table->foreignId('parent_id')->nullable()->constrained("categories")->nullOnDelete();
            $table->index(["title", "slug"]);
            $table->string("category_image")->nullable(true);
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
        Schema::dropIfExists('categories');
    }
};
