<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supporters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->timestamps();
            $table->string('vorname');
            $table->string('nachname');
            $table->string('mail');
            $table->string('strasse');
            $table->integer('plz');
            $table->string('ort');
            $table->string('land');
            $table->float('beitrag');
            $table->index(['uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supporters');
    }
}
