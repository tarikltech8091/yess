<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_coupon_transaction', function (Blueprint $table) {
            $table->increments('coupon_transaction_id');
            $table->bigInteger('coupon_id');
            $table->bigInteger('customer_id');
            $table->bigInteger('transaction_merchant_id');
            $table->bigInteger('transaction_branch_id');
            $table->string('customer_mobile');
            $table->string('coupon_code');
            $table->string('coupon_secret_code');
            $table->float('coupon_discount_rate', 8, 2);
            $table->float('coupon_commission_rate', 8, 2);
            $table->float('coupon_buy_price', 8, 2);
            $table->float('coupon_shopping_amount', 8, 2);
            $table->float('coupon_discount_amount', 8, 2);
            $table->float('coupon_commission_amount', 8, 2);
            $table->string('coupon_status');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_coupon_transaction');
    }
}
