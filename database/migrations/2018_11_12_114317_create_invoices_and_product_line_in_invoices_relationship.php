<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesAndProductLineInInvoicesRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('product_line_in_invoices', function (Blueprint $table) {
            $table->unsignedInteger('invoice_id')->nullable()->after('id');
            $table->foreign('invoice_id')->references('id')->on('invoices');
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
            $table->dropForeign(['invoice_id']);
        });
    }
}
