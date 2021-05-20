<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsNumberingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_numberings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('Draft')->nullable();
            $table->bigInteger('DraftVAT')->nullable();
            $table->bigInteger('Invoice')->nullable();
            $table->bigInteger('InvoiceVAT')->nullable();
            $table->bigInteger('Receipt')->nullable();
            $table->bigInteger('ReceiptVAT')->nullable();
            $table->bigInteger('InvoiceReceipt')->nullable();
            $table->bigInteger('InvoiceVATReceipt')->nullable();
            $table->bigInteger('CreditInvoice')->nullable();
            $table->bigInteger('ShippingCertificate')->nullable();
            $table->bigInteger('ReturnCertificate')->nullable();
            $table->bigInteger('order')->nullable();
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
        Schema::dropIfExists('docs_numberings');
    }
}
