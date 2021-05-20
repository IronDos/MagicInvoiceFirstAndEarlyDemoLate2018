<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceStatusesAndInvoicesRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('invoice_status_id')->after('id');
            $table->foreign('invoice_status_id')->references('id')->on('invoice_statuses');
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
            $table->dropForeign(['invoice_status_id']);
        });
    }
}
