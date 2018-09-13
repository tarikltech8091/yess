<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_featured_product', function (Blueprint $table) {
            $table->increments('featured_product_id');
            $table->string('merchant_id');
            $table->string('branch_id');
            $table->string('product_image');
            $table->string('product_original_price');
            $table->string('product_discount_rate');
            $table->string('product_discount_price');
            $table->string('product_featured_description');
            $table->string('featured_product_status');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::drop('tbl_featured_product');
    }
}
