<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_cheques', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->tinyInteger('bankId');
            $table->smallInteger('bankBranchId');
            $table->integer('bankAccountId');
            $table->string('chequeId');
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
        Schema::dropIfExists('payment_method_cheques');
    }
}
