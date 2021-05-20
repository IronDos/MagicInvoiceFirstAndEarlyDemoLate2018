<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLineInInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_line_in_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('productPrice');
            $table->integer('quantity')->default(1);
            $table->decimal('totalPrice');
            $table->boolean('VATRequired');
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
        Schema::dropIfExists('product_line_in_invoices');
    }
}
