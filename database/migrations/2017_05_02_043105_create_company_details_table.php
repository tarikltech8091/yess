<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->increments('company_id');
            $table->string('company_name');
            $table->string('company_name_slug');
            $table->string('company_email')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_location_lat')->nullable();
            $table->string('company_location_lng')->nullable();
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
        Schema::dropIfExists('company_details');
    }
}
