<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersRecordsAndInvoicesReceiptsRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('receipts', function (Blueprint $table) {
            $table->unsignedInteger('customer_record_id')->nullable()->after('id');
            $table->foreign('customer_record_id')->references('id')->on('customers_records');
        });

        schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('customer_record_id')->nullable()->after('id');
            $table->foreign('customer_record_id')->references('id')->on('customers_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['customer_record_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_record_id']);
        });
    }
}
