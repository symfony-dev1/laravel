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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("short_description")->nullable(true);
            $table->tinyInteger("coupon_status")->comment("0 = ACTIVE, 1 = INACTIVE");
            $table->tinyInteger("discount_type")->comment("1 = Free Dis and 2 = Percentage Dis and 3 = Flat Dis");
            $table->decimal("discount_value", 11, 2);
            $table->tinyInteger("allow_free_shipping")->comment("0 = NO, 1 = YES");
            $table->decimal("maximum_spend", 11, 2);
            $table->decimal("minimum_spend", 11, 2);
            $table->integer("usage_limt_coupon")->nullable(true);
            $table->integer("usage_limit_user")->nullable(true);
            $table->string("specific_categories")->nullable(true);
            $table->string("specific_product")->nullable(true);
            $table->tinyInteger("user_type")->comment("1 = All Users, 2 = New Users, 3 = Old Users, 4 = Specific Users	");
            $table->string("product_categories");
            $table->integer("coupon_used_no")->default(0);
            $table->index(["name", "short_description"]);
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
        Schema::dropIfExists('coupons');
    }
};
