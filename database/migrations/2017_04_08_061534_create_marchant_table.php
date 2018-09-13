<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merchant', function (Blueprint $table) {
            $table->increments('merchant_id');
            $table->bigInteger('merchant_user_id')->nullable();
            $table->string('merchant_name');
            $table->string('merchant_name_slug');
            $table->string('merchant_logo');
            $table->string('merchant_code')->unique();
            $table->string('merchant_propriter');
            $table->string('merchant_propriter_mobile');
            $table->string('merchant_email');
            $table->string('merchant_address');
            $table->string('merchant_status');
            $table->string('merchant_description');
            $table->string('merchant_website_url')->nullable();
            $table->string('merchant_featured_coupon')->nullable()->default(0);
            $table->string('merchant_rank')->nullable()->default(0);
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
        Schema::dropIfExists('tbl_merchant');
    }
}
