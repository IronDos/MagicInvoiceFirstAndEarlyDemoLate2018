<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('businessTaxIdNumber', 50)->unique()->nullable();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();
            $table->string('fax', 20)->unique()->nullable();
            $table->string('city', 255);
            $table->string('address', 255);
            $table->string('website')->nullable();
            $table->string('subTitle', 255)->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}
