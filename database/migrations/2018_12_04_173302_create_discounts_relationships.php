<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('discount_id')->nullable()->after('id');
            $table->foreign('discount_id')->references('id')->on('discounts');
        });

        schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->unsignedInteger('discount_id')->nullable()->after('id');
            $table->foreign('discount_id')->references('id')->on('discounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
        });

        Schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
        });
    }
}
