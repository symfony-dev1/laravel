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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("slug");
            $table->longText('description')->nullbale(true);
            $table->longText('short_description')->nullbale(true);
            $table->decimal('price', $precision = 11, $scale = 2);
            $table->decimal('sales_price', $precision = 11, $scale = 2)->nullable(true);
            $table->string("sku");
            $table->bigInteger('quantity');
            $table->foreignId('primary_cat_id')->nullable()->constrained("categories")->nullOnDelete();
            $table->string("product_image");
            $table->string("img_alt")->nullable(true);
            $table->string("img_caption")->nullable(true);
            $table->string("title");
            $table->bigInteger('stock');
            $table->tinyInteger('stock_status')->comment("1 = IN STOCK, 2 = OUT OF STOCK, 3 = ONBACKOREDER");
            $table->tinyInteger('allow_backorders')->comment("1 = Yes, 2 =NO, 3 = ONBACKOREDER")->default(0);
            $table->tinyInteger('status')->comment("1 = Draft, 2 = Pending Review, 3 = Published");
            $table->tinyInteger('sold_individually')->comment("0 = NO LIMIT, 1 = Limit purchases to 1 item per order");
            $table->integer('weight')->nullable(true);
            $table->integer('dim_length')->nullable(true);
            $table->integer('dim_width')->nullable(true);
            $table->integer('dim_height')->nullable(true);
            $table->string('up_sells')->nullable(true);
            $table->string('cross_sells')->nullable(true);
            $table->tinyText('purchase_note')->nullable(true);
            $table->integer('menu_oreder')->default(0);
            $table->tinyInteger('enable_reviews')->comment("0 = NO, 1 = YES");
            $table->tinyInteger('product_type')->comment("0 = simple, 1 = variable");
            $table->index(["sku", "title", "slug"]);
            $table->timestamp("created_at")->useCurrent();
            $table->date("start_date")->nullable(true);
            $table->date("end_date")->nullable(true);
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
        Schema::dropIfExists('products');
    }
};
