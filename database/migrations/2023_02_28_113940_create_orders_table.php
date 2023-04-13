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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('shipping_charge', 11, 2)->nullable();
            $table->decimal('total_amount', 11, 2);
            // $table->unsignedBigInteger('coupon_id');
            $table->tinyInteger('order_status')->comment('0 = PENDING, 1 = PROCESSING, 2 = ON HOLD, 3 = COMPLETED, 4 = CANCELLED, 5 = FAILED, 6 = DRAFT');;
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_company_name')->nullable();
            $table->string('billing_street_address_line_1');
            $table->string('billing_street_address_line_2')->nullable();
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_country');
            $table->integer('billing_pincode');
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_company_name')->nullable();
            $table->string('shipping_street_address_line_1');
            $table->string('shipping_street_address_line_2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_country');
            $table->integer('shipping_pincode');
            $table->string('email');
            $table->string('phone_no', 15);
            $table->foreign('user_id')->on('users')->references('id')->onDelete('set null');
            // $table->foreign('coupon_id')->on('coupon')->references('id')->onDelete('set null');
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
        Schema::dropIfExists('orders');
    }
};
