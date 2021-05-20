<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('quantity')->nullable();
            $table->decimal('price')->nullable();
            $table->enum('VATRequired', ['Yes', 'No']);
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
        Schema::dropIfExists('products_records');
    }
}
