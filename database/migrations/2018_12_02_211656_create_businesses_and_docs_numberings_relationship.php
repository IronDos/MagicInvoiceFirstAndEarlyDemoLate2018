<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesAndDocsNumberingsRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('docs_numberings', function (Blueprint $table) {
            $table->unsignedInteger('business_id')->after('id');
            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_numberings', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
        });
    }
}
