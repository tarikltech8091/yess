<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_follow_activity', function (Blueprint $table) {
            $table->increments('activity_id');
            $table->string('activity_type');
            $table->bigInteger('activity_user_id');
            $table->bigInteger('merchant_or_coupon_id');
            $table->string('activity_list_status');
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
        Schema::dropIfExists('tbl_follow_activity');
    }
}
