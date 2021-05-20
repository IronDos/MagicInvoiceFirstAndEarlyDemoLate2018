<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsRecordsRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('products_records', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->after('id');
            $table->foreign('product_id')->references('id')->on('products');
        });

        schema::table('products_records', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->after('id');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_records', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('products_records', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });
    }
}
