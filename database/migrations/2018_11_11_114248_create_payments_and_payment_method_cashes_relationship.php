<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsAndPaymentMethodCashesRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('payment_method_cashes', function (Blueprint $table) {
            $table->unsignedInteger('payment_id')->nullable()->after('id');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_cashes', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
        });
    }
}
