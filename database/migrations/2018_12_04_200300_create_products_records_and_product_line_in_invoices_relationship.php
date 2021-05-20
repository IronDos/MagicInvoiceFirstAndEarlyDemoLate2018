<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsRecordsAndProductLineInInvoicesRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->unsignedInteger('product_record_id')->nullable()->after('id');
            $table->foreign('product_record_id')->references('id')->on('products_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->dropForeign(['product_record_id']);
        });
    }
}
