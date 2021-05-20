<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciessAndProductLineInInvoicesRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->nullable()->after('id');
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
        Schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });
    }
}
