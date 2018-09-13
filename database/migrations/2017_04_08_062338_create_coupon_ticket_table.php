<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_coupon', function (Blueprint $table) {
            $table->increments('coupon_id');
            $table->bigInteger('coupon_branch_id');
            $table->bigInteger('coupon_merchant_id');
            $table->bigInteger('coupon_category_id');
            $table->bigInteger('coupon_sub_category_id');
            $table->string('coupon_keyword')();
            $table->string('coupon_code')->unique();
            $table->string('coupon_featured_image');
            $table->float('coupon_sele_price', 8, 2)->nullable()->default(0);
            $table->float('coupon_total_sell_price', 8, 2)->nullable()->default(0);
            $table->float('coupon_discount_rate', 8, 2);
            $table->float('coupon_max_discount', 8, 2)->nullable()->default(0);
            $table->float('coupon_total_discount', 8, 2)->nullable()->default(0);
            $table->float('coupon_commision_rate', 8, 2);
            $table->float('coupon_max_commission', 8, 2)->nullable()->default(0);
            $table->float('coupon_total_commission', 8, 2)->nullable()->default(0);
            $table->date('coupon_opening_date');
            $table->date('coupon_closing_date');
            $table->string('coupon_description');
            $table->float('coupon_applied_min_amount', 8, 2);
            $table->float('coupon_total_shopping_amount', 8, 2)->nullable()->default(0);
            $table->bigInteger('coupon_rating_client_count')->nullable()->default(0);
            $table->bigInteger('coupon_total_rating')->nullable()->default(0);
            $table->float('coupon_applied_point', 8, 2)->nullable()->default(0);
            $table->bigInteger('coupon_max_limit');
            $table->bigInteger('coupon_like')->nullable()->default(0);
            $table->bigInteger('coupon_dislike')->nullable()->default(0);
            $table->bigInteger('coupon_total_view')->nullable()->default(0);
            $table->bigInteger('coupon_total_selled')->nullable()->default(0);
            $table->string('coupon_invite')->nullable()->default(0);
            $table->string('coupon_highlight_status')->nullable()->default(0);
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
        Schema::dropIfExists('tbl_coupon');
    }
}
