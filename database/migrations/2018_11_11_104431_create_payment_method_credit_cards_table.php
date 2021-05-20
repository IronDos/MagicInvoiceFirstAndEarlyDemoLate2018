<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodCreditCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_credit_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->enum('creditCardType', ['Visa', 'AmericanExpress', 'MasterCard', 'Diners', 'Isracard', 'Other']);
            $table->smallInteger('creditCardLastFourNumbers');
            $table->enum('creditCardTransactionType', ['Regular', 'DeferredCharge', 'Installment', 'Credit', 'Other']);
            $table->tinyInteger('installmentNumber')->nullable();
            $table->decimal('paymentMethodTotal');
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
        Schema::dropIfExists('payment_method_credit_cards');
    }
}
