<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_sub_category', function (Blueprint $table) {
            $table->increments('sub_category_id');
            $table->bigInteger('category_id');
            $table->string('sub_category_name');
            $table->string('sub_category_name_slug');
            $table->string('sub_category_featured_image')->nullable();
            $table->string('sub_category_status')->nullable()->default(0);
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
        Schema::dropIfExists('tbl_sub_category');
    }
}
