<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_branch', function (Blueprint $table) {
            $table->increments('branch_id');
            $table->bigInteger('branch_user_id')->nullable()->default(0);
            $table->bigInteger('merchant_id');
            $table->string('branch_code')->unique();
            $table->string('branch_name');
            $table->string('branch_slug');
            $table->string('branch_mobile');
            $table->string('branch_email');
            $table->string('branch_city');
            $table->string('branch_address');
            $table->string('branch_gprs_lat')->nullable();
            $table->string('branch_gprs_lng')->nullable();
            $table->string('branch_status')->default(0);
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
        Schema::dropIfExists('tbl_branch');
    }
}
