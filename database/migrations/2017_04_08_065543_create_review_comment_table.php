<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_coupon_review_comments', function (Blueprint $table) {
            $table->increments('review_comments_id');
            $table->bigInteger('coupon_id');
            $table->bigInteger('customer_id');
            $table->string('coupon_rating')->nullable()->default(0);
            $table->string('coupon_comments')->nullable();
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
        Schema::dropIfExists('tbl_coupon_review_comments');
    }
}
